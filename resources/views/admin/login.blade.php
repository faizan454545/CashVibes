@extends('layouts.auth')

@section('title', 'Admin Login - CashVibes')

@section('content')
<main class="auth-container px-md">
    <div class="w-full max-w-[420px] z-10 flex flex-col items-center">
        <div class="mb-xl text-center">
            <h1 class="font-headline-xl text-headline-xl text-primary logo-tracking uppercase">CASHVIBES</h1>
            <p class="font-label-mono text-label-mono text-secondary mt-xs tracking-widest uppercase">Admin Access Portal</p>
        </div>

        @if($errors->any())
        <div class="w-full mb-lg p-md border border-error bg-error-container/20 rounded">
            <p class="font-label-mono text-label-mono text-error">{{ $errors->first() }}</p>
        </div>
        @endif

        <div class="w-full bg-surface-container-lowest border border-outline-variant p-lg shadow-2xl">
            <div class="mb-lg">
                <h2 class="font-headline-md text-headline-md text-on-surface">Admin Authentication</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Enter your admin credentials to access the control panel.</p>
            </div>

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="mb-md">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">ADMIN_ID</label>
                    <input type="text"
                           name="username"
                           value="{{ old('username') }}"
                           required
                           autocomplete="username"
                           placeholder="Enter your username"
                           class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
                </div>

                <div class="mb-md">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">ACCESS_PASSWORD</label>
                    <input type="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="Enter admin password"
                           class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
                </div>

                <div class="mb-lg">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">VERIFICATION</label>
                    <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}" data-theme="dark"></div>
                </div>

                <button type="submit"
                    class="w-full py-md bg-primary text-on-primary font-label-mono text-label-mono font-bold tracking-widest hover:bg-white transition-colors active:scale-[0.98] uppercase">
                    AUTHENTICATE
                </button>
            </form>
        </div>

        <div class="mt-lg text-center">
            <a href="{{ route('login') }}" class="font-label-mono text-[11px] text-on-surface-variant hover:text-secondary transition-colors">
                &larr; Back to User Login
            </a>
        </div>
    </div>
</main>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
