<x-layouts.app :title="__('Dashboard')">
  @livewire('pemasukan.tambah')
  @livewire('pengeluaran.tambah')
  @livewire('tagihan.tambah')
  @livewire('component.set-batas-harian')
  @livewire('dashboard.index')
</x-layouts.app>