<x-layouts.app :title="__('Dashboard')">
  @livewire('component.loginsuccess')
  @livewire('component.fitur-belum-ada')
  @livewire('pemasukan.tambah')
  @livewire('pengeluaran.tambah')
  @livewire('pengeluaran.scan')
  @livewire('tagihan.tambah')
  @livewire('component.set-batas-harian')
  @livewire('dashboard.index')
</x-layouts.app>