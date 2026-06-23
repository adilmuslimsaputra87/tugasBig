<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();

            // Relasi (Sesuai request kamu)
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tickets_id')->constrained('tickets')->onDelete('cascade');

            // Data Diri (Untuk menangkap input dari form)
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_number');
            $table->string('nik')->nullable(); // Sesuai form: Opsional

            // Detail Pesanan
            $table->integer('quantity');
            $table->bigInteger('total_price');
            $table->string('promo_code')->nullable(); // Field untuk Kode Promo

            // Pembayaran (Sesuai form nomor 2)
            $table->string('payment_method'); // Contoh: Bank BCA, GoPay, QRIS
            $table->string('payment_status')->default('pending');
            $table->dateTime('payment_date')->nullable(); // Ubah ke dateTime agar lebih presisi dibanding date

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
