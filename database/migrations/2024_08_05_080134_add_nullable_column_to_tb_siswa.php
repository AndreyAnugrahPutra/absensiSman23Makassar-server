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
        Schema::table('tb_siswa', function (Blueprint $table) {
            //
            $table->text('alamat')->nullable()->change();
        });

        Schema::table('tb_orangtua_siswa', function (Blueprint $table) {
            //
            $table->text('alamat')->nullable()->change();
            $table->string('status')->nullable()->change();
            $table->string('pekerjaan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_siswa', function (Blueprint $table) {
            //
        });
    }
};
