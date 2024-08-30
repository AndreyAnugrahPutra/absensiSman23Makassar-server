<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class adminGuruController extends adminController
{
    //
    public function guruPage()
    {
        $dataLevel = Level::where('nama_level','admin')->orWhere('nama_level','guru')->select('id_level','nama_level')->get();
        $dataGuru = User::where('level', 2)->select('user_id','nip','nama','jkel','tgl_lahir','email','no_telp','alamat','foto_path')->orderBy('nama')->get();
        return Inertia::render('Admin/Guru/Index', ['defaultLevel' => $dataLevel, 'dataGuru' => $dataGuru]);
    }

    public function viewDataGuru($user_id)
    {
        
        $dataGuru = User::where('user_id', $user_id)->select('user_id','nip','nama','tgl_lahir','jkel','email','no_telp','alamat','foto_profil', 'foto_path','level')->get();
        return Inertia::render('Admin/Guru/View', ['dataGuru' => $dataGuru]);
    }

    public function createDataGuru(Request $request)
    {
        $request->validate([
            'nip' => 'nullable|unique:users',
            'nama' => 'required',
            'tgl_lahir' => 'required',
            'jkel' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'no_telp' => 'required|unique:users',
            'level' => 'required',
        ]);

        $file = NULL;
        $fileName = NULL;
        $linkFile = NULL;

        if($request->hasFile('foto_profil'))
        {
            $file = $request->file('foto_profil');
            $filename = str()->random();
            $request->validate(['foto_profil' => 'mimes:jpg,jpeg,png']);
            $extension = $file->getClientOriginalExtension();
            $fileName = $filename.'-'.Carbon::now('Asia/Makassar')->format('Y-m-d').'.'.$extension;
            $path = 'upload/guru/foto_profil/'.$fileName;

            $insertFile = Storage::disk('public')->put($path, file_get_contents($file));

            if($insertFile)
            {
                $linkFile = Storage::url($path);
            }
        }

        $insert = User::create([
            'user_id' => Uuid::uuid(),
            'nip' => $request->nip,
            'nama' => $request->nama,
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
            'updated_at' => null
        ]);

        if($insert)
        {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'message' => 'Data Guru berhasil ditambahkan!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return redirect()->back()->with([
                'notif_status' => 'failed',
                'message' => 'Data Guru gagal ditambahkan!',
                'notif_show' => true,
            ]);
        }
    }

    public function updateDataGuru(Request $request)
    {  
        $file = null;
        $fileName = $request->foto_profil;
        $linkFile = $request->foto_path;

        if($request->hasFile('foto_profil'))
        {
            $oldFile = $request->og_foto_profil;
            $file = $request->file('foto_profil');
            $filename = str()->random();
            $request->validate(['foto_profil' => 'mimes:jpg,jpeg,png']);
            $extension = $file->getClientOriginalExtension();
            $fileName = $filename.'-'.Carbon::now('Asia/Makassar')->format('Y-m-d').'.'.$extension;
            $path = 'upload/guru/foto_profil/'.$fileName;
            $deletePath = 'upload/guru/foto_profil/'.$oldFile;

            // dd(Storage::disk('public')->delete($oldFile));
            // dd($oldFile);

            
            if($oldFile !== null)
            {
                Storage::disk('public')->delete($deletePath);
            }

            $insertFile = Storage::disk('public')->put($path, file_get_contents($file));

            if($insertFile)
            {
                $linkFile = Storage::url($path);
            }
        }

        $updateData = [
            'nip' => $request->nip,
            'nama' => $request->nama,
            'tgl_lahir' => Carbon::parse($request->tgl_lahir)->timezone('Asia/Makassar')->format('Y-m-d'),
            'jkel' => $request->jkel,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'foto_profil' => $fileName,
            'foto_path' => $linkFile,
            'level' => $request->level,
            'updated_at' => Carbon::now('Asia/Makassar'),
        ];

        $insert = User::where('user_id', $request->user_id)->update($updateData);

        if($insert)
        {
            return redirect()->route('admin.guru.index')->with([
                'notif_status' => 'success',
                'message' => 'Data Guru berhasil diupdate!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return redirect()->back()->with([
                'notif_status' => 'failed',
                'message' => 'Data Guru gagal diupdate!',
                'notif_show' => true,
            ]);
        }
        return redirect()->back()->with([
            'notif_status' => 'failed',
            'message' => 'Data Guru gagal diupdate!',
            'notif_show' => true,
        ]);
    }

    public function deleteDataGuru($user_id)
    {
        $insert = User::destroy($user_id);

        if($insert)
        {
            return redirect()->route('admin.guru.index')->with([
                'notif_status' => 'success',
                'message' => 'Data guru berhasil dihapus!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return redirect()->route('admin.guru.index')->with([
                'notif_status' => 'failed',
                'message' => 'Data guru gagal dihapus!',
                'notif_show' => true,
            ]);
        }
    }
}
