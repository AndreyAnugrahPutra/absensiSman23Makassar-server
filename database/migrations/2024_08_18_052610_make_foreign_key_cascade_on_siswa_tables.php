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
        // Schema::table('tb_siswa', function(Blueprint $table){
        //     $table->dropForeign(['orangtua_1']);
        //     $table->foreignUuid('orangtua_1')
        //     ->change()
        //     ->references('user_id')->on('tb_orangtua_siswa')
        //     ->onUpdate('cascade')
        //     ->onDelete('cascade')
        //     ;
        //     $table->dropForeign(['orangtua_2']);
        //     $table->foreignUuid('orangtua_2')
        //     ->change()
        //     ->references('user_id')->on('tb_orangtua_siswa')
        //     ->onUpdate('cascade')
        //     ->onDelete('cascade')
        //     ;
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
