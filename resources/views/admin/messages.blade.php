@extends('admin.layouts.admin', ['title' => 'Contact Messages'])

@section('admin-content')
<section class="bg-surface-container border border-outline-variant rounded-lg overflow-hidden">
    <div class="px-lg py-md border-b border-outline-variant bg-surface-container-high flex items-center justify-between">
        <h3 class="font-label-mono text-label-mono uppercase tracking-widest text-on-surface-variant">All Contact Messages</h3>
        <span class="font-label-mono text-[11px] text-on-surface-variant">{{ count($messages) }} total</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-outline-variant bg-surface-container-high">
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">Date</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">Name</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">Email</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">Subject</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">Message</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">Actions</th>
                </tr>
            </thead>
            <tbody class="zebra-table">
                @forelse($messages as $msg)
                <tr class="border-b border-outline-variant/50">
                    <td class="px-lg py-md font-label-mono text-[10px] text-on-surface-variant">{{ $msg->created_at->format('d M Y H:i') }}</td>
                    <td class="px-lg py-md font-label-mono text-label-mono text-on-surface text-[11px]">{{ $msg->name }}</td>
                    <td class="px-lg py-md font-label-mono text-label-mono text-secondary text-[11px]">{{ $msg->email }}</td>
                    <td class="px-lg py-md font-label-mono text-label-mono text-on-surface text-[11px]">{{ $msg->subject }}</td>
                    <td class="px-lg py-md font-body-sm text-on-surface-variant text-[11px] max-w-xs truncate">{{ Str::limit($msg->message, 80) }}</td>
                    <td class="px-lg py-md">
                        <div class="flex items-center gap-xs">
                            <button onclick="document.getElementById('msg-{{ $msg->id }}').classList.toggle('hidden')" class="px-sm py-xs bg-surface-container-high text-on-surface-variant border border-outline-variant rounded text-[10px] font-label-mono uppercase hover:text-secondary transition-colors">
                                View
                            </button>
                            <form method="POST" action="{{ route('admin.messages.destroy', $msg->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this message?')" class="px-sm py-xs bg-error/10 text-error border border-error/30 rounded text-[10px] font-label-mono uppercase hover:bg-error/20 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </div>
                        <div id="msg-{{ $msg->id }}" class="hidden mt-sm p-sm bg-surface-container-high border border-outline-variant rounded text-on-surface-variant font-body-sm text-[11px] whitespace-pre-wrap">{{ $msg->message }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-lg py-xl text-center font-label-mono text-on-surface-variant">No contact messages yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection