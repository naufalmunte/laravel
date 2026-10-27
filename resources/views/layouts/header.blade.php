<header class="sticky top-0 z-50 border-b border-stone-200 bg-[#FAF8F4]/95 backdrop-blur">

    <nav class="mx-auto flex max-w-7xl items-center justify-between gap-2 px-4 py-3 sm:px-5 sm:py-4 lg:px-8">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex min-w-0 shrink items-center gap-2.5 sm:gap-3">
            <img src="{{ asset('images/rklogo.jpeg') }}" alt="Logo {{ config('mitra.nama') }}"
                class="h-10 w-10 shrink-0 object-contain sm:h-11 sm:w-11">

            <div class="min-w-0">
                <span class="block truncate text-base font-bold leading-tight text-[#59372B] sm:text-lg">
                    {{ config('mitra.nama') }}
                </span>

                <span class="block truncate text-[10px] leading-4 text-stone-600 sm:text-xs">
                    Gerai Ikan Bilih Mario
                </span>
            </div>
        </a>

        {{-- Navigasi Desktop --}}
        <div class="hidden items-center gap-7 text-sm font-medium text-stone-600 md:flex">
            <a href="{{ url('/') }}"
                class="transition hover:text-[#8B4C35] {{ request()->is('/') ? 'font-bold text-[#8B4C35]' : '' }}">
                Beranda
            </a>

            <a href="{{ url('/produk') }}"
                class="transition hover:text-[#8B4C35] {{ request()->is('produk*') ? 'font-bold text-[#8B4C35]' : '' }}">
                Produk
            </a>

            <a href="{{ route('pelanggan.pesanan.index') }}"
                class="transition hover:text-[#8B4C35] {{ request()->routeIs('pelanggan.pesanan.*') ? 'font-bold text-[#8B4C35]' : '' }}">
                Pesanan Saya
            </a>

            <a href="{{ url('/#tentang') }}" class="transition hover:text-[#8B4C35]">
                Tentang Kami
            </a>
        </div>

        {{-- Aksi Navbar --}}
        <div class="flex shrink-0 items-center gap-1 sm:gap-2">

            {{-- Keranjang --}}
            <a href="{{ url('/keranjang') }}" aria-label="Keranjang belanja"
                class="flex h-10 w-10 items-center justify-center rounded-full text-[#59372B] transition hover:bg-stone-200 sm:h-11 sm:w-11">

                <svg xmlns="http://www.w3.org/2000/svg" class="h-[21px] w-[21px] sm:h-[22px] sm:w-[22px]"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                </svg>
            </a>

            {{-- Aksi Desktop --}}
            <div class="hidden items-center gap-3 md:flex">
                @auth
                    <span class="max-w-32 truncate text-sm font-semibold text-[#59372B]">
                        {{ auth()->user()->name }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-[#59372B] px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#744735]">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ url('/login') }}"
                        class="rounded-full border border-stone-300 px-5 py-2.5 text-sm font-medium transition hover:border-[#59372B]">
                        Masuk
                    </a>

                    <a href="{{ url('/register') }}"
                        class="rounded-full bg-[#59372B] px-5 py-2.5 text-sm font-medium text-white transition hover:bg-[#744735]">
                        Daftar
                    </a>
                @endauth
            </div>

            {{-- Tombol Hamburger Mobile --}}
            <button type="button" id="mobile-menu-button" aria-label="Buka menu navigasi" aria-controls="mobile-menu"
                aria-expanded="false"
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-[#59372B] transition hover:bg-stone-200 md:hidden">

                <svg id="mobile-menu-open-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>

                <svg id="mobile-menu-close-icon" xmlns="http://www.w3.org/2000/svg" class="hidden h-6 w-6"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </div>
    </nav>

    {{-- Menu Mobile --}}
    <div id="mobile-menu" class="hidden border-t border-stone-200 bg-[#FAF8F4] px-4 py-3 shadow-lg md:hidden">

        <div class="mx-auto flex max-w-7xl flex-col gap-1 text-sm font-medium text-stone-700">

            <a href="{{ url('/') }}"
                class="flex items-center rounded-xl px-4 py-3 transition hover:bg-amber-50 hover:text-[#8B4C35] {{ request()->is('/') ? 'bg-amber-50 font-semibold text-[#8B4C35]' : '' }}">
                <i class="fa-solid fa-house mr-3 w-5 text-center"></i>
                Beranda
            </a>

            <a href="{{ url('/produk') }}"
                class="flex items-center rounded-xl px-4 py-3 transition hover:bg-amber-50 hover:text-[#8B4C35] {{ request()->is('produk*') ? 'bg-amber-50 font-semibold text-[#8B4C35]' : '' }}">
                <i class="fa-solid fa-fish mr-3 w-5 text-center"></i>
                Produk
            </a>

            <a href="{{ route('pelanggan.pesanan.index') }}"
                class="flex items-center rounded-xl px-4 py-3 transition hover:bg-amber-50 hover:text-[#8B4C35] {{ request()->routeIs('pelanggan.pesanan.*') ? 'bg-amber-50 font-semibold text-[#8B4C35]' : '' }}">
                <i class="fa-solid fa-receipt mr-3 w-5 text-center"></i>
                Pesanan Saya
            </a>

            <a href="{{ url('/#tentang') }}"
                class="flex items-center rounded-xl px-4 py-3 transition hover:bg-amber-50 hover:text-[#8B4C35]">
                <i class="fa-solid fa-circle-info mr-3 w-5 text-center"></i>
                Tentang Kami
            </a>

            <div class="my-2 border-t border-stone-200"></div>

            @auth
                {{-- Informasi Akun --}}
                <div class="flex items-center gap-3 rounded-xl bg-[#F1E9DF] p-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#59372B] text-white">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs text-stone-500">Login sebagai</p>
                        <p class="truncate font-semibold text-[#59372B]">
                            {{ auth()->user()->name }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="mt-1">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center rounded-xl px-4 py-3 text-left font-semibold text-red-700 transition hover:bg-red-50">
                        <i class="fa-solid fa-right-from-bracket mr-3 w-5 text-center"></i>
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ url('/login') }}" class="flex items-center rounded-xl px-4 py-3 transition hover:bg-amber-50">
                    <i class="fa-solid fa-right-to-bracket mr-3 w-5 text-center"></i>
                    Masuk
                </a>

                <a href="{{ url('/register') }}"
                    class="mt-1 flex items-center justify-center gap-2 rounded-xl bg-[#59372B] px-4 py-3 font-semibold text-white transition hover:bg-[#744735]">
                    <i class="fa-solid fa-user-plus"></i>
                    Daftar Akun
                </a>
            @endauth
        </div>
    </div>
</header>

{{-- JavaScript Menu Mobile --}}
@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const button = document.getElementById('mobile-menu-button');
                const menu = document.getElementById('mobile-menu');
                const openIcon = document.getElementById('mobile-menu-open-icon');
                const closeIcon = document.getElementById('mobile-menu-close-icon');

                if (!button || !menu || !openIcon || !closeIcon) return;

                function setMenuOpen(isOpen) {
                    menu.classList.toggle('hidden', !isOpen);
                    openIcon.classList.toggle('hidden', isOpen);
                    closeIcon.classList.toggle('hidden', !isOpen);

                    button.setAttribute('aria-expanded', String(isOpen));
                    button.setAttribute(
                        'aria-label',
                        isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi'
                    );
                }

                button.addEventListener('click', function() {
                    const isOpen = button.getAttribute('aria-expanded') === 'true';
                    setMenuOpen(!isOpen);
                });

                menu.querySelectorAll('a').forEach(function(link) {
                    link.addEventListener('click', function() {
                        setMenuOpen(false);
                    });
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        setMenuOpen(false);
                    }
                });

                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        setMenuOpen(false);
                    }
                });
            });
        </script>
    @endpush
@endonce
