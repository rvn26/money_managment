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

            <div class="flex items-center gap-2 my-2">
                <hr class="w-full border-zinc-200 dark:border-zinc-700">
                <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Atau') }}</span>
                <hr class="w-full border-zinc-200 dark:border-zinc-700">
            </div>

            <div class="flex items-center justify-center">
                <flux:button variant="outline" href="{{ url('auth/google') }}" class="w-full flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="size-5">
                        <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20c11.045 0 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
                        <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691z"/>
                        <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.222 0-9.649-3.342-11.298-8.015l-6.571 4.819C9.656 39.663 16.318 44 24 44z"/>
                        <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
                    </svg>
                    {{ __('Masuk dengan Google') }}
                </flux:button>
            </div>

            @if (Route::has('register'))
                <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                    <span>{{ __('Belum punya akun?') }}</span>
                    <flux:link :href="route('register')" wire:navigate>{{ __('Daftar Sekarang') }}</flux:link>
                </div>
            @endif
        @endguest
    </div>
</x-layouts.auth>
