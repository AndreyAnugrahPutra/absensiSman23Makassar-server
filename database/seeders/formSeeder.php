<?php

namespace Database\Seeders;

use Faker\Provider\Uuid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class formSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tb_form_absen')->insert([
            'id_form' => Uuid::uuid(),
            'id_jadwal' => '8582aa9d-c646-3890-a623-c629dc121ab2',
            'id_kelas' => '6b3f1f1d-8aeb-3094-a4a9-618d6c85ac87',
            'kelas' => 'XI IPS 1',
            'id_mapel' => '533caa79-7ca6-3733-9133-c2bf46e5d452',
            'mapel' => 'GEO',
            'id_lokasi' => '9e724d81-504e-342d-8c35-82734f9896da',
            'created_at' => '2024-09-06 11:30:24',
        ]);
    }
}
