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
        Schema::create('tb_mapel', function (Blueprint $table) {
            $table->uuid('id_mapel')->primary();
            $table->string('nama_mapel');
            $table->foreignUuid('id_guru')->references('user_id')->on('users');
            $table->string('nama_guru');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_mapel');
    }
};
