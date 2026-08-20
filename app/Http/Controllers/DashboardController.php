<?php

namespace App\Http\Controllers;

use App\Models\CustomTask;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->fresh();

        $todayEarnings = $user->transactions()
            ->where('type', 'credit')
            ->whereDate('created_at', today())
            ->sum('amount');

        $totalLifetimeEarnings = $user->transactions()
            ->where('type', 'credit')
            ->where('status', 'settled')
            ->sum('amount');

        $completedTasks = $user->userTaskLogs()
            ->where('claimed', true)
            ->count();

        $pendingTasks = $user->userTaskLogs()
            ->where('visited', true)
            ->where('claimed', false)
            ->count();

        $totalTransactions = $user->transactions()->count();

        $pendingWithdrawals = $user->withdrawals()
            ->where('payout_status', 'pending')
            ->count();

        $pendingAmount = $user->withdrawals()
            ->where('payout_status', 'pending')
            ->sum('fiat_pkr_equivalent');

        $totalWithdrawnCoins = $user->withdrawals()
            ->where('payout_status', 'completed')
            ->sum('requested_coins');

        $totalWithdrawnPKR = $user->withdrawals()
            ->where('payout_status', 'completed')
            ->sum('fiat_pkr_equivalent');

        $recentTransactions = $user->transactions()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $referralCount = $user->referralsMade()->count();

        $totalEarnedFromReferrals = $user->transactions()
            ->where('source_ref', 'REFERRAL_BONUS')
            ->where('type', 'credit')
            ->sum('amount');

        $customTasks = CustomTask::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.index', compact(
            'user',
            'todayEarnings',
            'totalLifetimeEarnings',
            'completedTasks',
            'pendingTasks',
            'totalTransactions',
            'pendingWithdrawals',
            'pendingAmount',
            'totalWithdrawnCoins',
            'totalWithdrawnPKR',
            'recentTransactions',
            'referralCount',
            'totalEarnedFromReferrals',
            'customTasks'
        ));
    }
}
