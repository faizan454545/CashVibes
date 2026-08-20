@extends('layouts.app')

@section('title', 'Invite & Earn - CashVibes')

@section('content')
<header class="w-full top-0 sticky z-[60] bg-surface border-b border-outline-variant">
    <div class="flex items-center justify-between w-full max-w-container-max mx-auto px-3 sm:px-lg py-2 sm:py-md gap-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 min-w-0 flex-shrink-0">
            <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="CashVibes" class="w-8 h-8 sm:w-9 sm:h-9 object-contain flex-shrink-0">
            <h1 class="font-headline-md text-[14px] sm:text-headline-md-mobile font-bold tracking-tight text-on-surface whitespace-nowrap hidden sm:block">CASHVIBES</h1>
        </a>
        <x-user-balance-badge />
    </div>
</header>

<main class="w-full max-w-container-max mx-auto px-lg pb-32">
    <section class="mt-xl">
        <h2 class="font-headline-lg text-headline-lg mb-sm">Invite & Earn</h2>
        <p class="text-body-md text-on-surface-variant max-w-md">Grow your network and earn {{ config('app.referral_task_reward_pct') * 100 }}% of every friend's distribution reward directly to your wallet.</p>
    </section>

    <section class="mt-xl">
        <p class="font-label-mono text-label-mono text-outline uppercase tracking-widest mb-md">Your Referral Code</p>
        <div class="border border-outline-variant bg-surface-container-low p-lg flex justify-between items-center group">
            <span class="font-label-mono text-headline-md text-secondary tracking-widest">{{ $user->referral_code }}</span>
            <button onclick="copyReferralCode()" class="flex items-center gap-sm text-on-surface hover:text-secondary transition-colors active:scale-95">
                <span class="material-symbols-outlined">content_copy</span>
                <span class="font-label-mono text-label-mono uppercase">Copy</span>
            </button>
        </div>
        <div id="copy-feedback" class="hidden mt-sm font-label-mono text-label-mono text-secondary text-sm">Copied to clipboard!</div>
    </section>

    @if(!$user->referred_by)
    <section class="mt-xl">
        <p class="font-label-mono text-label-mono text-outline uppercase tracking-widest mb-md">Apply Referral</p>
        <form action="{{ route('invite.apply-code') }}" method="POST" class="flex gap-sm">
            @csrf
            <input type="text" name="referral_code" class="flex-1 bg-surface-container border-outline-variant text-on-surface font-label-mono focus:ring-secondary focus:border-secondary p-md" placeholder="Enter Referral Code" required>
            <button type="submit" class="px-lg bg-primary text-on-primary font-bold uppercase tracking-widest text-label-mono transition-transform active:scale-[0.98] hover:bg-white">
                Apply
            </button>
        </form>
        @error('referral_code')
        <p class="mt-xs font-label-mono text-label-mono text-error text-sm">{{ $message }}</p>
        @enderror
    </section>
    @endif

    @if(session('success'))
    <div class="mt-md p-md border border-secondary bg-secondary-container/20 rounded">
        <p class="font-label-mono text-label-mono text-secondary">{{ session('success') }}</p>
    </div>
    @endif

    <section class="mt-xl">
        <p class="font-label-mono text-label-mono text-outline uppercase tracking-widest mb-md">Your Referrals // Network_Stats</p>
        <div class="bento-grid border border-outline-variant">
            <div class="bento-item">
                <p class="font-label-mono text-label-mono text-on-surface-variant uppercase mb-md">Total Invites</p>
                <div class="flex items-baseline gap-sm">
                    <span class="font-headline-lg text-headline-lg text-on-surface">{{ $totalInvites }}</span>
                    <span class="font-label-mono text-label-mono text-outline">Users</span>
                </div>
            </div>
            <div class="bento-item">
                <p class="font-label-mono text-label-mono text-on-surface-variant uppercase mb-md">Total Earned</p>
                <div class="flex items-baseline gap-sm">
                    <span class="font-headline-lg text-headline-lg text-secondary">{{ number_format($totalEarned, 2) }}</span>
                    <span class="font-label-mono text-label-mono text-outline">Coins</span>
                </div>
            </div>
        </div>

        <div class="mt-md border border-outline-variant bg-surface-container-low">
            @forelse($recentReferrals as $referral)
            <div class="p-md border-b border-outline-variant flex justify-between items-center">
                <div class="flex items-center gap-md">
                    <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center">
                        <span class="material-symbols-outlined text-outline text-body-sm">person</span>
                    </div>
                    <div>
                        <p class="font-label-mono text-label-mono text-on-surface">{{ $referral->referee->name ?? 'UNKNOWN' }}</p>
                        <p class="text-[10px] text-outline uppercase">Joined {{ $referral->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <span class="font-label-mono text-label-mono text-secondary">+{{ $referral->is_first_task_done ? number_format(config('app.referral_bonus_amt'), 2) : '0.00' }} Coins</span>
            </div>
            @empty
            <div class="p-md text-center">
                <p class="font-label-mono text-label-mono text-on-surface-variant">No referrals yet. Share your code!</p>
            </div>
            @endforelse

            @if($totalInvites > 5)
            <div class="p-md flex justify-center">
                <button class="font-label-mono text-label-mono text-outline hover:text-on-surface uppercase tracking-widest py-xs">View All Connections</button>
            </div>
            @endif
        </div>
    </section>
</main>

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center h-16 bg-surface px-md pb-safe border-t border-outline-variant">
    <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('dashboard') }}">
        <span class="material-symbols-outlined">monetization_on</span>
        <span class="font-label-mono text-label-mono mt-xs">Earn</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('withdraw') }}">
        <span class="material-symbols-outlined">account_balance</span>
        <span class="font-label-mono text-label-mono mt-xs">Vault</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('surveys') }}">
        <span class="material-symbols-outlined">poll</span>
        <span class="font-label-mono text-label-mono mt-xs">Surveys</span>
    </a>
    <a class="flex flex-col items-center justify-center text-secondary font-bold transition-transform active:scale-95" href="{{ route('invite') }}">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">group_add</span>
        <span class="font-label-mono text-label-mono mt-xs">Invite</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('profile') }}">
        <span class="material-symbols-outlined">person</span>
        <span class="font-label-mono text-label-mono mt-xs">Profile</span>
    </a>
</nav>

@push('scripts')
<script>
    function copyReferralCode() {
        const link = '{{ url("/register?ref=" . $user->referral_code) }}';
        navigator.clipboard.writeText(link).then(() => {
            const feedback = document.getElementById('copy-feedback');
            feedback.classList.remove('hidden');
            setTimeout(() => feedback.classList.add('hidden'), 2000);
        });
    }
</script>
@endpush
@endsection
