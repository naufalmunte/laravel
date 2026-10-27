@extends('layouts.admin')

@section('content')
    @php
        $daftarBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    @endphp

    <div class="min-w-0 space-y-5">
        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-800 sm:text-2xl">
                    Laporan Transaksi
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Ringkasan transaksi dan pendapatan Khas Minang.
                </p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form action="{{ route('admin.laporan.index') }}" method="GET"
                class="flex flex-col gap-3 sm:flex-row sm:items-end">

                <div class="w-full sm:max-w-[220px]">
                    <label for="bulan" class="mb-1.5 block text-xs font-semibold text-gray-600">
                        Bulan
                    </label>
                    <select name="bulan" id="bulan"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100">
                        @foreach ($daftarBulan as $nomor => $nama)
                            <option value="{{ $nomor }}" @selected((int) $bulan === $nomor)>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:max-w-[150px]">
                    <label for="tahun" class="mb-1.5 block text-xs font-semibold text-gray-600">
                        Tahun
                    </label>
                    <select name="tahun" id="tahun"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100">
                        @foreach (range(2026, max(2026, (int) date('Y'))) as $tahunPilihan)
                            <option value="{{ $tahunPilihan }}" @selected((int) $tahun === $tahunPilihan)>
                                {{ $tahunPilihan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-600">
                        Terapkan
                    </button>

                    <a href="{{ route('admin.laporan.index') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        Reset
                    </a>
                    <a href="{{ route('admin.laporan.export-pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#30251F] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#49382F]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v12m0 0 4-4m-4 4-4-4M5 17v3h14v-3" />
                        </svg>
                        Export PDF
                    </a>
                </div>
            </form>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
            <div class="min-w-0 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-gray-500">Total Transaksi</p>
                <p class="mt-2 text-xl font-bold text-gray-800">
                    {{ number_format($totalTransaksi, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-gray-400">Semua status</p>
            </div>

            <div class="min-w-0 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-gray-500">Total Pendapatan</p>
                <p class="mt-2 break-words text-lg font-bold text-emerald-700 sm:text-xl">
                    Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-gray-400">Pembayaran lunas</p>
            </div>

            <div class="min-w-0 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-gray-500">Transaksi Selesai</p>
                <p class="mt-2 text-xl font-bold text-blue-700">
                    {{ number_format($totalSelesai, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-gray-400">Status selesai</p>
            </div>

            <div class="min-w-0 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-gray-500">Transaksi Dibatalkan</p>
                <p class="mt-2 text-xl font-bold text-red-600">
                    {{ number_format($totalDibatalkan, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-gray-400">Status dibatalkan</p>
            </div>
        </div>

        {{-- Tabel transaksi --}}
        <div id="daftar-transaksi" class="min-w-0 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div
                class="flex flex-col gap-1 border-b border-gray-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-sm font-bold text-gray-800">Daftar Transaksi</h2>
                <p class="text-xs text-gray-500">
                    {{ $daftarBulan[(int) $bulan] ?? '-' }} {{ $tahun }}
                    <span class="mx-1">·</span>
                    {{ $pesanan->total() }} data
                </p>
            </div>

            <div class="w-full">
                <table class="w-full table-fixed text-left text-xs">
                    <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="w-[8%] px-2 py-3 font-semibold sm:px-3">No.</th>
                            <th class="w-[19%] px-2 py-3 font-semibold sm:px-3">Kode Pesanan</th>
                            <th class="w-[22%] px-2 py-3 font-semibold sm:px-3">Pelanggan</th>
                            <th class="w-[16%] px-2 py-3 font-semibold sm:px-3">Tanggal</th>
                            <th class="w-[17%] px-2 py-3 font-semibold sm:px-3">Pembayaran</th>
                            <th class="w-[18%] px-2 py-3 font-semibold sm:px-3">Total</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($pesanan as $item)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-2 py-3 text-gray-400 sm:px-3">
                                    {{ $pesanan->firstItem() + $loop->index }}
                                </td>

                                <td class="overflow-hidden px-2 py-3 font-semibold text-gray-800 sm:px-3">
                                    <span class="block truncate" title="{{ $item->kode_pesanan }}">
                                        {{ $item->kode_pesanan }}
                                    </span>
                                </td>

                                <td class="overflow-hidden px-2 py-3 sm:px-3">
                                    <span class="block truncate font-medium"
                                        title="{{ $item->nama_penerima ?: $item->pelanggan->name ?? '-' }}">
                                        {{ $item->nama_penerima ?: $item->pelanggan->name ?? '-' }}
                                    </span>
                                </td>

                                <td class="px-2 py-3 text-gray-500 sm:px-3">
                                    <span class="block whitespace-nowrap">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </span>
                                </td>

                                <td class="px-2 py-3 sm:px-3">
                                    @if ($item->status_pembayaran === 'lunas')
                                        <span
                                            class="inline-block rounded-md bg-emerald-50 px-1.5 py-1 text-[10px] font-semibold text-emerald-700">
                                            Lunas
                                        </span>
                                    @elseif ($item->status_pembayaran === 'menunggu_verifikasi')
                                        <span
                                            class="inline-block rounded-md bg-amber-50 px-1.5 py-1 text-[10px] font-semibold text-amber-700">
                                            Menunggu
                                        </span>
                                    @else
                                        <span
                                            class="inline-block rounded-md bg-gray-100 px-1.5 py-1 text-[10px] font-semibold text-gray-600">
                                            Belum bayar
                                        </span>
                                    @endif
                                </td>

                                <td class="overflow-hidden px-2 py-3 font-semibold text-gray-800 sm:px-3">
                                    <span class="block truncate" title="Rp{{ number_format($item->total, 0, ',', '.') }}">
                                        Rp{{ number_format($item->total, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <div class="mx-auto flex max-w-xs flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mb-3 h-10 w-10 text-gray-300"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 5h6m-7 4h8m-8 4h5m-9 6h16a1 1 0 001-1V4a1 1 0 00-1-1H3a1 1 0 00-1 1v14a1 1 0 001 1z" />
                                        </svg>
                                        <p class="text-sm font-semibold text-gray-700">
                                            Belum ada transaksi
                                        </p>
                                        <p class="mt-1 text-xs text-gray-400">
                                            Tidak ada transaksi pada bulan dan tahun ini.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pesanan->hasPages())
                <div class="border-t border-gray-200 px-4 py-3">
                    {{ $pesanan->fragment('daftar-transaksi')->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
