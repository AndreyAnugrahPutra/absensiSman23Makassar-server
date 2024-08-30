<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuruJadwalController extends Controller
{
    //
    public function index()
    {
        $user_id = auth()->user()->user_id;
        $dataJadwal = Jadwal::join('tb_mapel','tb_jadwal.id_mapel','=','tb_mapel.id_mapel')->where('tb_mapel.id_guru',$user_id)->join('tb_tahun_ajar','tb_jadwal.id_tahun_ajar','tb_tahun_ajar.id_tahun_ajar')->orderBy('hari', 'DESC')->get();
        $dataKelas = Kelas::select('id_kelas','nama_kelas')->get();
        $dataMapel = Mapel::select('id_mapel','nama_mapel','waktu_mulai','waktu_selesai')->get();
        $dataTahunAjar = TahunAjar::select('id_tahun_ajar', 'semester','mulai','selesai')->orderBy('mulai','ASC')->get();  
        return Inertia::render('Guru/Jadwal/Index', [
            'dataJadwal' => $dataJadwal,
            'dataKelas' => $dataKelas,
            'dataMapel' => $dataMapel,
            'dataTahunAjar' => $dataTahunAjar,
        ]);
    }
}
