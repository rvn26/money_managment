<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Cetak laporan ke CSV (Spreadsheet).
     */
    public function csv(Request $request): StreamedResponse
    {
        $data = $this->validateAndCollect($request);
        $filename = 'laporan-keuangan_'.$data['rangeSlug'].'.csv';

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'wb');

            // BOM agar Excel mengenali UTF-8.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['Laporan Keuangan']);
            fputcsv($out, ['Pengguna', $data['user']->name]);
            fputcsv($out, ['Periode', $data['rangeLabel']]);
            fputcsv($out, ['Dicetak', now()->translatedFormat('d M Y H:i')]);
            fputcsv($out, []);

            if ($data['includePemasukan']) {
                fputcsv($out, ['Pemasukan']);
                fputcsv($out, ['Tanggal', 'Jenis', 'Total', 'Metode Pembayaran', 'Status', 'Deskripsi']);
                foreach ($data['pemasukan'] as $row) {
                    fputcsv($out, [
                        Carbon::parse($row->tanggal)->format('Y-m-d'),
                        $row->jenis,
                        $row->total,
                        $row->metode_pembayaran,
                        $row->status,
                        $row->deskripsi,
                    ]);
                }
                fputcsv($out, ['', '', 'TOTAL', $data['pemasukan']->sum('total')]);
                fputcsv($out, []);
            }

            if ($data['includePengeluaran']) {
                fputcsv($out, ['Pengeluaran']);
                fputcsv($out, ['Tanggal', 'Tujuan', 'Kategori', 'Total', 'Metode Pembayaran', 'Status', 'Deskripsi']);
                foreach ($data['pengeluaran'] as $row) {
                    fputcsv($out, [
                        Carbon::parse($row->tanggal_pengeluaran)->format('Y-m-d'),
                        $row->tujuan,
                        optional($row->kategori)->nama,
                        $row->total,
                        $row->metode_pembayaran,
                        $row->status,
                        $row->description,
                    ]);
                }
                fputcsv($out, ['', '', '', 'TOTAL', $data['pengeluaran']->sum('total')]);
                fputcsv($out, []);
            }

            if ($data['includeHutang']) {
                fputcsv($out, ['Hutang']);
                fputcsv($out, ['Tanggal Pinjaman', 'Kepada', 'Jumlah', 'Metode Pembayaran', 'Status', 'Catatan']);
                foreach ($data['hutang'] as $row) {
                    $kepada = $row->teman?->name ?: $row->nama;
                    fputcsv($out, [
                        Carbon::parse($row->tanggal_pinjaman)->format('Y-m-d'),
                        $kepada,
                        $row->jumlah,
                        $row->metode_pembayaran,
                        $row->status,
                        $row->catatan,
                    ]);
                }
                fputcsv($out, ['', '', 'TOTAL', $data['hutang']->sum('jumlah')]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Cetak laporan ke PDF (download file PDF asli via dompdf).
     */
    public function pdf(Request $request)
    {
        $data = $this->validateAndCollect($request);
        $filename = 'laporan-keuangan_'.$data['rangeSlug'].'.pdf';

        $pdf = Pdf::loadView('laporan.pdf', $data)->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    /**
     * @return array{
     *     user: \App\Models\User,
     *     dari: Carbon,
     *     sampai: Carbon,
     *     rangeLabel: string,
     *     rangeSlug: string,
     *     includePemasukan: bool,
     *     includePengeluaran: bool,
     *     includeHutang: bool,
     *     pemasukan: \Illuminate\Support\Collection,
     *     pengeluaran: \Illuminate\Support\Collection,
     *     hutang: \Illuminate\Support\Collection,
     * }
     */
    protected function validateAndCollect(Request $request): array
    {
        $request->validate([
            'periode' => ['required', Rule::in(['bulan_ini', 'setahun', 'custom'])],
            'tanggal_dari' => 'required_if:periode,custom|nullable|date',
            'tanggal_sampai' => 'required_if:periode,custom|nullable|date|after_or_equal:tanggal_dari',
            'sections' => 'required|array|min:1',
            'sections.*' => 'in:pemasukan,pengeluaran,hutang',
            'format' => ['required', Rule::in(['csv', 'pdf'])],
        ]);

        $user = Auth::user();

        [$dari, $sampai, $rangeLabel, $rangeSlug] = $this->resolveRange(
            $request->string('periode'),
            $request->input('tanggal_dari'),
            $request->input('tanggal_sampai'),
        );

        $sections = collect($request->input('sections', []));
        $includePemasukan = $sections->contains('pemasukan');
        $includePengeluaran = $sections->contains('pengeluaran');
        $includeHutang = $sections->contains('hutang');

        $pemasukan = $includePemasukan
            ? Pemasukan::where('id_user', $user->id)
                ->whereBetween('tanggal', [$dari->toDateString(), $sampai->toDateString()])
                ->orderBy('tanggal')
                ->get()
            : collect();

        $pengeluaran = $includePengeluaran
            ? Pengeluaran::with('kategori')
                ->where('id_user', $user->id)
                ->whereBetween('tanggal_pengeluaran', [$dari->toDateString(), $sampai->toDateString()])
                ->orderBy('tanggal_pengeluaran')
                ->get()
            : collect();

        $hutang = $includeHutang
            ? Hutang::with('teman')
                ->where('id_user', $user->id)
                ->whereBetween('tanggal_pinjaman', [$dari->toDateString(), $sampai->toDateString()])
                ->orderBy('tanggal_pinjaman')
                ->get()
            : collect();

        return [
            'user' => $user,
            'dari' => $dari,
            'sampai' => $sampai,
            'rangeLabel' => $rangeLabel,
            'rangeSlug' => $rangeSlug,
            'includePemasukan' => $includePemasukan,
            'includePengeluaran' => $includePengeluaran,
            'includeHutang' => $includeHutang,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'hutang' => $hutang,
        ];
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: string, 3: string}
     */
    protected function resolveRange(string $periode, ?string $dari, ?string $sampai): array
    {
        return match ($periode) {
            'setahun' => (function (): array {
                $start = now()->startOfYear();
                $end = now()->endOfYear();

                return [
                    $start,
                    $end,
                    'Tahun '.$start->year,
                    'tahun-'.$start->year,
                ];
            })(),
            'custom' => (function () use ($dari, $sampai): array {
                $start = Carbon::parse($dari)->startOfDay();
                $end = Carbon::parse($sampai)->endOfDay();

                return [
                    $start,
                    $end,
                    $start->translatedFormat('d M Y').' - '.$end->translatedFormat('d M Y'),
                    $start->format('Ymd').'-'.$end->format('Ymd'),
                ];
            })(),
            default => (function (): array {
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();

                return [
                    $start,
                    $end,
                    $start->translatedFormat('F Y'),
                    $start->format('Y-m'),
                ];
            })(),
        };
    }
}
