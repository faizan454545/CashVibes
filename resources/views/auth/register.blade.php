@extends('layouts.auth')

@section('title', 'Register - CashVibes')

@section('content')
<main class="auth-container px-md">
    <div class="w-full max-w-[420px] z-10 flex flex-col items-center">
        <div class="mb-xl text-center">
            <h1 class="font-headline-xl text-headline-xl text-primary logo-tracking uppercase">
                CASHVIBES
            </h1>
            <p class="font-label-mono text-label-mono text-secondary mt-xs tracking-widest uppercase">
                Institutional Precision
            </p>
        </div>

        @if($errors->any())
        <div class="w-full mb-lg p-md border border-error bg-error-container/20 rounded">
            <p class="font-label-mono text-label-mono text-error">{{ $errors->first() }}</p>
        </div>
        @endif

        <div class="w-full bg-surface-container-lowest border border-outline-variant p-lg shadow-2xl">
            <div class="mb-lg">
                <h2 class="font-headline-md text-headline-md text-on-surface">Create Account</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Initialize your node and start earning.</p>
            </div>

            <button id="google-register-btn"
               class="w-full border border-outline text-primary py-md font-label-mono text-label-mono hover:bg-surface-container-highest transition-colors active:scale-[0.98] uppercase flex items-center justify-center tracking-widest">
                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span id="google-btn-text">Continue with Google</span>
            </button>

            <div class="my-lg flex items-center gap-md">
                <div class="flex-1 h-[1px] bg-outline-variant"></div>
                <span class="font-label-mono text-[10px] text-on-surface-variant uppercase tracking-widest">or register with email</span>
                <div class="flex-1 h-[1px] bg-outline-variant"></div>
            </div>

            <form id="email-register-form" method="POST" action="{{ route('email.register') }}">
                @csrf
                <div class="mb-md">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">FULL_NAME</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autocomplete="name"
                           placeholder="Enter your name"
                           class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
                </div>

                <div class="mb-md">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">ACCOUNT_EMAIL</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           placeholder="user@gmail.com"
                           class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
                </div>

                <div class="mb-md">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">ACCESS_PASSWORD</label>
                    <div class="relative">
                        <input type="password"
                               id="reg-password"
                               name="password"
                               required
                               autocomplete="new-password"
                               placeholder="Min. 8 characters"
                               class="w-full bg-surface-container-high border border-outline-variant p-md pr-12 font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
                        <button type="button" id="toggle-reg-password"
                                class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors"
                                style="font-size: 20px;">
                            visibility
                        </button>
                    </div>
                    <div id="password-checklist" class="mt-sm space-y-[6px] opacity-0 max-h-0 overflow-hidden transition-all duration-300">
                        <div data-rule="length" class="flex items-center gap-sm text-[11px] font-label-mono text-on-surface-variant/50 transition-all duration-300">
                            <span class="checklist-icon w-4 h-4 rounded-full border border-outline-variant flex items-center justify-center text-[9px] transition-all duration-300"></span>
                            <span class="checklist-text">At least 8 characters</span>
                        </div>
                        <div data-rule="uppercase" class="flex items-center gap-sm text-[11px] font-label-mono text-on-surface-variant/50 transition-all duration-300">
                            <span class="checklist-icon w-4 h-4 rounded-full border border-outline-variant flex items-center justify-center text-[9px] transition-all duration-300"></span>
                            <span class="checklist-text">At least one uppercase letter (A-Z)</span>
                        </div>
                        <div data-rule="number" class="flex items-center gap-sm text-[11px] font-label-mono text-on-surface-variant/50 transition-all duration-300">
                            <span class="checklist-icon w-4 h-4 rounded-full border border-outline-variant flex items-center justify-center text-[9px] transition-all duration-300"></span>
                            <span class="checklist-text">At least one number (0-9)</span>
                        </div>
                        <div data-rule="special" class="flex items-center gap-sm text-[11px] font-label-mono text-on-surface-variant/50 transition-all duration-300">
                            <span class="checklist-icon w-4 h-4 rounded-full border border-outline-variant flex items-center justify-center text-[9px] transition-all duration-300"></span>
                            <span class="checklist-text">At least one special character (@, $, !, %, *, #, ?, &)</span>
                        </div>
                    </div>
                </div>

                <div class="mb-lg">
                    <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">CONFIRM_PASSWORD</label>
                    <div class="relative">
                        <input type="password"
                               id="reg-password-confirm"
                               name="password_confirmation"
                               required
                               autocomplete="new-password"
                               placeholder="Re-enter your password"
                               class="w-full bg-surface-container-high border border-outline-variant p-md pr-12 font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
                        <button type="button" id="toggle-reg-password-confirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors"
                                style="font-size: 20px;">
                            visibility
                        </button>
                    </div>
                </div>

                <button type="submit" id="email-register-btn" disabled
                    class="w-full py-md font-label-mono text-label-mono font-bold tracking-widest uppercase transition-colors active:scale-[0.98] bg-surface-container-highest text-on-surface-variant/40 cursor-not-allowed pointer-events-none">
                    INITIALIZE NODE
                </button>
            </form>

            <div class="mt-md text-center">
                <p class="font-label-mono text-[11px] text-on-surface-variant">
                    Already have an account? <a href="{{ route('login') }}" class="text-secondary hover:text-white transition-colors">Sign In</a>
                </p>
            </div>
        </div>

        <div class="mt-lg flex justify-between w-full px-xs">
            <p class="font-label-mono text-[10px] text-on-surface-variant uppercase">
                System Ver: {{ config('app.version') }}
            </p>
            <p class="font-label-mono text-[10px] text-on-surface-variant uppercase">
                &copy; 2026 CashVibes Corp.
            </p>
        </div>
    </div>
</main>

<div class="fixed right-lg top-1/2 -translate-y-1/2 hidden lg:flex flex-col gap-md opacity-20 select-none pointer-events-none">
    <div class="font-label-mono text-[10px] flex flex-col items-end">
        <span class="text-secondary">SYS_STATUS: ONLINE</span>
        <span class="text-on-surface-variant">PROTOCOL: SECURE</span>
    </div>
    <div class="h-64 w-[1px] bg-gradient-to-b from-transparent via-outline-variant to-transparent self-end"></div>
</div>

<div class="fixed left-lg top-1/2 -translate-y-1/2 hidden lg:flex flex-col gap-md opacity-20 select-none pointer-events-none">
    <div class="h-64 w-[1px] bg-gradient-to-b from-transparent via-outline-variant to-transparent self-start"></div>
    <div class="font-label-mono text-[10px] flex flex-col items-start">
        <span class="text-on-surface-variant">CASHVIBES</span>
        <span class="text-secondary">2026</span>
    </div>
</div>

<!-- Toast Container -->
<div id="firebase-toast" class="fixed top-4 right-4 z-[100] hidden">
    <div class="bg-surface-container-highest border border-error px-lg py-md rounded shadow-lg flex items-center gap-md max-w-[380px]">
        <span class="material-symbols-outlined text-error" style="font-size: 20px;">error</span>
        <p id="firebase-toast-message" class="font-label-mono text-label-mono text-on-surface text-[11px] leading-tight"></p>
    </div>
</div>

<!-- Firebase SDK -->
<script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.14.1/firebase-auth-compat.js"></script>

<script>
    function showToast(message, duration = 5000) {
        const toast = document.getElementById('firebase-toast');
        const toastMessage = document.getElementById('firebase-toast-message');
        toastMessage.textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => { toast.classList.add('hidden'); }, duration);
    }

    // Password toggles
    ['toggle-reg-password', 'toggle-reg-password-confirm'].forEach(id => {
        document.getElementById(id).addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                this.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                this.textContent = 'visibility';
            }
        });
    });

    // Password strength checklist
    const regPassword = document.getElementById('reg-password');
    const checklist = document.getElementById('password-checklist');
    const emailRegisterBtn = document.getElementById('email-register-btn');
    const emailRegisterForm = document.getElementById('email-register-form');
    let isPasswordValid = false;

    const rules = {
        length:   { el: checklist.querySelector('[data-rule="length"]'),   test: p => p.length >= 8 },
        uppercase:{ el: checklist.querySelector('[data-rule="uppercase"]'),test: p => /[A-Z]/.test(p) },
        number:   { el: checklist.querySelector('[data-rule="number"]'),   test: p => /[0-9]/.test(p) },
        special:  { el: checklist.querySelector('[data-rule="special"]'),  test: p => /[^A-Za-z0-9]/.test(p) }
    };

    function updateSubmitButton() {
        if (isPasswordValid) {
            emailRegisterBtn.disabled = false;
            emailRegisterBtn.classList.remove('bg-surface-container-highest', 'text-on-surface-variant/40', 'cursor-not-allowed', 'pointer-events-none');
            emailRegisterBtn.classList.add('bg-primary', 'text-on-primary', 'hover:bg-white');
        } else {
            emailRegisterBtn.disabled = true;
            emailRegisterBtn.classList.add('bg-surface-container-highest', 'text-on-surface-variant/40', 'cursor-not-allowed', 'pointer-events-none');
            emailRegisterBtn.classList.remove('bg-primary', 'text-on-primary', 'hover:bg-white');
        }
    }

    function highlightFailedRules() {
        const pw = regPassword.value;
        Object.values(rules).forEach(rule => {
            const met = rule.test(pw);
            const icon = rule.el.querySelector('.checklist-icon');
            if (!met) {
                rule.el.classList.remove('text-on-surface-variant/50', 'text-secondary');
                rule.el.classList.add('text-error');
                icon.classList.remove('border-outline-variant', 'border-secondary', 'bg-secondary/10');
                icon.classList.add('border-error', 'bg-error/10');
                icon.textContent = '✕';
            }
        });
    }

    regPassword.addEventListener('input', function() {
        const pw = this.value;
        if (pw.length > 0) {
            checklist.style.opacity = '1';
            checklist.style.maxHeight = '120px';
        } else {
            checklist.style.opacity = '0';
            checklist.style.maxHeight = '0';
        }
        let allMet = true;
        Object.values(rules).forEach(rule => {
            const met = rule.test(pw);
            const icon = rule.el.querySelector('.checklist-icon');
            if (met) {
                rule.el.classList.remove('text-on-surface-variant/50', 'text-error');
                rule.el.classList.add('text-secondary');
                icon.classList.remove('border-outline-variant', 'border-error', 'bg-error/10');
                icon.classList.add('border-secondary', 'bg-secondary/10', 'scale-110');
                icon.textContent = '✓';
            } else {
                rule.el.classList.remove('text-secondary', 'text-error');
                rule.el.classList.add('text-on-surface-variant/50');
                icon.classList.remove('border-secondary', 'bg-secondary/10', 'scale-110', 'border-error', 'bg-error/10');
                icon.classList.add('border-outline-variant');
                icon.textContent = '';
                allMet = false;
            }
        });
        isPasswordValid = allMet;
        updateSubmitButton();
    });

    emailRegisterForm.addEventListener('submit', function(e) {
        if (!isPasswordValid) {
            e.preventDefault();
            highlightFailedRules();
            regPassword.focus();
        }
    });

    // Firebase
    let firebaseReady = false;
    let auth = null;

    try {
        firebase.initializeApp({
            apiKey: "AIzaSyDIsKp1TcFnUw91JIMCVwS2GgS_m0AGqX4",
            authDomain: "cashvibes.firebaseapp.com",
            projectId: "cashvibes",
            storageBucket: "cashvibes.firebasestorage.app",
            messagingSenderId: "587133054651",
            appId: "1:587133054651:web:ec5db52374575315b8039e"
        });
        auth = firebase.auth();
        firebaseReady = true;
    } catch (e) {
        console.warn('Firebase init failed:', e);
        showToast('Google Sign-In unavailable. Use email registration below.');
    }

    document.getElementById('google-register-btn').addEventListener('click', async function() {
        if (!firebaseReady || !auth) {
            showToast('Google Sign-In is not configured for this domain. Use email registration.');
            return;
        }

        const btn = this;
        const btnText = document.getElementById('google-btn-text');
        btn.disabled = true;
        btn.classList.add('opacity-60', 'cursor-wait');
        btnText.textContent = 'Authenticating...';

        try {
            const provider = new firebase.auth.GoogleAuthProvider();
            const result = await auth.signInWithPopup(provider);
            const user = result.user;

            const response = await fetch('{{ route("firebase.callback") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    google_id: user.uid,
                    email: user.email,
                    name: user.displayName || user.email.split('@')[0],
                    avatar_url: user.photoURL || null
                })
            });

            const data = await response.json();
            if (data.success) {
                window.location.href = data.redirect || '{{ route("dashboard") }}';
            } else {
                showToast(data.message || 'Registration failed. Please try again.');
            }
        } catch (error) {
            if (error.code === 'auth/unauthorized-domain') {
                showToast('Google Sign-In not authorized for this domain. Use email registration below.');
            } else if (error.code === 'auth/popup-closed-by-user') {
                console.log('Popup closed.');
            } else {
                showToast('Google Sign-In failed: ' + (error.message || 'Unknown error') + '. Use email registration.');
            }
        } finally {
            btn.disabled = false;
            btn.classList.remove('opacity-60', 'cursor-wait');
            btnText.textContent = 'Continue with Google';
        }
    });
</script>
@endsection
