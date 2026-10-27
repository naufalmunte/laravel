@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-heading', 'Edit Produk')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Header --}}
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Produk</h2>
            <p class="mt-1 text-sm text-gray-500">
                Perbarui informasi {{ $produk->nama_produk }}.
            </p>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.produk.update', $produk) }}" method="POST" enctype="multipart/form-data"
            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            @csrf
            @method('PUT')

            <div class="space-y-6 p-5 sm:p-7">

                {{-- Nama Produk --}}
                <div>
                    <label for="nama_produk" class="mb-2 block text-sm font-semibold text-gray-700">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_produk" name="nama_produk"
                        value="{{ old('nama_produk', $produk->nama_produk) }}" required
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
                                {{ old('kategori_id', $produk->kategori_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    @error('kategori_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="mb-2 block text-sm font-semibold text-gray-700">
                        Slug Produk
                    </label>
                    <input type="text" value="{{ $produk->slug }}" readonly
                        class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-500">
                    <p class="mt-1 text-xs text-gray-400">
                        Slug diperbarui otomatis mengikuti nama produk setelah disimpan.
                    </p>
                </div>


                {{-- Harga dan Stok --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Harga --}}
                    <div>
                        <label for="harga" class="mb-2 block text-sm font-semibold text-gray-700">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>

                        <input type="number" id="harga" name="harga" value="{{ old('harga', $produk->harga) }}"
                            min="0" step="0.01" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                        @error('harga')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Stok Terkini: hanya ditampilkan --}}
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-gray-700">
                            Stok Terkini
                        </label>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">
                            <i class="fa-solid fa-boxes-stacked text-gray-400"></i>

                            <span class="font-semibold text-gray-700">
                                {{ number_format($produk->stok, 0, ',', '.') }}
                            </span>

                            <span class="text-sm text-gray-500">unit</span>
                        </div>

                        <p class="mt-2 text-xs text-gray-400">
                            Stok hanya dapat diubah melalui menu Manajemen Stok.
                        </p>
                    </div>

                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi" class="mb-2 block text-sm font-semibold text-gray-700">
                        Deskripsi
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" placeholder="Tuliskan deskripsi produk..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">{{ old('deskripsi', $produk->deskripsi) }}</textarea>

                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gambar --}}
                <div>
                    <label for="gambar" class="mb-2 block text-sm font-semibold text-gray-700">
                        Gambar Produk
                    </label>

                    @if ($produk->gambar)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                class="h-32 w-32 rounded-lg border border-gray-200 object-cover">
                            <p class="mt-1 text-xs text-gray-400">Gambar saat ini</p>
                        </div>
                    @endif

                    <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.webp"
                        class="w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-600 file:mr-4 file:border-0 file:bg-amber-50 file:px-4 file:py-3 file:font-medium file:text-amber-700 hover:file:bg-amber-100">

                    <p class="mt-2 text-xs text-gray-400">
                        Kosongkan jika tidak ingin mengganti gambar. Format JPG, JPEG, PNG, atau WEBP; maksimal 2 MB.
                    </p>

                    @error('gambar')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol --}}
            <div
                class="flex flex-col-reverse justify-end gap-3 border-t border-gray-200 bg-gray-50 px-5 py-4 sm:flex-row sm:px-7">
                <a href="{{ route('admin.produk.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-600 transition hover:bg-gray-100">
                    Batal
                </a>

                <button type="submit"
                    class="rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
