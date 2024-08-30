<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuruAbsenController extends guruController
{
    //
    public function viewAbsen($id_form)
    {
        $dataAbsen = Absensi::where('id_form',$id_form)->join('tb_siswa','tb_absensi.id_siswa','=','tb_siswa.user_id')->select('tb_absensi.*', 'tb_siswa.induk','tb_siswa.nisn')->orderBy('nama_siswa','ASC')->get();

        $jumlahStatus = Absensi::where('id_form', $id_form)->groupBy('status')->selectRaw('status, count(*) as jumlah')->get();

        return Inertia::render('Guru/Absensi/View', [
            'dataAbsen' => $dataAbsen,
            'jumlahStatus' => $jumlahStatus
        ]);
    }
}
