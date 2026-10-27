@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
    @php
        $statusPesanan = [
            'menunggu_konfirmasi' => [
                'label' => 'Menunggu Konfirmasi',
                'class' => 'bg-amber-100 text-amber-700',
            ],
            'diproses' => [
                'label' => 'Sedang Diproses',
                'class' => 'bg-blue-100 text-blue-700',
            ],
            'dikirim' => [
                'label' => 'Sedang Dikirim',
                'class' => 'bg-indigo-100 text-indigo-700',
            ],
            'siap_diambil' => [
                'label' => 'Siap Diambil',
                'class' => 'bg-purple-100 text-purple-700',
            ],
            'menunggu_konfirmasi_pelanggan' => [
                'label' => 'Menunggu Konfirmasi Pelanggan',
                'class' => 'bg-amber-100 text-amber-700',
            ],
            'selesai' => [
                'label' => 'Selesai',
                'class' => 'bg-green-100 text-green-700',
            ],
            'dibatalkan' => [
                'label' => 'Dibatalkan',
                'class' => 'bg-red-100 text-red-700',
            ],
        ];

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

        $statusInfo = $statusPesanan[$pesanan->status] ?? [
            'label' => ucfirst(str_replace('_', ' ', $pesanan->status)),
            'class' => 'bg-gray-100 text-gray-700',
        ];

        $pembayaranInfo = $statusPembayaran[$pesanan->status_pembayaran] ?? [
            'label' => ucfirst(str_replace('_', ' ', $pesanan->status_pembayaran ?? '-')),
            'class' => 'bg-gray-100 text-gray-700',
        ];

        $metodePengambilan = $pesanan->metode_pengambilan;
        $metodePembayaran = $pesanan->metode_pembayaran;
    @endphp

    <div class="space-y-6">

        {{-- Notifikasi --}}
        @if (session('success'))
            <div
                class="flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                <i class="fa-solid fa-circle-check mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <div class="mb-1 font-semibold">Terdapat kesalahan:</div>

                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <a href="{{ route('admin.pesanan.index') }}"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-50"
                    title="Kembali">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>

                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan</h1>

                    <p class="mt-1 text-sm text-gray-500">
                        Kode Pesanan:
                        <span class="font-semibold text-gray-700">
                            {{ $pesanan->kode_pesanan }}
                        </span>
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Dibuat pada {{ $pesanan->created_at?->format('d M Y, H:i') ?? '-' }}
                    </p>
                </div>
            </div>

            <span
                class="inline-flex w-fit items-center rounded-full px-3 py-1.5 text-sm font-semibold {{ $statusInfo['class'] }}">
                {{ $statusInfo['label'] }}
            </span>
        </div>

        {{-- Aksi Status Pesanan --}}
        @if ($pesanan->status === 'menunggu_konfirmasi')
            <div
                class="flex flex-col gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-gray-800">Konfirmasi Pesanan</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Pastikan pembayaran QRIS sudah diverifikasi sebelum pesanan diproses.
                    </p>
                </div>

                <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="diproses">

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700">
                        <i class="fa-solid fa-check"></i>
                        Konfirmasi Pesanan
                    </button>
                </form>
            </div>
        @elseif ($pesanan->status === 'diproses')
            <div
                class="flex flex-col gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-gray-800">Pesanan Sedang Diproses</h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ $metodePengambilan === 'diantar'
                            ? 'Jika barang siap, lanjutkan ke proses pengiriman.'
                            : 'Jika barang siap, lanjutkan ke proses pengambilan di toko.' }}
                    </p>
                </div>

                <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status"
                        value="{{ $metodePengambilan === 'diantar' ? 'dikirim' : 'siap_diambil' }}">

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                        <i class="fa-solid {{ $metodePengambilan === 'diantar' ? 'fa-truck' : 'fa-store' }}"></i>
                        {{ $metodePengambilan === 'diantar' ? 'Tandai Dikirim' : 'Tandai Siap Diambil' }}
                    </button>
                </form>
            </div>
        @endif

        {{-- Informasi Utama --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            {{-- Bukti Pembayaran --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-bold text-gray-800">Bukti Pembayaran</h2>
                    <i class="fa-solid fa-receipt text-gray-400"></i>
                </div>

                @if ($metodePembayaran === 'qris')
                    @if ($pesanan->bukti_pembayaran)
                        <a href="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" target="_blank"
                            rel="noopener noreferrer"
                            class="flex min-h-[300px] items-center justify-center rounded-lg border border-gray-200 bg-gray-50 p-3">
                            <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" alt="Bukti pembayaran QRIS"
                                class="max-h-[330px] max-w-full rounded-md object-contain">
                        </a>

                        <p class="mt-2 text-center text-xs text-gray-500">
                            Klik gambar untuk melihat ukuran penuh.
                        </p>
                    @else
                        <div
                            class="flex min-h-[300px] flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 p-4 text-center">
                            <i class="fa-solid fa-image mb-3 text-3xl text-gray-300"></i>
                            <p class="text-sm text-gray-500">Bukti pembayaran belum diunggah.</p>
                        </div>
                    @endif
                @else
                    <div
                        class="flex min-h-[300px] flex-col items-center justify-center rounded-lg border border-gray-200 bg-gray-50 p-4 text-center">
                        <i class="fa-solid fa-money-bill-wave mb-3 text-3xl text-green-500"></i>
                        <p class="font-semibold text-gray-700">Pembayaran Tunai</p>
                        <p class="mt-1 text-sm text-gray-500">
                            Pembayaran dilakukan secara tunai saat penyerahan barang.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Informasi Pelanggan --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-bold text-gray-800">Informasi Pelanggan</h2>
                    <i class="fa-solid fa-user text-gray-400"></i>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Nama Penerima</p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $pesanan->nama_penerima ?: $pesanan->pelanggan->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Nomor WhatsApp</p>
                        <p class="mt-1 text-sm text-gray-700">{{ $pesanan->no_whatsapp ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Metode Pengambilan</p>
                        <p class="mt-1 text-sm font-medium text-gray-700">
                            {{ $metodePengambilan === 'diantar' ? 'Diantar ke Alamat' : 'Jemput Sendiri' }}
                        </p>
                    </div>

                    @if ($metodePengambilan === 'diantar')
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Alamat Pengiriman</p>
                            <p class="mt-1 whitespace-pre-line text-sm leading-relaxed text-gray-700">
                                {{ $pesanan->alamat ?: '-' }}
                            </p>
                        </div>
                    @endif

                    @if ($pesanan->catatan)
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Catatan Pelanggan</p>
                            <p class="mt-1 whitespace-pre-line text-sm leading-relaxed text-gray-700">
                                {{ $pesanan->catatan }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Informasi Pembayaran --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="font-bold text-gray-800">Informasi Pembayaran</h2>
                    <i class="fa-solid fa-wallet text-gray-400"></i>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Metode Pembayaran</p>
                        <p class="mt-1 font-semibold uppercase text-gray-800">
                            {{ $metodePembayaran === 'qris' ? 'QRIS' : 'Tunai' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Status Pembayaran</p>
                        <span
                            class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $pembayaranInfo['class'] }}">
                            {{ $pembayaranInfo['label'] }}
                        </span>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-medium text-gray-800">
                                Rp {{ number_format((float) $pesanan->subtotal, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="mt-3 flex items-center justify-between text-sm">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-medium text-gray-800">
                                Rp {{ number_format((float) $pesanan->ongkir, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                            <span class="font-bold text-gray-800">Total Pembayaran</span>
                            <span class="text-lg font-bold text-amber-700">
                                Rp {{ number_format((float) $pesanan->total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Produk --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-4">
                <h2 class="font-bold text-gray-800">Produk yang Dipesan</h2>
                <p class="mt-1 text-sm text-gray-500">Rincian produk dalam pesanan ini.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[650px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold">No.</th>
                            <th class="px-5 py-4 font-semibold">Nama Produk</th>
                            <th class="px-5 py-4 text-right font-semibold">Harga</th>
                            <th class="px-5 py-4 text-center font-semibold">Jumlah</th>
                            <th class="px-5 py-4 text-right font-semibold">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($pesanan->detailPesanan as $detail)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-5 py-4 text-gray-500">{{ $loop->iteration }}</td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-gray-800">{{ $detail->nama_produk }}</p>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right text-gray-600">
                                    Rp {{ number_format((float) $detail->harga, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4 text-center text-gray-600">{{ $detail->jumlah }}</td>

                                <td class="whitespace-nowrap px-5 py-4 text-right font-semibold text-gray-800">
                                    Rp {{ number_format((float) $detail->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-gray-500">
                                    Belum ada produk pada pesanan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bukti Penyerahan Barang --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="mb-5 flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-box-open"></i>
                </div>

                <div>
                    <h2 class="font-bold text-gray-800">Bukti Penyerahan Barang</h2>
                    <p class="mt-1 text-sm leading-relaxed text-gray-500">
                        Wajib diunggah ketika barang benar-benar diserahkan kepada pelanggan,
                        baik melalui pengiriman maupun pengambilan langsung di toko.
                    </p>
                </div>
            </div>

            @if ($pesanan->bukti_penyerahan)
                <div class="mb-5">
                    <a href="{{ asset('storage/' . $pesanan->bukti_penyerahan) }}" target="_blank"
                        rel="noopener noreferrer"
                        class="flex min-h-[180px] items-center justify-center rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <img src="{{ asset('storage/' . $pesanan->bukti_penyerahan) }}"
                            alt="Foto bukti penyerahan barang" class="max-h-[400px] max-w-full rounded-md object-contain">
                    </a>

                    <div
                        class="mt-3 flex flex-col gap-1 text-sm text-gray-500 sm:flex-row sm:items-center sm:justify-between">
                        <span>
                            <i class="fa-regular fa-clock mr-1"></i>
                            Diserahkan pada: {{ $pesanan->diserahkan_pada?->format('d M Y, H:i') ?? '-' }}
                        </span>

                        <a href="{{ asset('storage/' . $pesanan->bukti_penyerahan) }}" target="_blank"
                            rel="noopener noreferrer" class="font-medium text-amber-700 hover:text-amber-800">
                            Lihat Foto Penuh
                            <i class="fa-solid fa-arrow-up-right-from-square ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
            @endif

            @if (in_array($pesanan->status, ['dikirim', 'siap_diambil'], true))
                <form id="formPenyerahan" action="{{ route('admin.pesanan.serahkan', $pesanan->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-4 border-t border-gray-100 pt-5">

                    @csrf

                    <div>
                        <label for="bukti_penyerahan" class="mb-2 block text-sm font-semibold text-gray-700">
                            Upload Foto Penyerahan
                            <span class="text-red-500">*</span>
                        </label>

                        <input type="file" id="bukti_penyerahan" name="bukti_penyerahan"
                            accept=".jpg,.jpeg,.png,.webp" required
                            class="block w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-700
                                   file:mr-4 file:border-0 file:bg-amber-50 file:px-4 file:py-3
                                   file:font-semibold file:text-amber-700 hover:file:bg-amber-100">

                        <p class="mt-2 text-xs text-gray-500">
                            Format JPG, JPEG, PNG, WEBP. Ukuran maksimal 5 MB.
                        </p>
                    </div>

                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Pastikan barang sudah diterima pelanggan. Untuk pembayaran tunai,
                        pastikan uang sudah diterima sebelum mengunggah bukti.
                        Setelah diunggah, pesanan menunggu konfirmasi pelanggan.
                    </div>

                    {{-- Tombol pembuka modal --}}
                    <button type="button" onclick="bukaModalPenyerahan()"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700">
                        <i class="fa-solid fa-camera"></i>
                        Upload Bukti & Konfirmasi Penyerahan
                    </button>
                </form>
            @elseif ($pesanan->status === 'menunggu_konfirmasi_pelanggan')
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-hourglass-half mt-0.5 text-amber-600"></i>

                        <div>
                            <p class="font-semibold text-amber-800">Menunggu Konfirmasi Pelanggan</p>
                            <p class="mt-1 text-sm leading-relaxed text-amber-700">
                                Bukti penyerahan sudah tercatat. Pelanggan dapat mengonfirmasi
                                penerimaan barang. Jika tidak dikonfirmasi, sistem akan
                                menyelesaikan pesanan setelah 24 jam dari waktu penyerahan.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif ($pesanan->status === 'selesai')
                <div class="rounded-lg border border-green-200 bg-green-50 p-4">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-check mt-0.5 text-green-600"></i>

                        <div>
                            <p class="font-semibold text-green-800">Pesanan Selesai</p>
                            <p class="mt-1 text-sm text-green-700">
                                Proses pesanan sudah selesai.

                                @if ($pesanan->diserahkan_pada)
                                    Barang diserahkan pada
                                    {{ $pesanan->diserahkan_pada->format('d M Y, H:i') }}.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
                    Bukti penyerahan dapat diunggah setelah pesanan memasuki tahap pengiriman
                    atau siap diambil.
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Konfirmasi Penyerahan --}}
    <div id="modalPenyerahan"
        class="fixed inset-0 z-[150] hidden items-center justify-center overflow-y-auto bg-black/50 p-4 backdrop-blur-sm">

        <div id="modalPenyerahanContent"
            class="my-auto w-full max-w-md scale-95 rounded-2xl bg-white p-6 opacity-0 shadow-2xl transition-all duration-200">

            {{-- Ikon --}}
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                <i class="fa-solid fa-box-open text-2xl"></i>
            </div>

            {{-- Judul --}}
            <div class="mt-4 text-center">
                <h3 class="text-lg font-bold text-gray-800">
                    Konfirmasi Penyerahan Barang
                </h3>

                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Kamu akan mengunggah bukti penyerahan untuk pesanan
                    <span class="font-semibold text-gray-700">
                        {{ $pesanan->kode_pesanan }}
                    </span>.
                    Pastikan informasi berikut sudah benar.
                </p>
            </div>

            {{-- Peringatan --}}
            <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-3">
                <div class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-info mt-0.5 text-amber-600"></i>

                    <p class="text-xs leading-5 text-amber-800">
                        Pastikan barang benar-benar sudah diserahkan kepada pelanggan.
                        Untuk pembayaran tunai, pastikan uang sudah diterima.
                        Setelah dikonfirmasi, bukti akan dikirim dan pesanan menunggu
                        konfirmasi pelanggan.
                    </p>
                </div>
            </div>

            {{-- Aksi Modal --}}
            <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button type="button" onclick="tutupModalPenyerahan()"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Batal
                </button>

                <button type="button" id="tombolKonfirmasiPenyerahan" onclick="konfirmasiPenyerahan()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700 disabled:cursor-not-allowed disabled:opacity-60">
                    <i class="fa-solid fa-check"></i>
                    Ya, Upload Bukti
                </button>
            </div>
        </div>
    </div>

    {{-- JavaScript Modal --}}
    <script>
        function bukaModalPenyerahan() {
            const form = document.getElementById('formPenyerahan');

            if (!form || !form.reportValidity()) {
                return;
            }

            const modal = document.getElementById('modalPenyerahan');
            const content = document.getElementById('modalPenyerahanContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.style.overflow = 'hidden';

            requestAnimationFrame(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });
        }

        function tutupModalPenyerahan() {
            const modal = document.getElementById('modalPenyerahan');
            const content = document.getElementById('modalPenyerahanContent');

            if (!modal || !content) return;

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }, 200);
        }

        function konfirmasiPenyerahan() {
            const form = document.getElementById('formPenyerahan');
            const tombol = document.getElementById('tombolKonfirmasiPenyerahan');

            if (!form || !form.reportValidity()) {
                tutupModalPenyerahan();
                return;
            }

            // Cegah klik ganda selama form dikirim.
            tombol.disabled = true;
            tombol.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin"></i> Mengunggah...';

            // Gunakan requestSubmit agar validasi dan event submit tetap berjalan.
            form.requestSubmit();
        }

        // Klik area gelap untuk menutup modal.
        document.getElementById('modalPenyerahan')?.addEventListener('click', function(event) {
            if (event.target === this) {
                tutupModalPenyerahan();
            }
        });

        // Tutup modal dengan tombol Escape.
        document.addEventListener('keydown', function(event) {
            const modal = document.getElementById('modalPenyerahan');

            if (event.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                tutupModalPenyerahan();
            }
        });
    </script>
@endsection
