<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;

class AdminWithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalApprovedPkr = Withdrawal::where('payout_status', 'completed')->sum('fiat_pkr_equivalent');
        $totalApprovedCount = Withdrawal::where('payout_status', 'completed')->count();
        $totalPendingPkr = Withdrawal::where('payout_status', 'pending')->sum('fiat_pkr_equivalent');
        $totalPendingCount = Withdrawal::where('payout_status', 'pending')->count();
        $totalRejectedPkr = Withdrawal::where('payout_status', 'rejected')->sum('fiat_pkr_equivalent');
        $totalRejectedCount = Withdrawal::where('payout_status', 'rejected')->count();

        return view('admin.withdrawals', compact(
            'withdrawals',
            'totalApprovedPkr',
            'totalApprovedCount',
            'totalPendingPkr',
            'totalPendingCount',
            'totalRejectedPkr',
            'totalRejectedCount'
        ));
    }

    public function complete(Withdrawal $withdrawal)
    {
        if ($withdrawal->payout_status !== 'pending') {
            return back()->withErrors(['error' => 'This withdrawal is no longer pending.']);
        }

        DB::transaction(function () use ($withdrawal) {
            $withdrawal->update([
                'payout_status' => 'completed',
                'processed_at' => now(),
            ]);

            $existingTx = Transaction::where('source_ref', 'WITHDRAWAL_'.$withdrawal->id)
                ->where('status', 'pending')
                ->first();

            if ($existingTx) {
                $existingTx->update(['status' => 'settled']);
            } else {
                $withdrawal->user->transactions()->create([
                    'source_ref' => 'WITHDRAWAL_'.$withdrawal->id,
                    'provider' => 'withdrawal',
                    'amount' => $withdrawal->requested_coins,
                    'type' => 'debit',
                    'status' => 'settled',
                    'metadata' => json_encode([
                        'gateway' => $withdrawal->payout_gateway,
                        'account' => $withdrawal->account_number_or_id,
                        'pkr_equivalent' => $withdrawal->fiat_pkr_equivalent,
                    ]),
                ]);
            }
        });

        return back()->with('success', 'Withdrawal #'.$withdrawal->id.' marked as completed.');
    }

    public function reject(Withdrawal $withdrawal)
    {
        if ($withdrawal->payout_status !== 'pending') {
            return back()->withErrors(['error' => 'This withdrawal is no longer pending.']);
        }

        DB::transaction(function () use ($withdrawal) {
            $withdrawal->user->increment('coin_balance', $withdrawal->requested_coins);

            $existingTx = Transaction::where('source_ref', 'WITHDRAWAL_'.$withdrawal->id)
                ->where('status', 'pending')
                ->first();

            if ($existingTx) {
                $existingTx->update(['status' => 'rejected']);
            } else {
                $withdrawal->user->transactions()->create([
                    'source_ref' => 'WITHDRAWAL_'.$withdrawal->id,
                    'provider' => 'withdrawal',
                    'amount' => $withdrawal->requested_coins,
                    'type' => 'debit',
                    'status' => 'rejected',
                    'metadata' => json_encode([
                        'gateway' => $withdrawal->payout_gateway,
                        'account' => $withdrawal->account_number_or_id,
                        'pkr_equivalent' => $withdrawal->fiat_pkr_equivalent,
                        'note' => 'Rejected - coins refunded',
                    ]),
                ]);
            }

            $withdrawal->update([
                'payout_status' => 'rejected',
                'processed_at' => now(),
            ]);
        });

        return back()->with('success', 'Withdrawal #'.$withdrawal->id.' rejected. Coins refunded to user.');
    }
}
