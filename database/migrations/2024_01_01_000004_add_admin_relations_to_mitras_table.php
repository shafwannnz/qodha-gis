<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ============================================================
 * Migration: Tambah Relasi antara `admins` dan `mitras`
 * ============================================================
 *
 * Relasi yang ditambahkan: ONE-TO-MANY (admins -> mitras)
 *
 *   - admins.id  ──┬──> mitras.created_by  (admin yang menambahkan data mitra)
 *                  └──> mitras.updated_by  (admin yang terakhir mengubah data mitra)
 *
 * Fungsi relasi ini:
 * 1. AKUNTABILITAS DATA — setiap data mitra yang dibuat/diubah
 *    melalui panel admin akan tercatat siapa adminnya. Berguna
 *    jika di masa depan ada lebih dari satu admin (multi-user).
 *
 * 2. AUDIT TRAIL SEDERHANA — memudahkan tracing jika ada data
 *    mitra yang salah/perlu diverifikasi, admin terkait dapat
 *    dihubungi atau ditelusuri.
 *
 * 3. FILTERING DI MASA DEPAN — admin dapat melihat "mitra yang
 *    saya input" vs "semua mitra" pada halaman /admin/mitras.
 *
 * `nullable()` digunakan karena data mitra hasil seeding awal
 * (import dari GeoJSON/Excel) tidak memiliki admin pembuat —
 * kolom akan NULL untuk data tersebut, dan hanya terisi untuk
 * data yang dibuat/diubah via form admin setelah migration ini.
 *
 * `onDelete('set null')` — jika akun admin dihapus, data mitra
 * TIDAK ikut terhapus (mitra adalah data bisnis utama yang harus
 * tetap ada), hanya referensi created_by/updated_by menjadi NULL.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            $table->foreignId('created_by')
                ->nullable()
                ->after('keterangan')
                ->constrained('admins')
                ->onDelete('set null');

            $table->foreignId('updated_by')
                ->nullable()
                ->after('created_by')
                ->constrained('admins')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};
