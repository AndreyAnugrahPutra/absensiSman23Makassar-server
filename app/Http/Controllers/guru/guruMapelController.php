<?php

namespace App\Http\Controllers\guru;

use App\Models\FormAbsensi;
use App\Models\Jadwal;
use App\Models\LokasiAbsen;
use App\Models\Mapel;
use Inertia\Inertia;

class guruMapelController extends guruController
{
    //
    public function index()
    {
        $user_id = auth()->user()->user_id;
        $dataMapel = Mapel::where('id_guru', $user_id)->join('tb_jadwal','tb_mapel.id_mapel','=','tb_jadwal.id_mapel')->orderBy('tb_jadwal.hari', 'DESC')->get();
        // $dataMapel = Mapel::where('id_guru', $user_id)->join('tb_jadwal','tb_mapel.id_mapel','=','tb_jadwal.id_mapel')->get();
        return Inertia::render('Guru/Mapel/Index', [
            'dataMapel' => $dataMapel
        ]);
    }
    
    public function detailMapel($id_jadwal)
    {
        $dataFormAbsen = FormAbsensi::where('tb_form_absen.id_jadwal', $id_jadwal)->join('tb_mapel', 'tb_form_absen.id_mapel','=','tb_mapel.id_mapel')->join('tb_jadwal','tb_form_absen.id_jadwal','=','tb_jadwal.id_jadwal')->join('tb_tahun_ajar','tb_jadwal.id_tahun_ajar','=','tb_tahun_ajar.id_tahun_ajar')->select('tb_form_absen.*', 'tb_mapel.waktu_mulai','tb_mapel.waktu_selesai','tb_mapel.nama_guru','tb_tahun_ajar.mulai','tb_tahun_ajar.selesai')->orderBy('tb_jadwal.hari', 'DESC')->get();

        $dataJadwal = Jadwal::where('id_jadwal',$id_jadwal)->get();
        $dataLokasi = LokasiAbsen::all();

        return Inertia::render('Guru/Mapel/Detail', [
            'dataFormAbsen' => $dataFormAbsen,
            'dataJadwal' => $dataJadwal,
            'dataLokasi' => $dataLokasi,
        ]);
    }
}
