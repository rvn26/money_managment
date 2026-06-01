<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-zinc-100 dark:bg-zinc-950 lg:p-3 lg:gap-3 lg:flex">
    <flux:sidebar sticky stashable
        class="border-zinc-200 bg-primary text-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900 lg:rounded-2xl lg:overflow-hidden lg:border lg:max-h-[calc(100vh-1.5rem)]">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate class="text-white!" />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Platform')" class="grid">
                <flux:sidebar.item icon="squares-2x2" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')" wire:navigate
                    class="text-white! dark:text-zinc-400! data-current:text-black! data-current:dark:text-white!">
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="document-arrow-up" :href="route('pengeluaran')"
                    :current="request()->routeIs('pengeluaran')" wire:navigate
                    class="text-white! dark:text-zinc-400! data-current:text-black! data-current:dark:text-white!">
                    {{ __('Pengeluaran') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="document-arrow-down" :href="route('pemasukan')"
                    :current="request()->routeIs('pemasukan')" wire:navigate
                    class="text-white! dark:text-zinc-400! data-current:text-black! data-current:dark:text-white!">
                    {{ __('Pemasukan') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="envelope" :href="route('tagihan')" :current="request()->routeIs('tagihan')"
                    wire:navigate
                    class="text-white! dark:text-zinc-400! data-current:text-black! data-current:dark:text-white!">
                    {{ __('Tagihan') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="envelope" :href="route('hutang')" :current="request()->routeIs('hutang')"
                    wire:navigate
                    class="text-white! dark:text-zinc-400! data-current:text-black! data-current:dark:text-white!">
                    {{ __('Hutang') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="banknotes" :href="route('hutang.saya')"
                    :current="request()->routeIs('hutang.saya')" wire:navigate
                    class="text-white! dark:text-zinc-400! data-current:text-black! data-current:dark:text-white!">
                    {{ __('Hutang Saya') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="user-group" :href="route('pertemanan')"
                    :current="request()->routeIs('pertemanan')" wire:navigate
                    class="text-white! dark:text-zinc-400! data-current:text-black! data-current:dark:text-white!">
                    {{ __('Teman') }}
                </flux:sidebar.item>

                <flux:sidebar.group expandable icon="clipboard-document-list"
                    class="grid text-white! [&_svg]:!text-white">
                    <x-slot name="heading">
                        <span class="text-white font-medium dark:text-zinc-400">Kategori</span>
                    </x-slot>
                    <flux:sidebar.item :href="route('kategori')" :current="request()->routeIs('kategori')"
                        wire:navigate
                        class="text-white! dark:text-zinc-400! data-current:text-black! data-current:dark:text-white!">
                        {{ __('Kategori Pengeluaran') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item :href="route('kategori.tagihan')"
                        :current="request()->routeIs('kategori.tagihan')" wire:navigate
                        class="text-white! dark:text-zinc-400! data-current:text-black! data-current:dark:text-white!">
                        {{ __('Kategori Tagihan') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />

        {{-- Tombol Logout menggantikan menu profil --}}
        <form method="POST" action="{{ route('logout') }}" class="px-2 pb-2">
            @csrf
            <button type="submit" data-test="logout-button"
                class="w-full gradient-sunset inline-flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-white/90 hover:bg-white/10 transition-colors">
                <flux:icon.arrow-right-start-on-rectangle class="size-5" />
                {{ __('Log Out') }}
            </button>
        </form>
    </flux:sidebar>

    {{-- Konten utama: header + slot --}}
    <div class="flex flex-col min-w-0 flex-1 lg:gap-3">
        <flux:header
            class="sticky top-0 lg:top-1 lg:z-50 bg-white text-zinc-900 shadow-sm border border-zinc-200 dark:bg-zinc-900 dark:text-white dark:border-zinc-800 lg:rounded-2xl">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            {{-- Theme toggle (default mengikuti sistem, klik untuk flip antara terang/gelap) --}}
            <button type="button" x-data
                @click="$flux.appearance = document.documentElement.classList.contains('dark') ? 'light' : 'dark'"
                class="inline-flex items-center justify-center size-9 rounded-lg text-zinc-500 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white transition-colors"
                aria-label="Ganti tema">
                <flux:icon.sun class="size-5 dark:hidden" />
                <flux:icon.moon class="size-5 hidden dark:block" />
            </button>

            {{-- Ikon Settings --}}
            <flux:button as="a" :href="route('profile.edit')" wire:navigate icon="cog-6-tooth" variant="ghost"
                square aria-label="Pengaturan" />

            {{-- Profil dengan nama + email --}}
            <flux:dropdown align="end" position="bottom">
                <button type="button"
                    class="flex items-center gap-3 rounded-full p-1 pl-3 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                    <div class="hidden text-right leading-tight sm:block">
                        <p class="text-sm font-semibold text-zinc-900 dark:text-white truncate max-w-[160px]">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate max-w-[160px]">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                    <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" size="sm" />
                </button>

                <flux:menu class="min-w-56">
                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                        <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                        <div class="grid flex-1 text-start text-sm leading-tight">
                            <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                            <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                        </div>
                    </div>

                    <flux:menu.separator />

                    <flux:menu.item :href="route('profile.edit')" icon="cog-6-tooth" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <main class="flex-1 min-w-0 px-3 pb-3 lg:px-8 lg:py-0 py-5">
            {{ $slot }}
        </main>
    </div>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.10.5/dist/autoNumeric.min.js"></script>

    @fluxScripts
    @stack('scripts')
    <script>
        // AutoNumeric Indonesian Rupiah format
        function initAutoNumeric() {
            document.querySelectorAll('[data-autonumeric]').forEach(function(el) {
                try {
                    if (!AutoNumeric.getAutoNumericElement(el)) {
                        new AutoNumeric(el, {
                            digitGroupSeparator: '.',
                            decimalCharacter: ',',
                            decimalPlaces: 0,
                            unformatOnSubmit: true,
                            currencySymbol: 'Rp ',
                            currencySymbolPlacement: 'p',
                        });
                    }
                } catch (e) {}
            });
        }

        document.addEventListener('DOMContentLoaded', initAutoNumeric);
        document.addEventListener('livewire:navigated', initAutoNumeric);
        document.addEventListener('livewire:init', function() {
            Livewire.hook('commit', ({ succeed }) => {
                succeed(() => setTimeout(initAutoNumeric, 100));
            });
        });
    </script>
    <script>
        function initAOS() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true,
                });
            }
        }

        document.addEventListener('livewire:navigated', initAOS);
        document.addEventListener('DOMContentLoaded', initAOS);
    </script>
</body>

</html>
