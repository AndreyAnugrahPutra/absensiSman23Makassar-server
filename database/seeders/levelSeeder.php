<?php

namespace Database\Seeders;

use App\Models\Level;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class levelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Level::insert([
            ['nama_level' => 'admin',
            'created_at' => Carbon::now('Asia/Makassar')],
            ['nama_level' => 'guru',
            'created_at' => Carbon::now('Asia/Makassar')],
            ['nama_level' => 'siswa',
            'created_at' => Carbon::now('Asia/Makassar')],
            ['nama_level' => 'orangtua_siswa',
            'created_at' => Carbon::now('Asia/Makassar')]
        ]);
    }
}
