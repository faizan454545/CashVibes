@extends('layouts.app')

@section('title', 'BitLabs Surveys - CashVibes')

@section('content')
<header class="w-full top-0 sticky z-50 bg-surface dark:bg-surface border-b border-outline-variant dark:border-outline-variant">
    <div class="flex items-center justify-between w-full max-w-container-max mx-auto px-3 sm:px-lg py-2 sm:py-md gap-2">
        <a href="{{ route('surveys') }}" class="flex items-center gap-2 min-w-0 flex-shrink-0">
            <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="CashVibes" class="w-8 h-8 sm:w-9 sm:h-9 object-contain flex-shrink-0">
            <h1 class="font-headline-md text-[14px] sm:text-headline-md-mobile font-bold tracking-tight text-on-surface dark:text-on-surface whitespace-nowrap hidden sm:block">CASHVIBES</h1>
        </a>
        <x-user-balance-badge />
    </div>
</header>

<main class="flex-grow w-full max-w-container-max mx-auto px-md md:px-lg py-xl pb-32">
    <div class="flex items-center gap-md mb-lg">
        <a href="{{ route('surveys') }}"
           class="flex items-center gap-sm px-md py-sm bg-surface-container border border-outline-variant rounded-DEFAULT text-on-surface hover:text-secondary hover:border-secondary transition-all duration-150 active:scale-95">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            <span class="font-label-mono text-label-mono">Back to Providers</span>
        </a>
        <div class="flex items-center gap-sm">
            <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">poll</span>
            <span class="font-label-mono text-label-mono uppercase tracking-widest text-on-surface-variant">BitLabs Surveys & Offers</span>
        </div>
    </div>

    <section class="bg-surface-container border border-outline-variant rounded-lg overflow-hidden">
        <div class="w-full" style="min-height: 850px;">
            <iframe
                width="100%"
                height="850"
                frameborder="0"
                src="{{ $iframeUrl }}"
                class="w-full block border-0"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"
                id="bitlabs-iframe"
                onload="checkBitLabsLoad()"
            ></iframe>
        </div>
    </section>

    <div id="bitlabs-fallback" class="hidden mt-lg bg-surface-container border border-outline-variant rounded-lg p-lg">
        <div class="flex items-start gap-md">
            <span class="material-symbols-outlined text-on-surface-variant" style="font-size: 24px;">info</span>
            <div>
                <h3 class="font-title-lg text-title-lg-mobile text-on-surface mb-xs">BitLabs Account Under Review</h3>
                <p class="font-body-sm text-on-surface-variant">BitLabs account is awaiting final publisher review. Please use CPX Research or TimeWall surveys in the meantime.</p>
                <a href="{{ route('surveys') }}" class="inline-flex items-center gap-sm mt-md px-md py-sm bg-secondary/10 text-secondary border border-secondary/30 rounded-DEFAULT font-label-mono text-label-mono hover:bg-secondary/20 transition-all duration-150 active:scale-95">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Back to Providers
                </a>
            </div>
        </div>
    </div>

    <script>
        let bitLabsLoadTimer = null;
        function checkBitLabsLoad() {
            clearTimeout(bitLabsLoadTimer);
            const iframe = document.getElementById('bitlabs-iframe');
            const fallback = document.getElementById('bitlabs-fallback');
            try {
                const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                const bodyText = iframeDoc.body ? iframeDoc.body.innerText : '';
                if (bodyText.toLowerCase().includes('publisher not verified') || bodyText.toLowerCase().includes('not verified')) {
                    iframe.classList.add('hidden');
                    fallback.classList.remove('hidden');
                }
            } catch (e) {
                bitLabsLoadTimer = setTimeout(function() {
                    iframe.classList.add('hidden');
                    fallback.classList.remove('hidden');
                }, 8000);
            }
        }
    </script>
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
@endsection
