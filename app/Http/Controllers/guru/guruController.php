<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class guruController extends Controller
{
    //
    public function dashboard()
    {
        $user_id = auth()->user()->user_id;
        $jmlhMapel = Mapel::where('id_guru',$user_id)->count();
        $jmlhKelas = Kelas::where('id_wali_kelas', $user_id)->count();
        $tahunAjar = TahunAjar::where('is_active', 1)->first();
        return Inertia::render('Guru/Dashboard', [
            'jmlhMapel' => $jmlhMapel,
            'jmlhKelas' => $jmlhKelas,
            'tahunAjar' => $tahunAjar,
        ]);
    }

    public function profilePage()
    {
        $dataProfil = User::where('user_id',auth()->user()->user_id)->select('user_id','nip','nama','tgl_lahir','jkel','email','no_telp','alamat','foto_profil', 'foto_path')->get();
        return Inertia::render('Guru/Profile', [
            'dataProfil' => $dataProfil
        ]);
    }

    public function updateProfile(Request $request)
    {
        // dd($request->password);
        $file = null;
        $fileName = $request->foto_profil;
        $linkFile = $request->foto_path;

        if($request->hasFile('foto_profil'))
        {
            $file = $request->file('foto_profil');
            $oldFile = $request->og_foto_profil;
            $filename = $request->nip;
            $request->validate(['foto_profil' => 'mimes:jpg,png,jpeg,webp|max:2048']);
            $extension = $file->getClientOriginalExtension();
            $fileName = $filename.'-'.Carbon::now('Asia/Makassar')->format('Y-m-d').'.'.$extension;
            $path = 'upload/guru/foto_profil/'.$fileName;
            $deletePath = 'upload/guru/foto_profil/'.$oldFile;

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
        if($request->password === null)
        {
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
                'updated_at' => Carbon::now('Asia/Makassar'),
            ];
        }
        else 
        {
            $updateData = [
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
                'updated_at' => Carbon::now('Asia/Makassar'),
            ];
        }

        $insert = User::where('user_id', $request->user_id)->update($updateData);

        if($insert)
        {
            return redirect()->back()->with([
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
    }
}
