<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Stok {{ $periode }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #333;
        }

        h2 {
            margin: 0 0 5px;
            font-size: 18px;
            color: #30251F;
        }

        .subtitle {
            margin-bottom: 20px;
            color: #666;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 7px 5px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #30251F;
            color: white;
            font-size: 8px;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f8f8;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 15px;
            color: #777;
            font-size: 8px;
        }
    </style>
</head>

<body>
    <h2>Riwayat Pergerakan Stok</h2>
    <div class="subtitle">
        Gerai Ikan Bilih Mario — Periode {{ $periode }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="center">No.</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Stok Sebelum</th>
                <th>Stok Sesudah</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($riwayatStok as $riwayat)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $riwayat->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $riwayat->produk->nama_produk ?? 'Produk tidak tersedia' }}</td>
                    <td>
                        @if ($riwayat->jenis === 'masuk')
                            Masuk
                        @elseif ($riwayat->jenis === 'keluar')
                            Keluar
                        @else
                            Penyesuaian
                        @endif
                    </td>
                    <td>{{ number_format($riwayat->jumlah, 0, ',', '.') }}</td>
                    <td>{{ number_format($riwayat->stok_sebelum, 0, ',', '.') }}</td>
                    <td>{{ number_format($riwayat->stok_sesudah, 0, ',', '.') }}</td>
                    <td>{{ $riwayat->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="center">
                        Tidak ada riwayat stok pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total riwayat: {{ $riwayatStok->count() }} catatan
    </div>
</body>

</html>
