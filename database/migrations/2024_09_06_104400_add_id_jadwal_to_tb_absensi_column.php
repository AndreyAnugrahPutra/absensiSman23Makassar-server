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
        Schema::table('tb_absensi', function (Blueprint $table) {
            //
            $table
            ->foreignUuid('id_jadwal')
            ->references('id_jadwal')->on('tb_jadwal')
            ->onUpdate('cascade')
            ->onDelete('cascade')
            ->after('id_form');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
