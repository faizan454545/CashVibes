@extends('admin.layouts.admin', ['title' => 'Admin Settings'])

@section('admin-content')
<div class="max-w-2xl">
    <section class="bg-surface-container border border-outline-variant rounded-lg p-lg mb-lg">
        <h3 class="font-headline-md text-headline-md text-on-surface mb-lg">Admin Profile</h3>

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-md">
                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">USERNAME</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $admin->name) }}"
                       required
                       class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
            </div>

            <div class="mb-md">
                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">EMAIL</label>
                <input type="email"
                       name="email"
                       value="{{ old('email', $admin->email) }}"
                       required
                       class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
            </div>

            <div class="my-lg h-[1px] bg-outline-variant"></div>
            <p class="font-label-mono text-label-mono text-on-surface-variant mb-md uppercase">Change Password (Optional)</p>

            <div class="mb-md">
                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">CURRENT_PASSWORD</label>
                <input type="password"
                       name="current_password"
                       placeholder="Enter current password"
                       class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
            </div>

            <div class="mb-md">
                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">NEW_PASSWORD</label>
                <input type="password"
                       name="new_password"
                       placeholder="Enter new password"
                       class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
            </div>

            <div class="mb-lg">
                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">CONFIRM_NEW_PASSWORD</label>
                <input type="password"
                       name="new_password_confirmation"
                       placeholder="Confirm new password"
                       class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors">
            </div>

            <button type="submit" class="w-full py-md bg-primary text-on-primary font-label-mono text-label-mono font-bold tracking-widest hover:bg-white transition-colors active:scale-[0.98] uppercase">
                Save Changes
            </button>
        </form>
    </section>

    <section class="bg-surface-container border border-outline-variant rounded-lg p-lg">
        <h3 class="font-headline-md text-headline-md text-on-surface mb-lg">reCAPTCHA Configuration</h3>

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-md">
                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">RECAPTCHA_SITE_KEY</label>
                <input type="text"
                       name="recaptcha_site_key"
                       value="{{ old('recaptcha_site_key', config('services.recaptcha.site_key')) }}"
                       placeholder="6Ld..."
                       class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors font-label-mono text-[12px]">
            </div>

            <div class="mb-lg">
                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">RECAPTCHA_SECRET_KEY</label>
                <input type="text"
                       name="recaptcha_secret_key"
                       value="{{ old('recaptcha_secret_key', config('services.recaptcha.secret_key')) }}"
                       placeholder="6Ld..."
                       class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors font-label-mono text-[12px]">
            </div>

            <button type="submit" class="w-full py-md bg-secondary/10 text-secondary border border-secondary/30 font-label-mono text-label-mono font-bold tracking-widest hover:bg-secondary/20 transition-colors active:scale-[0.98] uppercase">
                Update reCAPTCHA Keys
            </button>
        </form>
    </section>

    <section class="bg-surface-container border border-outline-variant rounded-lg p-lg">
        <h3 class="font-headline-md text-headline-md text-on-surface mb-lg">BitLabs Configuration</h3>

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-md">
                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">BITLABS_APP_TOKEN</label>
                <input type="text"
                       name="bitlabs_app_token"
                       value="{{ old('bitlabs_app_token', config('services.bitlabs.app_token')) }}"
                       placeholder="e.g. 1f354d36-439c-4bef-b78a-71a3a05c1a40"
                       class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors font-label-mono text-[12px]">
            </div>

            <div class="mb-md">
                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">BITLABS_SECRET_KEY</label>
                <input type="text"
                       name="bitlabs_secret_key"
                       value="{{ old('bitlabs_secret_key', config('services.bitlabs.secret_key')) }}"
                       placeholder="Your BitLabs Secret Key"
                       class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors font-label-mono text-[12px]">
            </div>

            <div class="mb-lg">
                <label class="block font-label-mono text-label-mono text-on-surface-variant mb-sm">BITLABS_SERVER_KEY</label>
                <input type="text"
                       name="bitlabs_server_key"
                       value="{{ old('bitlabs_server_key', config('services.bitlabs.server_key')) }}"
                       placeholder="Server-to-Server Key for postback hash verification"
                       class="w-full bg-surface-container-high border border-outline-variant p-md font-body-md text-on-surface placeholder:text-on-surface-variant/40 focus:border-secondary focus:outline-none transition-colors font-label-mono text-[12px]">
            </div>

            <div class="mb-lg p-md border border-outline-variant bg-surface-container-high rounded-DEFAULT">
                <p class="font-label-mono text-label-mono text-on-surface-variant mb-sm uppercase">POSTBACK URL</p>
                <p class="font-label-mono text-label-mono text-secondary text-[12px] break-all">{{ url('/api/postback/bitlabs') }}</p>
                <p class="font-body-xs text-on-surface-variant mt-sm">Set this as the Server-to-Server postback URL in your BitLabs dashboard under Integration &gt; Callbacks.</p>
            </div>

            <button type="submit" class="w-full py-md bg-secondary/10 text-secondary border border-secondary/30 font-label-mono text-label-mono font-bold tracking-widest hover:bg-secondary/20 transition-colors active:scale-[0.98] uppercase">
                Update BitLabs Keys
            </button>
        </form>
    </section>
</div>
@endsection
