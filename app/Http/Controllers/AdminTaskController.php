<?php

namespace App\Http\Controllers;

use App\Models\CustomTask;
use Illuminate\Http\Request;

class AdminTaskController extends Controller
{
    public function index()
    {
        $tasks = CustomTask::orderBy('created_at', 'desc')->get();

        return view('admin.tasks', ['tasks' => $tasks]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reward_coins' => 'required|numeric|min:0.01',
            'admin_revenue_estimate' => 'nullable|numeric|min:0',
            'task_url' => 'required|url',
        ]);

        CustomTask::create($request->only([
            'title',
            'description',
            'reward_coins',
            'admin_revenue_estimate',
            'task_url',
        ]) + ['is_active' => true]);

        return back()->with('success', 'Task created successfully.');
    }

    public function update(Request $request, CustomTask $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reward_coins' => 'required|numeric|min:0.01',
            'admin_revenue_estimate' => 'nullable|numeric|min:0',
            'task_url' => 'required|url',
        ]);

        $task->update($request->only([
            'title',
            'description',
            'reward_coins',
            'admin_revenue_estimate',
            'task_url',
        ]));

        return back()->with('success', 'Task updated successfully.');
    }

    public function toggle(CustomTask $task)
    {
        $task->update(['is_active' => ! $task->is_active]);

        return back()->with('success', 'Task status toggled.');
    }

    public function destroy(CustomTask $task)
    {
        $task->delete();

        return back()->with('success', 'Task deleted successfully.');
    }
}
