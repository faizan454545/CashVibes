@extends('layouts.app')

@section('title', 'Dashboard - CashVibes')

@section('content')
<header class="w-full top-0 sticky z-[60] bg-surface dark:bg-surface border-b border-outline-variant dark:border-outline-variant">
    <div class="flex items-center justify-between w-full max-w-container-max mx-auto px-3 sm:px-lg py-2 sm:py-md gap-2">
        <div class="flex items-center gap-2 min-w-0 flex-shrink-0">
            <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="CashVibes" class="w-8 h-8 sm:w-9 sm:h-9 object-contain flex-shrink-0">
            <h1 class="font-headline-md text-[14px] sm:text-headline-md-mobile font-bold tracking-tight text-on-surface dark:text-on-surface whitespace-nowrap hidden sm:block">CASHVIBES</h1>
        </div>
        <x-user-balance-badge />
        <button onclick="toggleTheme()" class="cursor-pointer active:opacity-80 hover:bg-surface-container-highest transition-colors p-xs rounded-full flex-shrink-0">
            <span class="material-symbols-outlined text-on-surface text-[18px] sm:text-[20px]">contrast</span>
        </button>
    </div>
</header>

<main class="w-full max-w-container-max mx-auto pb-32">
    <div class="ticker-wrap mt-md">
        <div class="ticker py-sm font-label-mono text-label-mono text-secondary uppercase">
            <span class="mx-lg">&bull; TODAY'S EARNINGS: +{{ number_format($todayEarnings, 2) }} COINS</span>
            <span class="mx-lg">&bull; LIFETIME EARNED: {{ number_format($totalLifetimeEarnings, 2) }} COINS</span>
            <span class="mx-lg">&bull; PENDING WITHDRAWALS: {{ $pendingWithdrawals }}</span>
            <span class="mx-lg">&bull; COMPLETED TASKS: {{ $completedTasks }}</span>
            <span class="mx-lg">&bull; COIN VALUE: 1 COIN = {{ config('app.coin_value_pkr') }} PKR</span>
        </div>
    </div>

    <section class="mt-xl px-lg md:px-0">
        <div class="flex flex-col gap-sm mb-lg">
            <h2 class="font-label-mono text-label-mono text-outline uppercase tracking-widest">Global Analytics</h2>
        </div>
        <div class="bento-grid border border-outline-variant">
            <div class="bento-item md:col-span-2 group">
                <div class="flex flex-col h-full justify-between">
                    <div>
                        <p class="font-label-mono text-label-mono text-on-surface-variant uppercase mb-md">Total Earning</p>
                        <div class="flex items-baseline gap-sm">
                            <span id="stat-earnings" class="font-headline-xl text-headline-xl text-secondary">{{ number_format($totalLifetimeEarnings, 2) }}</span>
                            <span class="font-label-mono text-label-mono text-outline">Coins</span>
                        </div>
                        <p id="stat-earnings-pkr" class="font-label-mono text-[10px] text-outline mt-xs uppercase">&approx; {{ number_format($totalLifetimeEarnings * config('app.coin_value_pkr'), 2) }} PKR</p>
                    </div>
                    <div class="mt-xl">
                        <div class="w-full bg-surface-container-highest h-[2px] overflow-hidden">
                            <div id="stat-earnings-bar" class="bg-secondary h-full animate-[pulse_2s_infinite]" style="width: {{ $totalLifetimeEarnings > 0 ? min(($totalLifetimeEarnings / config('app.daily_target')) * 100, 100) : 0 }}%"></div>
                        </div>
                        <p id="stat-earnings-pct" class="mt-sm font-label-mono text-[10px] text-outline text-right uppercase">{{ number_format(min(($totalLifetimeEarnings / config('app.daily_target')) * 100, 100), 0) }}% of Daily Target Reached</p>
                    </div>
                </div>
            </div>

            <div class="bento-item">
                <p class="font-label-mono text-label-mono text-on-surface-variant uppercase mb-md">Completed Tasks</p>
                <div class="flex items-baseline gap-sm">
                    <span id="stat-completed" class="font-headline-lg text-headline-lg text-on-surface">{{ $completedTasks }}</span>
                    <span class="font-label-mono text-label-mono text-outline">done</span>
                </div>
                <div class="mt-md flex gap-xs">
                    @for($i = 0; $i < min($completedTasks, 4); $i++)
                    <div class="w-4 h-1 bg-secondary"></div>
                    @endfor
                    @if($completedTasks < 4)
                    <div class="w-4 h-1 bg-outline-variant"></div>
                    @endif
                </div>
            </div>

            <div class="bento-item">
                <p class="font-label-mono text-label-mono text-on-surface-variant uppercase mb-md">Pending Cashouts</p>
                <div class="flex items-baseline gap-sm">
                    <span class="font-headline-lg text-headline-lg text-error">{{ number_format($pendingAmount, 2) }}</span>
                    <span class="font-label-mono text-label-mono text-outline">PKR</span>
                </div>
                <p class="mt-md font-body-sm text-body-sm text-on-surface-variant">Processing queue: {{ $pendingWithdrawals }} requests active.</p>
            </div>

            <div class="bento-item">
                <p class="font-label-mono text-label-mono text-on-surface-variant uppercase mb-md">Total Withdrawn</p>
                <div class="flex items-baseline gap-sm">
                    <span class="font-headline-lg text-headline-lg text-secondary">PKR {{ number_format($totalWithdrawnPKR, 2) }}</span>
                </div>
                <p class="mt-md font-body-sm text-body-sm text-on-surface-variant">{{ number_format($totalWithdrawnCoins, 2) }} coins settled.</p>
            </div>
        </div>
    </section>

    <section class="mt-xl px-lg md:px-0">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
            <div class="md:col-span-2 border border-outline-variant bg-surface-container-low p-lg relative overflow-hidden min-h-[240px]">
                <div class="relative z-10">
                    <h3 class="font-label-mono text-label-mono text-on-surface uppercase mb-lg flex items-center gap-sm">
                        <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                        Recent Activity
                    </h3>
                    <div class="flex flex-col gap-md">
                        @forelse($recentTransactions->take(3) as $transaction)
                        <div class="flex justify-between items-end border-b border-outline-variant pb-xs">
                            <span class="font-label-mono text-label-mono text-outline">{{ $transaction->source_ref }}</span>
                            <span class="font-label-mono text-label-mono {{ $transaction->type === 'credit' ? 'text-secondary' : 'text-error' }}">
                                {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} COINS
                            </span>
                        </div>
                        @empty
                        <p class="font-label-mono text-label-mono text-on-surface-variant">No transactions yet. Start earning!</p>
                        @endforelse
                    </div>
                </div>
                <div class="absolute inset-0 opacity-20 pointer-events-none"></div>
            </div>

            <div class="border border-outline-variant bg-surface-container p-lg flex flex-col justify-between">
                <div>
                    <h3 class="font-label-mono text-label-mono text-on-surface uppercase mb-md">Quick Action</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mb-xl">Complete tasks and surveys to earn coins.</p>
                    <a href="{{ route('earn') }}" class="block w-full py-md bg-primary text-on-primary font-bold uppercase tracking-widest text-label-mono transition-transform active:scale-[0.98] hover:bg-white text-center">
                        Start Earning
                    </a>
                </div>
                <div class="mt-xl p-md border-t border-outline-variant">
                    <div class="flex items-center gap-md">
                        <div class="w-10 h-10 rounded-full border border-secondary flex items-center justify-center">
                            <span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                        </div>
                        <div>
                            <p class="font-label-mono text-label-mono text-on-surface">CASHVIBES</p>
                            <p class="font-body-sm text-body-sm text-outline">Secure Earning Platform</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($customTasks->count() > 0)
    <section class="mt-xl px-lg md:px-0">
        <div class="flex flex-col gap-sm mb-lg">
            <h2 class="font-label-mono text-label-mono text-outline uppercase tracking-widest">Custom Tasks</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
            @foreach($customTasks as $task)
            @php
                $userLog = $user->userTaskLogs()->where('custom_task_id', $task->id)->first();
            @endphp
            <div class="bg-surface-container border border-outline-variant rounded-lg p-lg flex flex-col justify-between hover:border-secondary/50 transition-colors">
                <div>
                    <div class="flex items-center gap-sm mb-sm">
                        <span class="material-symbols-outlined text-secondary text-[20px]">task_alt</span>
                        <h3 class="font-label-mono text-label-mono text-on-surface uppercase">{{ $task->title }}</h3>
                    </div>
                    @if($task->description)
                    <p class="font-body-sm text-on-surface-variant text-[13px] mb-md">{{ $task->description }}</p>
                    @endif
                    <div class="flex items-center gap-sm mb-md">
                        <span class="material-symbols-outlined text-secondary text-[16px]">monetization_on</span>
                        <span class="font-label-mono text-label-mono text-secondary">{{ $task->reward_coins }} Coins</span>
                    </div>
                </div>
                <div>
                    @if($userLog && $userLog->claimed)
                    <div class="w-full py-md bg-surface-container-high text-on-surface-variant font-label-mono text-label-mono text-center uppercase tracking-widest border border-outline-variant">
                        Claimed
                    </div>
                    @elseif($userLog && $userLog->visited)
                    <form method="POST" action="{{ route('task.claim', $task->id) }}">
                        @csrf
                        <button type="submit" class="w-full py-md bg-primary text-on-primary font-label-mono text-label-mono font-bold tracking-widest hover:bg-white transition-colors active:scale-[0.98] uppercase">
                            Claim Reward
                        </button>
                    </form>
                    @else
                    <a href="{{ route('task.visit', $task->id) }}" class="block w-full py-md bg-secondary/10 text-secondary border border-secondary/30 font-label-mono text-label-mono font-bold tracking-widest hover:bg-secondary/20 transition-colors active:scale-[0.98] uppercase text-center">
                        Start Task
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <section class="mt-xl px-lg md:px-0">
        <div class="flex flex-col gap-sm mb-lg">
            <h2 class="font-label-mono text-label-mono text-outline uppercase tracking-widest">Offerwalls</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
            <a href="{{ route('surveys') }}" class="bg-surface-container border border-outline-variant rounded-lg p-lg flex flex-col justify-between hover:border-secondary/50 transition-colors group">
                <div>
                    <div class="flex items-center gap-sm mb-sm">
                        <span class="material-symbols-outlined text-secondary text-[20px]">poll</span>
                        <h3 class="font-label-mono text-label-mono text-on-surface uppercase">Surveys Hub</h3>
                    </div>
                    <p class="font-body-sm text-on-surface-variant text-[13px] mb-md">CPX Research & TimeWall surveys with instant payouts.</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-secondary transition-colors">arrow_forward</span>
            </a>
            <a href="{{ route('surveys') }}" class="bg-surface-container border border-outline-variant rounded-lg p-lg flex flex-col justify-between hover:border-secondary/50 transition-colors group">
                <div>
                    <div class="flex items-center gap-sm mb-sm">
                        <span class="material-symbols-outlined text-secondary text-[20px]">bolt</span>
                        <h3 class="font-label-mono text-label-mono text-on-surface uppercase">TimeWall</h3>
                    </div>
                    <p class="font-body-sm text-on-surface-variant text-[13px] mb-md">Quick micro tasks and ad engagement for fast coins.</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-secondary transition-colors">arrow_forward</span>
            </a>
            <a href="{{ route('surveys.bitlabs') }}" class="bg-surface-container border border-outline-variant rounded-lg p-lg flex flex-col justify-between hover:border-secondary/50 transition-colors group">
                <div>
                    <div class="flex items-center gap-sm mb-sm">
                        <span class="material-symbols-outlined text-secondary text-[20px]">star</span>
                        <h3 class="font-label-mono text-label-mono text-on-surface uppercase">BitLabs</h3>
                    </div>
                    <p class="font-body-sm text-on-surface-variant text-[13px] mb-md">High-paying surveys, games, and app installs for coins.</p>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-secondary transition-colors">arrow_forward</span>
            </a>
        </div>
    </section>
</main>

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center h-16 bg-surface dark:bg-surface px-md pb-safe border-t border-outline-variant dark:border-outline-variant">
    <a class="flex flex-col items-center justify-center text-secondary dark:text-secondary-fixed font-bold transition-transform active:scale-95" href="{{ route('dashboard') }}">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">monetization_on</span>
        <span class="font-label-mono text-label-mono mt-xs">Earn</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('withdraw') }}">
        <span class="material-symbols-outlined">account_balance</span>
        <span class="font-label-mono text-label-mono mt-xs">Vault</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('surveys') }}">
        <span class="material-symbols-outlined">poll</span>
        <span class="font-label-mono text-label-mono mt-xs">Surveys</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('invite') }}">
        <span class="material-symbols-outlined">person_add</span>
        <span class="font-label-mono text-label-mono mt-xs">Invite</span>
    </a>
    <a class="flex flex-col items-center justify-center text-on-surface-variant dark:text-on-surface-variant hover:text-primary transition-transform active:scale-95" href="{{ route('profile') }}">
        <span class="material-symbols-outlined">person</span>
        <span class="font-label-mono text-label-mono mt-xs">Profile</span>
    </a>
</nav>

@push('scripts')
<script>
    function toggleTheme() {
        var html = document.documentElement;
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            html.classList.add('light');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.remove('light');
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }

    (function() {
        var currentUserId = {{ auth()->id() }};
        function refreshDashboardStats() {
            fetch('{{ route("api.ban-status") }}?t=' + Date.now(), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                cache: 'no-store'
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data || typeof data.coins !== 'number') return;
                if (data.user_id !== currentUserId) return;
                var badge = document.getElementById('live-balance');
                if (badge) {
                    badge.textContent = data.formatted_coins + ' Coins (Rs. ' + data.pkr_value + ')';
                }
            })
            .catch(function() {});
        }
        setInterval(refreshDashboardStats, 3000);
    })();
</script>
@endpush
@endsection
