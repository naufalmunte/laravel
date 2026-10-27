@extends('layouts.admin')

@section('title', 'Kategori Produk')
@section('page-heading', 'Kategori Produk')

@section('content')
    <div class="space-y-6">

        {{-- Header halaman --}}
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Kategori</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola kategori produk.
                </p>
            </div>

            <a href="{{ route('admin.kategori.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600">
                <i class="fa-solid fa-plus"></i>
                Tambah Kategori
            </a>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tabel kategori --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            {{-- Header tabel dan pencarian --}}
            <div class="flex flex-col justify-between gap-3 border-b border-gray-200 p-5 sm:flex-row sm:items-center">
                <div>
                    <h4 class="font-semibold text-gray-800">Semua Kategori</h4>
                    <p class="mt-1 text-xs text-gray-500">
                        Total {{ $kategori->total() }} kategori
                    </p>
                </div>

                {{-- Pencarian kategori --}}
                <form action="{{ route('admin.kategori.index') }}" method="GET" id="searchKategoriForm"
                    class="relative w-full sm:w-64">

                    <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    <input type="text" name="search" id="searchKategori" value="{{ request('search') }}"
                        placeholder="Cari kategori..." autocomplete="off"
                        class="w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-10 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                    {{-- Tombol hapus pencarian --}}
                    <button type="button" id="clearSearchKategori"
                        class="{{ request('search') ? '' : 'hidden' }} absolute inset-y-0 right-3 items-center text-gray-400 transition hover:text-red-500"
                        aria-label="Hapus pencarian" title="Hapus pencarian">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </form>
            </div>

            {{-- Isi tabel --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-5 py-4">No.</th>
                            <th class="px-5 py-4">Nama Kategori</th>
                            <th class="px-5 py-4">Slug</th>
                            <th class="px-5 py-4">Deskripsi</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($kategori as $item)
                            <tr class="transition hover:bg-amber-50/40">

                                <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                                    {{ $kategori->firstItem() + $loop->index }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-800">
                                    {{ $item->nama_kategori }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="rounded-md bg-gray-100 px-2 py-1 text-xs text-gray-600">
                                        {{ $item->slug }}
                                    </span>
                                </td>

                                <td class="max-w-xs px-5 py-4 text-gray-500">
                                    {{ $item->deskripsi ?: '-' }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">

                                        {{-- Edit kategori --}}
                                        <a href="{{ route('admin.kategori.edit', $item) }}"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition hover:bg-blue-100"
                                            title="Edit kategori">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        {{-- Tombol hapus kategori --}}
                                        <button type="button" onclick="bukaModalKategori('{{ $item->id }}')"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100"
                                            title="Hapus kategori">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                            <i class="fa-solid fa-tags text-xl"></i>
                                        </div>

                                        <p class="font-semibold text-gray-700">
                                            Belum ada kategori
                                        </p>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Tambahkan kategori produk pertamamu.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($kategori->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $kategori->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- Modal diletakkan di luar tabel --}}
    @foreach ($kategori as $item)
        <div id="modalHapusKategori-{{ $item->id }}"
            class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 p-4"
            onclick="jikaKlikLatar(event, 'modalHapusKategori-{{ $item->id }}')">

            <div class="w-full max-w-sm overflow-hidden rounded-xl bg-white p-5 shadow-2xl">

                <h3 class="text-lg font-semibold text-gray-800">
                    Hapus Kategori?
                </h3>

                <p class="mt-2 whitespace-normal break-words text-sm leading-relaxed text-gray-500">
                    Apakah kamu yakin ingin menghapus kategori
                    <span class="font-medium text-gray-700 [overflow-wrap:anywhere]">{{ $item->nama_kategori }}</span>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="mt-5 flex flex-wrap justify-end gap-2">

                    <button type="button" onclick="tutupModalKategori('{{ $item->id }}')"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                        Batal
                    </button>

                    <form action="{{ route('admin.kategori.destroy', $item) }}" method="POST">
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
    @endforeach

    {{-- Script pencarian dan modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('searchKategori');
            const form = document.getElementById('searchKategoriForm');
            const clearButton = document.getElementById('clearSearchKategori');

            if (input && form && clearButton) {
                let timeout;

                function updateClearButton() {
                    clearButton.classList.toggle('hidden', input.value.length === 0);
                    clearButton.classList.toggle('flex', input.value.length > 0);
                }

                input.addEventListener('input', function() {
                    updateClearButton();
                    clearTimeout(timeout);

                    timeout = setTimeout(function() {
                        form.requestSubmit();
                    }, 400);
                });

                clearButton.addEventListener('click', function() {
                    clearTimeout(timeout);
                    input.value = '';
                    updateClearButton();
                    form.requestSubmit();
                });

                updateClearButton();
            }
        });

        function bukaModalKategori(id) {
            const modal = document.getElementById('modalHapusKategori-' + id);

            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function tutupModalKategori(id) {
            const modal = document.getElementById('modalHapusKategori-' + id);

            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function jikaKlikLatar(event, id) {
            if (event.target.id === id) {
                tutupModalKategori(id.replace('modalHapusKategori-', ''));
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('[id^="modalHapusKategori-"]').forEach(function(modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            }
        });
    </script>
@endsection
