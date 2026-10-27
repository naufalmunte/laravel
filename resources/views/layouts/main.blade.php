<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <title>@yield('title', config('mitra.nama', 'Gerai Ikan Bilih Mario'))</title>

    <meta name="description" content="@yield('description', 'Temukan produk ikan bilih dari ' . config('mitra.nama', 'Gerai Ikan Bilih Mario') . '. Hubungi kami untuk informasi produk dan pemesanan.')">

    <meta name="theme-color" content="#FAF8F4">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="flex min-h-screen flex-col bg-[#FAF8F4] font-sans text-[#30251F] antialiased">

    {{-- Header Pelanggan --}}
    @include('layouts.header')

    {{-- Konten Utama --}}
    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    {{-- Footer Pelanggan --}}
    @include('layouts.footer')

    {{-- Script Tambahan --}}
    @stack('scripts')

</body>

</html>
