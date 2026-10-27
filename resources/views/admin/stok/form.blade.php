 @extends('layouts.admin')

 @section('content')
     <div class="mx-auto max-w-4xl space-y-4">

         {{-- Header --}}
         <div>
             <h1 class="text-xl font-bold text-gray-800">Perubahan Stok</h1>
             <p class="mt-1 text-sm text-gray-500">
                 Kelola penambahan dan penyesuaian stok produk.
             </p>
         </div>

         {{-- Error Validasi --}}
         @if ($errors->any())
             <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                 <p class="mb-1 font-semibold">Periksa kembali data berikut:</p>
                 <ul class="list-inside list-disc space-y-1">
                     @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                     @endforeach
                 </ul>
             </div>
         @endif

         {{-- Form --}}
         <form action="{{ route('admin.stok.store') }}" method="POST"
             class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
             @csrf

             <div class="space-y-5 p-5 sm:p-6">

                 {{-- Produk --}}
                 <div>
                     <label for="cari_produk" class="mb-1.5 block text-sm font-semibold text-gray-700">
                         Produk <span class="text-red-500">*</span>
                     </label>

                     <div class="relative">
                         <input type="text" id="cari_produk" placeholder="Ketik nama produk untuk mencari..."
                             autocomplete="off"
                             class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 pr-10 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                         <i
                             class="fa-solid fa-chevron-down pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>

                         <div id="dropdown_produk"
                             class="absolute z-50 mt-1 hidden max-h-52 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                         </div>
                     </div>

                     <input type="hidden" name="produk_id" id="produk_id"
                         value="{{ old('produk_id', request('produk_id')) }}" required>

                     @error('produk_id')
                         <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                     @enderror
                 </div>

                 {{-- Jenis Perubahan --}}
                 <div>
                     <label class="mb-2 block text-sm font-semibold text-gray-700">
                         Jenis Perubahan <span class="text-red-500">*</span>
                     </label>

                     <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                         <label
                             class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 transition hover:border-amber-400 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                             <input type="radio" name="jenis" value="masuk" required @checked(old('jenis', 'masuk') === 'masuk')
                                 class="mt-1 accent-amber-500">

                             <span class="min-w-0">
                                 <span class="block text-sm font-semibold text-gray-800">
                                     Restock
                                 </span>
                                 <span class="mt-0.5 block text-xs leading-5 text-gray-500">
                                     Menambahkan stok dari jumlah saat ini.
                                 </span>
                             </span>
                         </label>

                         <label
                             class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 transition hover:border-amber-400 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                             <input type="radio" name="jenis" value="penyesuaian" required @checked(old('jenis') === 'penyesuaian')
                                 class="mt-1 accent-amber-500">

                             <span class="min-w-0">
                                 <span class="block text-sm font-semibold text-gray-800">
                                     Penyesuaian Stok
                                 </span>
                                 <span class="mt-0.5 block text-xs leading-5 text-gray-500">
                                     Menyamakan jumlah dengan stok fisik.
                                 </span>
                             </span>
                         </label>

                     </div>

                     @error('jenis')
                         <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                     @enderror
                 </div>

                 {{-- Jumlah dan Informasi Stok --}}
                 <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                     <div>
                         <label for="jumlah" id="labelJumlah" class="mb-1.5 block text-sm font-semibold text-gray-700">
                             Jumlah Restock <span class="text-red-500">*</span>
                         </label>

                         <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah') }}" min="0"
                             step="1" required placeholder="Masukkan jumlah"
                             class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                         <p id="bantuanJumlah" class="mt-1.5 text-xs text-gray-500">
                             Masukkan jumlah stok tambahan.
                         </p>

                         @error('jumlah')
                             <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                         @enderror
                     </div>

                     <div class="grid grid-cols-2 gap-2">
                         <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                             <p class="text-xs text-gray-500">Stok Saat Ini</p>
                             <p id="stokSaatIni" class="mt-1 text-lg font-bold text-gray-800">—</p>
                         </div>

                         <div class="rounded-lg border border-amber-200 bg-amber-50 p-3">
                             <p class="text-xs text-amber-700">Perkiraan Stok Akhir</p>
                             <p id="stokAkhir" class="mt-1 text-lg font-bold text-amber-700">—</p>
                         </div>
                     </div>
                 </div>

                 <p class="-mt-2 text-xs text-gray-400">
                     Perkiraan stok akhir akan dihitung otomatis. Stok terbaru tetap divalidasi saat disimpan.
                 </p>

                 {{-- Keterangan --}}
                 <div>
                     <label for="keterangan" class="mb-1.5 block text-sm font-semibold text-gray-700">
                         Keterangan <span class="font-normal text-gray-400">(Opsional)</span>
                     </label>

                     <textarea name="keterangan" id="keterangan" rows="2" maxlength="255"
                         placeholder="Contoh: penambahan stok dari pemasok"
                         class="w-full resize-y rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">{{ old('keterangan') }}</textarea>

                     @error('keterangan')
                         <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                     @enderror
                 </div>

             </div>

             {{-- Tombol --}}
             <div
                 class="flex flex-col-reverse gap-2 border-t border-gray-200 bg-gray-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                 <a href="{{ route('admin.stok.index') }}"
                     class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-600 transition hover:bg-gray-100">
                     Batal
                 </a>

                 <button type="submit"
                     class="rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600">
                     Simpan Perubahan
                 </button>
             </div>
         </form>
     </div>

     <script>
         document.addEventListener('DOMContentLoaded', function() {
             const cariProduk = document.getElementById('cari_produk');
             const dropdownProduk = document.getElementById('dropdown_produk');
             const produkId = document.getElementById('produk_id');
             const jumlahInput = document.getElementById('jumlah');
             const jenisInputs = document.querySelectorAll('input[name="jenis"]');
             const labelJumlah = document.getElementById('labelJumlah');
             const bantuanJumlah = document.getElementById('bantuanJumlah');
             const stokSaatIni = document.getElementById('stokSaatIni');
             const stokAkhir = document.getElementById('stokAkhir');

             const daftarProduk =
                 {{ Illuminate\Support\Js::from(
                     $produk->map(function ($item) {
                             return [
                                 'id' => $item->id,
                                 'nama' => $item->nama_produk,
                                 'stok' => $item->stok,
                             ];
                         })->values(),
                 ) }};

             let produkTerpilih = null;

             function formatAngka(angka) {
                 return new Intl.NumberFormat('id-ID').format(angka);
             }

             function renderDropdown() {
                 const keyword = cariProduk.value.trim().toLowerCase();

                 const hasil = daftarProduk
                     .filter(produk => produk.nama.toLowerCase().includes(keyword))
                     .sort((a, b) => {
                         const namaA = a.nama.toLowerCase();
                         const namaB = b.nama.toLowerCase();
                         const mulaiA = namaA.startsWith(keyword);
                         const mulaiB = namaB.startsWith(keyword);

                         if (mulaiA !== mulaiB) {
                             return mulaiA ? -1 : 1;
                         }

                         return namaA.localeCompare(namaB, 'id');
                     });

                 dropdownProduk.innerHTML = '';

                 if (hasil.length === 0) {
                     const kosong = document.createElement('div');
                     kosong.className = 'px-3.5 py-3 text-sm text-gray-500';
                     kosong.textContent = 'Produk tidak ditemukan';
                     dropdownProduk.appendChild(kosong);
                 } else {
                     hasil.forEach(produk => {
                         const tombol = document.createElement('button');
                         tombol.type = 'button';
                         tombol.className =
                             'flex w-full items-center justify-between gap-3 px-3.5 py-2.5 text-left text-sm transition hover:bg-amber-50';

                         const nama = document.createElement('span');
                         nama.className = 'font-medium text-gray-800';
                         nama.textContent = produk.nama;

                         const stok = document.createElement('span');
                         stok.className = 'shrink-0 text-xs text-gray-500';
                         stok.textContent = 'Stok: ' + formatAngka(produk.stok);

                         tombol.append(nama, stok);
                         tombol.addEventListener('click', function() {
                             pilihProduk(produk);
                         });

                         dropdownProduk.appendChild(tombol);
                     });
                 }

                 dropdownProduk.classList.remove('hidden');
             }

             function pilihProduk(produk) {
                 produkTerpilih = produk;
                 produkId.value = produk.id;
                 cariProduk.value = produk.nama;
                 dropdownProduk.classList.add('hidden');
                 perbaruiTampilan();
             }

             cariProduk.addEventListener('focus', renderDropdown);

             cariProduk.addEventListener('input', function() {
                 produkTerpilih = null;
                 produkId.value = '';
                 perbaruiTampilan();
                 renderDropdown();
             });

             document.addEventListener('click', function(event) {
                 if (
                     !cariProduk.contains(event.target) &&
                     !dropdownProduk.contains(event.target)
                 ) {
                     dropdownProduk.classList.add('hidden');
                 }
             });

             function perbaruiTampilan() {
                 const jenis = document.querySelector('input[name="jenis"]:checked')?.value;
                 const jumlah = jumlahInput.value === '' ? NaN : Number(jumlahInput.value);
                 const stok = produkTerpilih ? Number(produkTerpilih.stok) : NaN;

                 labelJumlah.innerHTML = jenis === 'penyesuaian' ?
                     'Jumlah Stok Fisik <span class="text-red-500">*</span>' :
                     'Jumlah Restock <span class="text-red-500">*</span>';

                 bantuanJumlah.textContent = jenis === 'penyesuaian' ?
                     'Masukkan total stok fisik sebenarnya, bukan selisihnya.' :
                     'Masukkan jumlah stok tambahan yang akan diterima.';

                 stokSaatIni.textContent = Number.isFinite(stok) ?
                     formatAngka(stok) :
                     '—';

                 let akhir = NaN;

                 if (Number.isFinite(stok) && Number.isFinite(jumlah) && jumlah >= 0) {
                     akhir = jenis === 'penyesuaian' ? jumlah : stok + jumlah;
                 }

                 stokAkhir.textContent = Number.isFinite(akhir) ?
                     formatAngka(akhir) :
                     '—';
             }

             jenisInputs.forEach(input => {
                 input.addEventListener('change', perbaruiTampilan);
             });

             jumlahInput.addEventListener('input', perbaruiTampilan);

             // Pulihkan produk jika validasi sebelumnya gagal.
             const produkAwal = daftarProduk.find(
                 produk => String(produk.id) === String(produkId.value)
             );

             if (produkAwal) {
                 pilihProduk(produkAwal);
             }

             perbaruiTampilan();
         });
     </script>
 @endsection
