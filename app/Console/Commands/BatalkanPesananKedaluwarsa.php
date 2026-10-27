<?php

namespace App\Console\Commands;

use App\Models\Pesanan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BatalkanPesananKedaluwarsa extends Command
{
    protected $signature = 'pesanan:batalkan-kedaluwarsa';

    protected $description = 'Membatalkan pesanan QRIS yang melewati batas pembayaran';

    public function handle(): int
    {
        $jumlah = DB::transaction(function () {
            $pesanan = Pesanan::where(
                'status_pembayaran',
                'menunggu_pembayaran'
            )
                ->where('status', 'menunggu_konfirmasi')
                ->whereNotNull('batas_pembayaran')
                ->where('batas_pembayaran', '<=', now())
                ->lockForUpdate()
                ->get();

            foreach ($pesanan as $item) {
                $item->update([
                    'status' => 'dibatalkan',
                    'status_pembayaran' => 'ditolak',
                ]);
            }

            return $pesanan->count();
        });

        $this->info("Jumlah pesanan kedaluwarsa dibatalkan: {$jumlah}");

        return self::SUCCESS;
    }
}
