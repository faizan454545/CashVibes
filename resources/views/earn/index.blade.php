@extends('layouts.app')

@section('title', 'Earn - CashVibes')

@section('content')
<header class="w-full top-0 sticky z-50 bg-surface dark:bg-surface border-b border-outline-variant dark:border-outline-variant">
    <div class="flex items-center justify-between w-full max-w-container-max mx-auto px-3 sm:px-lg py-2 sm:py-md gap-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 min-w-0 flex-shrink-0">
            <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="CashVibes" class="w-8 h-8 sm:w-9 sm:h-9 object-contain flex-shrink-0">
            <h1 class="font-headline-md text-[14px] sm:text-headline-md-mobile font-bold tracking-tight text-on-surface dark:text-on-surface uppercase whitespace-nowrap hidden sm:block">CASHVIBES</h1>
        </a>
        <x-user-balance-badge />
    </div>
</header>

<main class="max-w-container-max mx-auto px-lg pt-lg relative overflow-hidden">
    <section class="mb-lg">
        <h2 class="font-headline-lg text-headline-lg-mobile mb-xs">Earn Station</h2>
        <p class="text-on-surface-variant font-body-sm">Complete high-yield tasks from our verified partners.</p>
    </section>

    <div class="relative min-h-[600px] w-full rounded-xl overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-lg filter blur-lg opacity-30 select-none pointer-events-none">
            <div class="border border-outline-variant rounded-lg p-lg h-96 flex flex-col gap-md">
                <div class="h-12 w-32 bg-outline-variant rounded"></div>
                <div class="space-y-sm">
                    <div class="h-16 w-full bg-surface-container-highest rounded"></div>
                    <div class="h-16 w-full bg-surface-container-highest rounded"></div>
                    <div class="h-16 w-full bg-surface-container-highest rounded"></div>
                </div>
            </div>
            <div class="border border-outline-variant rounded-lg p-lg h-96 flex flex-col gap-md">
                <div class="h-12 w-32 bg-outline-variant rounded"></div>
                <div class="grid grid-cols-2 gap-sm">
                    <div class="h-24 bg-surface-container-highest rounded"></div>
                    <div class="h-24 bg-surface-container-highest rounded"></div>
                    <div class="h-24 bg-surface-container-highest rounded"></div>
                    <div class="h-24 bg-surface-container-highest rounded"></div>
                </div>
            </div>
            <div class="border border-outline-variant rounded-lg p-lg h-96 flex flex-col gap-md">
                <div class="h-12 w-32 bg-outline-variant rounded"></div>
                <div class="h-full bg-surface-container-highest rounded flex items-center justify-center">
                    <div class="w-1/2 h-4 bg-outline rounded"></div>
                </div>
            </div>
        </div>

        <div class="absolute inset-0 flex items-center justify-center p-md z-20">
            <div class="glass-panel max-w-lg w-full p-xl rounded-xl text-center border-t-2 border-t-secondary relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[2px] bg-secondary opacity-50 overflow-hidden">
                    <div class="shimmer w-full h-full"></div>
                </div>
                <div class="mb-lg">
                    <span class="material-symbols-outlined text-secondary scale-[2.5] mb-md block" style="font-variation-settings: 'FILL' 1;">construction</span>
                </div>
                <h3 class="font-headline-md text-headline-md mb-md text-on-surface">Task Station is Under Development</h3>
                <p class="font-body-md text-on-surface-variant mb-xl leading-relaxed">
                    We are currently integrating top-tier offerwalls <span class="text-primary font-bold">(Lootably, Monlix, TimeWall)</span> to give you the highest reward rates. This section will go live shortly. Stay tuned!
                </p>
                <div class="flex flex-col sm:flex-row gap-md justify-center">
                    <button class="bg-primary text-on-primary font-label-mono text-label-mono px-lg py-sm rounded-none tracking-widest uppercase active:scale-95 transition-transform">
                        Notify Me
                    </button>
                    <button class="border border-outline text-on-surface font-label-mono text-label-mono px-lg py-sm rounded-none tracking-widest uppercase hover:bg-surface-container-highest transition-colors">
                        View Roadmap
                    </button>
                </div>
            </div>
        </div>

        <div class="absolute inset-0 -z-10 bg-surface-container-lowest opacity-40">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(220, 220, 224, 0.05) 1px, transparent 1px); background-size: 32px 32px;"></div>
        </div>
    </div>

    <section class="mt-xl">
        <div class="flex items-center justify-between mb-md border-b border-outline-variant pb-xs">
            <h4 class="font-label-mono text-label-mono text-on-surface-variant uppercase tracking-widest">Incoming Providers</h4>
            <span class="font-label-mono text-label-mono text-secondary">Q3 2026</span>
        </div>
        <div class="zebra-striping border border-outline-variant rounded">
            @foreach($availableProviders as $provider)
            <div class="flex items-center justify-between p-md">
                <div class="flex items-center gap-md">
                    <div class="w-8 h-8 bg-surface-container-highest rounded border border-outline-variant flex items-center justify-center font-bold text-xs">{{ $provider['initial'] }}</div>
                    <span class="font-body-md">{{ $provider['name'] }}</span>
                </div>
                <span class="text-xs font-label-mono px-sm py-xs bg-surface-container rounded text-outline uppercase tracking-tighter">{{ ucfirst($provider['status']) }}</span>
            </div>
            @endforeach
        </div>
    </section>
</main>

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center h-16 bg-surface dark:bg-surface border-t border-outline-variant dark:border-outline-variant px-md pb-safe">
    <a class="flex flex-col items-center justify-center text-secondary dark:text-secondary-fixed font-bold transition-transform active:scale-95" href="{{ route('dashboard') }}">
        <span class="material-symbols-outlined" data-icon="monetization_on" style="font-variation-settings: 'FILL' 1;">monetization_on</span>
        <span class="font-label-mono text-label-mono mt-xs">Earn</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant transition-transform active:scale-95 hover:text-primary dark:hover:text-primary-fixed" href="{{ route('withdraw') }}">
        <span class="material-symbols-outlined" data-icon="account_balance">account_balance</span>
        <span class="font-label-mono text-label-mono mt-xs">Vault</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant transition-transform active:scale-95 hover:text-primary dark:hover:text-primary-fixed" href="{{ route('surveys') }}">
        <span class="material-symbols-outlined">poll</span>
        <span class="font-label-mono text-label-mono mt-xs">Surveys</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant transition-transform active:scale-95 hover:text-primary dark:hover:text-primary-fixed" href="{{ route('invite') }}">
        <span class="material-symbols-outlined" data-icon="group_add">group_add</span>
        <span class="font-label-mono text-label-mono mt-xs">Invite</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant transition-transform active:scale-95 hover:text-primary dark:hover:text-primary-fixed" href="{{ route('profile') }}">
        <span class="material-symbols-outlined" data-icon="person">person</span>
        <span class="font-label-mono text-label-mono mt-xs">Profile</span>
    </a>
</nav>

@push('styles')
<style>
    .glass-panel {
        background: rgba(22, 23, 26, 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(220, 220, 224, 0.1);
    }
    .shimmer {
        background: linear-gradient(90deg, transparent, rgba(220, 220, 224, 0.05), transparent);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .zebra-striping div:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.02);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('main');
        container.addEventListener('mousemove', (e) => {
            const amount = 5;
            const x = (e.clientX / window.innerWidth - 0.5) * amount;
            const y = (e.clientY / window.innerHeight - 0.5) * amount;
            const grid = document.querySelector('.filter.blur-lg');
            if (grid) {
                grid.style.transform = `translate3d(${x}px, ${y}px, 0)`;
            }
        });
    });
</script>
@endpush
@endsection
