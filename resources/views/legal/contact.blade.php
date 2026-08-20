@extends('layouts.app')

@section('title', 'Contact Us - CashVibes')

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
    @if(session('success'))
    <div class="mb-lg p-md border border-secondary bg-secondary-container/10 rounded flex items-center gap-sm">
        <span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
        <p class="font-label-mono text-label-mono text-secondary">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
        <div class="md:col-span-2 border border-outline-variant bg-surface-container-low p-lg sm:p-xl">
            <h1 class="font-headline-lg text-headline-lg text-primary mb-lg">Contact Us</h1>
            <p class="font-body-md text-on-surface-variant mb-xl">Have a question or need help? Send us a message and we will get back to you within 24 hours.</p>

            <form method="POST" action="{{ route('legal.contact.submit') }}">
                @csrf
                <div class="space-y-lg">
                    <div>
                        <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">YOUR_NAME</label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                            class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
                        @error('name') <p class="font-label-mono text-[11px] text-error mt-xs">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">EMAIL_ADDRESS</label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                            class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
                        @error('email') <p class="font-label-mono text-[11px] text-error mt-xs">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">SUBJECT</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="e.g. Withdrawal Issue"
                            class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
                        @error('subject') <p class="font-label-mono text-[11px] text-error mt-xs">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">MESSAGE</label>
                        <textarea name="message" rows="5" required placeholder="Describe your issue or question..."
                            class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors resize-none">{{ old('message') }}</textarea>
                        @error('message') <p class="font-label-mono text-[11px] text-error mt-xs">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full py-md bg-primary text-on-primary font-label-mono text-label-mono font-bold tracking-widest hover:bg-white transition-colors active:scale-[0.98] uppercase">
                        SEND_MESSAGE
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-lg">
            <div class="border border-outline-variant bg-surface-container-low p-lg">
                <div class="flex items-center gap-sm mb-md">
                    <span class="material-symbols-outlined text-secondary">mail</span>
                    <h3 class="font-label-mono text-label-mono text-on-surface uppercase">Email</h3>
                </div>
                <a href="mailto:support@cashvibes.online" class="font-body-md text-secondary hover:underline">support@cashvibes.online</a>
            </div>

            <div class="border border-outline-variant bg-surface-container-low p-lg">
                <div class="flex items-center gap-sm mb-md">
                    <span class="material-symbols-outlined text-secondary">schedule</span>
                    <h3 class="font-label-mono text-label-mono text-on-surface uppercase">Response Time</h3>
                </div>
                <p class="font-body-md text-on-surface-variant">Within 24 hours</p>
            </div>

            <div class="border border-outline-variant bg-surface-container-low p-lg">
                <div class="flex items-center gap-sm mb-md">
                    <span class="material-symbols-outlined text-secondary">help</span>
                    <h3 class="font-label-mono text-label-mono text-on-surface uppercase">Quick Help</h3>
                </div>
                <a href="{{ route('legal.faq') }}" class="font-body-md text-secondary hover:underline">Check our FAQ page</a>
            </div>
        </div>
    </div>
</main>
@endsection