<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    public function index()
    {
        $user = Auth::user()->fresh();

        $recentWithdrawals = $user->withdrawals()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentTransactions = $user->transactions()
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        $totalSettledCoins = $user->withdrawals()
            ->where('payout_status', 'completed')
            ->sum('requested_coins');

        $totalSettledPKR = $user->withdrawals()
            ->where('payout_status', 'completed')
            ->sum('fiat_pkr_equivalent');

        return view('withdraw.index', compact(
            'user',
            'recentWithdrawals',
            'recentTransactions',
            'totalSettledCoins',
            'totalSettledPKR'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payout_gateway' => 'required|in:easypaisa,jazzcash,binance_pay',
            'requested_coins' => 'required|integer|min:'.config('app.min_withdrawal_coins'),
            'account_number_or_id' => 'required|string',
            'account_title_receiver' => 'nullable|string|max:50',
        ]);

        $user = Auth::user()->fresh();

        if (! Withdrawal::validateGatewayAccount($request->payout_gateway, $request->account_number_or_id)) {
            $message = match ($request->payout_gateway) {
                'easypaisa', 'jazzcash' => 'Invalid account number. Must be a valid Pakistani mobile number (03XXXXXXXXX, 11 digits).',
                'binance_pay' => 'Invalid Binance Pay ID. Must be 9-12 numeric digits.',
                default => 'Invalid account details.',
            };

            return back()->withErrors(['account_number_or_id' => $message]);
        }

        $fiatPkr = $request->requested_coins * config('app.coin_value_pkr');

        $success = DB::transaction(function () use ($user, $request, $fiatPkr) {
            $fresh = $user->fresh();

            if (! $fresh->canWithdraw()) {
                return false;
            }

            if ($fresh->coin_balance < $request->requested_coins) {
                return false;
            }

            $user->decrement('coin_balance', $request->requested_coins);

            $user->transactions()->create([
                'source_ref' => 'WITHDRAWAL_REQUEST',
                'provider' => 'withdrawal',
                'amount' => $request->requested_coins,
                'type' => 'debit',
                'status' => 'pending',
                'metadata' => json_encode([
                    'gateway' => $request->payout_gateway,
                    'account' => $request->account_number_or_id,
                    'title' => $request->account_title_receiver,
                    'pkr_equivalent' => $fiatPkr,
                ]),
            ]);

            Withdrawal::create([
                'user_id' => $user->id,
                'payout_gateway' => $request->payout_gateway,
                'requested_coins' => $request->requested_coins,
                'fiat_pkr_equivalent' => $fiatPkr,
                'account_number_or_id' => $request->account_number_or_id,
                'account_title_receiver' => $request->account_title_receiver,
                'payout_status' => 'pending',
                'user_ip' => $request->ip(),
            ]);

            return true;
        });

        if (! $success) {
            return back()->withErrors([
                'requested_coins' => 'Insufficient balance or minimum withdrawal not met.',
            ]);
        }

        return redirect()->route('withdraw')
            ->with('success', 'Withdrawal request submitted! '.number_format($fiatPkr, 2).' PKR will be transferred shortly.');
    }
}
