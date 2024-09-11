<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\apiResource;
use App\Models\Absensi;
use App\Models\FormAbsensi;
use App\Models\Jadwal;
use App\Models\Mapel;
use App\Models\Siswa;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class absenSiswaController extends Controller
{
    
    public function store($id_form, Request $request)
    {
        //
        $file = null;
        $fileName = null;
        $linkFile = null;
        $deskripsi = null;

        $absensiData = $request->validate([
            'email' => 'required',
            'status' => 'required',
            'waktu_absen' => 'required'
        ]);

        $siswa = Siswa::where('email', $absensiData['email'])->first();
        $jadwal = Jadwal::where('id_form',$id_form)->first(); 

        if($request->hasFile('lampiran'))
        {
            $file = $request->file('lampiran');
            $filename = $id_form;
            $extension = $file->getClientOriginalExtension();
            $fileName = $filename.'-'.$siswa->user_id.'-'.Carbon::now('Asia/Makassar')->format('Y-m-d').'.'.$extension;
            $path = 'upload/absensi/lampiran/ '.$fileName;

            $insertFile = Storage::disk('public')->put($path, file_get_contents($file));

            if($insertFile)
            {
                $linkFile = Storage::url($path);
            }

        }

        $request->deskripsi === null || 'null' ? $deskripsi = 'siswa '.$absensiData['status'] : $deskripsi = $request->deskripsi;

        $insert = Absensi::create([
            'id_absensi' => Uuid::uuid(),
            'id_form' => $id_form,
            'id_jadwal' => $jadwal->id_jadwal,
            'id_siswa' => $siswa->user_id,
            'nama_siswa' => $siswa->nama,
            'lampiran_file' => $fileName,
            'lampiran_path' => $linkFile,
            'deskripsi' => $deskripsi,
            'status' => $absensiData['status'],
            'waktu_absen' => $absensiData['waktu_absen'],
            'created_at' => Carbon::now('Asia/Makassar'),
        ]);

        if(!$insert)
        {
            return new apiResource(true, 'Absensi Gagal', null);
        }
    }

    public function historyAbsensi(string $email, $id_jadwal)
    {
        //
        $jadwal = Jadwal::where('id_jadwal',$id_jadwal)->first();
        $siswa = Siswa::where('email',$email)->first();
        $absensi = Absensi::where('id_jadwal',$id_jadwal)->where('id_siswa',$siswa->user_id)->orderBy('tb_absensi.created_at','DESC')->get();
        $mapel = Mapel::where('id_mapel',$jadwal->id_mapel)->first();
        $dataAbsensi = [];

        $jmlHadir = count(Absensi::where('id_jadwal',$id_jadwal)->where('id_siswa',$siswa->user_id)->where('status','Hadir')->get());
        $jmlSakit = count(Absensi::where('id_jadwal',$id_jadwal)->where('id_siswa',$siswa->user_id)->where('status','Sakit')->get());
        $jmlIzin = count(Absensi::where('id_jadwal',$id_jadwal)->where('id_siswa',$siswa->user_id)->where('status','Izin')->get());
        $alphaSiswa = $jmlHadir + $jmlIzin + $jmlSakit;
        $jmlAlpha = count(FormAbsensi::where('id_jadwal',$id_jadwal)->get()) - $alphaSiswa;

        foreach($absensi as $s)
        {
            $data['mapel'] = $mapel->nama_mapel;
            $data['guru'] = $mapel->nama_guru;
            $data['kelas'] = $jadwal->kelas;

            if(count($absensi) > 0)
            {
                $data['created_at'] = Carbon::parse($s->created_at)->format('Y-m-d H:i:s');
                $data['waktu_absen'] = $s->waktu_absen;
                $data['lampiran'] = $s->lampiran_path;
                $data['status'] = $s->status;
                $data['deskripsi'] = $s->deskripsi;
            }
            $dataAbsensi[] = $data;
        }
        $statistikAbsensi = [
            ['Status' => 'Hadir', 'Jumlah' => $jmlHadir],
            ['Status' => 'Sakit', 'Jumlah' => $jmlSakit],
            ['Status' => 'Izin', 'Jumlah' => $jmlIzin],
            ['Status' => 'Alpha', 'Jumlah' => $jmlAlpha],
        ];  

        if(count($dataAbsensi) > 0 )
        {
            return new apiResource(true,'Data Absensi Siswa', [$dataAbsensi, $statistikAbsensi]);
        }

        return new apiResource(true,'Data Absensi Siswa', null);
    }

    public function formList ($id_jadwal, Request $req)
    {   
        $cariAbsen = FormAbsensi::where('id_jadwal', $id_jadwal)->orderBy('created_at','DESC')->first();
        
        $siswa = Siswa::where('email',$req->email)->first();
        $cariAbsenSiswa = Absensi::where('id_form',$cariAbsen->id_form)->where('id_siswa',$siswa->user_id)->first();

        if($cariAbsen !== null && $cariAbsenSiswa == null)
        {

            $tgl_sekarang = Carbon::now('Asia/Makassar')->format('Y-m-d');
            $jam_sekarang = Carbon::now('Asia/Makassar')->format('H:i:s');
    
            $tgl_absen_db = Carbon::parse($cariAbsen->created_at)->format('Y-m-d');
            $jam_absen_db = Carbon::parse($cariAbsen->created_at)->format('H:i:s');

            if($cariAbsen->count() > 0 && $tgl_absen_db === $tgl_sekarang && $jam_sekarang >= $jam_absen_db)
            {
                $dataAbsen = FormAbsensi::where('tb_form_absen.id_form', $cariAbsen->id_form)->join('tb_mapel', 'tb_form_absen.id_mapel','=','tb_mapel.id_mapel')->join('tb_jadwal','tb_form_absen.id_jadwal','=','tb_jadwal.id_jadwal')->join('tb_lokasi_absen','tb_form_absen.id_lokasi','=','tb_lokasi_absen.id_lokasi')->select('tb_form_absen.*', 'tb_mapel.waktu_mulai','tb_mapel.waktu_selesai','tb_mapel.nama_guru', 'tb_form_absen.created_at as tanggal_dibuat','tb_lokasi_absen.*')->get();

                return new apiResource(true, 'Daftar Absensi', $dataAbsen);
            }
        }
        else if($cariAbsen !== null && $cariAbsenSiswa)
        {
            return new apiResource(true, 'Anda sudah melakukan absen', null);

        }
        else 
        {
            return new apiResource(true, 'Form absensi belum dibuat!', null);
        }
    }
}
