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
        Schema::create('tb_lokasi_absen', function (Blueprint $table) {
            $table->uuid('id_lokasi')->primary();
            $table->string('nama_lokasi');
            $table->text('alamat');
            $table->double('latitude');
            $table->double('longitude');
            $table->double('radius');
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_absen');
    }
};
