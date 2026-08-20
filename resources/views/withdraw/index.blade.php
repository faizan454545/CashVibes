@php
    $freshBalance = auth()->user()->fresh()->coin_balance;
@endphp
@extends('layouts.app')

@section('title', 'Vault - CashVibes')

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
    @if(session('success'))
    <div id="toast-success" class="mb-lg p-md border border-secondary bg-secondary-container/20 rounded flex justify-between items-center">
        <p class="font-label-mono text-label-mono text-secondary">{{ session('success') }}</p>
        <button onclick="this.parentElement.remove()" class="material-symbols-outlined text-secondary">close</button>
    </div>
    @endif

    @if($errors->any())
    <div id="toast-error" class="mb-lg p-md border border-error bg-error-container/20 rounded flex justify-between items-center">
        <p class="font-label-mono text-label-mono text-error">{{ $errors->first() }}</p>
        <button onclick="this.parentElement.remove()" class="material-symbols-outlined text-error">close</button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg">
        <div class="lg:col-span-7 space-y-lg">
            <section class="bg-surface-container border border-outline-variant p-lg relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="font-label-mono text-label-mono uppercase tracking-widest text-on-surface-variant mb-md">Current Liquidity</h2>
                    <div class="flex flex-col gap-xs">
                        <p class="font-headline-xl text-headline-xl text-primary leading-none">{{ number_format($freshBalance, 2) }} <span class="text-body-md font-normal text-on-surface-variant">Coins</span></p>
                        <div class="flex items-center gap-sm mt-sm">
                            <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                            <p class="font-body-lg text-body-lg text-secondary font-bold">&approx; {{ number_format($freshBalance * config('app.coin_value_pkr'), 2) }} PKR</p>
                        </div>
                    </div>
                </div>
                <div class="absolute top-0 left-0 w-full h-[1px] bg-secondary/20 animate-[scan_4s_linear_infinite]"></div>
            </section>

            @if($totalSettledPKR > 0)
            <section class="bg-surface-container border border-outline-variant p-lg">
                <h2 class="font-label-mono text-label-mono uppercase tracking-widest text-on-surface-variant mb-md">Total Settled Withdrawals</h2>
                <div class="flex flex-col gap-xs">
                    <p class="font-headline-lg text-headline-lg text-secondary leading-none">PKR {{ number_format($totalSettledPKR, 2) }}</p>
                    <p class="font-body-sm text-on-surface-variant">{{ number_format($totalSettledCoins, 2) }} coins successfully withdrawn.</p>
                </div>
            </section>
            @endif

            <section class="space-y-md">
                <h2 class="font-label-mono text-label-mono text-on-surface-variant uppercase">Select Payout Network</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md" id="gateway-options">
                    <label class="border-2 border-secondary bg-surface-container-high p-md cursor-pointer usdt-glow transition-all gateway-option active" data-gateway="easypaisa">
                        <input type="radio" name="payout_gateway" value="easypaisa" class="hidden" checked>
                        <div class="flex justify-between items-start mb-md">
                            <div class="w-12 h-12 flex items-center justify-center bg-surface-container-high p-1 rounded-lg border border-outline-variant/30 overflow-hidden">
                                <img src="{{ asset('assets/images/gateways/easypaisa.jpeg') }}?v={{ time() }}" alt="Easypaisa" class="w-full h-full object-contain">
                            </div>
                            <span class="material-symbols-outlined text-secondary">currency_exchange</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-primary mb-xs">Easypaisa</h3>
                        <p class="font-label-mono text-label-mono text-secondary">Pakistani Mobile Wallet</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-sm">Fee: {{ number_format(config('app.withdrawal_fee_easypaisa'), 2) }} PKR &bull; Instant</p>
                    </label>

                    <label class="border-2 border-secondary bg-surface-container-high p-md cursor-pointer usdt-glow transition-all gateway-option" data-gateway="jazzcash">
                        <input type="radio" name="payout_gateway" value="jazzcash" class="hidden">
                        <div class="flex justify-between items-start mb-md">
                            <div class="w-12 h-12 flex items-center justify-center bg-surface-container-high p-1 rounded-lg border border-outline-variant/30 overflow-hidden">
                                <img src="{{ asset('assets/images/gateways/jazzcash.jpeg') }}?v={{ time() }}" alt="JazzCash" class="w-full h-full object-contain">
                            </div>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-primary mb-xs">JazzCash</h3>
                        <p class="font-label-mono text-label-mono text-secondary">Pakistani Mobile Wallet</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-sm">Fee: {{ number_format(config('app.withdrawal_fee_jazzcash'), 2) }} PKR &bull; Instant</p>
                    </label>

                    <label class="border border-outline-variant bg-surface-container p-md opacity-50 cursor-not-allowed hover:bg-surface-container-highest transition-all gateway-option" data-gateway="binance_pay">
                        <input type="radio" name="payout_gateway" value="binance_pay" class="hidden">
                        <div class="flex justify-between items-start mb-md">
                            <div class="w-12 h-12 flex items-center justify-center bg-surface-container-high p-1 rounded-lg border border-outline-variant/30 overflow-hidden">
                                <img src="{{ asset('assets/images/gateways/binance.png') }}?v={{ time() }}" alt="Binance" class="w-full h-full object-contain">
                            </div>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface-variant mb-xs">Binance Pay</h3>
                        <p class="font-label-mono text-label-mono text-on-surface-variant">Crypto Transfer</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-sm">Status: Coming Soon</p>
                    </label>
                </div>
            </section>
        </div>

        <div class="lg:col-span-5">
            <section class="bg-surface-container border border-outline-variant p-lg space-y-lg sticky top-24">
                <div class="border-b border-outline-variant pb-md">
                    <h2 class="font-headline-md text-headline-md text-primary">Secure Cashout</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Authorized withdrawal gateway for verified vaults.</p>
                </div>

                <form action="{{ route('withdraw.store') }}" method="POST" class="space-y-lg">
                    @csrf
                    <input type="hidden" name="payout_gateway" id="selected_gateway" value="easypaisa">

                    <div class="group">
                        <label class="font-label-mono text-label-mono text-on-surface-variant mb-sm block">Conversion Amount (Coins)</label>
                        <div class="relative">
                            <input name="requested_coins" class="w-full bg-surface-container-low border-b border-outline-variant focus:border-secondary focus:ring-0 text-headline-md font-headline-md text-primary p-md transition-all outline-none" placeholder="0.00" type="number" min="{{ config('app.min_withdrawal_coins') }}" max="{{ $freshBalance }}" required>
                            <div class="absolute right-md top-1/2 -translate-y-1/2 font-label-mono text-secondary cursor-pointer" onclick="setMaxAmount()">MAX</div>
                        </div>
                        <p class="text-[10px] text-on-surface-variant mt-xs font-label-mono">Minimum: {{ config('app.min_withdrawal_coins') }} Coins</p>
                    </div>

                    <div>
                        <label class="font-label-mono text-label-mono text-on-surface-variant mb-sm block" id="account_label">Account Number</label>
                        <div class="flex items-center border border-outline-variant bg-surface-container-low px-md focus-within:border-secondary transition-all" id="account_field_wrapper">
                            <input name="account_number_or_id" class="flex-grow bg-transparent border-none focus:ring-0 text-body-md font-label-mono text-primary py-md outline-none" placeholder="03XXXXXXXXX" type="text" id="account_input" required>
                            <span class="material-symbols-outlined text-on-surface-variant cursor-pointer hover:text-secondary transition-colors">qr_code_scanner</span>
                        </div>
                        <p class="text-[10px] text-error mt-xs font-label-mono uppercase tracking-tighter" id="account_error">* Verify account carefully. Transactions are irreversible.</p>
                    </div>

                    <div id="account_title_group" class="group">
                        <label class="font-label-mono text-label-mono text-on-surface-variant mb-sm block">Account Title</label>
                        <div class="flex items-center border border-outline-variant bg-surface-container-low px-md focus-within:border-secondary transition-all">
                            <input name="account_title_receiver" class="flex-grow bg-transparent border-none focus:ring-0 text-body-md font-label-mono text-primary py-md outline-none" placeholder="Account Holder Name" type="text">
                        </div>
                    </div>

                    <div class="pt-md">
                        <button type="submit" class="w-full bg-primary hover:bg-white text-on-primary-fixed py-md font-headline-md text-headline-md tracking-tight active:scale-[0.98] transition-all flex items-center justify-center gap-md">
                            <span>Secure Cashout</span>
                            <span class="material-symbols-outlined">lock_open</span>
                        </button>
                        <div class="mt-lg flex items-center justify-center gap-sm text-on-surface-variant/40">
                            <span class="material-symbols-outlined text-sm">verified_user</span>
                            <span class="font-label-mono text-[10px] uppercase">Encrypted Terminal Session</span>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <div class="mt-xl border-t border-outline-variant pt-lg">
        <h3 class="font-label-mono text-label-mono text-on-surface-variant mb-md flex items-center gap-sm">
            <span class="w-1 h-3 bg-secondary"></span>
            Recent Transactions
        </h3>
        <div class="space-y-[1px] bg-outline-variant border border-outline-variant overflow-hidden">
            @forelse($recentTransactions as $tx)
            <div class="bg-surface-container-low px-md py-sm flex justify-between items-center group hover:bg-surface-container-high transition-colors">
                <div class="flex items-center gap-md">
                    @if($tx->status === 'settled')
                    <span class="font-label-mono body-sm {{ $tx->type === 'credit' ? 'text-secondary' : 'text-error' }}">
                        {{ $tx->type === 'credit' ? '+' : '-' }} {{ number_format($tx->amount, 2) }}
                    </span>
                    @elseif($tx->status === 'pending')
                    <span class="font-label-mono body-sm text-secondary/60">
                        {{ $tx->type === 'credit' ? '+' : '-' }} {{ number_format($tx->amount, 2) }}
                    </span>
                    @else
                    <span class="font-label-mono body-sm text-error/60">
                        {{ $tx->type === 'credit' ? '+' : '-' }} {{ number_format($tx->amount, 2) }}
                    </span>
                    @endif
                    <div>
                        <span class="font-body-sm text-on-surface">{{ str_replace('_', ' ', $tx->source_ref) }}</span>
                        @if($tx->provider)
                        <span class="font-label-mono text-[10px] text-outline ml-sm">via {{ ucfirst($tx->provider) }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-sm">
                    @if($tx->status === 'pending')
                    <span class="inline-flex items-center px-xs py-[2px] rounded text-[8px] font-label-mono uppercase bg-secondary/10 text-secondary border border-secondary/20">Pending</span>
                    @elseif($tx->status === 'settled')
                    <span class="inline-flex items-center px-xs py-[2px] rounded text-[8px] font-label-mono uppercase bg-[#02c473]/20 text-[#02c473] border border-[#02c473]/30">Completed</span>
                    @else
                    <span class="inline-flex items-center px-xs py-[2px] rounded text-[8px] font-label-mono uppercase bg-error/10 text-error border border-error/20">Rejected</span>
                    @endif
                    <span class="font-label-mono text-[10px] text-on-surface-variant">{{ $tx->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
            @empty
            <div class="bg-surface-container-low px-md py-sm text-center">
                <span class="font-label-mono text-label-mono text-on-surface-variant">No transactions yet</span>
            </div>
            @endforelse
        </div>
    </div>
</main>

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center h-16 bg-surface dark:bg-surface px-md pb-safe border-t border-outline-variant dark:border-outline-variant">
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant transition-transform active:scale-95" href="{{ route('dashboard') }}">
        <span class="material-symbols-outlined">monetization_on</span>
        <span class="font-label-mono text-label-mono">Earn</span>
    </a>
    <a class="flex flex-col items-center justify-center text-secondary dark:text-secondary-fixed font-bold transition-transform active:scale-95" href="{{ route('withdraw') }}">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">account_balance</span>
        <span class="font-label-mono text-label-mono">Vault</span>
    </a>
    <a href="{{ route('surveys') }}" class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant transition-transform active:scale-95">
        <span class="material-symbols-outlined">poll</span>
        <span class="font-label-mono text-label-mono">Surveys</span>
    </a>
    <a href="{{ route('invite') }}" class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant transition-transform active:scale-95">
        <span class="material-symbols-outlined">group_add</span>
        <span class="font-label-mono text-label-mono">Invite</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant transition-transform active:scale-95" href="{{ route('profile') }}">
        <span class="material-symbols-outlined">person</span>
        <span class="font-label-mono text-label-mono">Profile</span>
    </a>
</nav>

@push('styles')
<style>
    .usdt-glow {
        box-shadow: 0 0 15px -5px rgba(64, 225, 140, 0.3);
    }
    .gateway-option.active {
        border-color: #40e18c;
        box-shadow: 0 0 15px -5px rgba(64, 225, 140, 0.3);
    }
    .gateway-option:not(.active) {
        border-color: #45474a;
        opacity: 0.5;
    }
    @keyframes scan {
        0% { transform: translateY(0); opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { transform: translateY(160px); opacity: 0; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.gateway-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.gateway-option').forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('selected_gateway').value = this.dataset.gateway;

            const accountInput = document.getElementById('account_input');
            const accountLabel = document.getElementById('account_label');
            const accountTitleGroup = document.getElementById('account_title_group');

            if (this.dataset.gateway === 'binance_pay') {
                accountInput.placeholder = 'Enter Binance Pay ID (9-12 digits)';
                accountLabel.textContent = 'Binance Pay ID';
                accountTitleGroup.style.display = 'none';
            } else {
                accountInput.placeholder = '03XXXXXXXXX';
                accountLabel.textContent = 'Account Number';
                accountTitleGroup.style.display = 'block';
            }
        });
    });

    function setMaxAmount() {
        const maxBalance = {{ $freshBalance }};
        document.querySelector('input[name="requested_coins"]').value = Math.floor(maxBalance);
    }

    document.querySelector('input[name="account_number_or_id"]').addEventListener('input', function(e) {
        const gateway = document.getElementById('selected_gateway').value;
        const wrapper = document.getElementById('account_field_wrapper');
        const errorEl = document.getElementById('account_error');

        if (gateway === 'binance_pay') {
            if (e.target.value.length > 0 && !/^[0-9]+$/.test(e.target.value)) {
                wrapper.classList.add('border-error');
                wrapper.classList.remove('border-secondary');
                errorEl.textContent = '* Binance Pay ID must be numeric only.';
            } else if (e.target.value.length > 0 && (e.target.value.length < 9 || e.target.value.length > 12)) {
                wrapper.classList.add('border-error');
                wrapper.classList.remove('border-secondary');
                errorEl.textContent = '* Binance Pay ID must be 9-12 digits.';
            } else if (e.target.value.length > 0) {
                wrapper.classList.remove('border-error');
                wrapper.classList.add('border-secondary');
                errorEl.textContent = '* Verify account carefully. Transactions are irreversible.';
            } else {
                wrapper.classList.remove('border-error', 'border-secondary');
                errorEl.textContent = '* Verify account carefully. Transactions are irreversible.';
            }
        } else {
            if (e.target.value.length > 0 && !/^03[0-9]{9}$/.test(e.target.value)) {
                wrapper.classList.add('border-error');
                wrapper.classList.remove('border-secondary');
                errorEl.textContent = '* Must be a valid Pakistani number (03XXXXXXXXX, 11 digits).';
            } else if (e.target.value.length > 0) {
                wrapper.classList.remove('border-error');
                wrapper.classList.add('border-secondary');
                errorEl.textContent = '* Verify account carefully. Transactions are irreversible.';
            } else {
                wrapper.classList.remove('border-error', 'border-secondary');
                errorEl.textContent = '* Verify account carefully. Transactions are irreversible.';
            }
        }
    });

    // Auto-dismiss toasts after 5 seconds
    setTimeout(() => {
        const success = document.getElementById('toast-success');
        const error = document.getElementById('toast-error');
        if (success) success.remove();
        if (error) error.remove();
    }, 5000);
</script>
@endpush
@endsection
