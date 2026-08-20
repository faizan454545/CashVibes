@extends('admin.layouts.admin', ['title' => 'Task Management'])

@section('admin-content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">
    <div class="lg:col-span-1">
        <section class="bg-surface-container border border-outline-variant rounded-lg p-lg sticky top-20">
            <h3 class="font-headline-md text-headline-md text-on-surface mb-lg">Create New Task</h3>

            <form method="POST" action="{{ route('admin.tasks.store') }}">
                @csrf

                <div class="mb-md">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">TITLE</label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           required
                           placeholder="Task title"
                           class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
                </div>

                <div class="mb-md">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">DESCRIPTION</label>
                    <textarea name="description"
                              rows="3"
                              placeholder="Brief description"
                              class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors resize-none">{{ old('description') }}</textarea>
                </div>

                <div class="mb-md">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">REWARD_COINS</label>
                    <input type="number"
                           name="reward_coins"
                           value="{{ old('reward_coins') }}"
                           required
                           min="0.01"
                           step="0.01"
                           placeholder="10.00"
                           class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
                </div>

                <div class="mb-md">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">ADMIN_REVENUE_ESTIMATE (USD)</label>
                    <input type="number"
                           name="admin_revenue_estimate"
                           value="{{ old('admin_revenue_estimate') }}"
                           min="0"
                           step="0.01"
                           placeholder="0.05"
                           class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
                </div>

                <div class="mb-lg">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">TASK_URL</label>
                    <input type="url"
                           name="task_url"
                           value="{{ old('task_url') }}"
                           required
                           placeholder="https://example.com/task"
                           class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors font-label-mono text-[12px]">
                </div>

                <button type="submit" class="w-full py-md bg-secondary/10 text-secondary border border-secondary/30 font-label-mono text-label-mono font-bold tracking-widest hover:bg-secondary/20 transition-colors active:scale-[0.98] uppercase">
                    Create Task
                </button>
            </form>
        </section>
    </div>

    <div class="lg:col-span-2">
        <section class="bg-surface-container border border-outline-variant rounded-lg overflow-hidden">
            <div class="px-lg py-md border-b border-outline-variant bg-surface-container-high">
                <h3 class="font-label-mono text-label-mono uppercase tracking-widest text-on-surface-variant">Active Tasks ({{ $tasks->where('is_active')->count() }})</h3>
            </div>

            <div class="divide-y divide-outline-variant/50">
                @forelse($tasks as $task)
                <div class="px-lg py-md flex flex-col md:flex-row md:items-center justify-between gap-md {{ !$task->is_active ? 'opacity-50' : '' }}">
                    <div class="flex-1">
                        <div class="flex items-center gap-sm mb-xs">
                            <h4 class="font-label-mono text-label-mono text-on-surface">{{ $task->title }}</h4>
                            @if($task->is_active)
                            <span class="px-xs py-[2px] bg-secondary/10 text-secondary text-[9px] font-label-mono rounded uppercase">Active</span>
                            @else
                            <span class="px-xs py-[2px] bg-error/10 text-error text-[9px] font-label-mono rounded uppercase">Inactive</span>
                            @endif
                        </div>
                        <p class="font-body-sm text-on-surface-variant text-[13px] mb-xs">{{ $task->description ?? 'No description' }}</p>
                        <div class="flex items-center gap-md font-label-mono text-[11px] text-outline">
                            <span>Reward: {{ $task->reward_coins }} coins</span>
                            <span>Revenue: ${{ number_format($task->admin_revenue_estimate ?? 0, 2) }}</span>
                            <span>{{ $task->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-sm shrink-0">
                        <form method="POST" action="{{ route('admin.tasks.toggle', $task->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-sm py-xs border border-outline-variant rounded text-[10px] font-label-mono uppercase {{ $task->is_active ? 'text-secondary hover:bg-secondary/10' : 'text-error hover:bg-error/10' }} transition-colors">
                                {{ $task->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <button onclick="document.getElementById('edit-modal-{{ $task->id }}').classList.remove('hidden')" class="px-sm py-xs border border-outline-variant rounded text-[10px] font-label-mono uppercase text-on-surface-variant hover:bg-surface-container-high transition-colors">
                            Edit
                        </button>

                        <form method="POST" action="{{ route('admin.tasks.destroy', $task->id) }}" onsubmit="return confirm('Delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-sm py-xs border border-error/30 rounded text-[10px] font-label-mono uppercase text-error hover:bg-error/10 transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Edit Modal --}}
                <div id="edit-modal-{{ $task->id }}" class="hidden fixed inset-0 z-[100] bg-black/60 flex items-center justify-center p-md" onclick="if(event.target===this)this.classList.add('hidden')">
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-lg w-full max-w-lg max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-lg">Edit Task</h3>
                        <form method="POST" action="{{ route('admin.tasks.update', $task->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-md">
                                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-xs text-[11px]">TITLE</label>
                                <input type="text" name="title" value="{{ $task->title }}" required class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface focus:border-secondary focus:outline-none transition-colors">
                            </div>
                            <div class="mb-md">
                                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-xs text-[11px]">DESCRIPTION</label>
                                <textarea name="description" rows="2" class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface focus:border-secondary focus:outline-none transition-colors resize-none">{{ $task->description }}</textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-md mb-md">
                                <div>
                                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-xs text-[11px]">REWARD_COINS</label>
                                    <input type="number" name="reward_coins" value="{{ $task->reward_coins }}" required min="0.01" step="0.01" class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface focus:border-secondary focus:outline-none transition-colors">
                                </div>
                                <div>
                                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-xs text-[11px]">REVENUE_ESTIMATE</label>
                                    <input type="number" name="admin_revenue_estimate" value="{{ $task->admin_revenue_estimate }}" min="0" step="0.01" class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface focus:border-secondary focus:outline-none transition-colors">
                                </div>
                            </div>
                            <div class="mb-lg">
                                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-xs text-[11px]">TASK_URL</label>
                                <input type="url" name="task_url" value="{{ $task->task_url }}" required class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface focus:border-secondary focus:outline-none transition-colors font-label-mono text-[12px]">
                            </div>
                            <div class="flex gap-sm">
                                <button type="submit" class="flex-1 py-md bg-primary text-on-primary font-label-mono text-label-mono font-bold tracking-widest hover:bg-white transition-colors active:scale-[0.98] uppercase">Save</button>
                                <button type="button" onclick="document.getElementById('edit-modal-{{ $task->id }}').classList.add('hidden')" class="px-lg py-md border border-outline-variant text-on-surface-variant font-label-mono text-label-mono uppercase hover:bg-surface-container-high transition-colors">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <div class="px-lg py-xl text-center font-label-mono text-on-surface-variant">No tasks created yet.</div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
