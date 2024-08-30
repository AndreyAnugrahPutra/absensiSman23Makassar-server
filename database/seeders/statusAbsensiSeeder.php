<?php

namespace Database\Seeders;

use App\Models\StatusAbsensi;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class statusAbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        StatusAbsensi::insert([
            ['status_absensi' => 'Hadir',
            'created_at' => Carbon::now('Asia/Makassar')],
            ['status_absensi' => 'Sakit',
            'created_at' => Carbon::now('Asia/Makassar')],
            ['status_absensi' => 'Ijin',
            'created_at' => Carbon::now('Asia/Makassar')],
            ['status_absensi' => 'Alpha',
            'created_at' => Carbon::now('Asia/Makassar')],
            ['status_absensi' => 'Pending',
            'created_at' => Carbon::now('Asia/Makassar')]
        ]);
    }
}
