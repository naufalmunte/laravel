
@extends('layouts.admin')

@section('title', 'Tambah Kategori')
@section('page-heading', 'Tambah Kategori')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    <div>
        <h3 class="mt-3 text-xl font-bold text-gray-800">Tambah Kategori Baru</h3>
        <p class="mt-1 text-sm text-gray-500">
            Masukkan informasi kategori produk yang ingin ditambahkan.
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="nama_kategori" class="mb-2 block text-sm font-semibold text-gray-700">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="nama_kategori"
                    name="nama_kategori"
                    value="{{ old('nama_kategori') }}"
                    placeholder="Contoh: Keripik Balado"
                    required
                    maxlength="255"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100"
                >

                @error('nama_kategori')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="deskripsi" class="mb-2 block text-sm font-semibold text-gray-700">
                    Deskripsi
                </label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="4"
                    placeholder="Jelaskan kategori produk ini (opsional)"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100"
                >{{ old('deskripsi') }}</textarea>

                @error('deskripsi')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.kategori.index') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                    Batal
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Kategori
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
