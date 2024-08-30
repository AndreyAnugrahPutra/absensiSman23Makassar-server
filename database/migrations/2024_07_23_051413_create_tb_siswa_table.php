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
        Schema::create('tb_siswa', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->string('nisn')->unique();
            $table->foreignUuid('id_kelas')->references('id_kelas')->on('tb_kelas');
            $table->char('nama');
            $table->date('tgl_lahir');
            $table->enum('jkel', ["Laki-Laki","Perempuan"]);
            $table->string('email')->unique();
            $table->text('password');
            $table->string('no_telp')->unique();
            $table->text('alamat');
            $table->binary('foto_profil')->nullable();
            $table->rememberToken();
            $table->foreignId('level')->references('id_level')->on('tb_level');
            $table->foreignUuid('orangtua_1')->references('user_id')->on('tb_orangtua_siswa');
            $table->foreignUuid('orangtua_2')->references('user_id')->on('tb_orangtua_siswa')->nullable();
            $table->datetime('last_login')->nullable();
            $table->datetime('last_logout')->nullable();
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_siswa');
    }
};
