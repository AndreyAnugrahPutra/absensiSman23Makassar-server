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
        Schema::table('tb_absensi', function(Blueprint $table)
        {
            // $table->dropIndex('tb_absensi_status_absensi_foreign');
            $table->dropForeign(['status_absensi']);
            $table->dropColumn('id_status_absensi');
            $table->string('status')->after('deskripsi');
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
