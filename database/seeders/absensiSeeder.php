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
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => 'df202f44-0cb4-338a-b19d-23a54a1f41c5',
            'id_jadwal' => '8582aa9d-c646-3890-a623-c629dc121ab2',
            'id_siswa' => '13538798-4dc3-37dc-88e0-d1d63ab6ad17',
            'nama_siswa' => 'AZIZAH KHAIRUNISWA',
            'status' => 'Hadir',
            'waktu_absen' => '11:32:00',
            'created_at' => '2024-09-04 11:32:00',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => 'df202f44-0cb4-338a-b19d-23a54a1f41c5',
            'id_jadwal' => '8582aa9d-c646-3890-a623-c629dc121ab2',
            'id_siswa' => '1a3afe4d-a005-385e-950d-e71e85cdd78a',
            'nama_siswa' => 'ANDINA MUTIARA SYAFRI',
            'status' => 'Hadir',
            'waktu_absen' => '11:35:05',
            'created_at' => '2024-09-04 11:35:05',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => 'df202f44-0cb4-338a-b19d-23a54a1f41c5',
            'id_jadwal' => '8582aa9d-c646-3890-a623-c629dc121ab2',
            'id_siswa' => '1f44d00c-1034-394f-992a-905bda96bac4',
            'nama_siswa' => 'ANDI MANILA MAJAJARENG',
            'status' => 'Hadir',
            'waktu_absen' => '11:34:15',
            'created_at' => '2024-09-04 11:34:15',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => 'df202f44-0cb4-338a-b19d-23a54a1f41c5',
            'id_jadwal' => '8582aa9d-c646-3890-a623-c629dc121ab2',
            'id_siswa' => '1e250c1b-88bb-3313-b2d3-4dbc6c8f51b0',
            'nama_siswa' => 'CHEZA RAMADHANTY AMAT',
            'status' => 'Hadir',
            'waktu_absen' => '11:34:10',
            'created_at' => '2024-09-04 11:34:10',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => 'df202f44-0cb4-338a-b19d-23a54a1f41c5',
            'id_jadwal' => '8582aa9d-c646-3890-a623-c629dc121ab2',
            'id_siswa' => '203e8119-3069-3fcf-9dfb-85539b807047',
            'nama_siswa' => 'FAREL AL ALIYYU YUSUF BALLADO',
            'status' => 'Hadir',
            'waktu_absen' => '11:32:45',
            'created_at' => '2024-09-04 11:32:45',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => 'df202f44-0cb4-338a-b19d-23a54a1f41c5',
            'id_jadwal' => '8582aa9d-c646-3890-a623-c629dc121ab2',
            'id_siswa' => '2191db9c-f1f5-30bd-a2b9-0dda3f618423',
            'nama_siswa' => 'ALIKHA SAYYIDINA',
            'status' => 'Hadir',
            'waktu_absen' => '11:32:18',
            'created_at' => '2024-09-04 11:32:18',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => 'df202f44-0cb4-338a-b19d-23a54a1f41c5',
            'id_jadwal' => '8582aa9d-c646-3890-a623-c629dc121ab2',
            'id_siswa' => '3f8d2537-c1f1-365c-b875-06cb11ab26e9',
            'nama_siswa' => 'CANTIKA NUR ANNISA',
            'status' => 'Hadir',
            'waktu_absen' => '11:32:10',
            'created_at' => '2024-09-04 11:32:10',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => 'df202f44-0cb4-338a-b19d-23a54a1f41c5',
            'id_jadwal' => '8582aa9d-c646-3890-a623-c629dc121ab2',
            'id_siswa' => 'bfbaa2e6-e09f-3228-8f7a-4fab59ebd964',
            'nama_siswa' => 'ANNISA ALMAGHFIRAH',
            'status' => 'Hadir',
            'waktu_absen' => '11:34:00',
            'created_at' => '2024-09-04 11:34:00',
        ]);
        DB::table('tb_absensi')->insert([
            'id_absensi' => Uuid::uuid(),
            'id_form' => 'df202f44-0cb4-338a-b19d-23a54a1f41c5',
            'id_jadwal' => '8582aa9d-c646-3890-a623-c629dc121ab2',
            'id_siswa' => '8483f6d0-86fd-31a0-a0a8-c7a9f279ecb5',
            'nama_siswa' => 'ANDI FIKRI NUR SABANI',
            'status' => 'Hadir',
            'waktu_absen' => '11:33:39',
            'created_at' => '2024-09-04 11:33:39',
        ]);
    }
}
