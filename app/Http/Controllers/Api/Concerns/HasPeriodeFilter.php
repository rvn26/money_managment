<?php

namespace App\Http\Controllers\Api\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Filter rentang tanggal untuk API endpoints.
 * Default: bulan berjalan (bulan_ini).
 */
trait HasPeriodeFilter
{
    /**
     * Parse query parameters dan return rentang tanggal.
     *
     * Query params:
     * - periode: 'semua' | 'bulan_ini' | 'minggu_ini' | 'custom' (default: 'bulan_ini')
     * - bulan_custom: Format Y-m (contoh: 2024-06) - hanya digunakan jika periode = 'custom'
     *
     * @return array{0: Carbon, 1: Carbon}|null Null jika periode 'semua'
     */
    protected function getPeriodeRange(Request $request): ?array
    {
        $periode = $request->query('periode', 'bulan_ini');

        return match ($periode) {
            'semua' => null,
            'minggu_ini' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'custom' => $this->getCustomRange($request),
            default => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
        };
    }

    /**
     * Get custom month range from query parameter.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    protected function getCustomRange(Request $request): array
    {
        $bulanCustom = $request->query('bulan_custom', Carbon::now()->format('Y-m'));

        try {
            $base = Carbon::createFromFormat('Y-m', $bulanCustom)->startOfMonth();
        } catch (\Throwable $e) {
            $base = Carbon::now()->startOfMonth();
        }

        return [$base->copy()->startOfMonth(), $base->copy()->endOfMonth()];
    }

    /**
     * Apply periode filter to query.
     *
     * @param  string  $column  Column name untuk filter tanggal
     */
    protected function applyPeriodeFilter(Builder $query, string $column, Request $request): Builder
    {
        $rentang = $this->getPeriodeRange($request);

        if ($rentang === null) {
            return $query;
        }

        [$dari, $sampai] = $rentang;

        return $query->whereBetween($column, [
            $dari->toDateString(),
            $sampai->toDateString(),
        ]);
    }
}
