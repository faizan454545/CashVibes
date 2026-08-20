<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v={{ time() }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}?v={{ time() }}">
    <title>@yield('title', 'CashVibes - Earn Rewards Online')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=JetBrains+Mono:wght@400;500&family=Hanken+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        body {
            background-color: #121315;
            color: #e3e2e5;
            overflow-x: hidden;
        }
        html.light body {
            background-color: #f5f5f5;
            color: #1a1a1a;
        }
        html.light .bg-surface { background-color: #f5f5f5; }
        html.light .bg-surface-container { background-color: #e8e8e8; }
        html.light .bg-surface-container-low { background-color: #ebebeb; }
        html.light .bg-surface-container-high { background-color: #e0e0e0; }
        html.light .bg-surface-container-highest { background-color: #d5d5d5; }
        html.light .bg-surface-container-lowest { background-color: #ffffff; }
        html.light .text-on-surface { color: #1a1a1a; }
        html.light .text-on-surface-variant { color: #4a4a4a; }
        html.light .text-primary { color: #1a1a1a; }
        html.light .text-outline { color: #6b6b6b; }
        html.light .border-outline-variant { border-color: #c0c0c0; }
        .ticker-wrap {
            width: 100%;
            overflow: hidden;
            background: #1b1c1e;
            border-top: 1px solid #45474a;
            border-bottom: 1px solid #45474a;
        }
        .ticker {
            display: inline-block;
            white-space: nowrap;
            padding-right: 100%;
            animation: ticker 30s linear infinite;
        }
        @keyframes ticker {
            0% { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-100%, 0, 0); }
        }
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background-color: #45474a;
        }
        .bento-item {
            background-color: #121315;
            padding: 24px;
        }
        @media (max-width: 768px) {
            .bento-grid {
                grid-template-columns: 1fr;
            }
        }
        .input-border-bottom {
            border-bottom: 1px solid #45474a;
        }
        .input-border-bottom:focus-within {
            border-bottom: 1px solid #dcdce0;
        }
        .zebra-table tr:nth-child(even) {
            background-color: #1b1c1e;
        }
    </style>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
    @stack('styles')
    <script>
        (function() {
            var saved = localStorage.getItem('theme');
            var html = document.documentElement;
            if (saved === 'light') {
                html.classList.remove('dark');
                html.classList.add('light');
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="font-body-md text-body-md selection:bg-secondary selection:text-on-secondary-fixed">
    @yield('content')
    @include('components.footer')

    <div id="ban-overlay" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/80 backdrop-blur-sm">
        <div class="bg-surface-container border-2 border-error rounded-lg p-xl w-full max-w-md mx-4 text-center">
            <div class="mb-lg">
                <span class="material-symbols-outlined text-error" style="font-size: 64px;">block</span>
            </div>
            <h2 class="font-headline-lg text-headline-lg text-error mb-md">Your Account Has Been Banned</h2>
            <div class="bg-surface-container-high border border-outline-variant rounded p-md mb-lg">
                <p class="font-label-mono text-label-mono text-on-surface-variant uppercase mb-xs">Ban Reason</p>
                <p id="ban-reason-text" class="font-body-md text-on-surface">{{ session('ban_reason', '') }}</p>
            </div>
            <p class="font-body-sm text-on-surface-variant mb-lg">If you believe this is an error, please contact our support team.</p>
            <a href="{{ route('legal.contact') }}" class="block w-full py-md bg-secondary text-on-primary font-label-mono text-label-mono font-bold uppercase tracking-widest hover:bg-secondary/80 transition-colors mb-sm items-center justify-center gap-sm">
                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">support_agent</span>
                Contact Help & Support
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full py-md bg-error/20 border border-error text-error font-label-mono text-label-mono uppercase tracking-widest hover:bg-error hover:text-white transition-colors">
                    Sign Out
                </button>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('button, a').forEach(el => {
            el.addEventListener('mousedown', () => {
                el.style.transform = 'scale(0.96)';
            });
            el.addEventListener('mouseup', () => {
                el.style.transform = 'scale(1)';
            });
            el.addEventListener('mouseleave', () => {
                el.style.transform = 'scale(1)';
            });
        });

        @if(session('banned'))
        (function() {
            var overlay = document.getElementById('ban-overlay');
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            document.body.style.overflow = 'hidden';
        })();
        @endif

        (function() {
            var checkInterval = 3000;
            var banShown = {{ session('banned') ? 'true' : 'false' }};
            var currentUserId = {{ auth()->id() }};

            function showBanOverlay(reason) {
                if (banShown) return;
                banShown = true;
                var overlay = document.getElementById('ban-overlay');
                var reasonEl = document.getElementById('ban-reason-text');
                if (reasonEl) reasonEl.textContent = reason;
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }

            function fetchUserStatus() {
                fetch('{{ route("api.ban-status") }}?t=' + Date.now(), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin',
                    cache: 'no-store'
                })
                .then(function(response) {
                    if (response.status === 403) {
                        return response.json().then(function(data) {
                            if (data.is_banned) {
                                showBanOverlay(data.ban_reason || 'Violation of Terms of Service');
                            }
                        });
                    }
                    return response.json();
                })
                .then(function(data) {
                    if (!data) return;
                    if (data.user_id !== currentUserId) return;

                    if (data.is_banned) {
                        showBanOverlay(data.ban_reason || 'Violation of Terms of Service');
                    }

                    if (typeof data.coins === 'number') {
                        var badge = document.getElementById('live-balance');
                        if (badge) {
                            badge.textContent = data.formatted_coins + ' Coins (Rs. ' + data.pkr_value + ')';
                        }
                    }
                })
                .catch(function() {});
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    fetchUserStatus();
                    setInterval(fetchUserStatus, checkInterval);
                });
            } else {
                fetchUserStatus();
                setInterval(fetchUserStatus, checkInterval);
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
