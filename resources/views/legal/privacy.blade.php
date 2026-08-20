@extends('layouts.app')

@section('title', 'Privacy Policy - CashVibes')

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
        <h1 class="font-headline-lg text-headline-lg text-primary mb-lg">Privacy Policy</h1>
        <p class="font-label-mono text-[11px] text-on-surface-variant mb-xl">Last updated: August 10, 2026</p>

        <div class="space-y-xl font-body-md text-body-md text-on-surface-variant leading-relaxed">
            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">1. Information We Collect</h2>
                <p>We collect information you provide directly, including your name, email address, and payment account details when you register and request withdrawals. We also automatically collect your IP address, device information, and usage data for security and fraud prevention purposes.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">2. How We Use Your Information</h2>
                <p>Your information is used to operate and improve our platform, process transactions, detect and prevent fraud, communicate with you about your account, and comply with legal obligations. We use your IP address to enforce our one-account-per-user policy and maintain platform integrity.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">3. Information Sharing</h2>
                <p>We do not sell your personal information. We may share your data with trusted third-party service providers who assist in operating our platform, processing payments, and providing customer support. These providers are contractually obligated to protect your information.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">4. Data Security</h2>
                <p>We implement industry-standard security measures including SSL encryption, secure database storage, and access controls to protect your personal information. However, no method of transmission over the Internet is 100% secure.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">5. Cookies</h2>
                <p>We use cookies and similar technologies to maintain your session, remember your preferences, and analyze usage patterns. You can control cookie settings through your browser preferences.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">6. Your Rights</h2>
                <p>You have the right to access, correct, or delete your personal data. You may request account deletion by contacting our support team. Upon account deletion, your personal data will be removed from our active systems within 30 days.</p>
            </section>

            <section>
                <h2 class="font-headline-md text-headline-md text-on-surface mb-md">7. Contact Us</h2>
                <p>If you have questions about this Privacy Policy, please contact us at <a href="mailto:support@cashvibes.online" class="text-secondary hover:underline">support@cashvibes.online</a>.</p>
            </section>
        </div>
    </div>
</main>
@endsection