<?php

namespace App\Http\Controllers;

use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EarnController extends Controller
{
    public function __construct(
        private ReferralService $referralService
    ) {}

    public function index()
    {
        $user = Auth::user()->fresh();

        $todayEarnings = $user->transactions()
            ->where('type', 'credit')
            ->whereDate('created_at', today())
            ->sum('amount');

        $dailyTarget = config('app.daily_target');
        $targetPercentage = min(($todayEarnings / $dailyTarget) * 100, 100);

        $completedTasksToday = $user->transactions()
            ->where('source_ref', 'TASK_COMPLETION')
            ->whereDate('created_at', today())
            ->count();

        $dailyTaskLimit = config('app.daily_task_limit');

        $availableProviders = [
            ['name' => 'Lootably Offerwall', 'status' => 'integrating', 'initial' => 'L'],
            ['name' => 'Monlix Rewards', 'status' => 'integrating', 'initial' => 'M'],
            ['name' => 'TimeWall Micro-tasks', 'status' => 'integrating', 'initial' => 'T'],
        ];

        return view('earn.index', compact(
            'user',
            'todayEarnings',
            'targetPercentage',
            'completedTasksToday',
            'dailyTaskLimit',
            'availableProviders'
        ));
    }

    public function completeTask(Request $request)
    {
        $user = Auth::user()->fresh();

        $maxReward = config('app.max_task_reward');

        $request->validate([
            'task_id' => 'required|string',
            'source' => 'required|string',
            'reward' => 'required|numeric|min:0.01|max:'.$maxReward,
        ]);

        $transaction = $user->credit(
            $request->reward,
            'TASK_COMPLETION',
            json_encode([
                'task_id' => $request->task_id,
                'source' => $request->source,
            ])
        );

        $this->referralService->checkAndTriggerReward($user);

        return response()->json([
            'success' => true,
            'new_balance' => $user->fresh()->coin_balance,
            'transaction' => $transaction,
        ]);
    }
}
