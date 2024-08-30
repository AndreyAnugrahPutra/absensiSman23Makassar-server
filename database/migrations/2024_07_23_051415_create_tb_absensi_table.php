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
        Schema::create('tb_absensi', function (Blueprint $table) {
            $table->uuid('id_absensi')->primary();
            $table->foreignUuid('id_form')->references('id_form')->on('tb_form_absen');
            $table->foreignUuid('id_siswa')->references('user_id')->on('tb_siswa');
            $table->char('nama_siswa');
            $table->foreignId('status_absensi')->references('id_status_absensi')->on('tb_status_absensi');
            $table->binary('lampiran')->nullable();
            $table->string('waktu_absen');
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_absensi');
    }
};
