<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v={{ time() }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}?v={{ time() }}">
    <title>@yield('title', 'Admin Panel') - CashVibes</title>
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
        body { background-color: #121315; color: #e3e2e5; }
        .zebra-table tr:nth-child(even) { background-color: #1b1c1e; }
        .sidebar-link.active { background-color: #1f2022; border-right: 3px solid #40e18c; color: #40e18c; }
        .sidebar-link:hover { background-color: #1f2022; }
    </style>
</head>
<body class="font-body-md text-body-md min-h-screen flex">
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-surface-container-low border-r border-outline-variant z-50 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-200">
        <div class="p-lg border-b border-outline-variant">
            <div class="flex items-center gap-sm">
                <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="CashVibes" class="w-[28px] h-[28px] object-contain flex-shrink-0">
                <div>
                    <h1 class="font-headline-md text-headline-md font-bold text-on-surface">CASHVIBES</h1>
                    <p class="font-label-mono text-[10px] text-secondary uppercase tracking-widest">Admin Panel</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 p-md flex flex-col gap-xs overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-sm px-md py-sm rounded text-on-surface-variant font-label-mono text-label-mono uppercase tracking-wider {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                Dashboard
            </a>
            <a href="{{ route('admin.tasks') }}" class="sidebar-link flex items-center gap-sm px-md py-sm rounded text-on-surface-variant font-label-mono text-label-mono uppercase tracking-wider {{ request()->routeIs('admin.tasks') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-[20px]">task_alt</span>
                Tasks
            </a>
            <a href="{{ route('admin.withdrawals') }}" class="sidebar-link flex items-center gap-sm px-md py-sm rounded text-on-surface-variant font-label-mono text-label-mono uppercase tracking-wider {{ request()->routeIs('admin.withdrawals') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-[20px]">account_balance</span>
                Withdrawals
            </a>
            <a href="{{ route('admin.messages') }}" class="sidebar-link flex items-center gap-sm px-md py-sm rounded text-on-surface-variant font-label-mono text-label-mono uppercase tracking-wider {{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-[20px]">mail</span>
                Messages
            </a>
            <a href="{{ route('admin.settings') }}" class="sidebar-link flex items-center gap-sm px-md py-sm rounded text-on-surface-variant font-label-mono text-label-mono uppercase tracking-wider {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-[20px]">settings</span>
                Settings
            </a>
            <div class="flex-1"></div>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-sm px-md py-sm rounded text-on-surface-variant font-label-mono text-label-mono uppercase tracking-wider hover:text-secondary">
                <span class="material-symbols-outlined text-[20px]">open_in_new</span>
                View Site
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-sm px-md py-sm rounded text-error font-label-mono text-label-mono uppercase tracking-wider hover:bg-error-container/20">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    Logout
                </button>
            </form>
        </nav>
    </aside>

    <div class="flex-1 lg:ml-64">
        <header class="sticky top-0 z-40 bg-surface border-b border-outline-variant px-lg py-md flex items-center justify-between">
            <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')" class="lg:hidden material-symbols-outlined text-on-surface">menu</button>
            <h2 class="font-headline-md text-headline-md text-on-surface">{{ $title ?? 'Dashboard' }}</h2>
            <div class="flex items-center gap-md">
                <a href="{{ route('dashboard') }}" target="_blank" class="hidden md:flex items-center gap-xs px-sm py-xs border border-outline-variant rounded text-[10px] font-label-mono text-on-surface-variant hover:text-secondary hover:border-secondary transition-colors uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                    View Site
                </a>
                <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">admin_panel_settings</span>
                <span class="font-label-mono text-label-mono text-on-surface-variant hidden md:inline">{{ Auth::guard('admin')->user()->name }}</span>
            </div>
        </header>

        <main class="p-lg md:p-xl">
            @if(session('success'))
            <div class="mb-lg p-md border border-secondary bg-secondary-container/10 rounded flex items-center gap-sm">
                <span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
                <p class="font-label-mono text-label-mono text-secondary">{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-lg p-md border border-error bg-error-container/10 rounded flex items-center gap-sm">
                <span class="material-symbols-outlined text-error text-[20px]">error</span>
                <p class="font-label-mono text-label-mono text-error">{{ $errors->first() }}</p>
            </div>
            @endif

            @yield('admin-content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
