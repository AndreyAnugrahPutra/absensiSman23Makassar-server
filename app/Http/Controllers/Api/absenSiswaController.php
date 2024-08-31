<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\apiResource;
use App\Models\Absensi;
use App\Models\FormAbsensi;
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
        $siswa = Siswa::where('email',$email)->first();
        $dataAbsensi = FormAbsensi::where('tb_form_absen.id_jadwal',$id_jadwal)->join('tb_absensi','tb_form_absen.id_form','=','tb_absensi.id_form')->join('tb_mapel','tb_form_absen.id_mapel','=','tb_mapel.id_mapel')->select('tb_absensi.id_form','tb_absensi.nama_siswa','tb_absensi.lampiran_file','tb_absensi.lampiran_path','tb_absensi.status','tb_absensi.waktu_absen','tb_absensi.created_at','tb_absensi.deskripsi','tb_mapel.nama_mapel','tb_mapel.nama_guru','tb_form_absen.kelas')->where('tb_absensi.id_siswa',$siswa->user_id)->get();

        if($dataAbsensi->count() > 0)
        {
            return new apiResource(true,'Data Absensi Siswa', $dataAbsensi);
        }

        return new apiResource(true,'Data Absensi Siswa', null);
    }

    public function formList ($id_jadwal, Request $req)
    {   
        $cariAbsen = FormAbsensi::where('id_jadwal', $id_jadwal)->orderBy('created_at','DESC')->first();
        
        $siswa = Siswa::where('email',$req->email)->first();
        $cariAbsenSiswa = Absensi::where('id_form',$cariAbsen->id_form)->where('id_siswa',$siswa->user_id)->first();

        // return new apiResource(true, 'Debugging', $cariAbsenSiswa == null);

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
