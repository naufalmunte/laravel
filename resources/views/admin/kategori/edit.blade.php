@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
    <div class="mx-auto max-w-3xl space-y-4">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">Edit Kategori</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Perbarui informasi kategori produk.
                </p>
            </div>
        </div>

        {{-- Form --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                    <p class="font-medium">Periksa kembali data yang kamu masukkan.</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.kategori.update', $kategori) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Nama Kategori --}}
                <div>
                    <label for="nama_kategori" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>

                    <input type="text" name="nama_kategori" id="nama_kategori"
                        value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                        placeholder="Contoh: Makanan Khas Minang" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                    @error('nama_kategori')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label for="slug" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Slug
                    </label>

                    <input type="text" id="slug" value="{{ old('slug', $kategori->slug) }}" readonly
                        class="w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-500">

                    <p class="mt-1 text-xs text-gray-400">
                        Slug mengikuti nama kategori.
                    </p>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Deskripsi
                    </label>

                    <textarea name="deskripsi" id="deskripsi" rows="2" placeholder="Masukkan deskripsi kategori (opsional)"
                        class="w-full resize-none rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-100">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>

                    @error('deskripsi')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-2 border-t border-gray-100 pt-4">
                    <a href="{{ route('admin.kategori.index') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-1">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
