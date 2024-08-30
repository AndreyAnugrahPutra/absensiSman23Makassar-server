<?php

namespace App\Http\Controllers\guru;

use App\Models\Absensi;
use App\Models\FormAbsensi;
use App\Models\Mapel;
use App\Models\Siswa;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class guruFormAbsenController extends guruController
{
    //
    public function index()
    {
        return Inertia::render();
    }

    public function daftarAbsen($id_form)
    {
        // $last_logout = '2024-08-17 22:10:04';
        // $allowLogin = Carbon::parse($last_logout)->addHours(24);
        // $now = Carbon::parse('2024-08-17 22:12:04');
        // $canLogin = Carbon::parse($now)->greaterThan($allowLogin); 
        // dd($canLogin);
        
        $dataAbsensi = Absensi::where('tb_absensi.id_form',$id_form)->join('tb_form_absen','tb_absensi.id_form','=','tb_form_absen.id_form')->select('tb_form_absen.kelas','tb_absensi.*')->orderBy('nama_siswa','ASC')->get();

        $jumlahStatus = Absensi::where('id_form', $id_form)->groupBy('status')->selectRaw('status, count(*) as jumlah')->get();

        return Inertia::render('Guru/Mapel/Absensi', [
            'dataAbsensi' => $dataAbsensi,
            'jumlahStatus' => $jumlahStatus
        ]);
    }

    public function createAbsen(Request $request)
    {
        // $created_at = "2024-08-19";
        // $now = "12:35:00";
        $now = Carbon::now('Asia/Makassar');
        // dd(Carbon::parse($now)->format('Y-m-d H:i:s'));
        $mapel = Mapel::find($request->id_mapel);
        if($now >= $mapel->waktu_mulai && $now <= $mapel->waktu_selesai)
        {
            $insert = FormAbsensi::create([
                'id_form' => Uuid::uuid(),
                'id_jadwal' => $request->id_jadwal,
                'id_kelas' => $request->id_kelas,
                'kelas' => $request->kelas,
                'id_mapel' => $request->id_mapel,
                'mapel' => $request->mapel,
                'id_lokasi' => $request->id_lokasi,
                // 'created_at' => Carbon::parse($created_at.$now)->format('Y-m-d H:i:s'),
                'created_at' => Carbon::now('Asia/Makassar'),
            ]);

            if($insert)
            {
                return back()->with([
                    'notif_status' => 'success',
                    'message' => 'Form Absensi berhasil dibuat!',
                    'notif_show' => true,
                ]);
            }
            else
            {
                return redirect()->route('guru.mapel.index')->with([
                    'notif_status' => 'failed',
                    'message' => 'Form Absensi gagal dibuat!',
                    'notif_show' => true,
                ]);
            }
        }
        else 
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Gagal membuat form absen! Jam pelajaran belum dimulai!',
                'notif_show' => true,
            ]);
        }

    }

    public function deleteFormAbsen($id_form)
    {
        $absen = Absensi::where('id_form',$id_form)->delete();
        $insert = FormAbsensi::destroy($id_form);

        if($absen || $insert)
        {
            return back()->with([
                'notif_status' => 'success',
                'message' => 'Form Absensi berhasil dihapus!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Form Absensi gagal dihapus!',
                'notif_show' => true,
            ]);
        }
    }
}
