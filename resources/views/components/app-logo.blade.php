@props([
    'sidebar' => false,
])

@php
    // Warna biru toska sesuai gambar
    $logoContainerClasses = 'flex aspect-square size-10 items-center justify-center rounded-lg bg-zinc-900';
@endphp

@if ($sidebar)
    {{-- Menggunakan !text-white untuk memaksa warna putih (Important) --}}
    <flux:sidebar.brand class="!text-white !font-bold !leading-tight" {{ $attributes }}>
        <x-slot name="logo" class="{{ $logoContainerClasses }}">

            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-linear-to-br from-amber-400 to-amber-600 shadow-lg shadow-amber-500/20">
                <x-app-logo-icon class="h-7 fill-current text-neutral-950" />
            </div>

        </x-slot>
        <x-slot name="name">
            Aplikasi<br>Tuan Crab
        </x-slot>
    </flux:sidebar.brand>
@else
    {{-- Tambahkan !text-white juga di sini jika diperlukan --}}
    <flux:brand name="Aplikasi Tuan Crab" class="!text-white" {{ $attributes }}>
        <x-slot name="logo" class="{{ $logoContainerClasses }}">
            <x-app-logo-icon class="size-7 fill-current text-white" />
        </x-slot>
        Aplikasi<br>Tuan Crab
    </flux:brand>
@endif

<style>
    /* Jika !text-white masih gagal, gunakan CSS murni ini untuk menembus shadow DOM komponen */
    [data-flux-sidebar-brand] div,
    [data-flux-brand] div {
        color: white !important;
        font-size: 14px !important;
        /* Ukuran font disesuaikan agar pas 2 baris */
        font-weight: 700 !important;
        /* Membuat Bold */
        line-height: 1.2 !important;
        /* Mengatur jarak antar baris agar rapat */
        white-space: pre-line;
        /* Memungkinkan penggunaan baris baru */
    }
</style>
