<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('tb_kelas', function(Blueprint $table){
            $table->dropForeign(['id_wali_kelas']);
            $table->foreignUuid('id_wali_kelas')
            ->change()
            ->references('user_id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
        Schema::table('tb_mapel', function(Blueprint $table){
            $table->dropForeign(['id_guru']);
            $table->foreignUuid('id_guru')
            ->change()
            ->references('user_id')->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade')
            ;
        });
        Schema::table('tb_jadwal', function(Blueprint $table){
            $table->dropForeign(['id_mapel']);
            $table->foreignUuid('id_mapel')
            ->change()
            ->references('id_mapel')->on('tb_mapel')
            ->onUpdate('cascade')
            ->onDelete('cascade')
            ;

            $table->dropForeign(['id_kelas']);
            $table->foreignUuid('id_kelas')
            ->change()
            ->references('id_kelas')->on('tb_kelas')
            ->onUpdate('cascade')
            ->onDelete('cascade')
            ;
        });
        Schema::table('tb_form_absen', function(Blueprint $table){
            $table->dropForeign(['id_jadwal']);
            $table->foreignUuid('id_jadwal')
            ->change()
            ->references('id_jadwal')->on('tb_jadwal')
            ->onUpdate('cascade')
            ->onDelete('cascade')
            ;
        });

        Schema::table('tb_absensi', function(Blueprint $table){
            // $table->dropForeign(['id_form']);
            $table->foreignUuid('id_form')
            ->change()
            ->references('id_form')->on('tb_form_absen')
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
