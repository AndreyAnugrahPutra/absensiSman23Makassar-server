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
        Schema::create('tb_jadwal', function (Blueprint $table) {
            $table->uuid('id_jadwal')->primary();
            $table->string('hari');
            $table->foreignUuid('id_kelas')->references('id_kelas')->on('tb_kelas');
            $table->string('kelas');
            $table->foreignUuid('id_mapel')->references('id_mapel')->on('tb_mapel');
            $table->string('mapel');
            $table->foreignUuid('id_tahun_ajar')->references('id_tahun_ajar')->on('tb_tahun_ajar');
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_jadwal');
    }
};
