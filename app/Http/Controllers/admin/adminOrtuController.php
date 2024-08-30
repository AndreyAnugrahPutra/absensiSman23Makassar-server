<?php

namespace App\Http\Controllers\admin;

use App\Models\Level;
use App\Models\Orangtua;
use App\Models\Siswa;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class adminOrtuController extends adminController
{
    //
    public function ortuPage()
    {
        $dataOrtu = Orangtua::all();
        $dataLevel = Level::where('nama_level', 'orangtua')->get();

        return Inertia::render('Admin/OrangTua/Index', [
            'dataOrtu' => $dataOrtu,
            'defaultLevel' => $dataLevel
        ]);
    }
    public function viewDataOrtu($user_id)
    {
        $dataOrtu = Orangtua::where('user_id',$user_id)->get();

        return Inertia::render('Admin/OrangTua/View', [
            'dataOrtu' => $dataOrtu
        ]);
    }

    public function createDataOrtu(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'status' => 'required',
            'tgl_lahir' => 'required',
            'jkel' => 'required',
            'email' => 'required|unique:tb_orangtua_siswa',
            'password' => 'required',
            'no_telp' => 'required|unique:tb_orangtua_siswa',
            'pekerjaan' => 'required',
            'level' => 'required',
        ]);

        $file = NULL;
        $fileName = NULL;
        $linkFile = NULL;

        if($request->hasFile('foto_profil'))
        {
            $file = $request->file('foto_profil');
            $filename = Str::snake($request->nama);
            $request->validate(['foto_profil' => 'mimes:jpg,jpeg,png']);
            $extension = $file->getClientOriginalExtension();
            $fileName = $filename.'-'.Carbon::now('Asia/Makassar')->format('Y-m-d').'.'.$extension;
            $path = 'upload/orangtua/foto_profil/'.$fileName;

            $insertFile = Storage::disk('public')->put($path, file_get_contents($file));

            if($insertFile)
            {
                $linkFile = Storage::url($path);
            }
        }

        $insert = Orangtua::create([
            'user_id' => Uuid::uuid(),
            'nama' => $request->nama,
            'status' => $request->status,
            'tgl_lahir' => Carbon::parse($request->tgl_lahir)->timezone('Asia/Makassar')->format('Y-m-d'),
            'jkel' => $request->jkel,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'foto_profil' => $fileName,
            'foto_path' => $linkFile,
            'level' => $request->level,
            'created_at' => Carbon::now('Asia/Makassar'),
            'updated_at' => null
        ]);

        if($insert)
        {
            return back()->with([
                'notif_status' => 'success',
                'message' => 'Data orang tua siswa berhasil ditambahkan!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Data orang tua siswa gagal ditambahkan!',
                'notif_show' => true,
            ]);
        }
    }
    public function updateDataOrtu(Request $request)
    {
        $tb_ortu = Orangtua::where('user_id', $request->user_id);

        $file = null;
        $fileName = $request->foto_profil;
        $linkFile = $request->foto_path;

        if($request->hasFile('foto_profil'))
        {
            $oldFile = $request->og_foto_path;
            $file = $request->file('foto_profil');
            $filename = Str::snake($request->nama);
            $request->validate(['foto_profil' => 'mimes:jpg,jpeg,png']);
            $extension = $file->getClientOriginalExtension();
            $fileName = $filename.'-'.Carbon::now('Asia/Makassar')->format('Y-m-d').'.'.$extension;
            $path = 'upload/orangtua/foto_profil/'.$fileName;
            
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
            'nama' => $request->nama,
            'status' => $request->status,
            'tgl_lahir' => Carbon::parse($request->tgl_lahir)->timezone('Asia/Makassar')->format('Y-m-d'),
            'jkel' => $request->jkel,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'foto_profil' => $fileName,
            'foto_path' => $linkFile,
            'level' => $request->level,
            'updated_at' => Carbon::now('Asia/Makassar'),
        ];

        if($tb_ortu->get()[0]->email !== $request->email)
        {
            $request->validate([
                'email' => 'unique:tb_orangtua_siswa',
            ]);
        }
        else if($tb_ortu->get()[0]->no_telp !== $request->no_telp)
        {
            $request->validate([
                'no_telp' => 'unique:tb_orangtua_siswa',
            ]);
        }
        else
        {
            $insert = $tb_ortu->update($updateData);
        }

        if($insert)
        {
            return redirect()->route('admin.orangtua.index')->with([
                'notif_status' => 'success',
                'message' => 'Data orang tua siswa berhasil diupdate!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Data orang tua siswa gagal diupdate!',
                'notif_show' => true,
            ]);
        }
    }
    public function deleteDataOrtu($user_id)
    {
        $ortu = Siswa::where(function ($query) use ($user_id) {
            $query->where('orangtua_1', $user_id)
                ->orWhere('orangtua_2', $user_id);
        })
        ->update(['orangtua_1' => DB::raw("CASE WHEN orangtua_1 = '$user_id' THEN NULL ELSE orangtua_1 END"),
                  'orangtua_2' => DB::raw("CASE WHEN orangtua_2 = '$user_id' THEN NULL ELSE orangtua_2 END")]);
        
        $insert = Orangtua::destroy($user_id);

        if($ortu || $insert)
        {
            return redirect()->route('admin.orangtua.index')->with([
                'notif_status' => 'success',
                'message' => 'Data orang tua siswa berhasil dihapus!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return redirect()->back()->with([
                'notif_status' => 'failed',
                'message' => 'Data orang tua siswa gagal dihapus!',
                'notif_show' => true,
            ]);
        }
    }
    
}
