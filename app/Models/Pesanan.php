<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'user_id',
        'kode_pesanan',
        'nama_penerima',
        'no_whatsapp',
        'alamat',
        'metode_pengambilan',
        'catatan',
        'subtotal',
        'ongkir',
        'total',
        'metode_pembayaran',
        'bukti_pembayaran',
        'bukti_penyerahan',
        'diserahkan_pada',
        'status',
        'status_pembayaran',
        'batas_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'ongkir' => 'decimal:2',
            'total' => 'decimal:2',
            'diserahkan_pada' => 'datetime',
            'batas_pembayaran' => 'datetime',
        ];
    }

    public function pelanggan()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }
}
