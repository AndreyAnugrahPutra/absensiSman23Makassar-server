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
        Schema::table('tb_jadwal', function(Blueprint $table){
            $table->dropForeign(['id_tahun_ajar']);
            $table->foreignUuid('id_tahun_ajar')
            ->change()
            ->references('id_tahun_ajar')->on('tb_tahun_ajar')
            ->onUpdate('cascade')
            ->onDelete('cascade')
            ;
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
