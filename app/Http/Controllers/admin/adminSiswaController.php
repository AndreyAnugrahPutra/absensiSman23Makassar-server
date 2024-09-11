<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Level;
use App\Models\Orangtua;
use App\Models\Siswa;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class adminSiswaController extends adminController
{
    //
      
    public function siswaPage()
    {
        $dataLevel = Level::where('nama_level', 'siswa')->select('id_level','nama_level')->get();
        $dataKelas = Kelas::select('id_kelas','nama_kelas')->get();
        $dataSiswa = Siswa::join('tb_kelas', 'tb_siswa.id_kelas','=','tb_kelas.id_kelas')->select('tb_siswa.*','tb_kelas.nama_kelas')->orderBy('tb_siswa.nama','ASC')->get();
        $dataOrangtua = Orangtua::select('user_id','nama','status')->get();
        return Inertia::render('Admin/Siswa/Index', ['defaultLevel' => $dataLevel,
         'dataKelas' => $dataKelas,
         'dataSiswa' => $dataSiswa,
         'dataOrangtua' => $dataOrangtua
        ]);
    }

    public function viewDataSiswa($user_id)
    {
        $dataSiswa = Siswa::select('user_id','induk','nisn','nama','tgl_lahir','jkel','email','no_telp','foto_profil','foto_path')->where('user_id', $user_id)->get();

        $dataOrangtua = Orangtua::select('user_id','nama')->get();

        return Inertia::render('Admin/Siswa/View', [
            'dataSiswa' => $dataSiswa,
            'dataOrangtua' => $dataOrangtua
        ]);
    }

    public function createDataSiswa(Request $request)
    {
        $file = NULL;
        $fileName = NULL;
        $linkFile = NULL;

        $request->validate([
            'induk' => 'required|unique:tb_siswa',
            'nisn' => 'required|unique:tb_siswa',
            'nama' => 'required',
            'tgl_lahir' => 'required',
            'jkel' => 'required',
            'email' => 'required|unique:tb_siswa',
            'password' => 'required',
            'no_telp' => 'required|unique:tb_siswa',
            'level' => 'required',
        ]);

        if($request->hasFile('foto_profil'))
        {
            $file = $request->file('foto_profil');
            $filename = Str::random(8);
            $request->validate(['foto_profil' => 'mimes:jpg,jpeg,png']);
            $extension = $file->getClientOriginalExtension();
            $fileName = $filename.'-'.Carbon::now('Asia/Makassar')->format('Y-m-d').'.'.$extension;
            $path = 'upload/siswa/foto_profil/'.$fileName;
            $linkFile = Storage::url($path);
            $insertFile = Storage::disk('public')->put($path, file_get_contents($file));
        }

        $insert = Siswa::create([
            'user_id' => Uuid::uuid(),
            'induk' => $request->induk,
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'id_kelas' => $request->id_kelas,
            'tgl_lahir' => Carbon::parse($request->tgl_lahir)->timezone('Asia/Makassar')->format('Y-m-d'),
            'jkel' => $request->jkel,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'foto_profil' => $fileName,
            'foto_path' => $linkFile,
            'level' => $request->level,
            'created_at' => Carbon::now('Asia/Makassar'),
            'updated_at' => NULL
        ]);

        if($insert)
        {
            return back()->with([
                'notif_status' => 'success',
                'message' => 'Data siswa berhasil ditambahkan!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Data siswa gagal ditambahkan!',
                'notif_show' => true,
            ]);
        }
    }   

    public function updateDataSiswa(Request $request)
    {
        $file = null;
        $fileName = $request->foto_profil;
        $linkFile = $request->foto_path;

        if($request->hasFile('foto_profil'))
        {
            $oldFile = $request->og_foto_path;
            $file = $request->file('foto_profil');
            $filename = Str::random(8);
            $request->validate(['foto_profil' => 'mimes:jpg,jpeg,png']);
            $extension = $file->getClientOriginalExtension();
            $fileName = $filename.'-'.Carbon::now('Asia/Makassar')->format('Y-m-d').'.'.$extension;
            $path = 'upload/siswa/foto_profil/'.$fileName;

            if($oldFile !== null)
            {
                Storage::disk('public')->delete($oldFile);
            }

            $insertFile = Storage::disk('public')->put($path, file_get_contents($file));


            if($insertFile)
            {
                $linkFile = Storage::url($path);
            }
        }

        $updateData = [
            'induk' => $request->induk,
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'tgl_lahir' => Carbon::parse($request->tgl_lahir)->timezone('Asia/Makassar')->format('Y-m-d'),
            'jkel' => $request->jkel,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'orangtua_1' => $request->ortu1,
            'orangtua_2' => $request->ortu2,
            'foto_profil' => $fileName,
            'foto_path' => $linkFile,
            'updated_at' => Carbon::now('Asia/Makassar'),
        ];

        $insert = Siswa::where('user_id', $request->user_id)->update($updateData);

        if($insert)
        {
            return redirect()->route('admin.siswa.index')->with([
                'notif_status' => 'success',
                'message' => 'Data siswa berhasil diupdate!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Data siswa gagal diupdate!',
                'notif_show' => true,
            ]);
        }
        return back()->with([
            'notif_status' => 'failed',
            'message' => 'Data siswa gagal diupdate!',
            'notif_show' => true,
        ]);
    }

    public function deleteDataSiswa($user_id)
    {
        $insert = Siswa::destroy($user_id);

        if($insert)
        {
            return redirect()->route('admin.siswa.index')->with([
                'notif_status' => 'success',
                'message' => 'Data siswa berhasil dihapus!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return redirect()->route('admin.siswa.index')->with([
                'notif_status' => 'failed',
                'message' => 'Data siswa gagal dihapus!',
                'notif_show' => true,
            ]);
        }
    }
}
