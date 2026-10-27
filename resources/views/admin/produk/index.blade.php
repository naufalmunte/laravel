@extends('layouts.admin')

@section('title', 'Produk')
@section('page-heading', 'Produk')

@section('content')
    <div class="space-y-6">

        {{-- Header Halaman --}}
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Produk</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola produk.
                </p>
            </div>

            <a href="{{ route('admin.produk.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600">
                <i class="fa-solid fa-plus"></i>
                Tambah Produk
            </a>
        </div>

        {{-- Notifikasi Berhasil --}}
        @if (session('success'))
            <div
                class="flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif


        {{-- Pencarian Produk --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                <input type="text" id="searchProduk" value="{{ request('search') }}"
                    placeholder="Ketik nama produk untuk mencari..." autocomplete="off"
                    class="w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-10 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                <button type="button" id="clearSearch"
                    class="absolute right-3 top-1/2 hidden -translate-y-1/2 text-gray-400 hover:text-gray-700"
                    title="Hapus pencarian">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>


        {{-- Tabel Produk --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                <h3 class="font-semibold text-gray-800">Data Produk</h3>
                <span class="text-sm text-gray-500">
                    Total: {{ $produk->total() }} produk
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-5 py-4">No.</th>
                            <th class="px-5 py-4">Produk</th>
                            <th class="px-5 py-4">Kategori</th>
                            <th class="px-5 py-4">Harga</th>
                            <th class="px-5 py-4">Stok</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($produk as $item)
                            <tr class="transition hover:bg-amber-50/40">
                                <td class="px-5 py-4 text-gray-500">
                                    {{ $produk->firstItem() + $loop->index }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($item->gambar)
                                            <img src="{{ asset('storage/' . $item->gambar) }}"
                                                alt="{{ $item->nama_produk }}"
                                                class="h-12 w-12 rounded-lg border border-gray-200 object-cover">
                                        @else
                                            <div
                                                class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 text-gray-400">
                                                <i class="fa-solid fa-image text-lg"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                {{ $item->nama_produk }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-400">
                                                {{ $item->slug }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="rounded-md bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                        {{ $item->kategori->nama_kategori }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-700">
                                    Rp{{ number_format($item->harga, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($item->stok > 10)
                                        <span
                                            class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                            {{ $item->stok }}
                                        </span>
                                    @elseif ($item->stok > 0)
                                        <span
                                            class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">
                                            {{ $item->stok }}
                                        </span>
                                    @else
                                        <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">
                                            Habis
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.produk.edit', $item) }}"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition hover:bg-blue-100"
                                            title="Edit produk">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <button type="button"
                                            onclick="document.getElementById('modalHapusProduk-{{ $item->id }}').classList.remove('hidden'); document.getElementById('modalHapusProduk-{{ $item->id }}').classList.add('flex')"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100"
                                            title="Hapus produk">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                        <!-- Modal Konfirmasi -->
                                        <div id="modalHapusProduk-{{ $item->id }}"
                                            class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 p-4">
                                            <div class="w-full max-w-sm rounded-xl bg-white p-5 shadow-2xl">

                                                <h3 class="text-lg font-semibold text-gray-800">
                                                    Hapus Produk?
                                                </h3>

                                                <p class="mt-2 text-sm leading-relaxed text-gray-500">
                                                    Apakah kamu yakin ingin menghapus produk
                                                    <span
                                                        class="font-medium text-gray-700">{{ $item->nama_produk }}</span>?
                                                    Tindakan ini tidak dapat dibatalkan.
                                                </p>

                                                <div class="mt-5 flex justify-end gap-2">
                                                    <button type="button"
                                                        onclick="document.getElementById('modalHapusProduk-{{ $item->id }}').classList.add('hidden'); document.getElementById('modalHapusProduk-{{ $item->id }}').classList.remove('flex')"
                                                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                                                        Batal
                                                    </button>

                                                    <form action="{{ route('admin.produk.destroy', $item) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                                                            Ya, Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-box-open mb-3 text-4xl text-gray-300"></i>
                                        <p class="font-medium text-gray-600">Belum ada produk</p>
                                        <p class="mt-1 text-sm text-gray-400">
                                            Silakan tambahkan produk pertama kamu.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($produk->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $produk->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('searchProduk');
            const clearButton = document.getElementById('clearSearch');
            const rows = document.querySelectorAll('tbody tr');

            // Simpan data asli dari setiap baris produk
            const products = Array.from(rows).map(row => ({
                element: row,
                text: row.textContent.toLowerCase()
            }));

            function searchProducts() {
                const keyword = input.value.trim().toLowerCase();
                let total = 0;

                products.forEach(product => {
                    // Abaikan baris pesan ketika data kosong
                    if (product.element.querySelector('td[colspan]')) {
                        return;
                    }

                    const match = product.text.includes(keyword);

                    product.element.style.display = match ? '' : 'none';

                    if (match) total++;
                });

                clearButton.classList.toggle('hidden', input.value.length === 0);

                // Tampilkan pesan jika tidak ada produk yang cocok
                let emptyRow = document.getElementById('searchEmptyRow');

                if (!emptyRow) {
                    emptyRow = document.createElement('tr');
                    emptyRow.id = 'searchEmptyRow';
                    emptyRow.innerHTML = `
                    <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-500">
                        <i class="fa-solid fa-magnifying-glass mb-3 block text-3xl text-gray-300"></i>
                        Produk tidak ditemukan.
                    </td>
                `;
                    document.querySelector('tbody').appendChild(emptyRow);
                }

                emptyRow.style.display = total === 0 ? '' : 'none';
            }

            input.addEventListener('input', searchProducts);

            clearButton.addEventListener('click', function() {
                input.value = '';
                searchProducts();
                input.focus();
            });

            // Jalankan jika input sudah berisi kata pencarian
            searchProducts();
        });
    </script>
@endpush
