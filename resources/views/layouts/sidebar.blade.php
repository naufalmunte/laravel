<aside class="flex h-screen w-full flex-col overflow-hidden bg-[#30251F] text-white">

    {{-- Logo --}}
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-white/10 px-5">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-white">
            <img src="{{ asset('images/rklogo.jpeg') }}" alt="Logo {{ config('toko.nama') }}"
                class="h-full w-full object-contain">
        </div>

        <div class="min-w-0">
            <h1 class="truncate text-sm font-bold leading-5">
                {{ config('toko.nama') }}
            </h1>
            <p class="mt-0.5 text-[11px] text-gray-300">
                Panel Administrator
            </p>
        </div>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-3">

        <p class="px-3 pb-1 pt-1 text-[10px] font-semibold uppercase tracking-wider text-gray-400">
            Menu Utama
        </p>

        {{-- Dashboard --}}
        <a href="{{ url('/admin/dashboard') }}"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
            {{ request()->is('admin/dashboard') ? 'bg-amber-500 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-gauge-high w-4 text-center"></i>
            Dashboard
        </a>

        {{-- Produk --}}
        <a href="{{ url('/admin/produk') }}"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
            {{ request()->is('admin/produk*') ? 'bg-amber-500 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-box-open w-4 text-center"></i>
            Produk
        </a>

        {{-- Kategori --}}
        <a href="{{ url('/admin/kategori') }}"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
            {{ request()->is('admin/kategori*') ? 'bg-amber-500 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-tags w-4 text-center"></i>
            Kategori
        </a>

        {{-- Pesanan --}}
        <a href="{{ url('/admin/pesanan') }}"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
            {{ request()->is('admin/pesanan*') ? 'bg-amber-500 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-receipt w-4 text-center"></i>
            Pesanan
        </a>

        {{-- Pembayaran --}}
        <a href="{{ url('/admin/pembayaran') }}"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
            {{ request()->is('admin/pembayaran*') ? 'bg-amber-500 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-money-bill-wave w-4 text-center"></i>
            Pembayaran
        </a>

        {{-- Stok --}}
        <a href="{{ url('/admin/stok') }}"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
            {{ request()->is('admin/stok*') ? 'bg-amber-500 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-warehouse w-4 text-center"></i>
            Stok
        </a>

        <p class="px-3 pb-1 pt-4 text-[10px] font-semibold uppercase tracking-wider text-gray-400">
            Lainnya
        </p>

        {{-- Laporan --}}
        <a href="{{ url('/admin/laporan') }}"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
            {{ request()->is('admin/laporan*') ? 'bg-amber-500 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-chart-column w-4 text-center"></i>
            Laporan
        </a>

        {{-- Pengaturan
        <a href="{{ url('/admin/pengaturan') }}"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
            {{ request()->is('admin/pengaturan*') ? 'bg-amber-500 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
            <i class="fa-solid fa-gear w-4 text-center"></i>
            Pengaturan
        </a> --}}

    </nav>

    {{-- Tombol Logout Sidebar --}}
    <div class="shrink-0 border-t border-white/10 p-3">
        <button type="button" onclick="bukaModalLogout()"
            class="flex w-full items-center gap-3 rounded-lg px-3 py-3 text-sm font-semibold text-red-300 transition hover:bg-red-500/10 hover:text-red-400">
            <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
            Logout
        </button>
    </div>

    {{-- Modal Konfirmasi Logout --}}
    <div id="modalLogout" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/60 p-4">

        <div class="w-full max-w-sm rounded-xl bg-white p-6 shadow-2xl" role="dialog" aria-modal="true"
            aria-labelledby="judulModalLogout">

            <h3 id="judulModalLogout" class="text-lg font-bold text-gray-800">
                Konfirmasi Logout
            </h3>

            <p class="mt-2 text-sm leading-6 text-gray-600">
                Apakah kamu yakin ingin keluar dari panel administrator?
            </p>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" onclick="tutupModalLogout()"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Batal
                </button>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                        Ya, Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function bukaModalLogout() {
            const modal = document.getElementById('modalLogout');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.style.overflow = 'hidden';
        }

        function tutupModalLogout() {
            const modal = document.getElementById('modalLogout');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.style.overflow = '';
        }

        document.getElementById('modalLogout').addEventListener('click', function(event) {
            if (event.target === this) {
                tutupModalLogout();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                tutupModalLogout();
            }
        });
    </script>

</aside>
