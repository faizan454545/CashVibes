<footer class="border-t border-outline-variant bg-surface-container-low mt-xl pb-20 md:pb-0">
    <div class="max-w-container-max mx-auto px-4 sm:px-8 py-xl">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-lg mb-xl">
            <div class="col-span-2 sm:col-span-1">
                <div class="flex items-center gap-sm mb-md">
                    <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="CashVibes" class="w-6 h-6 object-contain flex-shrink-0">
                    <span class="font-headline-md text-headline-md font-bold text-on-surface">CASHVIBES</span>
                </div>
                <p class="font-body-sm text-on-surface-variant">Earn real rewards by completing tasks, surveys, and offers.</p>
            </div>

            <div>
                <h4 class="font-label-mono text-label-mono text-on-surface uppercase tracking-wider mb-md">Legal</h4>
                <ul class="space-y-sm">
                    <li><a href="{{ route('legal.privacy') }}" class="font-body-sm text-on-surface-variant hover:text-secondary transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('legal.terms') }}" class="font-body-sm text-on-surface-variant hover:text-secondary transition-colors">Terms & Conditions</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-label-mono text-label-mono text-on-surface uppercase tracking-wider mb-md">Support</h4>
                <ul class="space-y-sm">
                    <li><a href="{{ route('legal.faq') }}" class="font-body-sm text-on-surface-variant hover:text-secondary transition-colors">FAQ</a></li>
                    <li><a href="{{ route('legal.contact') }}" class="font-body-sm text-on-surface-variant hover:text-secondary transition-colors">Contact Us</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-label-mono text-label-mono text-on-surface uppercase tracking-wider mb-md">Platform</h4>
                <ul class="space-y-sm">
                    <li><a href="{{ route('earn') }}" class="font-body-sm text-on-surface-variant hover:text-secondary transition-colors">Earn Coins</a></li>
                    <li><a href="{{ route('surveys') }}" class="font-body-sm text-on-surface-variant hover:text-secondary transition-colors">Surveys</a></li>
                    <li><a href="{{ route('withdraw') }}" class="font-body-sm text-on-surface-variant hover:text-secondary transition-colors">Withdraw</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-outline-variant pt-lg flex flex-col sm:flex-row justify-between items-center gap-sm">
            <p class="font-label-mono text-[11px] text-on-surface-variant">&copy; {{ date('Y') }} CashVibes. All rights reserved.</p>
            <p class="font-label-mono text-[10px] text-on-surface-variant">v{{ config('app.version') }}</p>
        </div>
    </div>
</footer>
