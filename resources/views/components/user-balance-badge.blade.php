@php
    $user = $user ?? auth()->user();
    $freshUser = $user->fresh();
    $balance = $freshUser ? $freshUser->coin_balance : $user->coin_balance;
    $pkr = $balance * config('app.coin_value_pkr');
@endphp
<div class="flex items-center gap-xs sm:gap-sm px-xs sm:px-md py-[2px] sm:py-xs bg-surface-container-high rounded-full border border-outline-variant flex-shrink-0 min-w-0">
    <span class="material-symbols-outlined text-secondary text-[14px] sm:text-[16px] flex-shrink-0" style="font-variation-settings: 'FILL' 1;">monetization_on</span>
    <span id="live-balance" class="font-label-mono text-secondary whitespace-nowrap truncate text-[10px] sm:text-[11px] md:text-label-mono">{{ number_format($balance, 2) }} Coins (Rs. {{ number_format($pkr, 2) }})</span>
</div>
