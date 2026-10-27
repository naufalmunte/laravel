
@extends('layouts.main')

@section('content')
<div class="flex min-h-[80vh] items-center justify-center bg-[#FAF7F2] px-4 py-12">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl sm:p-9">

        {{-- Logo --}}
        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
            <i class="fa-solid fa-envelope-open-text text-2xl"></i>
        </div>

        <div class="text-center">
            <h1 class="text-2xl font-bold text-[#30251F]">
                Verifikasi Email
            </h1>

            <p class="mt-3 text-sm leading-6 text-gray-500">
                Kami sudah mengirim kode OTP 6 digit ke alamat email:
            </p>

            <p class="mt-2 break-all font-semibold text-[#30251F]">
                {{ $email }}
            </p>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="mt-6 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Form OTP --}}
        <form action="{{ route('register.verify.submit') }}" method="POST" class="mt-7 space-y-5">
            @csrf

            <div>
                <label for="otp" class="mb-2 block text-sm font-semibold text-gray-700">
                    Kode OTP
                </label>

                <input
                    type="text"
                    id="otp"
                    name="otp"
                    value="{{ old('otp') }}"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    minlength="6"
                    maxlength="6"
                    autocomplete="one-time-code"
                    placeholder="000000"
                    required
                    autofocus
                    class="w-full rounded-xl border border-gray-300 px-4 py-4 text-center text-2xl font-bold tracking-[0.5em] text-[#30251F] outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100"
                >

                <p class="mt-2 text-xs text-gray-500">
                    Kode berlaku selama 5 menit sejak dikirim.
                </p>
            </div>

            <button
                type="submit"
                class="w-full rounded-xl bg-[#30251F] px-4 py-3.5 font-semibold text-white transition hover:bg-[#49372D] focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
            >
                Verifikasi Email
                <i class="fa-solid fa-arrow-right ml-2"></i>
            </button>
        </form>

        {{-- Kirim Ulang OTP --}}
        <div class="mt-6 border-t border-gray-100 pt-5 text-center">
            <p class="mb-3 text-sm text-gray-500">
                Belum menerima kode?
            </p>

            <form action="{{ route('register.otp.resend') }}" method="POST">
                @csrf

                <button
                    type="submit"
                    class="text-sm font-semibold text-amber-700 transition hover:text-amber-900"
                >
                    <i class="fa-solid fa-rotate-right mr-1"></i>
                    Kirim Ulang OTP
                </button>
            </form>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('register') }}" class="text-sm text-gray-500 transition hover:text-amber-700">
                <i class="fa-solid fa-arrow-left mr-1"></i>
                Kembali ke pendaftaran
            </a>
        </div>

    </div>
</div>
@endsection
