<?php

namespace App\Http\Controllers\guru;

use App\Models\Kelas;
use App\Models\Orangtua;
use Inertia\Inertia;

class GuruKelasController extends guruController
{
    //
    public function kelasPage()
    {
        $user_id = auth()->user()->user_id;
        $dataKelas = Kelas::where('id_wali_kelas', $user_id)->join('tb_siswa','tb_kelas.id_kelas','=','tb_siswa.id_kelas')->select('tb_kelas.*','tb_siswa.induk','tb_siswa.nisn','tb_siswa.nama','tb_siswa.jkel','tb_siswa.foto_path','tb_siswa.tgl_lahir','tb_siswa.alamat','tb_siswa.orangtua_1','tb_siswa.orangtua_2')->orderBy('tb_siswa.nama','ASC')->get();

        $dataOrangtua = Orangtua::select('user_id','nama','status')->get();

        return Inertia::render('Guru/Kelas/Index', [
            'dataKelas' => $dataKelas,
            'dataOrangtua' => $dataOrangtua
        ]);
    }
}
