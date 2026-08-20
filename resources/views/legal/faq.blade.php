@extends('layouts.app')

@section('title', 'FAQ - CashVibes')

@section('content')
<header class="w-full top-0 sticky z-50 bg-surface border-b border-outline-variant">
    <div class="flex items-center justify-between w-full max-w-container-max mx-auto px-3 sm:px-lg py-2 sm:py-md gap-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 min-w-0 flex-shrink-0">
            <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="CashVibes" class="w-6 h-6 sm:w-[30px] sm:h-[30px] object-contain flex-shrink-0">
            <h1 class="font-headline-md text-[14px] sm:text-headline-md-mobile font-bold tracking-tight text-on-surface whitespace-nowrap hidden sm:block">CASHVIBES</h1>
        </a>
        <x-user-balance-badge />
    </div>
</header>

<main class="max-w-3xl mx-auto px-4 sm:px-8 py-xl">
    <div class="border border-outline-variant bg-surface-container-low p-lg sm:p-xl">
        <h1 class="font-headline-lg text-headline-lg text-primary mb-lg">Frequently Asked Questions</h1>

        <div class="space-y-lg">
            <div class="border-b border-outline-variant pb-lg">
                <h3 class="font-headline-md text-headline-md text-on-surface mb-sm">What is CashVibes?</h3>
                <p class="font-body-md text-on-surface-variant">CashVibes is a rewards platform where you earn coins by completing tasks, surveys, and offers. You can then withdraw your earnings as real money through EasyPaisa, JazzCash, or Binance Pay.</p>
            </div>

            <div class="border-b border-outline-variant pb-lg">
                <h3 class="font-headline-md text-headline-md text-on-surface mb-sm">How much is 1 Coin worth?</h3>
                <p class="font-body-md text-on-surface-variant">1 Coin = Rs. 0.30 PKR. The minimum withdrawal threshold is 500 Coins (Rs. 150 PKR).</p>
            </div>

            <div class="border-b border-outline-variant pb-lg">
                <h3 class="font-headline-md text-headline-md text-on-surface mb-sm">How do I earn coins?</h3>
                <p class="font-body-md text-on-surface-variant">You can earn coins by completing custom tasks (visit &amp; claim), answering surveys through CPX Research, TimeWall, and BitLabs offerwalls, and by inviting friends using your referral code.</p>
            </div>

            <div class="border-b border-outline-variant pb-lg">
                <h3 class="font-headline-md text-headline-md text-on-surface mb-sm">How do withdrawals work?</h3>
                <p class="font-body-md text-on-surface-variant">Navigate to the Vault page, select your payment gateway (EasyPaisa, JazzCash, or Binance Pay), enter your account details, and submit. Your request will be reviewed by our admin team. Approved withdrawals are typically processed within 24-48 hours.</p>
            </div>

            <div class="border-b border-outline-variant pb-lg">
                <h3 class="font-headline-md text-headline-md text-on-surface mb-sm">Can I create multiple accounts?</h3>
                <p class="font-body-md text-on-surface-variant">No. Each user is strictly limited to one account. Creating multiple accounts, using VPNs, or any form of multi-accounting will result in immediate and permanent ban without coin refund.</p>
            </div>

            <div class="border-b border-outline-variant pb-lg">
                <h3 class="font-headline-md text-headline-md text-on-surface mb-sm">My withdrawal was rejected. What happened?</h3>
                <p class="font-body-md text-on-surface-variant">Withdrawals may be rejected if the account details are incorrect, if there is suspicious activity detected, or if the withdrawal violates our terms. Your coins will be refunded automatically. Please contact support if you need clarification.</p>
            </div>

            <div class="border-b border-outline-variant pb-lg">
                <h3 class="font-headline-md text-headline-md text-on-surface mb-sm">How does the referral system work?</h3>
                <p class="font-body-md text-on-surface-variant">Share your referral code with friends. When they register using your code, you earn bonus coins. You can find your referral code on the Invite page.</p>
            </div>

            <div>
                <h3 class="font-headline-md text-headline-md text-on-surface mb-sm">How do I contact support?</h3>
                <p class="font-body-md text-on-surface-variant">Visit our <a href="{{ route('legal.contact') }}" class="text-secondary hover:underline">Contact Us</a> page to send us a message. We typically respond within 24 hours.</p>
            </div>
        </div>
    </div>
</main>
@endsection