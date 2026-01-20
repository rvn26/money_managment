<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div class="relative hidden h-full flex-col p-12 text-white lg:flex overflow-hidden">
            <div class="absolute inset-0 z-0 bg-neutral-950">
                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_var(--tw-gradient-stops))] from-neutral-800/40 via-neutral-950 to-neutral-950">
                </div>
            </div>

            <div class="absolute inset-0 z-0 opacity-10"
                style="background-image: radial-gradient(#ffffff 0.5px, transparent 0.5px); background-size: 24px 24px;">
            </div>

            <a href="{{ route('home') }}"
                class="relative z-20 flex items-center gap-3 text-xl font-bold tracking-tighter" wire:navigate>
                <div
                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-linear-to-br from-amber-400 to-amber-600 shadow-lg shadow-amber-500/20">
                    <x-app-logo-icon class="h-7 fill-current text-neutral-950" />
                </div>
                <span class="bg-linear-to-r from-white to-neutral-400 bg-clip-text text-transparent">
                    {{ config('app.name', 'KrustyKas') }}
                </span>
            </a>

            <div class="relative z-20 mt-24 space-y-6">
                <flux:heading size="xl" class="text-3xl font-semibold leading-tight text-white">
                    Kelola uang<br> <span class="text-amber-500">dengan disiplin kapten.</span>
                </flux:heading>
                <p class="max-w-md text-neutral-400 leading-relaxed">
                    Pantau setiap transaksi, lacak tagihan sebelum jatuh tempo, dan pastikan brankasmu selalu terisi
                    penuh.
                </p>
            </div>

            @php
                [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
            @endphp

            <div class="relative z-20 mt-auto">
                <div class="space-y-4 border-l-2 border-amber-500 pl-6">
                    <blockquote class="text-lg italic font-medium text-neutral-200">
                        &ldquo;{{ trim($message) }}&rdquo;
                    </blockquote>
                    <flux:heading class="text-xs tracking-[0.2em] uppercase text-neutral-500">
                        — {{ trim($author) }}
                    </flux:heading>
                </div>
            </div>
        </div>
        <div class="w-full lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden"
                    wire:navigate>
                    <span class="flex h-9 w-9 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                    </span>

                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                {{ $slot }}
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>
