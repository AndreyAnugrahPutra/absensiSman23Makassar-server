<?php

namespace Database\Seeders;

use Faker\Provider\Uuid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class absensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $idForm = '2a655f85-ae80-3f1e-b4da-af6228a74a1b';
        $idJadwal = 'de0d2d1a-8143-3716-a396-3d81b8be5a87';

        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => 'dddea6a7-c712-327b-ba1b-edd15550136f',
            'nama_siswa' => 'AFDAL ZULHIJA',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:00',
            'created_at' => '2024-09-03 13:02:00',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '800922f7-bee7-3065-9e1b-e91ff1ccc05e',
            'nama_siswa' => 'AHMAD AIDIL ALHAQ',
            'status' => 'Hadir',
            'waktu_absen' => '13:01:00',
            'created_at' => '2024-09-03 13:01:00',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '0e8a0060-baf0-3077-877e-1b071592763b',
            'nama_siswa' => 'AISYAH',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:00',
            'created_at' => '2024-09-03 13:02:00',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '2f89284f-8501-3aa3-9a3a-02e7d29dcc04',
            'nama_siswa' => 'ALIKA AMRAENI',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:48',
            'created_at' => '2024-09-03 13:02:48',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '4c4a025a-3423-3282-b3a8-a9034939f7b9',
            'nama_siswa' => 'AMALIA MUSTIKA DEWI',
            'status' => 'Hadir',
            'waktu_absen' => '13:01:30',
            'created_at' => '2024-09-03 13:01:30',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => 'ab7e8c30-2e61-3a6f-b3b4-4145efe1528c',
            'nama_siswa' => 'ANDI ARDILA GHIVARI',
            'status' => 'Hadir',
            'waktu_absen' => '13:01:45',
            'created_at' => '2024-09-03 13:01:45',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '9e123ddc-0397-355e-9543-d711669f8dbf',
            'nama_siswa' => 'ANDI FAQJAH AHMAD',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:20',
            'created_at' => '2024-09-03 13:02:20',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => 'd4a6f79c-8135-34b9-a104-51447579fe0b',
            'nama_siswa' => 'ANDI RENGGA FEBRIANTO',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:25',
            'created_at' => '2024-09-03 13:02:25',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '2a43bba2-6fea-3e66-87ec-80473b667513',
            'nama_siswa' => 'ANNISA FARHA',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:18',
            'created_at' => '2024-09-03 13:02:18',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '102914cf-a0b2-31ff-891d-3da401e73f3e',
            'nama_siswa' => 'AZ ZAHRAH SAPUTRI ARIEF',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:55',
            'created_at' => '2024-09-03 13:02:55',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '124d4c76-6106-3038-a24e-66d22635e7ac',
            'nama_siswa' => 'DZAKI KHAERUL ANAM RAMADHAN',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:22',
            'created_at' => '2024-09-03 13:02:22',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => 'c0cdb6c4-1d85-3912-acd4-2bc146bc8b66',
            'nama_siswa' => 'FADILA AMALIA SYALWA',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:12',
            'created_at' => '2024-09-03 13:02:12',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => 'a5f550fb-cad1-3fbc-8211-ef05ec6e8949',
            'nama_siswa' => 'IMELDA TRYPELANGI RAYA',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:16',
            'created_at' => '2024-09-03 13:02:16',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '1e752aa7-57d6-32c5-8f36-f06d104936e0',
            'nama_siswa' => 'JIHAN WASILAH',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:20',
            'created_at' => '2024-09-03 13:02:20',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => 'c8f250d3-7d2b-3fcd-95d5-a7701dddcfd1',
            'nama_siswa' => 'JILL GRACIA SHANEN NATASHA',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:21',
            'created_at' => '2024-09-03 13:02:21',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '7a90d013-c96e-3da3-b677-c3bbbfabfcc7',
            'nama_siswa' => 'KAYLA NURUL AFIFAH',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:52',
            'created_at' => '2024-09-03 13:02:52',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '9742cfce-9da9-3ded-83d1-9c113a5499fe',
            'nama_siswa' => 'KERENHAPUKH ETA',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:59',
            'created_at' => '2024-09-03 13:02:59',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => 'accd34d0-32be-365d-8716-7ab5a4e3adf0',
            'nama_siswa' => 'KEZIA PUTRI FITRAWAN',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:08',
            'created_at' => '2024-09-03 13:02:08',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => 'de67a83f-8188-3dfc-8316-435cdcaede16',
            'nama_siswa' => 'MIKAYLA AZ ZURAH BENING',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:16',
            'created_at' => '2024-09-03 13:02:16',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => 'cfe8ed40-9cb6-358f-83ee-b616056fc3b3',
            'nama_siswa' => 'MUH FAHLEVY ABHINAYA',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:19',
            'created_at' => '2024-09-03 13:02:19',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '17d71716-d7ef-3002-a4d4-0403e186d1ea',
            'nama_siswa' => 'MUH ILHAM NUR G K',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:21',
            'created_at' => '2024-09-03 13:02:21',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '161d8d48-907e-31b7-86a2-cc0bcadced82',
            'nama_siswa' => 'MUH REIHAN ALAM SAPUTRA',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:20',
            'created_at' => '2024-09-03 13:02:20',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '87ca8107-f95c-3a63-b387-c8c156eabaff',
            'nama_siswa' => 'MUH REIHAN REFALDI',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:34',
            'created_at' => '2024-09-03 13:02:34',
        ]);
        // DB::table('tb_absensi')->insert([
        //     'id_absensi' => Uuid::uuid(),
        //     'id_form' => $idForm,
        //     'id_jadwal' => $idJadwal,
        //     'id_siswa' => '28d25adb-56b7-31f7-b980-010a88c3c881',
        //     'nama_siswa' => 'MUHAMMAD AFGAN AHMAD',
        //     'status' => 'Hadir',
        //     'waktu_absen' => '13:02:25',
        //     'created_at' => '2024-09-03 13:02:25',
        // ]);
        // DB::table('tb_absensi')->insert([
        //     'id_absensi' => Uuid::uuid(),
        //     'id_form' => $idForm,
        //     'id_jadwal' => $idJadwal,
        //     'id_siswa' => 'daf2da6d-635f-3eb8-9927-b257fdc9ef15',
        //     'nama_siswa' => 'MUHAMMAD LABIB WAHYUDDIN',
        //     'status' => 'Hadir',
        //     'waktu_absen' => '13:02:22',
        //     'created_at' => '2024-09-03 13:02:22',
        // ]);
        // DB::table('tb_absensi')->insert([
        //     'id_absensi' => Uuid::uuid(),
        //     'id_form' => $idForm,
        //     'id_jadwal' => $idJadwal,
        //     'id_siswa' => 'c3d78c80-f8b4-321a-ab07-c50b9b01e129',
        //     'nama_siswa' => 'NAHDA KUSPIA S',
        //     'status' => 'Hadir',
        //     'waktu_absen' => '13:02:15',
        //     'created_at' => '2024-09-03 13:02:15',
        // ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => 'd941388a-b09c-3f97-bee3-323ef593076e',
            'nama_siswa' => 'NURUL ANNISA RAHMAWATI',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:14',
            'created_at' => '2024-09-03 13:02:14',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '6d111e0d-674b-3b9f-a367-df3c8099b7fb',
            'nama_siswa' => 'NURUL ATIRAH',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:08',
            'created_at' => '2024-09-03 13:02:08',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => 'f5bd0684-4c55-3294-98a6-7827a91925bb',
            'nama_siswa' => 'RENALD RANTESALU',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:22',
            'created_at' => '2024-09-03 13:02:22',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '0eb1d86c-e055-37ab-833c-d468c47e7b1d',
            'nama_siswa' => 'RONALD RANTESALU',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:25',
            'created_at' => '2024-09-03 13:02:25',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '29fd88b2-0c40-3810-ab92-9aa8186acc17',
            'nama_siswa' => 'RIYAD AFGANSYAH IKRAM',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:28',
            'created_at' => '2024-09-03 13:02:28',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => 'f1b47b1a-18bd-3e97-8967-26f9b5c474be',
            'nama_siswa' => 'SATRIO BINTANG YASYKUR',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:30',
            'created_at' => '2024-09-03 13:02:30',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '5a2d0487-091d-3c10-8dc6-e29fc7e34633',
            'nama_siswa' => 'SUCI RAHMADANI J',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:29',
            'created_at' => '2024-09-03 13:02:29',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '752c98eb-79e2-3511-99d3-cb91d955afcc',
            'nama_siswa' => 'WINNIE DESTRYFIANTY MICHAELA',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:10',
            'created_at' => '2024-09-03 13:02:10',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $idForm,
            'id_jadwal' => $idJadwal,
            'id_siswa' => '341d4c20-217c-36df-affb-a84b032ff88c',
            'nama_siswa' => 'WULAN RANTE GUSI',
            'status' => 'Hadir',
            'waktu_absen' => '13:02:18',
            'created_at' => '2024-09-03 13:02:18',
        ]);
    }
}
