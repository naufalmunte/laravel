<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Khas Minang</title>

    <style>
        @page {
            margin: 25px 28px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #30251F;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #30251F;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .brand {
            font-size: 22px;
            font-weight: bold;
            margin: 0 0 5px;
        }

        .subtitle {
            color: #777;
            font-size: 10px;
        }

        .period {
            margin-top: 10px;
            font-size: 11px;
            font-weight: bold;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .summary td {
            width: 25%;
            border: 1px solid #e5e7eb;
            padding: 10px;
            vertical-align: top;
        }

        .summary-label {
            font-size: 8px;
            color: #777;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 13px;
            font-weight: bold;
        }

        h3 {
            font-size: 12px;
            margin: 0 0 8px;
        }

        .transactions {
            width: 100%;
            border-collapse: collapse;
        }

        .transactions th {
            background: #30251F;
            color: #fff;
            font-size: 8px;
            padding: 8px 6px;
            text-align: left;
        }

        .transactions td {
            border: 1px solid #e5e7eb;
            padding: 7px 6px;
            vertical-align: top;
        }

        .transactions tr:nth-child(even) {
            background: #faf9f7;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .footer {
            margin-top: 16px;
            color: #777;
            font-size: 8px;
            text-align: right;
        }
    </style>
</head>
<body>
    @php
        $namaStatus = [
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'lunas' => 'Lunas',
            'belum_bayar' => 'Belum Dibayar',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'diproses' => 'Diproses',
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
        ];
    @endphp

    <div class="header">
        <p class="brand">KHAS MINANG</p>
        <div class="subtitle">Laporan Transaksi Penjualan</div>
        <div class="period">
            Periode {{ $namaBulan[(int) $bulan] }} {{ $tahun }}
        </div>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-label">Total Transaksi</div>
                <div class="summary-value">
                    {{ number_format($totalTransaksi, 0, ',', '.') }}
                </div>
            </td>
            <td>
                <div class="summary-label">Total Pendapatan</div>
                <div class="summary-value">
                    Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
                </div>
            </td>
            <td>
                <div class="summary-label">Transaksi Selesai</div>
                <div class="summary-value">
                    {{ number_format($totalSelesai, 0, ',', '.') }}
                </div>
            </td>
            <td>
                <div class="summary-label">Transaksi Dibatalkan</div>
                <div class="summary-value">
                    {{ number_format($totalDibatalkan, 0, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>

    <h3>Rincian Transaksi</h3>

    <table class="transactions">
        <thead>
            <tr>
                <th style="width: 4%;" class="center">No.</th>
                <th style="width: 17%;">Kode Pesanan</th>
                <th style="width: 19%;">Pelanggan</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 15%;">Pembayaran</th>
                <th style="width: 15%;">Status Pesanan</th>
                <th style="width: 18%;" class="right">Total</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pesanan as $item)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_pesanan }}</td>
                    <td>{{ $item->nama_penerima ?: ($item->pelanggan->name ?? '-') }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        {{ $namaStatus[$item->status_pembayaran] ?? ucfirst(str_replace('_', ' ', $item->status_pembayaran ?? 'Belum Dibayar')) }}
                    </td>
                    <td>
                        {{ $namaStatus[$item->status] ?? ucfirst(str_replace('_', ' ', $item->status ?? '-')) }}
                    </td>
                    <td class="right">
                        Rp{{ number_format($item->total, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">
                        Tidak ada transaksi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i') }}
        &bull; Khas Minang
    </div>
</body>
</html>
