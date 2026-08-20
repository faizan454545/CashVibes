@extends('layouts.app')

@section('title', 'Surveys - CashVibes')

@section('content')
<header class="w-full top-0 sticky z-50 bg-surface dark:bg-surface border-b border-outline-variant dark:border-outline-variant">
    <div class="flex items-center justify-between w-full max-w-container-max mx-auto px-3 sm:px-lg py-2 sm:py-md gap-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 min-w-0 flex-shrink-0">
            <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="CashVibes" class="w-8 h-8 sm:w-9 sm:h-9 object-contain flex-shrink-0">
            <h1 class="font-headline-md text-[14px] sm:text-headline-md-mobile font-bold tracking-tight text-on-surface dark:text-on-surface whitespace-nowrap hidden sm:block">CASHVIBES</h1>
        </a>
        <x-user-balance-badge />
    </div>
</header>

<main class="flex-grow w-full max-w-container-max mx-auto px-md md:px-lg py-xl pb-32">

    {{-- Provider Selection View --}}
    <div id="provider-selection">
        <section class="mb-lg">
            <h2 class="font-headline-lg text-headline-lg-mobile mb-xs">Surveys</h2>
            <p class="text-on-surface-variant font-body-sm">Choose a provider below to start earning coins from paid surveys and micro tasks.</p>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-lg mb-xl">
            {{-- CPX Research Card --}}
            <div
                onclick="loadProvider('cpx')"
                class="group bg-surface-container border border-outline-variant rounded-lg p-lg cursor-pointer transition-all duration-200 hover:border-secondary hover:bg-surface-container-high hover:shadow-lg hover:shadow-secondary/5 active:scale-[0.98]"
            >
                <div class="flex items-start justify-between mb-md">
                    <div class="flex items-center gap-sm">
                        <span class="inline-flex items-center px-sm py-xs bg-secondary/10 text-secondary font-label-mono text-label-mono rounded-DEFAULT uppercase tracking-widest">Paid Surveys</span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-secondary transition-colors">arrow_forward</span>
                </div>

                <div class="flex items-center gap-md mb-md">
                    <div class="w-14 h-14 rounded-lg bg-surface-container-high border border-outline-variant flex items-center justify-center overflow-hidden">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" rx="8" fill="#1a1c1e"/>
                            <text x="20" y="25" text-anchor="middle" fill="#40e18c" font-size="14" font-weight="bold" font-family="monospace">CPX</text>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-title-lg text-title-lg-mobile text-on-surface">CPX Research</h3>
                        <p class="font-body-sm text-on-surface-variant">High-paying surveywall</p>
                    </div>
                </div>

                <p class="font-body-sm text-on-surface-variant mb-md">Access thousands of daily surveys from premium brands. Earn coins instantly upon completion with verified payouts.</p>

                <div class="flex items-center gap-sm">
                    <span class="material-symbols-outlined text-secondary text-[16px]">verified</span>
                    <span class="font-body-xs text-on-surface-variant">Instant coin payout</span>
                    <span class="mx-xs text-outline-variant">|</span>
                    <span class="material-symbols-outlined text-secondary text-[16px]">schedule</span>
                    <span class="font-body-xs text-on-surface-variant">5-20 min per survey</span>
                </div>
            </div>

            {{-- TimeWall Card (Coming Soon) --}}
            <div
                onclick="showComingSoonToast()"
                class="group bg-surface-container border border-outline-variant rounded-lg p-lg opacity-60 cursor-not-allowed pointer-events-none relative"
            >
                <div class="absolute top-md right-md">
                    <span class="inline-flex items-center px-sm py-xs bg-amber-500/20 text-amber-400 font-label-mono text-label-mono rounded-DEFAULT uppercase tracking-widest text-[10px]">Coming Soon</span>
                </div>
                <div class="flex items-start justify-between mb-md">
                    <div class="flex items-center gap-sm">
                        <span class="inline-flex items-center px-sm py-xs bg-secondary/10 text-secondary font-label-mono text-label-mono rounded-DEFAULT uppercase tracking-widest">Micro Tasks & Ads</span>
                    </div>
                </div>

                <div class="flex items-center gap-md mb-md">
                    <div class="w-14 h-14 rounded-lg bg-surface-container-high border border-outline-variant flex items-center justify-center overflow-hidden">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" rx="8" fill="#1a1c1e"/>
                            <text x="20" y="25" text-anchor="middle" fill="#40e18c" font-size="11" font-weight="bold" font-family="monospace">TW</text>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-title-lg text-title-lg-mobile text-on-surface">TimeWall</h3>
                        <p class="font-body-sm text-on-surface-variant">Micro tasks & ad engagement</p>
                    </div>
                </div>

                <p class="font-body-sm text-on-surface-variant mb-md">Complete micro tasks, watch ads, and engage with content to earn coins. Quick tasks with fast payouts and low minimum thresholds.</p>

                <div class="flex items-center gap-sm">
                    <span class="material-symbols-outlined text-secondary text-[16px]">bolt</span>
                    <span class="font-body-xs text-on-surface-variant">Quick micro tasks</span>
                    <span class="mx-xs text-outline-variant">|</span>
                    <span class="material-symbols-outlined text-secondary text-[16px]">speed</span>
                    <span class="font-body-xs text-on-surface-variant">Fast coin rewards</span>
                </div>
            </div>

            {{-- BitLabs Card (Coming Soon) --}}
            <div
                onclick="showComingSoonToast()"
                class="group bg-surface-container border border-outline-variant rounded-lg p-lg opacity-60 cursor-not-allowed pointer-events-none relative"
            >
                <div class="absolute top-md right-md">
                    <span class="inline-flex items-center px-sm py-xs bg-amber-500/20 text-amber-400 font-label-mono text-label-mono rounded-DEFAULT uppercase tracking-widest text-[10px]">Coming Soon</span>
                </div>
                <div class="flex items-start justify-between mb-md">
                    <div class="flex items-center gap-sm">
                        <span class="inline-flex items-center px-sm py-xs bg-secondary/10 text-secondary font-label-mono text-label-mono rounded-DEFAULT uppercase tracking-widest">Surveys & Offers</span>
                    </div>
                </div>

                <div class="flex items-center gap-md mb-md">
                    <div class="w-14 h-14 rounded-lg bg-surface-container-high border border-outline-variant flex items-center justify-center overflow-hidden">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" rx="8" fill="#1a1c1e"/>
                            <text x="20" y="25" text-anchor="middle" fill="#40e18c" font-size="10" font-weight="bold" font-family="monospace">BL</text>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-title-lg text-title-lg-mobile text-on-surface">BitLabs</h3>
                        <p class="font-body-sm text-on-surface-variant">Surveys & offerwall</p>
                    </div>
                </div>

                <p class="font-body-sm text-on-surface-variant mb-md">High-paying surveys and offerwall from BitLabs. Complete surveys, play games, and install apps to earn coins with instant payouts.</p>

                <div class="flex items-center gap-sm">
                    <span class="material-symbols-outlined text-secondary text-[16px]">star</span>
                    <span class="font-body-xs text-on-surface-variant">Top survey rates</span>
                    <span class="mx-xs text-outline-variant">|</span>
                    <span class="material-symbols-outlined text-secondary text-[16px]">paid</span>
                    <span class="font-body-xs text-on-surface-variant">Instant rewards</span>
                </div>
            </div>
        </section>

        <section class="mt-lg grid grid-cols-1 md:grid-cols-3 gap-md">
            <div class="bg-surface-container border border-outline-variant p-md rounded-lg">
                <div class="flex items-center gap-sm mb-sm">
                    <span class="material-symbols-outlined text-secondary text-[20px]">task_alt</span>
                    <span class="font-label-mono text-label-mono text-on-surface-variant uppercase">How It Works</span>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Choose a provider, complete tasks or surveys, and receive coins directly to your wallet upon completion.</p>
            </div>
            <div class="bg-surface-container border border-outline-variant p-md rounded-lg">
                <div class="flex items-center gap-sm mb-sm">
                    <span class="material-symbols-outlined text-secondary text-[20px]">speed</span>
                    <span class="font-label-mono text-label-mono text-on-surface-variant uppercase">Instant Payout</span>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Completed tasks are credited to your balance automatically via secure server-to-server postback.</p>
            </div>
            <div class="bg-surface-container border border-outline-variant p-md rounded-lg">
                <div class="flex items-center gap-sm mb-sm">
                    <span class="material-symbols-outlined text-secondary text-[20px]">verified</span>
                    <span class="font-label-mono text-label-mono text-on-surface-variant uppercase">Trusted Providers</span>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Both CPX Research and TimeWall are verified networks with thousands of daily earning opportunities.</p>
            </div>
        </section>
    </div>

    {{-- Iframe View (hidden by default) --}}
    <div id="provider-iframe-view" class="hidden">
        <div class="flex items-center gap-md mb-lg">
            <button
                onclick="goBack()"
                class="flex items-center gap-sm px-md py-sm bg-surface-container border border-outline-variant rounded-DEFAULT text-on-surface hover:text-secondary hover:border-secondary transition-all duration-150 active:scale-95"
            >
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                <span class="font-label-mono text-label-mono">Back to Providers</span>
            </button>
            <div class="flex items-center gap-sm">
                <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">poll</span>
                <span id="iframe-title" class="font-label-mono text-label-mono uppercase tracking-widest text-on-surface-variant"></span>
            </div>
        </div>

        <section class="bg-surface-container border border-outline-variant rounded-lg overflow-hidden">
            <div id="iframe-container" class="w-full" style="min-height: 850px;"></div>
        </section>
    </div>
</main>

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center h-16 bg-surface dark:bg-surface px-md pb-safe border-t border-outline-variant dark:border-outline-variant">
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('dashboard') }}">
        <span class="material-symbols-outlined">monetization_on</span>
        <span class="font-label-mono text-label-mono mt-xs">Earn</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('withdraw') }}">
        <span class="material-symbols-outlined">account_balance</span>
        <span class="font-label-mono text-label-mono mt-xs">Vault</span>
    </a>
    <a class="flex flex-col items-center justify-center text-secondary dark:text-secondary-fixed font-bold transition-transform active:scale-95" href="{{ route('surveys') }}">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">poll</span>
        <span class="font-label-mono text-label-mono mt-xs">Surveys</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('invite') }}">
        <span class="material-symbols-outlined">person_add</span>
        <span class="font-label-mono text-label-mono mt-xs">Invite</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('profile') }}">
        <span class="material-symbols-outlined">person</span>
        <span class="font-label-mono text-label-mono mt-xs">Profile</span>
    </a>
</nav>

<script>
    const providerUrls = {
        cpx: '{{ $cpxUrl }}',
        timewall: '{{ $timewallUrl }}',
    };

    const providerTitles = {
        cpx: 'CPX Research Offerwall',
        timewall: 'TimeWall Offerwall',
    };

    function loadProvider(provider) {
        const selection = document.getElementById('provider-selection');
        const iframeView = document.getElementById('provider-iframe-view');
        const container = document.getElementById('iframe-container');
        const title = document.getElementById('iframe-title');

        selection.classList.add('hidden');
        iframeView.classList.remove('hidden');
        title.textContent = providerTitles[provider];

        container.innerHTML = '<iframe width="100%" height="850" frameborder="0" src="' + providerUrls[provider] + '" class="w-full block border-0" allowfullscreen referrerpolicy="no-referrer-when-downgrade"></iframe>';

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function goBack() {
        const selection = document.getElementById('provider-selection');
        const iframeView = document.getElementById('provider-iframe-view');
        const container = document.getElementById('iframe-container');

        iframeView.classList.add('hidden');
        container.innerHTML = '';
        selection.classList.remove('hidden');

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showComingSoonToast() {
        const existing = document.getElementById('coming-soon-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'coming-soon-toast';
        toast.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-[9999] bg-surface-container-high border border-amber-500/30 text-amber-400 px-lg py-sm rounded-lg shadow-lg flex items-center gap-sm animate-bounce';
        toast.innerHTML = '<span class="material-symbols-outlined text-[18px]">info</span><span class="font-body-sm font-medium">This provider will be available after platform approval.</span>';
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.transition = 'opacity 0.3s'; toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
    }
</script>
@endsection
