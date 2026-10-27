@extends('layouts.admin')

@section('title', 'Manajemen Pembayaran')

@section('content')
    @php
        $statusPembayaran = [
            'menunggu_verifikasi' => [
                'label' => 'Menunggu Verifikasi',
                'class' => 'bg-amber-100 text-amber-700',
            ],
            'belum_bayar' => [
                'label' => 'Belum Dibayar',
                'class' => 'bg-red-100 text-red-700',
            ],
            'lunas' => [
                'label' => 'Lunas',
                'class' => 'bg-green-100 text-green-700',
            ],
        ];
    @endphp

    <div class="min-w-0 space-y-5">

        {{-- Header --}}
        <div>
            <h1 class="text-xl font-bold text-gray-800 sm:text-2xl">
                Manajemen Pembayaran
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Periksa bukti pembayaran dan pantau status pembayaran pesanan pelanggan.
            </p>
        </div>

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifikasi error --}}
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="mb-1 font-semibold">Proses pembayaran gagal:</p>
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

            <div class="min-w-0 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">Total Transaksi</p>
                <p class="mt-2 text-2xl font-bold text-gray-800">
                    {{ $pesanans->total() }}
                </p>
                <p class="mt-1 text-xs text-gray-400">
                    Seluruh transaksi yang tercatat
                </p>
            </div>

            <div class="min-w-0 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">Menunggu Verifikasi</p>
                <p class="mt-2 text-2xl font-bold text-amber-600">
                    {{ \App\Models\Pesanan::where('metode_pembayaran', 'qris')->where('status_pembayaran', 'menunggu_verifikasi')->count() }}
                </p>
                <p class="mt-1 text-xs text-gray-400">
                    Perlu diperiksa oleh admin
                </p>
            </div>

            <div class="min-w-0 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-gray-500">Pembayaran Lunas</p>
                <p class="mt-2 text-2xl font-bold text-green-700">
                    {{ \App\Models\Pesanan::where('status_pembayaran', 'lunas')->count() }}
                </p>
                <p class="mt-1 text-xs text-gray-400">
                    Transaksi yang telah lunas
                </p>
            </div>

        </div>

        {{-- Tabel Pembayaran --}}
        <div class="min-w-0 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-100 px-4 py-4 sm:px-5">
                <h2 class="font-bold text-gray-800">Daftar Transaksi</h2>
                <p class="mt-1 text-xs text-gray-500 sm:text-sm">
                    Periksa pembayaran QRIS atau lihat status pembayaran tunai.
                </p>
            </div>

            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[760px] table-fixed text-left text-xs">
                    <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="w-[17%] px-3 py-3 font-semibold">Pesanan</th>
                            <th class="w-[19%] px-3 py-3 font-semibold">Pelanggan</th>
                            <th class="w-[11%] px-3 py-3 font-semibold">Metode</th>
                            <th class="w-[15%] px-3 py-3 text-right font-semibold">Total</th>
                            <th class="w-[16%] px-3 py-3 font-semibold">Status</th>
                            <th class="w-[11%] px-3 py-3 text-center font-semibold">Bukti</th>
                            <th class="w-[11%] px-3 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($pesanans as $pesanan)
                            @php
                                $infoPembayaran = $statusPembayaran[$pesanan->status_pembayaran] ?? [
                                    'label' => ucfirst(str_replace('_', ' ', $pesanan->status_pembayaran ?? '-')),
                                    'class' => 'bg-gray-100 text-gray-700',
                                ];
                            @endphp

                            <tr class="transition hover:bg-gray-50">

                                {{-- Pesanan --}}
                                <td class="overflow-hidden px-3 py-3">
                                    <a href="{{ route('admin.pesanan.show', $pesanan->id) }}"
                                        class="block truncate font-semibold text-amber-700 hover:text-amber-800"
                                        title="{{ $pesanan->kode_pesanan }}">
                                        {{ $pesanan->kode_pesanan }}
                                    </a>
                                    <p class="mt-1 text-[10px] text-gray-400">
                                        {{ $pesanan->created_at?->format('d/m/Y H:i') ?? '-' }}
                                    </p>
                                </td>

                                {{-- Pelanggan --}}
                                <td class="overflow-hidden px-3 py-3">
                                    <p class="truncate font-medium text-gray-800"
                                        title="{{ $pesanan->nama_penerima ?: $pesanan->pelanggan->name ?? '-' }}">
                                        {{ $pesanan->nama_penerima ?: $pesanan->pelanggan->name ?? '-' }}
                                    </p>
                                    <p class="mt-1 truncate text-[10px] text-gray-400">
                                        {{ $pesanan->no_whatsapp ?: '-' }}
                                    </p>
                                </td>

                                {{-- Metode --}}
                                <td class="px-3 py-3">
                                    <span class="font-medium text-gray-700">
                                        {{ $pesanan->metode_pembayaran === 'qris' ? 'QRIS' : 'Tunai' }}
                                    </span>
                                </td>

                                {{-- Total --}}
                                <td class="overflow-hidden px-3 py-3 text-right">
                                    <span class="block truncate font-semibold text-gray-800"
                                        title="Rp {{ number_format((float) $pesanan->total, 0, ',', '.') }}">
                                        Rp {{ number_format((float) $pesanan->total, 0, ',', '.') }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-3 py-3">
                                    <span
                                        class="inline-block max-w-full truncate rounded-full px-2 py-1 text-[10px] font-semibold {{ $infoPembayaran['class'] }}"
                                        title="{{ $infoPembayaran['label'] }}">
                                        {{ $infoPembayaran['label'] }}
                                    </span>
                                </td>

                                {{-- Bukti --}}
                                <td class="px-3 py-3 text-center">
                                    @if ($pesanan->metode_pembayaran === 'qris' && $pesanan->bukti_pembayaran)
                                        <button type="button"
                                            onclick="bukaPopupBukti(@js(asset('storage/' . $pesanan->bukti_pembayaran)), 'Bukti Pembayaran QRIS')"
                                            class="whitespace-nowrap rounded-lg border border-gray-200 px-2 py-1.5 font-medium text-gray-700 transition hover:bg-gray-100">
                                            Lihat Bukti
                                        </button>
                                    @elseif ($pesanan->metode_pembayaran === 'qris')
                                        <span class="text-[10px] text-red-500">Belum tersedia</span>
                                    @else
                                        <span class="text-[10px] text-gray-400">Tunai</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-3 py-3 text-center">
                                    @if (
                                        $pesanan->metode_pembayaran === 'qris' &&
                                            $pesanan->status_pembayaran === 'menunggu_verifikasi' &&
                                            $pesanan->bukti_pembayaran)
                                        <button type="button"
                                            onclick="bukaModalVerifikasi(
                                                @js(route('admin.pembayaran.verifikasi', $pesanan->id)),
                                                @js($pesanan->kode_pesanan)
                                            )"
                                            class="whitespace-nowrap rounded-lg bg-green-600 px-2 py-1.5 font-semibold text-white transition hover:bg-green-700">
                                            Verifikasi
                                        </button>
                                    @elseif ($pesanan->status_pembayaran === 'lunas')
                                        <span class="text-[10px] font-medium text-green-700">
                                            Terverifikasi
                                        </span>
                                    @elseif ($pesanan->metode_pembayaran === 'qris')
                                        <span class="text-[10px] text-gray-400">
                                            Menunggu bukti
                                        </span>
                                    @else
                                        <a href="{{ route('admin.pesanan.show', $pesanan->id) }}"
                                            class="inline-block whitespace-nowrap rounded-lg border border-gray-200 px-2 py-1.5 font-semibold text-gray-700 transition hover:bg-gray-50">
                                            Detail
                                        </a>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <p class="font-medium text-gray-600">
                                        Belum ada transaksi pembayaran
                                    </p>
                                    <p class="mt-1 text-xs text-gray-400">
                                        Transaksi akan muncul setelah pelanggan membuat pesanan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($pesanans->hasPages())
                <div class="border-t border-gray-100 px-4 py-3 sm:px-5">
                    {{ $pesanans->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- Modal Preview Bukti Pembayaran --}}
    <div id="popupBukti" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/70 p-4"
        onclick="tutupPopupBukti()">

        <div class="relative w-full max-w-3xl rounded-xl bg-white p-4 shadow-2xl" onclick="event.stopPropagation()">

            <div class="mb-4 flex items-center justify-between gap-4">
                <h3 id="judulPopupBukti" class="font-bold text-gray-800">
                    Bukti Pembayaran
                </h3>

                <button type="button" onclick="tutupPopupBukti()"
                    class="rounded-lg px-3 py-1.5 text-sm text-gray-500 transition hover:bg-gray-100 hover:text-gray-800">
                    Tutup
                </button>
            </div>

            <div
                class="flex max-h-[75vh] min-h-[200px] items-center justify-center overflow-auto rounded-lg bg-gray-50 p-2">
                <img id="gambarPopupBukti" src="" alt="Preview bukti pembayaran"
                    class="max-h-[70vh] max-w-full rounded object-contain">
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Verifikasi --}}
    <div id="modalVerifikasi" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/50 p-4"
        onclick="klikLuarModalVerifikasi(event)">

        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl" role="dialog" aria-modal="true"
            aria-labelledby="judulModalVerifikasi" onclick="event.stopPropagation()">

            <h3 id="judulModalVerifikasi" class="text-lg font-bold text-gray-800">
                Verifikasi Pembayaran
            </h3>

            <p class="mt-2 text-sm leading-6 text-gray-600">
                Pastikan bukti QRIS sudah diperiksa dan dana benar-benar diterima.
            </p>

            <div class="mt-4 rounded-lg bg-gray-50 p-3">
                <p class="text-xs text-gray-500">Kode pesanan</p>
                <p id="kodePesananVerifikasi" class="mt-1 break-all text-sm font-semibold text-gray-800"></p>
            </div>

            <p class="mt-3 text-xs text-gray-500">
                Setelah dikonfirmasi, pembayaran akan diproses sebagai lunas.
            </p>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="tutupModalVerifikasi()"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Batal
                </button>

                <form id="formVerifikasi" method="POST">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                        class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700">
                        Ya, Verifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript Popup --}}
    <script>
        function bukaPopupBukti(url, judul) {
            const popup = document.getElementById('popupBukti');
            const gambar = document.getElementById('gambarPopupBukti');

            document.getElementById('judulPopupBukti').textContent = judul;
            gambar.src = url;

            popup.classList.remove('hidden');
            popup.classList.add('flex');

            document.body.style.overflow = 'hidden';
        }

        function tutupPopupBukti() {
            const popup = document.getElementById('popupBukti');

            popup.classList.add('hidden');
            popup.classList.remove('flex');

            document.getElementById('gambarPopupBukti').src = '';

            pulihkanScrollJikaTidakAdaModal();
        }

        function bukaModalVerifikasi(url, kodePesanan) {
            const modal = document.getElementById('modalVerifikasi');

            document.getElementById('formVerifikasi').action = url;
            document.getElementById('kodePesananVerifikasi').textContent = kodePesanan;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.style.overflow = 'hidden';
        }

        function tutupModalVerifikasi() {
            const modal = document.getElementById('modalVerifikasi');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            pulihkanScrollJikaTidakAdaModal();
        }

        function klikLuarModalVerifikasi(event) {
            if (event.target === document.getElementById('modalVerifikasi')) {
                tutupModalVerifikasi();
            }
        }

        function pulihkanScrollJikaTidakAdaModal() {
            const popupBuktiTerbuka = !document.getElementById('popupBukti').classList.contains('hidden');
            const modalVerifikasiTerbuka = !document.getElementById('modalVerifikasi').classList.contains('hidden');

            if (!popupBuktiTerbuka && !modalVerifikasiTerbuka) {
                document.body.style.overflow = '';
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                tutupPopupBukti();
                tutupModalVerifikasi();
            }
        });
    </script>
@endsection
