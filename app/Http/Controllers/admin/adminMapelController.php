<?php

namespace App\Http\Controllers\admin;

use App\Models\FormAbsensi;
use App\Models\Mapel;
use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class adminMapelController extends adminController
{
    //
    public function mapelPage()
    {

        // dd(Carbon::now('Asia/Makassar'));
        // dd(Carbon::parse('16-08-2024 08:10')->format('H:i:s'));

        $dataMapel = Mapel::select('id_mapel','nama_mapel','nama_guru','waktu_mulai','waktu_selesai')->get();
        // Carbon::parse($dataMapel['waktu_mulai'])->timezone('Asia/Makassar')->format('H:i');
        // Carbon::parse($dataMapel['waktu_selesai'])->timezone('Asia/Makassar')->format('H:i');

        $dataGuru = User::where('level', 2)->select('user_id', 'nip', 'nama')->get();

        return Inertia::render('Admin/Mapel/Index', ['dataGuru' => $dataGuru, 'dataMapel' => $dataMapel]);
    }

    public function viewDataMapel($id_mapel)
    {
        $dataMapel = Mapel::where('id_mapel', $id_mapel)->select('id_mapel','id_guru','nama_mapel','nama_guru','waktu_mulai','waktu_selesai')->get();

        $dataGuru = User::where('level', 2)->select('user_id', 'nama')->get();

        return Inertia::render('Admin/Mapel/View', ['dataGuru' => $dataGuru, 'dataMapel' => $dataMapel]);
    }

    public function createDataMapel(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required',
            'id_guru' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
        ]);

        $nama_guru = User::where('user_id', $request->id_guru)->select('nama')->get();

        $insert = Mapel::insert([
            'id_mapel' => Uuid::uuid(),
            'nama_mapel' => $request->nama_mapel,
            'id_guru' => $request->id_guru,
            'nama_guru' => $nama_guru[0]['nama'],
            'waktu_mulai' => Carbon::parse($request->waktu_mulai)->timezone('Asia/Makassar')->format('H:i:s'),
            'waktu_selesai' => Carbon::parse($request->waktu_selesai)->timezone('Asia/Makassar')->format('H:i:s'),
            'created_at' => Carbon::now('Asia/Makassar')
        ]);

        if($insert)
        {
            return back()->with([
                'notif_status' => 'success',
                'message' => 'Data mapel berhasil ditambahkan!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Data mapel gagal ditambahkan!',
                'notif_show' => true,
            ]);
        }
    }

    public function updateDataMapel(Request $request, $id_mapel)
    {

        // dd(Carbon::parse($request->waktu_mulai)->format('H:i:s'));
        // dd(Carbon::parse($request->waktu_mulai)->timezone('Asia/Makassar')->format('H:i:s'));
        $nama_guru = User::where('user_id', $request->id_guru)->select('nama')->get();
        $updateData = [
            'nama_mapel' => $request->nama_mapel,
            'id_guru' => $request->id_guru,
            'nama_guru' => $nama_guru[0]['nama'],
            'waktu_mulai' => Carbon::parse($request->waktu_mulai)->timezone('Asia/Makassar')->format('H:i:s'),
            'waktu_selesai' => Carbon::parse($request->waktu_selesai)->timezone('Asia/Makassar')->format('H:i:s'),
            'updated_at' => Carbon::now('Asia/Makassar')
        ];

        $insert = Mapel::find($id_mapel)->update($updateData);

        if($insert)
        {
            return redirect()->route('admin.mapel.index')->with([
                'notif_status' => 'success',
                'message' => 'Data mapel berhasil diupdate!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Data mapel gagal diupdate!',
                'notif_show' => true,
            ]);
        }
    }

    public function deleteDataMapel($id_mapel)
    {
        $formAbsen = FormAbsensi::where('id_mapel',$id_mapel)->delete();
        $insert = Mapel::destroy($id_mapel);

        if($insert || $formAbsen)
        {
            return redirect()->route('admin.mapel.index')->with([
                'notif_status' => 'success',
                'message' => 'Data mapel berhasil dihapus!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return redirect()->route('admin.mapel.index')->with([
                'notif_status' => 'failed',
                'message' => 'Data mapel gagal dihapus!',
                'notif_show' => true,
            ]);
        }
    }
}
