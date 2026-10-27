<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard Admin')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    @stack('styles')
</head>

<body class="bg-gray-100 font-sans text-gray-800 antialiased">

    <div class="min-h-screen">

        {{-- Sidebar Tetap --}}
        <div class="fixed inset-y-0 left-0 z-40 w-60">
            @include('layouts.sidebar')
        </div>

        {{-- Area Konten --}}

        {{-- Area Konten --}}
        <div class="flex min-h-screen min-w-0 flex-col md:ml-60">

            {{-- Header tetap di atas --}}
            <div class="sticky top-0 z-30">
                @include('layouts.admin-header')
            </div>

            {{-- Konten mengisi ruang yang tersedia --}}
            <main class="min-w-0 flex-1 p-5 sm:p-8">
                @yield('content')
            </main>

            {{-- Footer terdorong ke bawah --}}
            <footer
                class="mt-auto border-t border-gray-200 bg-white px-5 py-4 text-center text-xs text-gray-500 sm:px-8">
                &copy; {{ date('Y') }} Khas Minang. All rights reserved.
            </footer>

        </div>

    </div>

    @stack('scripts')

</body>

</html>
