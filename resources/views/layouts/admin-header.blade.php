<header class="flex min-h-20 items-center justify-between border-b border-gray-200 bg-white px-5 py-4 sm:px-8">

    {{-- Judul Halaman --}}
    <div>
        {{-- <h2 class="text-xl font-bold text-gray-800">
            @yield('page-heading', 'Dashboard')
        </h2> --}}
    </div>

    {{-- Informasi Admin --}}
    <div class="flex items-center gap-3">

        <div class="hidden text-right sm:block">
            <p class="text-sm font-semibold text-gray-800">
                Administrator
            </p>
            <p class="text-xs text-gray-500">
                Pengelola Toko
            </p>
        </div>

        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-700">
            <i class="fa-solid fa-user text-lg"></i>
        </div>

    </div>

</header>
