<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mitra');
            $table->string('nama_toko')->nullable();
            $table->string('no_hp', 50)->nullable();
            $table->enum('kategori', ['Super Distributor', 'Distributor', 'Reseller', 'Agen'])->default('Reseller');
            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Aktif');
            $table->string('wilayah')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitras');
    }
};
