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

use function Laravel\Prompts\select;

class guruFormAbsenController extends guruController
{
    //
    public function index()
    {
        return Inertia::render();
    }

    public function daftarAbsen($id_form)
    {
        $id_kelas = FormAbsensi::where('id_form', $id_form)->select('id_kelas','created_at')->first();
        $nama_kelas = Kelas::where('id_kelas',$id_kelas->id_kelas)->select('nama_kelas')->get();
        $dataAbsensi = Siswa::where('id_kelas', $id_kelas->id_kelas)->orderBy('nama','ASC')->get();

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
            $data['kelas'] = $nama_kelas[0]['nama_kelas'];
            $newData[] = $data;
        }
        $jumlahStatus = Absensi::where('id_form', $id_form)->groupBy('status')->selectRaw('status, count(*) as jumlah')->get();
        // $dataAbsensi = Absensi::where('tb_absensi.id_form',$id_form)->join('tb_form_absen','tb_absensi.id_form','=','tb_form_absen.id_form')->select('tb_form_absen.kelas','tb_absensi.*')->orderBy('nama_siswa','ASC')->get();


        return Inertia::render('Guru/Mapel/Absensi', [
            'dataAbsensi' => $newData,
            'jumlahStatus' => $jumlahStatus,
            'jumlahAlpha' => (string) $alpha,
        ]);
    }

    public function rekapAbsensi($id_jadwal)
    {
        $jadwal = Jadwal::where('id_jadwal', $id_jadwal)->select('id_kelas','id_mapel','mapel')->first();
        $mapel = Mapel::where('id_mapel',$jadwal->id_mapel)->first();
        $nama_kelas = Kelas::where('id_kelas',$jadwal->id_kelas)->select('nama_kelas')->first();
        $siswa = Siswa::where('id_kelas', $jadwal->id_kelas)->orderBy('nama','ASC')->get();
        $newData = [];

        $index = 0;

        foreach($siswa as $s)
        {
            $data['absensi'] = Absensi::where('id_jadwal', $id_jadwal)->where('id_siswa',$s->user_id)->get();
            if(count($data['absensi']) > 0)
            {
                $data['hadir'] = count(Absensi::where('id_jadwal',$id_jadwal)->where('id_siswa',$s->user_id)->where('status','Hadir')->get());
                $data['sakit'] = count(Absensi::where('id_jadwal',$id_jadwal)->where('id_siswa',$s->user_id)->where('status','Sakit')->get());
                $data['izin'] = count(Absensi::where('id_jadwal',$id_jadwal)->where('id_siswa',$s->user_id)->where('status','Izin')->get());
                $data['alpha'] = count(Absensi::where('id_jadwal',$id_jadwal)->where('id_siswa',$s->user_id)->where('status',NULL)->get());
            }
            else
            {
                $data['hadir'] = 0;
                $data['alpha'] = 0;
                $data['izin'] = 0;
                $data['sakit'] = 0;
            }
            $data['nisn'] = $s->nisn;
            $data['nama_siswa'] = $s->nama;
            $data['kelas'] = $nama_kelas->nama_kelas;
            $data['mapel'] = $jadwal->mapel;
            $data['waktu_mulai'] = $mapel->waktu_mulai;
            $data['waktu_selesai'] = $mapel->waktu_selesai;
            $index++;
            $newData[] = $data;
        }
        return Inertia::render('Guru/Absensi/Rekap', [
            'dataAbsensi' => $newData,
            'idJadwal' => $id_jadwal,
        ]);
    }

    public function createAbsen(Request $request)
    {
        $now = Carbon::now('Asia/Makassar')->format('H:i:s');
        $mapel = Mapel::find($request->id_mapel);
        if ($now >= $mapel->waktu_mulai && $now <= $mapel->waktu_selesai) {
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
                return redirect()->route('guru.mapel.index')->with([
                    'notif_status' => 'failed',
                    'message' => 'Form Absensi gagal dibuat!',
                    'notif_show' => true,
                ]);
            }
        } else {
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
