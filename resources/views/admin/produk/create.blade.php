
@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-heading', 'Tambah Produk')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Header --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Produk</h2>
            <p class="mt-1 text-sm text-gray-500">
                Masukkan informasi produk.
            </p>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.produk.store') }}" method="POST"
            enctype="multipart/form-data"
            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            @csrf

            <div class="space-y-6 p-5 sm:p-7">

                {{-- Nama Produk --}}
                <div>
                    <label for="nama_produk" class="mb-2 block text-sm font-semibold text-gray-700">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_produk" name="nama_produk"
                        value="{{ old('nama_produk') }}"
                        placeholder="Contoh: Keripik Sanjai Balado"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                    @error('nama_produk')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="kategori_id" class="mb-2 block text-sm font-semibold text-gray-700">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="kategori_id" name="kategori_id" required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">
                        <option value="">Pilih kategori produk</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id }}"
                                {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    @error('kategori_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror

                    @if ($kategori->isEmpty())
                        <p class="mt-2 text-sm text-amber-600">
                            Belum ada kategori. Tambahkan kategori terlebih dahulu.
                        </p>
                    @endif
                </div>

                {{-- Harga dan Stok --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="harga" class="mb-2 block text-sm font-semibold text-gray-700">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="harga" name="harga"
                            value="{{ old('harga') }}"
                            min="0" step="0.01" placeholder="25000"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                        @error('harga')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stok" class="mb-2 block text-sm font-semibold text-gray-700">
                            Stok Awal <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="stok" name="stok"
                            value="{{ old('stok', 0) }}"
                            min="0" step="1" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                        @error('stok')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi" class="mb-2 block text-sm font-semibold text-gray-700">
                        Deskripsi
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="5"
                        placeholder="Tuliskan deskripsi produk..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gambar --}}
                <div>
                    <label for="gambar" class="mb-2 block text-sm font-semibold text-gray-700">
                        Gambar Produk
                    </label>
                    <input type="file" id="gambar" name="gambar"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-600 file:mr-4 file:border-0 file:bg-amber-50 file:px-4 file:py-3 file:font-medium file:text-amber-700 hover:file:bg-amber-100">

                    <p class="mt-2 text-xs text-gray-400">
                        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                    </p>

                    @error('gambar')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex flex-col-reverse justify-end gap-3 border-t border-gray-200 bg-gray-50 px-5 py-4 sm:flex-row sm:px-7">
                <a href="{{ route('admin.produk.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-600 transition hover:bg-gray-100">
                    Batal
                </a>

                <button type="submit"
                    class="rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
@endsection
