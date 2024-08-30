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
        Schema::table('tb_absensi', function (Blueprint $table)
        {
            $table->string('lampiran_file')->nullable()->after('nama_siswa');
            $table->string('lampiran_path')->nullable()->after('lampiran_file');
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
