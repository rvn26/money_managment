<x-layouts.auth>
    <div class="flex flex-col gap-6">
        @auth
            <div class="flex flex-col gap-6 text-center py-8">
                <div class="flex justify-center">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/30">
                        <flux:icon.check-circle variant="solid" class="size-12 text-amber-600 dark:text-amber-500" />
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <flux:heading size="xl" class="font-bold tracking-tight">
                        {{ __('Sesi Kamu Masih Aktif') }}
                    </flux:heading>
                    <flux:subheading>
                        {{ __('Halo Kapten :name, kamu sudah berada di dalam brankas.', ['name' => auth()->user()->name]) }}
                    </flux:subheading>
                </div>

                <flux:button :href="route('dashboard')" variant="primary"
                    class="w-full bg-primary font-bold" wire:navigate>
                    {{ __('Kembali ke Dashboard') }}
                </flux:button>
            </div>
        @endauth
        @guest
            <div class="flex flex-col gap-1 text-center">
                <flux:heading size="xl" class="font-bold tracking-tight">
                    {{ __('Selamat Datang Kembali!') }}
                </flux:heading>
                <flux:subheading>
                    {{ __('Masukkan kuncimu untuk mengakses brankas.') }}
                </flux:subheading>
            </div>
            <!-- Session Status -->
            <x-auth-session-status class="text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
                @csrf

                <!-- Email Address -->
                <flux:input 
                name="email" 
                :label="__('Email address')" 
                :value="old('email')" 
                type="email" 
                required
                autofocus autocomplete="email" 
                placeholder="email@example.com" />

                <!-- Password -->
                <div class="relative">
                    <flux:input 
                    name="password" 
                    :label="__('Password')" 
                    type="password" 
                    required
                    autocomplete="current-password" 
                    :placeholder="__('Password')" 
                    viewable />

                    @if (Route::has('password.request'))
                        <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                            {{ __('Lupa password?') }}
                        </flux:link>
                    @endif
                </div>

                <!-- Remember Me -->
                <flux:checkbox 
                name="remember" 
                :label="__('Remember me')" 
                :checked="old('remember')" />

                <div class="flex items-center justify-end ">
                    <flux:button variant="primary" type="submit" class="w-full bg-primary" data-test="login-button">
                        {{ __('Masuk ke Brankas') }}
                    </flux:button>
                </div>
            </form>

            @if (Route::has('register'))
                <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                    <span>{{ __('Belum punya akun?') }}</span>
                    <flux:link :href="route('register')" wire:navigate>{{ __('Daftar Sekarang') }}</flux:link>
                </div>
            @endif
        @endguest
    </div>
</x-layouts.auth>
