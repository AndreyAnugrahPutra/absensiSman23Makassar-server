<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\FormAbsensi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class adminAbsenController extends adminController
{
    //
    public function absensiPage()
    {
        return redirect()->route('admin.jadwal.index');
    }

    public function daftarAbsen($id_jadwal)
    {
        $dataAbsensi = FormAbsensi::where('id_jadwal', $id_jadwal)->orderBy('created_at','DESC')->get();

        return Inertia::render('Admin/Absensi/Index',[
            'dataAbsensi' => $dataAbsensi,
        ]);
    }

    public function viewAbsen($id_form)
    {
        $dataAbsen = Absensi::where('id_form',$id_form)->join('tb_siswa','tb_absensi.id_siswa','=','tb_siswa.user_id')->select('tb_absensi.*', 'tb_siswa.induk','tb_siswa.nisn')->orderBy('nama_siswa','ASC')->get();

        $jumlahStatus = Absensi::where('id_form', $id_form)->groupBy('status')->selectRaw('status, count(*) as jumlah')->get();

        return Inertia::render('Admin/Absensi/View', [
            'dataAbsen' => $dataAbsen,
            'jumlahStatus' => $jumlahStatus
        ]);
    }
}
