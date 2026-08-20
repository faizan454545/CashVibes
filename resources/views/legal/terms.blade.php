@extends('layouts.app')

@section('title', 'Terms & Conditions - CashVibes')

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
        <h1 class="font-headline-lg text-headline-lg text-primary mb-lg">Terms & Conditions</h1>
        <p class="font-label-mono text-[11px] text-on-surface-variant mb-xl">Last updated: August 10, 2026</p>

        <div class="space-y-xl font-body-md text-body-md text-on-surface-variant leading-relaxed">
            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">1. Acceptance of Terms</h2>
                <p>By accessing or using CashVibes, you agree to be bound by these Terms & Conditions. If you do not agree to these terms, you may not use our platform.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">2. Eligibility</h2>
                <p>You must be at least 18 years old to use CashVibes. By creating an account, you represent and warrant that you meet this age requirement and have the legal capacity to enter into a binding agreement.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">3. Account Rules</h2>
                <p>Each user is permitted one account only. Creating multiple accounts, using VPNs, datacenters, or any method to circumvent our fraud detection is strictly prohibited and will result in immediate account termination without coin refund.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">4. Earning & Withdrawals</h2>
                <p>Coins earned through completing tasks, offers, and surveys are credited to your account balance. The minimum withdrawal threshold is 500 Coins (equivalent to Rs. 150 PKR). Withdrawal processing is subject to admin review and approval. CashVibes reserves the right to modify coin values and withdrawal thresholds at any time.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">5. Prohibited Activities</h2>
                <p>Users may not use bots, automated scripts, or any artificial means to complete tasks. Any form of fraud, manipulation, or abuse of the platform will result in permanent account suspension and forfeiture of all earned coins.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">6. Limitation of Liability</h2>
                <p>CashVibes provides the platform on an "as is" basis. We are not liable for any indirect, incidental, or consequential damages arising from your use of the platform. Our total liability shall not exceed the amount of coins in your account.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">7. Modifications</h2>
                <p>We reserve the right to modify these terms at any time. Continued use of the platform after changes constitutes acceptance of the modified terms. We will notify users of significant changes via email or platform notification.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">8. Contact</h2>
                <p>For questions about these Terms, contact us at <a href="mailto:support@cashvibes.online" class="text-secondary hover:underline">support@cashvibes.online</a>.</p>
            </section>
        </div>
    </div>
</main>
@endsection