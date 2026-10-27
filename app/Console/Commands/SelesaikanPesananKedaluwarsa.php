<?php

namespace App\Console\Commands;

use App\Models\Pesanan;
use Illuminate\Console\Command;

class SelesaikanPesananKedaluwarsa extends Command
{
    protected $signature = 'pesanan:selesaikan-kedaluwarsa';

    protected $description =
        'Menyelesaikan pesanan yang tidak dikonfirmasi setelah 24 jam';

    public function handle(): int
    {
        $jumlah = Pesanan::where(
            'status',
            'menunggu_konfirmasi_pelanggan'
        )
            ->whereNotNull('bukti_penyerahan')
            ->whereNotNull('diserahkan_pada')
            ->where(
                'diserahkan_pada',
                '<=',
                now()->subHours(24)
            )
            ->update([
                'status' => 'selesai',
            ]);

        $this->info(
            "{$jumlah} pesanan otomatis diselesaikan."
        );

        return self::SUCCESS;
    }
}
