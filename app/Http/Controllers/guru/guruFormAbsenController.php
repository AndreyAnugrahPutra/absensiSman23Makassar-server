<?php

namespace App\Http\Controllers\guru;

use App\Models\Absensi;
use App\Models\FormAbsensi;
use App\Models\Jadwal;
use App\Models\Kelas;
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
        $id_kelas = FormAbsensi::where('id_form', $id_form)->first();
        $nama_kelas = Kelas::where('id_kelas',$id_kelas->id_kelas)->select('nama_kelas','nama_wali_kelas')->first();
        $dataAbsensi = Siswa::where('id_kelas', $id_kelas->id_kelas)->orderBy('nama','ASC')->get();
        $mapel = Mapel::where('id_mapel',$id_kelas->id_mapel)->first();

        $newData = [];
        $alpha = 0;
        foreach ($dataAbsensi as $d) {
            $data['absensi'] = Absensi::where('id_form', $id_form)->where('id_siswa', $d->user_id)->get();
            if($data['absensi'] != null && count($data['absensi']) > 0)
            {
                $data['status'] = $data['absensi'][0]->status;
                $data['deskripsi'] = $data['absensi'][0]->deskripsi ? $data['absensi'][0]->deskripsi : 'Siswa '.$data['status'];
                $data['lampiran_path'] = $data['absensi'][0]->lampiran_path;
                $data['waktu_absen'] = $data['absensi'][0]->waktu_absen;
            }
            else
            {
                $data['status'] = 'Alpha';
                $data['deskripsi'] = 'siswa '.$data['status'];
                $data['lampiran_path'] = null;
                $data['waktu_absen'] = '-';
                $alpha++;
            }
            $data['nama_siswa'] = $d->nama;
            $data['tanggal_dibuat'] = $id_kelas->created_at;
            $newData[] = $data;
        }

        $jumlahStatus = Absensi::where('id_form', $id_form)->groupBy('status')->selectRaw('status, count(*) as jumlah')->get();


        return Inertia::render('Guru/Mapel/Absensi', [
            'dataMapel' => [
                'namaMapel' => $mapel->nama_mapel,
                'namaGuru' => $mapel->nama_guru,
            ],
            'dataKelas' => [
                'namaKelas' => $nama_kelas->nama_kelas,
                'waliKelas' => $nama_kelas->nama_wali_kelas,
            ],
            'idForm' => $id_form,
            'dataAbsensi' => $newData,
            'jumlahStatus' => $jumlahStatus,
            'jumlahAlpha' => (string) $alpha,
        ]);
    }

    public function rekapAbsensi($id_jadwal)
    {
        $jadwal = Jadwal::where('id_jadwal', $id_jadwal)->select('id_kelas','id_mapel','mapel')->first();
        $mapel = Mapel::where('id_mapel',$jadwal->id_mapel)->first();
        $nama_kelas = Kelas::where('id_kelas',$jadwal->id_kelas)->select('nama_kelas','nama_wali_kelas')->first();
        $siswa = Siswa::where('id_kelas', $jadwal->id_kelas)->orderBy('nama','ASC')->get();
        $newData = [];

        $jmlHadir = 0;
        $jmlSakit = 0;
        $jmlIzin = 0;
        $jmlAlpha = 0;
        $index = 0;

        foreach($siswa as $s)
        {
            $data['absensi'] = Absensi::where('id_jadwal', $id_jadwal)->where('id_siswa',$s->user_id)->get();
            if(count($data['absensi']) > 0)
            {
                $data['hadir'] = count(Absensi::where('id_jadwal',$id_jadwal)->where('id_siswa',$s->user_id)->where('status','Hadir')->get());
                $jmlHadir = count(Absensi::where('id_jadwal',$id_jadwal)->where('status','Hadir')->get());
                
                $data['sakit'] = count(Absensi::where('id_jadwal',$id_jadwal)->where('id_siswa',$s->user_id)->where('status','Sakit')->get());
                $jmlSakit = count(Absensi::where('id_jadwal',$id_jadwal)->where('status','Sakit')->get());

                $data['izin'] = count(Absensi::where('id_jadwal',$id_jadwal)->where('id_siswa',$s->user_id)->where('status','Izin')->get());
                $jmlIzin = count(Absensi::where('id_jadwal',$id_jadwal)->where('status','Izin')->get());
                
                $alphaSiswa = $data['hadir'] + $data['sakit'] + $data['izin'];

                $data['alpha'] = count(FormAbsensi::where('id_jadwal',$id_jadwal)->get()) - $alphaSiswa;

                if($data['alpha'] > 0 ) { $jmlAlpha++; }
                
            }
            else
            {
                $data['hadir'] = 0;
                $data['alpha'] = 0;
                $data['izin'] = 0;
                $data['sakit'] = 0;
                $jmlAlpha++;
            }

            $data['nisn'] = $s->nisn;
            $data['nama_siswa'] = $s->nama;
            $data['kelas'] = $nama_kelas->nama_kelas;
            $data['mapel'] = $mapel->nama_mapel;
            $data['waktu_mulai'] = $mapel->waktu_mulai;
            $data['waktu_selesai'] = $mapel->waktu_selesai;
            $index++;
            $newData[] = $data;
        }
        return Inertia::render('Guru/Absensi/Rekap', [
            'dataAbsensi' => $newData,
            'kelas' => [
                'namaKelas' => $nama_kelas->nama_kelas,
                'waliKelas' => $nama_kelas->nama_wali_kelas,
            ],
            'mapel' => 
            [
                'namaMapel' => $mapel->nama_mapel,
                'namaGuru' => $mapel->nama_guru,
            ],
            'idJadwal' => $id_jadwal,
            'statistikAbsen' => [
                'Hadir' => (string) $jmlHadir, 
                'Sakit' => (string) $jmlSakit, 
                'Izin' => (string) $jmlIzin,
                'Alpha' => (string) $jmlAlpha,
            ],
        ]);
    }

    public function createAbsen(Request $request)
    {
        $now = Carbon::now('Asia/Makassar')->format('H:i:s');
        $nowDate = Carbon::now('Asia/Makassar')->format('Y-m-d');
        $mapel = Mapel::find($request->id_mapel);
        $formAbsen = FormAbsensi::where('id_mapel',$request->id_mapel)->first();

        if($nowDate == Carbon::parse($formAbsen->created_at)->format('Y-m-d'))
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Form absensi sudah dibuat!',
                'notif_show' => true,
            ]);
        }
        else if($now >= $mapel->waktu_mulai && $now <= $mapel->waktu_selesai) 
        {
            $insert = FormAbsensi::create([
                'id_form' => Uuid::uuid(),
                'id_jadwal' => $request->id_jadwal,
                'id_kelas' => $request->id_kelas,
                'kelas' => $request->kelas,
                'id_mapel' => $request->id_mapel,
                'mapel' => $request->mapel,
                'id_lokasi' => $request->id_lokasi,
                'created_at' => Carbon::now('Asia/Makassar'),
            ]);

            if ($insert) {
                return back()->with([
                    'notif_status' => 'success',
                    'message' => 'Form Absensi berhasil dibuat!',
                    'notif_show' => true,
                ]);
            } else {
                return back()->with([
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
        $absen = Absensi::where('id_form', $id_form)->delete();
        $insert = FormAbsensi::destroy($id_form);

        if ($absen || $insert) {
            return back()->with([
                'notif_status' => 'success',
                'message' => 'Form Absensi berhasil dihapus!',
                'notif_show' => true,
            ]);
        } else {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Form Absensi gagal dihapus!',
                'notif_show' => true,
            ]);
        }
    }
}
