@extends('layouts.main')

@section('content')
    <div class="flex min-h-[80vh] items-center justify-center bg-[#FAF7F2] px-4 py-8 sm:px-6">

        <div
            class="grid w-full max-w-4xl overflow-hidden rounded-xl border border-[#EAE3DA] bg-white shadow-sm lg:grid-cols-2">

            {{-- Panel Kiri: Branding --}}
            <div class="relative flex flex-col justify-between overflow-hidden bg-[#30251F] p-7 text-white sm:p-9">

                {{-- Dekorasi --}}
                <div class="pointer-events-none absolute -right-16 -top-16 h-40 w-40 rounded-full border border-white/10">
                </div>
                <div
                    class="pointer-events-none absolute -bottom-16 -left-12 h-40 w-40 rounded-full border-[20px] border-amber-500/10">
                </div>

                {{-- Logo dan Nama Toko --}}
                <a href="/" class="relative z-10 flex w-fit items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500 text-sm text-[#30251F]">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </span>

                    <span class="text-lg font-bold">
                        {{ config('toko.nama', config('app.name')) }}
                    </span>
                </a>

                {{-- Konten Branding --}}
                <div class="relative z-10 my-12">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-amber-400">
                        Bergabung Bersama Kami
                    </p>

                    <h1 class="max-w-xs text-3xl font-bold leading-snug">
                        Mulai Pengalaman Belanja yang Lebih Mudah.
                    </h1>

                    <p class="mt-4 max-w-xs text-sm leading-6 text-white/60">
                        Buat akun untuk menikmati kemudahan berbelanja dan mengelola pesananmu.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="relative z-10 border-t border-white/10 pt-4 text-xs text-white/40">
                    © {{ date('Y') }} {{ config('toko.nama', config('app.name')) }}
                </div>
            </div>

            {{-- Panel Kanan: Form Registrasi --}}
            <div class="flex items-center justify-center p-6 sm:p-9 lg:p-10">
                <div class="w-full max-w-sm">

                    {{-- Header --}}
                    <div class="mb-6">
                        <a href="/"
                            class="mb-5 inline-flex items-center gap-2 text-xs text-gray-500 transition hover:text-amber-700">
                            <i class="fa-solid fa-arrow-left"></i>
                            Kembali ke Beranda
                        </a>

                        <h2 class="text-2xl font-bold text-[#30251F]">
                            Buat Akun
                        </h2>

                        <p class="mt-2 text-sm text-gray-500">
                            Lengkapi data untuk mulai berbelanja.
                        </p>
                    </div>

                    {{-- Pesan Error --}}
                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                            <p class="mb-2 font-semibold">Periksa kembali data kamu:</p>

                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Registrasi Manual --}}
                    <form action="{{ route('register.store') }}" method="POST" class="space-y-3.5">
                        @csrf

                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">
                                Nama Lengkap
                            </label>

                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap" autocomplete="name" required
                                class="w-full rounded-lg border border-gray-200 bg-[#FCFBF9] px-3.5 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-amber-600 focus:bg-white focus:ring-2 focus:ring-amber-100">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">
                                Email
                            </label>

                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="nama@email.com" autocomplete="email" required
                                class="w-full rounded-lg border border-gray-200 bg-[#FCFBF9] px-3.5 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-amber-600 focus:bg-white focus:ring-2 focus:ring-amber-100">
                        </div>

                        {{-- Nomor WhatsApp --}}
                        <div>
                            <label for="no_whatsapp" class="mb-1.5 block text-sm font-medium text-gray-700">
                                Nomor WhatsApp
                            </label>

                            <input type="tel" id="no_whatsapp" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                                placeholder="Contoh: 081234567890" autocomplete="tel" required
                                class="w-full rounded-lg border border-gray-200 bg-[#FCFBF9] px-3.5 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-amber-600 focus:bg-white focus:ring-2 focus:ring-amber-100">
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">
                                Password
                            </label>

                            <div class="relative">
                                <input type="password" id="password" name="password" placeholder="Minimal 8 karakter"
                                    autocomplete="new-password" required
                                    class="w-full rounded-lg border border-gray-200 bg-[#FCFBF9] px-3.5 py-2.5 pr-11 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-amber-600 focus:bg-white focus:ring-2 focus:ring-amber-100">

                                <button type="button" onclick="togglePasswordVisibility('password', 'passwordIcon')"
                                    class="absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-400 transition hover:text-amber-700"
                                    aria-label="Tampilkan password">
                                    <i class="fa-solid fa-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700">
                                Konfirmasi Password
                            </label>

                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Ulangi password" autocomplete="new-password" required
                                    class="w-full rounded-lg border border-gray-200 bg-[#FCFBF9] px-3.5 py-2.5 pr-11 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-amber-600 focus:bg-white focus:ring-2 focus:ring-amber-100">

                                <button type="button"
                                    onclick="togglePasswordVisibility('password_confirmation', 'confirmationIcon')"
                                    class="absolute inset-y-0 right-0 flex items-center px-3.5 text-gray-400 transition hover:text-amber-700"
                                    aria-label="Tampilkan konfirmasi password">
                                    <i class="fa-solid fa-eye" id="confirmationIcon"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Script Toggle Password --}}
                        <script>
                            function togglePasswordVisibility(inputId, iconId) {
                                const input = document.getElementById(inputId);
                                const icon = document.getElementById(iconId);

                                const isHidden = input.type === 'password';

                                input.type = isHidden ? 'text' : 'password';

                                icon.classList.toggle('fa-eye', !isHidden);
                                icon.classList.toggle('fa-eye-slash', isHidden);
                            }
                        </script>

                        {{-- Tombol Daftar --}}
                        <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#30251F] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#49382F] focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                            Daftar Sekarang
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </form>

                    {{-- Pemisah --}}
                    <div class="my-4 flex items-center gap-3">
                        <div class="h-px flex-1 bg-gray-200"></div>
                        <span class="text-xs text-gray-400">atau</span>
                        <div class="h-px flex-1 bg-gray-200"></div>
                    </div>

                    {{-- Daftar dengan Google --}}
                    <a href="{{ route('google.redirect') }}"
                        class="flex w-full items-center justify-center gap-2.5 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        <i class="fa-brands fa-google"></i>
                        Daftar dengan Google
                    </a>

                    {{-- Link Login --}}
                    <p class="mt-5 text-center text-sm text-gray-500">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-semibold text-amber-700 transition hover:text-amber-800">
                            Masuk di sini
                        </a>
                    </p>

                </div>
            </div>
        </div>
    </div>
@endsection
