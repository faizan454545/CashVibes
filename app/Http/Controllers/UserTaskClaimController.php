<?php

namespace App\Http\Controllers;

use App\Models\CustomTask;
use App\Models\UserTaskLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserTaskClaimController extends Controller
{
    public function visit(CustomTask $task)
    {
        $user = Auth::user();

        UserTaskLog::updateOrCreate(
            ['user_id' => $user->id, 'custom_task_id' => $task->id],
            ['visited' => true]
        );

        return redirect($task->task_url);
    }

    public function claim(CustomTask $task)
    {
        $user = Auth::user();

        $log = UserTaskLog::where('user_id', $user->id)
            ->where('custom_task_id', $task->id)
            ->where('visited', true)
            ->first();

        if (! $log) {
            return back()->withErrors(['error' => 'You must visit the task first before claiming.']);
        }

        if ($log->claimed) {
            return back()->withErrors(['error' => 'You have already claimed this reward.']);
        }

        DB::transaction(function () use ($user, $task, $log) {
            $user->increment('coin_balance', $task->reward_coins);

            $user->transactions()->create([
                'source_ref' => 'CUSTOM_TASK_'.$task->id,
                'provider' => 'custom_task',
                'amount' => $task->reward_coins,
                'type' => 'offerwall',
                'status' => 'approved',
                'metadata' => json_encode([
                    'task_id' => $task->id,
                    'task_title' => $task->title,
                ]),
            ]);

            $log->update([
                'claimed' => true,
                'coins_awarded' => $task->reward_coins,
            ]);
        });

        return back()->with('success', $task->reward_coins.' coins credited to your balance!');
    }
}
