<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $activeUsersToday = User::where('last_login_at', '>=', Carbon::today())->count();

        $totalRevenueCoins = Transaction::where('type', 'credit')
            ->where('status', 'settled')
            ->sum('amount');

        $totalPayoutsCoins = Transaction::where('type', 'credit')
            ->where('status', 'settled')
            ->where('source_ref', 'like', 'TASK_%')
            ->sum('amount');

        $totalWithdrawnCoins = Withdrawal::where('payout_status', 'completed')
            ->sum('requested_coins');

        $totalRevenuePkr = $totalRevenueCoins * config('app.coin_value_pkr');
        $totalPayoutsPkr = $totalPayoutsCoins * config('app.coin_value_pkr');

        $adminProfitPkr = $totalRevenuePkr * config('app.admin_profit_pct');

        $users = User::select('id', 'name', 'email', 'coin_balance', 'ip_address', 'last_login_at', 'last_login_ip', 'account_status', 'is_admin', 'ban_reason')
            ->orderBy('created_at', 'desc')
            ->get();

        $ipCounts = $users->groupBy('ip_address')->filter(fn ($group) => $group->count() > 1 && ! is_null($group->first()->ip_address));

        $flaggedIps = [];
        foreach ($ipCounts as $ip => $ipUsers) {
            foreach ($ipUsers as $u) {
                $flaggedIps[$u->id] = $ip;
            }
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsersToday',
            'totalRevenueCoins',
            'totalPayoutsCoins',
            'totalWithdrawnCoins',
            'totalRevenuePkr',
            'totalPayoutsPkr',
            'adminProfitPkr',
            'users',
            'flaggedIps'
        ));
    }

    public function toggleUser(Request $request, User $user)
    {
        $newStatus = $user->account_status === 'active' ? 'suspended' : 'active';

        $banReason = null;
        if ($newStatus === 'suspended') {
            $request->validate([
                'ban_reason' => 'required|string|max:500',
            ]);
            $banReason = $request->ban_reason;
        }

        DB::transaction(function () use ($user, $newStatus, $banReason) {
            User::where('id', $user->id)->update([
                'account_status' => $newStatus,
                'ban_reason' => $banReason,
            ]);

            if ($newStatus === 'suspended') {
                if (Schema::hasTable('personal_access_tokens')) {
                    $user->tokens()->delete();
                }
                $user->setRememberToken(null);
                $user->save();
            }
        });

        $user->refresh();

        $action = $newStatus === 'suspended' ? 'banned' : 'unbanned';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "User {$user->name} has been {$action}.",
                'account_status' => $user->account_status,
            ]);
        }

        return back()->with('success', "User {$user->name} has been {$action}.");
    }

    public function updateCoins(Request $request, User $user)
    {
        $validated = $request->validate([
            'coin_balance' => 'required|numeric|min:0',
        ]);

        $oldBalance = (float) $user->coin_balance;
        $newBalance = (float) $validated['coin_balance'];
        $difference = $newBalance - $oldBalance;

        DB::transaction(function () use ($user, $validated, $oldBalance, $newBalance, $difference) {
            User::where('id', $user->id)->update([
                'coin_balance' => $validated['coin_balance'],
            ]);

            if ($difference != 0) {
                $type = $difference > 0 ? 'credit' : 'debit';
                $amount = abs($difference);
                $description = $difference > 0
                    ? 'Balance added by Admin'
                    : 'Balance cut by Admin';

                $user->transactions()->create([
                    'source_ref' => 'ADMIN_ADJUSTMENT',
                    'amount' => $amount,
                    'type' => $type,
                    'status' => 'settled',
                    'metadata' => json_encode([
                        'description' => $description,
                        'old_balance' => $oldBalance,
                        'new_balance' => $newBalance,
                        'admin_id' => Auth::guard('admin')->id(),
                    ]),
                ]);
            }
        });

        $user->refresh();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Balance updated for {$user->name}: {$oldBalance} → {$user->coin_balance} coins.",
                'coin_balance' => $user->coin_balance,
            ]);
        }

        return back()->with('success', "Balance updated for {$user->name}: {$oldBalance} → {$user->coin_balance} coins.");
    }
}
