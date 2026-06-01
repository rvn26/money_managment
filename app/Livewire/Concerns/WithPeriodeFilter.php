<?php

namespace App\Livewire\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Filter rentang tanggal yang dipakai bersama oleh halaman Pengeluaran,
 * Pemasukan, Tagihan, dan Hutang. Default-nya adalah bulan berjalan.
 */
trait WithPeriodeFilter
{
    /** 'semua' | 'bulan_ini' | 'minggu_ini' | 'custom' */
    public string $periode = 'bulan_ini';

    /** Format Y-m (contoh: 2026-05). */
    public ?string $bulanCustom = null;

    public function mountWithPeriodeFilter(): void
    {
        if (! $this->bulanCustom) {
            $this->bulanCustom = now()->format('Y-m');
        }
    }

    public function updatedPeriode(): void
    {
        if (! in_array($this->periode, ['semua', 'bulan_ini', 'minggu_ini', 'custom'], true)) {
            $this->periode = 'bulan_ini';
        }

        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function updatedBulanCustom(): void
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    /**
     * @return array{0: Carbon, 1: Carbon}|null Null saat periode 'semua'.
     */
    protected function rentangPeriode(): ?array
    {
        return match ($this->periode) {
            'semua' => null,
            'minggu_ini' => [now()->startOfWeek(), now()->endOfWeek()],
            'custom' => (function (): array {
                $bulan = $this->bulanCustom ?: now()->format('Y-m');

                try {
                    $base = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
                } catch (\Throwable $e) {
                    $base = now()->startOfMonth();
                }

                return [$base->copy()->startOfMonth(), $base->copy()->endOfMonth()];
            })(),
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    /**
     * Terapkan filter rentang tanggal pada query.
     */
    protected function applyPeriodeScope(Builder $query, string $column): Builder
    {
        $rentang = $this->rentangPeriode();

        if ($rentang === null) {
            return $query;
        }

        [$dari, $sampai] = $rentang;

        return $query->whereBetween($column, [
            $dari->toDateString(),
            $sampai->toDateString(),
        ]);
    }

    /**
     * Label rentang tanggal aktif untuk ditampilkan di UI.
     */
    public function getLabelPeriodeProperty(): string
    {
        $rentang = $this->rentangPeriode();

        if ($rentang === null) {
            return 'Semua data';
        }

        [$dari, $sampai] = $rentang;

        return $dari->translatedFormat('d M Y').' — '.$sampai->translatedFormat('d M Y');
    }
}
