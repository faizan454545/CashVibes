<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v={{ time() }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}?v={{ time() }}">
    <title>@yield('title', 'CashVibes - Earn Rewards Online')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=JetBrains+Mono:wght@400;500&family=Hanken+Grotesk:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-secondary-fixed": "#00210f",
                        "on-primary-container": "#4d4e52",
                        "primary": "#dcdce0",
                        "on-tertiary-fixed": "#201a17",
                        "on-background": "#e3e2e5",
                        "error-container": "#93000a",
                        "on-surface": "#e3e2e5",
                        "surface-container": "#1f2022",
                        "error": "#ffb4ab",
                        "on-surface-variant": "#c6c6ca",
                        "on-primary-fixed": "#1a1c1f",
                        "secondary-container": "#02c473",
                        "surface-container-highest": "#343537",
                        "on-error": "#690005",
                        "tertiary-fixed": "#ece0da",
                        "tertiary": "#e5dad3",
                        "secondary": "#40e18c",
                        "secondary-fixed-dim": "#40e18c",
                        "on-secondary-container": "#004a28",
                        "on-secondary-fixed-variant": "#00522d",
                        "surface": "#121315",
                        "surface-container-low": "#1b1c1e",
                        "primary-container": "#c0c0c4",
                        "on-primary": "#2f3034",
                        "on-secondary": "#00391d",
                        "primary-fixed-dim": "#c6c6ca",
                        "surface-container-lowest": "#0d0e10",
                        "surface-variant": "#343537",
                        "tertiary-fixed-dim": "#cfc4be",
                        "on-error-container": "#ffdad6",
                        "inverse-surface": "#e3e2e5",
                        "inverse-on-surface": "#303033",
                        "on-tertiary-container": "#544d48",
                        "outline": "#909095",
                        "surface-bright": "#38393b",
                        "outline-variant": "#45474a",
                        "on-tertiary-fixed-variant": "#4d4541",
                        "surface-container-high": "#292a2c",
                        "on-primary-fixed-variant": "#45474a",
                        "on-tertiary": "#362f2b",
                        "secondary-fixed": "#63fea6",
                        "primary-fixed": "#e2e2e6",
                        "surface-tint": "#c6c6ca",
                        "surface-dim": "#121315",
                        "tertiary-container": "#c9beb8",
                        "background": "#121315",
                        "inverse-primary": "#5d5e62"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "xl": "40px",
                        "container-max": "1280px",
                        "xs": "4px",
                        "sm": "8px",
                        "unit": "4px",
                        "gutter": "20px",
                        "md": "16px",
                        "lg": "24px"
                    },
                    "fontFamily": {
                        "headline-md": ["Sora"],
                        "label-mono": ["JetBrains Mono"],
                        "body-md": ["Hanken Grotesk"],
                        "body-lg": ["Hanken Grotesk"],
                        "headline-lg-mobile": ["Sora"],
                        "headline-lg": ["Sora"],
                        "headline-xl": ["Sora"],
                        "body-sm": ["Hanken Grotesk"]
                    },
                    "fontSize": {
                        "headline-md": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}],
                        "label-mono": ["12px", {"lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "500"}],
                        "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "headline-lg-mobile": ["28px", {"lineHeight": "1.2", "fontWeight": "600"}],
                        "headline-lg": ["32px", {"lineHeight": "1.2", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "headline-xl": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-sm": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}]
                    }
                },
            },
        }
    </script>
    <style>
        body {
            background-color: #121315;
            color: #e3e2e5;
            -webkit-font-smoothing: antialiased;
        }
        .auth-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .scanning-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: #40e18c;
            opacity: 0.3;
            animation: scanning 4s linear infinite;
        }
        @keyframes scanning {
            0% { transform: translateY(-10px); }
            100% { transform: translateY(100vh); }
        }
        .input-focus-effect:focus-within {
            border-color: #40e18c !important;
        }
        .logo-tracking {
            letter-spacing: -0.05em;
        }
    </style>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>
<body class="font-body-md text-body-md overflow-x-hidden">
    <div class="fixed inset-0 pointer-events-none opacity-10">
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(#45474a 1px, transparent 1px); background-size: 32px 32px;"></div>
    </div>
    <div class="scanning-bar"></div>

    @yield('content')

    <footer class="fixed bottom-0 left-0 w-full py-sm px-lg border-t border-outline-variant/50 bg-surface/80 backdrop-blur-sm z-10">
        <div class="flex flex-wrap justify-center gap-x-md gap-y-xs">
            <a href="{{ route('legal.privacy') }}" class="font-label-mono text-[10px] text-on-surface-variant hover:text-secondary transition-colors uppercase">Privacy</a>
            <a href="{{ route('legal.terms') }}" class="font-label-mono text-[10px] text-on-surface-variant hover:text-secondary transition-colors uppercase">Terms</a>
            <a href="{{ route('legal.faq') }}" class="font-label-mono text-[10px] text-on-surface-variant hover:text-secondary transition-colors uppercase">FAQ</a>
            <a href="{{ route('legal.contact') }}" class="font-label-mono text-[10px] text-on-surface-variant hover:text-secondary transition-colors uppercase">Contact</a>
        </div>
    </footer>

    @if(session('banned'))
    <div id="ban-overlay" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 backdrop-blur-sm">
        <div class="bg-surface-container border-2 border-error rounded-lg p-xl w-full max-w-md mx-4 text-center">
            <div class="mb-lg">
                <span class="material-symbols-outlined text-error" style="font-size: 64px;" style="font-variation-settings: 'FILL' 1;">block</span>
            </div>
            <h2 class="font-headline-lg text-headline-lg text-error mb-md">Your account has been banned</h2>
            <div class="bg-surface-container-high border border-outline-variant rounded p-md mb-lg">
                <p class="font-label-mono text-label-mono text-on-surface-variant uppercase mb-xs">Ban Reason</p>
                <p class="font-body-md text-on-surface">{{ session('ban_reason', 'No reason provided.') }}</p>
            </div>
            <p class="font-body-sm text-on-surface-variant mb-lg">If you believe this is an error, please contact support.</p>
            <a href="{{ route('login') }}" class="block w-full py-md bg-error text-white font-label-mono text-label-mono font-bold uppercase tracking-widest hover:bg-error/80 transition-colors">
                Acknowledge & Return
            </a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.overflow = 'hidden';
            document.addEventListener('keydown', function(e) { e.preventDefault(); });
            document.addEventListener('contextmenu', function(e) { e.preventDefault(); });
        });
    </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.querySelector('button[type="submit"]');
            if (btn) {
                btn.addEventListener('mousedown', () => {
                    btn.style.letterSpacing = '0.1em';
                });
                btn.addEventListener('mouseup', () => {
                    btn.style.letterSpacing = 'normal';
                });
            }
        });
    </script>
</body>
</html>
