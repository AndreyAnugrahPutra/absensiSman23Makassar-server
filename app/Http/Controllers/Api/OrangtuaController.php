<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\apiResource;
use App\Models\Absensi;
use App\Models\FormAbsensi;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Orangtua;
use App\Models\Siswa;

class OrangtuaController extends Controller
{
    //
    public function index($email)
    {
        $id_ortu = Orangtua::where('email', $email)->select('user_id')->get();

        $anak = Siswa::where('orangtua_1', $id_ortu[0]['user_id'])->orWhere('orangtua_2', $id_ortu[0]['user_id'])->join('tb_kelas','tb_siswa.id_kelas','=','tb_kelas.id_kelas')->select('tb_siswa.user_id','tb_siswa.induk','tb_siswa.nisn','tb_siswa.nama','tb_kelas.id_kelas','tb_kelas.nama_kelas','tb_kelas.nama_wali_kelas')->get();

        if($anak->count() > 0)
        {
            return new apiResource(true, 'data anak',$anak);
        }
        else 
        {    
            return new apiResource(false, 'data anak', null);
        }

        // $anak = Siswa::where('');
    }

    public function jadwalAnak($id_kelas)
    {
        $dataKelas = Kelas::where('tb_kelas.id_kelas', $id_kelas)->join('tb_jadwal','tb_kelas.id_kelas','=','tb_jadwal.id_kelas')->join('tb_mapel','tb_jadwal.id_mapel','=','tb_mapel.id_mapel')->select('tb_jadwal.hari','tb_mapel.nama_mapel', 'tb_jadwal.id_jadwal','tb_mapel.nama_guru','tb_mapel.waktu_mulai','tb_mapel.waktu_selesai','tb_kelas.*')->orderBy('tb_jadwal.hari','DESC')->get();

        if(!$dataKelas)
        {
            return new apiResource(true, 'Data Jadwal Kelas', null);
        }        
        return new apiResource(true, 'Data Jadwal Kelas', $dataKelas);
    }
    
    public function absenAnak($id_jadwal,$id_anak)
    {
        $dataAbsen = FormAbsensi::where('tb_form_absen.id_jadwal',$id_jadwal)->join('tb_absensi','tb_form_absen.id_form','=','tb_absensi.id_form')->join('tb_mapel','tb_form_absen.id_mapel','=','tb_mapel.id_mapel')->select('tb_absensi.id_form','tb_absensi.nama_siswa','tb_absensi.lampiran_file','tb_absensi.lampiran_path','tb_absensi.status','tb_absensi.waktu_absen','tb_absensi.created_at','tb_absensi.deskripsi','tb_mapel.nama_mapel','tb_mapel.nama_guru','tb_form_absen.kelas')->where('tb_absensi.id_siswa',$id_anak)->get();

        $jadwal = Jadwal::where('id_jadwal',$id_jadwal)->join('tb_mapel','tb_jadwal.id_mapel','=','tb_mapel.id_mapel')->select('nama_guru','nama_mapel')->get();
        
        if($dataAbsen->count() < 1)
        {
            return new apiResource(true, 'data absen anak', [null,$jadwal]);
        }

        $jumlahStatus = Absensi::where('id_form', $dataAbsen[0]['id_form'])->where('id_siswa',$id_anak)->groupBy('status')->selectRaw('status, count(*) as jumlah')->get();  

        return new apiResource(true, 'data absen anak', [$dataAbsen, $jadwal, $jumlahStatus]);

    }
}
