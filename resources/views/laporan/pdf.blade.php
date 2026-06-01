<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan — {{ $rangeLabel }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #111827;
            margin: 24px 32px;
            font-size: 12px;
        }
        h1, h2, h3 { margin: 0 0 8px; }
        h1 { font-size: 22px; }
        h2 { font-size: 16px; margin-top: 18px; padding-bottom: 6px; border-bottom: 1px solid #e5e7eb; }
        .meta { color: #4b5563; font-size: 11px; margin-bottom: 14px; }
        .meta strong { color: #111827; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 4px;
            font-size: 11px;
        }
        th, td {
            text-align: left;
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        thead th {
            background: #f9fafb;
            border-bottom: 1px solid #d1d5db;
            font-weight: 600;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #374151;
        }
        tfoot td { font-weight: 600; border-top: 2px solid #d1d5db; border-bottom: none; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            background: #f3f4f6;
            color: #374151;
        }
        .summary {
            display: flex;
            gap: 16px;
            margin: 14px 0 8px;
            flex-wrap: wrap;
        }
        .summary > div {
            flex: 1 1 30%;
            min-width: 180px;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        .summary p { margin: 0; }
        .summary .label { color: #6b7280; font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.05em; }
        .summary .value { color: #111827; font-size: 16px; font-weight: 700; margin-top: 4px; }
        .actions { margin-bottom: 18px; }
        .actions button {
            padding: 8px 14px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
        }
        .actions .primary { background: #111827; color: #ffffff; border-color: #111827; }
        @media print {
            body { margin: 12mm; }
            .actions { display: none; }
            h2 { page-break-after: avoid; }
            tr { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <h1>Laporan Keuangan</h1>
    <p class="meta">
        <strong>{{ $user->name }}</strong> &middot;
        Periode: {{ $rangeLabel }} &middot;
        Dicetak: {{ now()->translatedFormat('d M Y H:i') }}
    </p>

    @php
        $totalPemasukan = $includePemasukan ? $pemasukan->sum('total') : 0;
        $totalPengeluaran = $includePengeluaran ? $pengeluaran->sum('total') : 0;
        $totalHutang = $includeHutang ? $hutang->sum('jumlah') : 0;
        $rupiah = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    @endphp

    @if ($includePemasukan || $includePengeluaran || $includeHutang)
        <div class="summary">
            @if ($includePemasukan)
                <div>
                    <p class="label">Total Pemasukan</p>
                    <p class="value">{{ $rupiah($totalPemasukan) }}</p>
                </div>
            @endif
            @if ($includePengeluaran)
                <div>
                    <p class="label">Total Pengeluaran</p>
                    <p class="value">{{ $rupiah($totalPengeluaran) }}</p>
                </div>
            @endif
            @if ($includeHutang)
                <div>
                    <p class="label">Total Hutang</p>
                    <p class="value">{{ $rupiah($totalHutang) }}</p>
                </div>
            @endif
        </div>
    @endif

    @if ($includePemasukan)
        <h2>Pemasukan</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pemasukan as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}</td>
                        <td>{{ ucwords($row->jenis) }}</td>
                        <td><span class="badge">{{ $row->metode_pembayaran }}</span></td>
                        <td>{{ ucwords($row->status) }}</td>
                        <td>{{ $row->deskripsi }}</td>
                        <td class="text-right">{{ $rupiah($row->total) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:#6b7280;">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
            @if ($pemasukan->isNotEmpty())
                <tfoot>
                    <tr>
                        <td colspan="5">Total</td>
                        <td class="text-right">{{ $rupiah($totalPemasukan) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    @endif

    @if ($includePengeluaran)
        <h2>Pengeluaran</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tujuan</th>
                    <th>Kategori</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengeluaran as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal_pengeluaran)->translatedFormat('d M Y') }}</td>
                        <td>{{ $row->tujuan }}</td>
                        <td>{{ optional($row->kategori)->nama }}</td>
                        <td><span class="badge">{{ $row->metode_pembayaran }}</span></td>
                        <td>{{ ucwords($row->status) }}</td>
                        <td class="text-right">{{ $rupiah($row->total) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:#6b7280;">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
            @if ($pengeluaran->isNotEmpty())
                <tfoot>
                    <tr>
                        <td colspan="5">Total</td>
                        <td class="text-right">{{ $rupiah($totalPengeluaran) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    @endif

    @if ($includeHutang)
        <h2>Hutang</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal Pinjaman</th>
                    <th>Kepada</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($hutang as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal_pinjaman)->translatedFormat('d M Y') }}</td>
                        <td>{{ $row->teman?->name ?: $row->nama }}</td>
                        <td><span class="badge">{{ $row->metode_pembayaran }}</span></td>
                        <td>{{ str_replace('_', ' ', $row->status) }}</td>
                        <td>{{ $row->catatan }}</td>
                        <td class="text-right">{{ $rupiah($row->jumlah) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:#6b7280;">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
            @if ($hutang->isNotEmpty())
                <tfoot>
                    <tr>
                        <td colspan="5">Total</td>
                        <td class="text-right">{{ $rupiah($totalHutang) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    @endif

    <script>
        // Tidak ada lagi auto-print: dompdf akan menghasilkan PDF langsung.
    </script>
</body>
</html>
