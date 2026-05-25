<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e  border-zinc-200 bg-primary  dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate class="text-white!" />

            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Platform')" class="grid">
                <flux:sidebar.item icon="squares-2x2" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')" wire:navigate
                    class="
                        text-white!
                        dark:text-zinc-400!
                        data-current:text-black!
                        data-current:dark:text-white!
                        ">
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="document-arrow-up" :href="route('pengeluaran')"
                    :current="request()->routeIs('pengeluaran')" wire:navigate
                    class="
                         text-white!
                        dark:text-zinc-400!
                        data-current:text-black!
                        data-current:dark:text-white!
                        ">
                    {{ __('Pengeluaran') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="document-arrow-down" :href="route('pemasukan')"
                    :current="request()->routeIs('pemasukan')" wire:navigate
                    class="
                        text-white!
                        dark:text-zinc-400!
                        data-current:text-black!
                        data-current:dark:text-white!
                        ">
                    {{ __('pemasukan') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="envelope" :href="route('tagihan')" :current="request()->routeIs('tagihan')"
                    wire:navigate
                    class="
                        text-white!
                        dark:text-zinc-400!
                        data-current:text-black!
                        data-current:dark:text-white!
                        ">
                    {{ __('Tagihan') }}
                </flux:sidebar.item>
                <flux:sidebar.group expandable icon="clipboard-document-list"
                    class="grid text-white! [&_svg]:!text-white">
                    <x-slot name="heading">
                        <span class="text-white font-medium dark:text-zinc-400">Kategori</span>
                    </x-slot>
                    <flux:sidebar.item :href="route('kategori')" :current="request()->routeIs('kategori')"
                        wire:navigate
                        class="
                        text-white!
                        dark:text-zinc-400!
                        data-current:text-black!
                        data-current:dark:text-white!
                        ">
                        {{ __('Kategori Pengeluaran') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item :href="route('kategori.tagihan')"
                        :current="request()->routeIs('kategori.tagihan')" wire:navigate
                        class="
                        text-white!
                        dark:text-zinc-400!
                        data-current:text-black!
                        data-current:dark:text-white!
                        ">
                        {{ __('Kategori Tagihan') }}
                    </flux:sidebar.item>

                </flux:sidebar.group>

            </flux:sidebar.group>


        </flux:sidebar.nav>

        <flux:spacer />



        <x-desktop-user-menu class="hidden lg:flex !text-white !bg-white " :name="auth()->user()->name" />
    </flux:sidebar>


    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-white text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}
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
                } catch(e) {}
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
        // Fungsi inisialisasi agar bisa dipanggil berulang
        function initAOS() {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true,
                });
            }
        }

        // Jalankan saat navigasi Livewire (wire:navigate)
        document.addEventListener('livewire:navigated', initAOS);

        // Jalankan saat pemuatan halaman tradisional (refresh/login pertama kali)
        document.addEventListener('DOMContentLoaded', initAOS);
    </script>

</body>

</html>
