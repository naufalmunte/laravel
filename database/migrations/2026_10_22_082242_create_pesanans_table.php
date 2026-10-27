<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('kode_pesanan')->unique();

            // Data penerima saat checkout
            $table->string('nama_penerima');
            $table->string('no_whatsapp', 20);
            $table->text('alamat');
            $table->text('catatan')->nullable();

            // Ringkasan pembayaran
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            // Metode pembayaran dan status pesanan
            $table->string('metode_pembayaran');
            $table->string('status')->default('menunggu_pembayaran');
            $table->string('status_pembayaran')->default('belum_bayar');

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};