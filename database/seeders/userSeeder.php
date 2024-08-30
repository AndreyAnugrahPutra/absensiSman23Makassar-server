<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::insert([
            'user_id' => Uuid::uuid(),
            'nip' => '12345678',
            'nama' => 'Andrey Anugrah Putra',
            'tgl_lahir' => '2001-01-19',
            'jkel' => 'Laki-Laki',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('@12345678'),
            'no_telp' => '08112233445501',
            'alamat' => 'perumnas sudiang',
            'level' => '1',
            'created_at' => Carbon::now('Asia/Makassar'),
        ]);
    }
}
