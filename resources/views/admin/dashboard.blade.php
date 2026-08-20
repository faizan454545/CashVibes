@extends('admin.layouts.admin', ['title' => 'Dashboard'])

@section('admin-content')
<section class="mb-xl">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
        <div class="bg-surface-container border border-outline-variant p-lg rounded-lg">
            <div class="flex items-center gap-sm mb-sm">
                <span class="material-symbols-outlined text-secondary text-[20px]">group</span>
                <span class="font-label-mono text-label-mono text-on-surface-variant uppercase">Total Users</span>
            </div>
            <span class="font-headline-lg text-headline-lg text-on-surface">{{ $totalUsers }}</span>
            <p class="font-body-sm text-on-surface-variant mt-xs">{{ $activeUsersToday }} active today</p>
        </div>

        <div class="bg-surface-container border border-outline-variant p-lg rounded-lg">
            <div class="flex items-center gap-sm mb-sm">
                <span class="material-symbols-outlined text-secondary text-[20px]">trending_up</span>
                <span class="font-label-mono text-label-mono text-on-surface-variant uppercase">Total Revenue</span>
            </div>
            <span class="font-headline-lg text-headline-lg text-on-surface">{{ number_format($totalRevenuePkr, 2) }} PKR</span>
            <p class="font-body-sm text-on-surface-variant mt-xs">≈ {{ number_format($totalRevenueCoins, 2) }} coins earned</p>
        </div>

        <div class="bg-surface-container border border-outline-variant p-lg rounded-lg">
            <div class="flex items-center gap-sm mb-sm">
                <span class="material-symbols-outlined text-secondary text-[20px]">payments</span>
                <span class="font-label-mono text-label-mono text-on-surface-variant uppercase">User Payouts</span>
            </div>
            <span class="font-headline-lg text-headline-lg text-on-surface">{{ number_format($totalPayoutsPkr, 2) }} PKR</span>
            <p class="font-body-sm text-on-surface-variant mt-xs">≈ {{ number_format($totalPayoutsCoins, 2) }} coins rewarded</p>
        </div>

        <div class="bg-surface-container border border-outline-variant p-lg rounded-lg">
            <div class="flex items-center gap-sm mb-sm">
                <span class="material-symbols-outlined text-secondary text-[20px]">account_balance</span>
                <span class="font-label-mono text-label-mono text-on-surface-variant uppercase">Admin Profit (30%)</span>
            </div>
            <span class="font-headline-lg text-headline-lg text-secondary">{{ number_format($adminProfitPkr, 2) }} PKR</span>
            <p class="font-body-sm text-on-surface-variant mt-xs">Net margin after 70% user reward</p>
        </div>
    </div>
</section>

<section>
    <div class="flex items-center justify-between mb-md">
        <h3 class="font-headline-md text-headline-md text-on-surface">User Security Table</h3>
    </div>

    <div class="bg-surface-container border border-outline-variant rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-container-high">
                        <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase">User</th>
                        <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase">Balance</th>
                        <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase">Signup IP</th>
                        <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase">Last Login IP</th>
                        <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase">Status</th>
                        <th class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="zebra-table">
                    @forelse($users as $u)
                    <tr class="border-b border-outline-variant/50 {{ isset($flaggedIps[$u->id]) ? 'bg-error-container/10' : '' }}">
                        <td class="px-lg py-md">
                            <div class="flex items-center gap-sm">
                                @if($u->is_admin)
                                <span class="material-symbols-outlined text-secondary text-[16px]" style="font-variation-settings: 'FILL' 1;">shield</span>
                                @endif
                                @if(isset($flaggedIps[$u->id]))
                                <span class="material-symbols-outlined text-error text-[16px]" style="font-variation-settings: 'FILL' 1;">warning</span>
                                @endif
                                <div>
                                    <p class="font-label-mono text-label-mono text-on-surface">{{ $u->name }}</p>
                                    <p class="font-body-sm text-on-surface-variant text-[12px]">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-lg py-md font-label-mono text-label-mono text-secondary">{{ number_format($u->coin_balance, 2) }}</td>
                        <td class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant">{{ $u->ip_address ?? 'N/A' }}</td>
                        <td class="px-lg py-md font-label-mono text-label-mono text-on-surface-variant">{{ $u->last_login_ip ?? 'N/A' }}</td>
                        <td class="px-lg py-md">
                            <span class="inline-flex items-center px-sm py-xs rounded-full text-[10px] font-label-mono uppercase {{ $u->account_status === 'active' ? 'bg-secondary/10 text-secondary' : 'bg-error/10 text-error' }}">
                                {{ $u->account_status }}
                            </span>
                            @if(isset($flaggedIps[$u->id]))
                            <span class="ml-xs inline-flex items-center px-sm py-xs rounded-full text-[10px] font-label-mono bg-error/10 text-error" title="Shared IP: {{ $flaggedIps[$u->id] }}">
                                MULTI-ACCOUNT
                            </span>
                            @endif
                        </td>
                        <td class="px-lg py-md">
                            <div class="flex items-center gap-sm">
                                @if($u->account_status === 'active')
                                <button type="button" onclick="openBanModal({{ $u->id }}, '{{ addslashes($u->name) }}')" class="px-sm py-xs border border-outline-variant rounded text-[10px] font-label-mono uppercase text-error hover:bg-error/10 transition-colors">
                                    Ban
                                </button>
                                @else
                                <form method="POST" action="{{ route('admin.user.toggle', $u->id) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-sm py-xs border border-outline-variant rounded text-[10px] font-label-mono uppercase text-secondary hover:bg-secondary/10 transition-colors">
                                        Unban
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('admin.user.update-coins', $u->id) }}" class="inline-flex items-center gap-xs" id="coin-form-{{ $u->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="coin_balance" value="{{ $u->coin_balance }}" step="0.01" class="w-24 bg-surface-container-high border border-outline-variant px-sm py-xs font-label-mono text-label-mono text-on-surface text-[11px] rounded focus:border-secondary focus:outline-none">
                                    <button type="submit" class="px-sm py-xs bg-secondary/10 text-secondary border border-secondary/30 rounded text-[10px] font-label-mono uppercase hover:bg-secondary/20 transition-colors">Set</button>
                                </form>
                            </div>
                            @if($u->ban_reason && $u->account_status === 'suspended')
                            <p class="mt-xs font-body-sm text-error text-[10px]">Reason: {{ $u->ban_reason }}</p>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-lg py-xl text-center font-label-mono text-on-surface-variant">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<div id="ban-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-surface-container border border-outline-variant rounded-lg p-lg w-full max-w-md mx-4">
        <h3 class="font-headline-md text-headline-md text-error mb-md">Ban User</h3>
        <p class="font-body-sm text-on-surface-variant mb-lg">You are about to ban <span id="ban-user-name" class="text-on-surface font-bold"></span>. This user will see a ban notice immediately.</p>
        <form method="POST" id="ban-form" action="">
            @csrf
            @method('PATCH')
            <div class="mb-lg">
                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">BAN REASON (required)</label>
                <textarea name="ban_reason" rows="3" required placeholder="e.g. Multiple account abuse detected" class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors resize-none"></textarea>
            </div>
            <div class="flex gap-sm">
                <button type="button" onclick="closeBanModal()" class="flex-1 py-md border border-outline-variant text-on-surface-variant font-label-mono text-label-mono uppercase tracking-widest hover:bg-surface-container-highest transition-colors">Cancel</button>
                <button type="submit" class="flex-1 py-md bg-error text-white font-label-mono text-label-mono font-bold uppercase tracking-widest hover:bg-error/80 transition-colors">Confirm Ban</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openBanModal(userId, userName) {
    document.getElementById('ban-user-name').textContent = userName;
    document.getElementById('ban-form').action = '/admin/user/' + userId + '/toggle';
    const modal = document.getElementById('ban-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeBanModal() {
    const modal = document.getElementById('ban-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endpush
@endsection
