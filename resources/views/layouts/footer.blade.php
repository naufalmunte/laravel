<footer class="border-t-1 border-white-500 bg-[#241B17] text-white">
    <div class="mx-auto max-w-7xl px-5 py-8 sm:px-8">

        <div class="grid gap-8 md:grid-cols-3 md:gap-0">

            {{-- Identitas Toko --}}
            <div class="md:border-r md:border-white/15 md:pr-8">
                <h2 class="flex items-center gap-2 text-lg font-bold">
                    <i class="fa-solid fa-fish text-amber-400"></i>
                    {{ config('toko.nama') }}
                </h2>

                <p class="mt-3 max-w-xs text-sm leading-6 text-stone-300">
                    Temukan produk pilihan dari Gerai Ikan Bilih Mario.
                </p>
            </div>

            {{-- Kontak --}}
            <div class="md:border-r md:border-white/15 md:px-8">
                <h3 class="font-semibold">Hubungi Kami</h3>

                <a href="mailto:{{ config('toko.email') }}"
                    class="mt-4 flex items-start gap-3 text-sm text-stone-300 transition hover:text-amber-400">
                    <i class="fa-solid fa-envelope mt-1 text-amber-400"></i>
                    <span class="break-all">{{ config('toko.email') }}</span>
                </a>

                <a href="https://wa.me/{{ preg_replace('/\D/', '', config('toko.whatsapp')) }}" target="_blank"
                    rel="noopener noreferrer"
                    class="mt-4 flex items-center gap-3 text-sm text-stone-300 transition hover:text-amber-400">
                    <i class="fa-brands fa-whatsapp text-amber-400"></i>
                    {{ config('toko.whatsapp_format') }}
                </a>

                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(config('toko.alamat')) }}"
                    target="_blank" rel="noopener noreferrer"
                    class="mt-5 inline-flex items-center gap-2 rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-semibold text-[#30251F] transition hover:bg-amber-400">
                    <i class="fa-solid fa-diamond-turn-right"></i>
                    Petunjuk Arah
                </a>
            </div>

            {{-- Google Maps --}}
            <div class="md:pl-8">
                <h3 class="mb-3 flex items-center gap-2 font-semibold">
                    <i class="fa-solid fa-map-location-dot text-amber-400"></i>
                    Lokasi Toko
                </h3>

                <div class="overflow-hidden rounded-xl border border-white/20 bg-white/5 p-1">
                    <iframe src="https://maps.google.com/maps?q={{ urlencode(config('toko.alamat')) }}&output=embed"
                        class="h-48 w-full rounded-lg sm:h-52" style="border: 0;" loading="lazy" allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi {{ config('toko.nama') }} di Google Maps"></iframe>
                </div>
            </div>

        </div>
    </div>

    {{-- Copyright --}}
    <div class="border-t border-white/15 px-5 py-4 text-center text-xs text-stone-400">
        &copy; {{ date('Y') }} {{ config('toko.nama') }}. Hak cipta dilindungi.
    </div>
</footer>
