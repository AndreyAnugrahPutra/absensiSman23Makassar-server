<?php

namespace Database\Seeders;

use Faker\Provider\Uuid;
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
            'id_jadwal' => 'de0d2d1a-8143-3716-a396-3d81b8be5a87',
            'id_kelas' => '14fedc2f-591f-3005-9585-008927645f0a',
            'kelas' => 'XI MIPA 1',
            'id_mapel' => '6d68036e-5368-3cc4-88dc-ef7d2921b2e2',
            'mapel' => 'PJOK',
            'id_lokasi' => '9e724d81-504e-342d-8c35-82734f9896da',
            'created_at' => '2024-03-06 12:35:15',
        ]);
    }
}
