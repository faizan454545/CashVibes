@extends('admin.layouts.admin', ['title' => 'Withdrawal Management'])

@section('admin-content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-md mb-xl">
    <div class="bg-surface-container border border-outline-variant p-lg rounded-lg">
        <div class="flex items-center gap-sm mb-sm">
            <span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
            <span class="font-label-mono text-label-mono text-on-surface-variant uppercase">Approved (PKR)</span>
        </div>
        <span class="font-headline-lg text-headline-lg text-on-surface">{{ number_format($totalApprovedPkr, 2) }} PKR</span>
        <p class="font-label-mono text-[10px] text-on-surface-variant mt-xs">{{ number_format($totalApprovedCount) }} withdrawal(s)</p>
    </div>
    <div class="bg-surface-container border border-outline-variant p-lg rounded-lg">
        <div class="flex items-center gap-sm mb-sm">
            <span class="material-symbols-outlined text-secondary text-[20px]">pending_actions</span>
            <span class="font-label-mono text-label-mono text-on-surface-variant uppercase">Pending (PKR)</span>
        </div>
        <span class="font-headline-lg text-headline-lg text-secondary">{{ number_format($totalPendingPkr, 2) }} PKR</span>
        <p class="font-label-mono text-[10px] text-on-surface-variant mt-xs">{{ number_format($totalPendingCount) }} withdrawal(s)</p>
    </div>
    <div class="bg-surface-container border border-outline-variant p-lg rounded-lg">
        <div class="flex items-center gap-sm mb-sm">
            <span class="material-symbols-outlined text-error text-[20px]">cancel</span>
            <span class="font-label-mono text-label-mono text-on-surface-variant uppercase">Rejected (PKR)</span>
        </div>
        <span class="font-headline-lg text-headline-lg text-error">{{ number_format($totalRejectedPkr, 2) }} PKR</span>
        <p class="font-label-mono text-[10px] text-on-surface-variant mt-xs">{{ number_format($totalRejectedCount) }} withdrawal(s)</p>
    </div>
</div>

<section class="bg-surface-container border border-outline-variant rounded-lg overflow-hidden">
    <div class="px-lg py-md border-b border-outline-variant bg-surface-container-high flex items-center justify-between">
        <h3 class="font-label-mono text-label-mono uppercase tracking-widest text-on-surface-variant">All Withdrawal Requests</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-outline-variant bg-surface-container-high">
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">ID</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">User</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">IP Address</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">Payment</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">Account</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">Amount</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">Date</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">Status</th>
                    <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase text-[10px]">Actions</th>
                </tr>
            </thead>
            <tbody class="zebra-table">
                @forelse($withdrawals as $w)
                <tr class="border-b border-outline-variant/50 {{ $w->payout_status === 'pending' ? 'bg-secondary/5' : '' }}">
                    <td class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant text-[11px]">#{{ $w->id }}</td>
                    <td class="px-lg py-md">
                        <div>
                            <p class="font-label-mono text-label-mono text-on-surface text-[11px]">{{ $w->user->name ?? 'N/A' }}</p>
                            <p class="font-body-sm text-on-surface-variant text-[11px]">{{ $w->user->email ?? 'N/A' }}</p>
                        </div>
                    </td>
                    <td class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant text-[11px]">{{ $w->user_ip ?? 'N/A' }}</td>
                    <td class="px-lg py-md">
                        <span class="inline-flex items-center px-sm py-xs rounded text-[10px] font-label-mono uppercase bg-surface-container-high text-on-surface-variant border border-outline-variant">
                            {{ $w->payout_gateway }}
                        </span>
                    </td>
                    <td class="px-lg py-md">
                        <div>
                            @if($w->account_title_receiver)
                            <p class="font-label-mono text-label-mono text-on-surface text-[11px]">{{ $w->account_title_receiver }}</p>
                            @endif
                            <p class="font-label-mono text-label-mono text-on-surface-variant text-[11px]">{{ $w->account_number_or_id }}</p>
                        </div>
                    </td>
                    <td class="px-lg py-md">
                        <p class="font-label-mono text-label-mono text-secondary text-[11px]">{{ number_format($w->requested_coins, 2) }} coins</p>
                        <p class="font-label-mono text-[10px] text-on-surface-variant">≈ {{ number_format($w->fiat_pkr_equivalent, 2) }} PKR</p>
                    </td>
                    <td class="px-lg py-md">
                        <p class="font-label-mono text-[10px] text-on-surface-variant">{{ $w->created_at->format('d M Y') }}</p>
                        <p class="font-label-mono text-[10px] text-outline">{{ $w->created_at->format('H:i:s') }}</p>
                    </td>
                    <td class="px-lg py-md">
                        @if($w->payout_status === 'pending')
                        <span class="inline-flex items-center px-sm py-xs rounded-full text-[9px] font-label-mono uppercase bg-secondary/10 text-secondary border border-secondary/30">
                            Pending
                        </span>
                        @elseif($w->payout_status === 'completed')
                        <span class="inline-flex items-center px-sm py-xs rounded-full text-[9px] font-label-mono uppercase bg-secondary/20 text-secondary border border-secondary/30">
                            Completed
                        </span>
                        @else
                        <span class="inline-flex items-center px-sm py-xs rounded-full text-[9px] font-label-mono uppercase bg-error/10 text-error border border-error/30">
                            Rejected
                        </span>
                        @endif
                    </td>
                    <td class="px-lg py-md">
                        @if($w->payout_status === 'pending')
                        <div class="flex items-center gap-xs">
                            <form method="POST" action="{{ route('admin.withdrawals.complete', $w->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-sm py-xs bg-secondary/10 text-secondary border border-secondary/30 rounded text-[10px] font-label-mono uppercase hover:bg-secondary/20 transition-colors">
                                    Complete
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.withdrawals.reject', $w->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('Reject this withdrawal? Coins will be refunded.')" class="px-sm py-xs bg-error/10 text-error border border-error/30 rounded text-[10px] font-label-mono uppercase hover:bg-error/20 transition-colors">
                                    Reject
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="font-label-mono text-[10px] text-outline">{{ $w->processed_at?->format('d M H:i') ?? '-' }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-lg py-xl text-center font-label-mono text-on-surface-variant">No withdrawal requests yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
