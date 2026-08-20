@php
    $freshBalance = auth()->user()->fresh()->coin_balance;
@endphp
@extends('layouts.app')

@section('title', 'Profile - CashVibes')

@section('content')
<header class="w-full top-0 sticky z-40 bg-surface border-b border-outline-variant cursor-default">
    <div class="flex items-center justify-between w-full max-w-container-max mx-auto px-3 sm:px-lg py-2 sm:py-md gap-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 min-w-0 flex-shrink-0">
            <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="CashVibes" class="w-8 h-8 sm:w-9 sm:h-9 object-contain flex-shrink-0">
            <h1 class="font-headline-md text-[14px] sm:text-headline-md-mobile font-bold tracking-tight text-on-surface whitespace-nowrap hidden sm:block">CASHVIBES</h1>
        </a>
        <x-user-balance-badge />
    </div>
</header>

<main class="max-w-container-max mx-auto px-lg py-xl grid grid-cols-1 lg:grid-cols-12 gap-lg">
    <aside class="lg:col-span-4 space-y-lg">
        <div class="border border-outline-variant p-lg bg-surface-container-low">
            <div class="flex items-center gap-md mb-xl">
                <div class="w-16 h-16 border border-outline-variant flex items-center justify-center bg-surface-container-highest overflow-hidden">
                    @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                    <span class="material-symbols-outlined text-primary" style="font-size: 32px;">person</span>
                    @endif
                </div>
                <div>
                    <p class="font-label-mono text-label-mono text-on-surface-variant mb-xs">USER_NAME</p>
                    <p class="font-headline-md text-headline-md text-primary">{{ $user->name }}</p>
                </div>
            </div>

            <div class="space-y-lg">
                <div class="group">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">ACCOUNT_EMAIL</label>
                    <div class="input-border-bottom pb-xs transition-all" style="border-bottom-color: rgb(69, 71, 74);">
                        <input class="w-full bg-transparent border-none p-0 focus:ring-0 text-primary font-body-md" readonly type="text" value="{{ auth()->user()->email }}">
                    </div>
                </div>

                <div class="group">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">WALLET_BALANCE</label>
                    <div class="input-border-bottom pb-xs transition-all" style="border-bottom-color: rgb(69, 71, 74);">
                        <input class="w-full bg-transparent border-none p-0 focus:ring-0 text-secondary font-headline-md font-bold" readonly type="text" value="{{ number_format($freshBalance, 2) }} Coins (Rs. {{ number_format($freshBalance * config('app.coin_value_pkr'), 2) }} PKR)">
                    </div>
                </div>

                <div class="group">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">JOINED_DATE</label>
                    <div class="input-border-bottom pb-xs transition-all" style="border-bottom-color: rgb(69, 71, 74);">
                        <input class="w-full bg-transparent border-none p-0 focus:ring-0 text-primary font-body-md" readonly type="text" value="{{ $user->created_at->format('Y.m.d H:i:s') }}">
                    </div>
                </div>

                <div class="group">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">LAST_LOGIN_IP</label>
                    <div class="input-border-bottom pb-xs transition-all" style="border-bottom-color: rgb(69, 71, 74);">
                        <input class="w-full bg-transparent border-none p-0 focus:ring-0 text-primary font-body-md" readonly type="text" value="{{ $user->last_login_ip ?? 'N/A' }}">
                    </div>
                </div>

                <div class="group">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">LAST_ACCESS</label>
                    <div class="input-border-bottom pb-xs transition-all" style="border-bottom-color: rgb(69, 71, 74);">
                        <input class="w-full bg-transparent border-none p-0 focus:ring-0 text-primary font-body-md" readonly type="text" value="{{ $user->last_login_at ? $user->last_login_at->format('Y.m.d H:i:s') : 'N/A' }}">
                    </div>
                </div>

                <div class="pt-md border-t border-outline-variant">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-md bg-error/20 border border-error text-error font-label-mono text-label-mono font-bold tracking-widest hover:bg-error hover:text-white transition-colors cursor-pointer uppercase">
                            TERMINATE SESSION
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <section class="lg:col-span-8 space-y-lg">
        <div class="flex items-center justify-between mb-md">
            <h2 class="font-headline-md text-headline-md text-primary flex items-center gap-sm">
                <span class="material-symbols-outlined">receipt_long</span>
                TRANSACTION_LEDGER
            </h2>
        </div>

        <div class="border border-outline-variant bg-surface overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left zebra-table border-collapse">
                    <thead>
                        <tr class="bg-surface-container-high border-b border-outline-variant">
                            <th class="px-md py-md font-label-mono text-label-mono text-on-surface-variant font-bold">DATE_TIME</th>
                            <th class="px-md py-md font-label-mono text-label-mono text-on-surface-variant font-bold">SOURCE_REF</th>
                            <th class="px-md py-md font-label-mono text-label-mono text-on-surface-variant font-bold text-right">COINS</th>
                            <th class="px-md py-md font-label-mono text-label-mono text-on-surface-variant font-bold text-right">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="font-label-mono text-label-mono">
                        @forelse($transactions as $transaction)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-highest transition-colors">
                            <td class="px-md py-md text-on-surface-variant">{{ $transaction->created_at->format('Y.m.d H:i') }}</td>
                            <td class="px-md py-md text-primary">
                                {{ $transaction->source_ref }}
                                @php
                                    $meta = is_string($transaction->metadata) ? json_decode($transaction->metadata, true) : $transaction->metadata;
                                @endphp
                                @if(is_array($meta) && !empty($meta['description']))
                                <span class="block text-[10px] text-on-surface-variant mt-xs">{{ $meta['description'] }}</span>
                                @endif
                            </td>
                            <td class="px-md py-md text-right {{ $transaction->type === 'credit' ? 'text-secondary' : 'text-error' }}">
                                {{ $transaction->formatted_amount }}
                            </td>
                            <td class="px-md py-md text-right">
                                @if($transaction->status === 'settled')
                                <span class="inline-flex items-center px-xs py-[2px] rounded text-[9px] font-label-mono uppercase bg-[#02c473]/20 text-[#02c473] border border-[#02c473]/30">Completed</span>
                                @elseif($transaction->status === 'pending')
                                <span class="inline-flex items-center px-xs py-[2px] rounded text-[9px] font-label-mono uppercase bg-secondary/10 text-secondary border border-secondary/20">Pending</span>
                                @else
                                <span class="inline-flex items-center px-xs py-[2px] rounded text-[9px] font-label-mono uppercase bg-error/10 text-error border border-error/20">{{ ucfirst($transaction->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr class="border-b border-outline-variant">
                            <td colspan="4" class="px-md py-md text-center text-on-surface-variant">No transactions yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-md py-md bg-surface-container-low flex justify-between items-center border-t border-outline-variant">
                <span class="font-label-mono text-label-mono text-on-surface-variant">PAGE_{{ str_pad($transactions->currentPage(), 2, '0', STR_PAD_LEFT) }}_OF_{{ str_pad($transactions->lastPage(), 2, '0', STR_PAD_LEFT) }}</span>
                <div class="flex gap-sm">
                    @if($transactions->previousPageUrl())
                    <a href="{{ $transactions->previousPageUrl() }}" class="p-xs border border-outline-variant hover:bg-surface-container-highest">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </a>
                    @else
                    <button class="p-xs border border-outline-variant disabled:opacity-30" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    @endif
                    @if($transactions->nextPageUrl())
                    <a href="{{ $transactions->nextPageUrl() }}" class="p-xs border border-outline-variant hover:bg-surface-container-highest">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center h-16 bg-surface border-t border-outline-variant px-md pb-safe">
    <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('dashboard') }}">
        <span class="material-symbols-outlined">monetization_on</span>
        <span class="font-label-mono text-label-mono">Earn</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('withdraw') }}">
        <span class="material-symbols-outlined">account_balance</span>
        <span class="font-label-mono text-label-mono">Vault</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('surveys') }}">
        <span class="material-symbols-outlined">poll</span>
        <span class="font-label-mono text-label-mono">Surveys</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('invite') }}">
        <span class="material-symbols-outlined">group_add</span>
        <span class="font-label-mono text-label-mono">Invite</span>
    </a>
    <a class="flex flex-col items-center justify-center text-secondary font-bold transition-transform active:scale-95" href="{{ route('profile') }}">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person</span>
        <span class="font-label-mono text-label-mono">Profile</span>
    </a>
</nav>

@push('styles')
<style>
    ::-webkit-scrollbar {
        width: 4px;
    }
    ::-webkit-scrollbar-track {
        background: #121315;
    }
    ::-webkit-scrollbar-thumb {
        background: #45474a;
    }
    .zebra-table tr:nth-child(even) {
        background-color: #1b1c1e;
    }
    .input-border-bottom {
        border-bottom: 1px solid #45474a;
    }
    .input-border-bottom:focus-within {
        border-bottom: 1px solid #dcdce0;
    }
</style>
@endpush

@endsection
