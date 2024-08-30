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
        //
        Schema::table('tb_siswa', function (Blueprint $table) {
            //
            $table->string('foto_profil')->nullable()->change();
            $table->string('foto_path')->nullable()->after('foto_profil');
        });
        Schema::table('tb_orangtua_siswa', function (Blueprint $table) {
            //
            $table->string('foto_profil')->nullable()->change();
            $table->string('foto_path')->nullable()->after('foto_profil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
