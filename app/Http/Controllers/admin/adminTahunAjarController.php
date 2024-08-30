<?php

namespace App\Http\Controllers\admin;

use App\Models\Jadwal;
use App\Models\TahunAjar;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class adminTahunAjarController extends adminController
{
    //
    public function indexPage()
    {
        $dataTahunAjar = TahunAjar::orderBy('mulai')->get();
        return Inertia::render('Admin/TahunAjar/Index', [
            'dataTahunAjar' => $dataTahunAjar
        ]);
    }
    
    public function createDataTahunAjar(Request $request)
    {
        $request->validate([
            'semester' => 'required',
            'mulai' => 'required|numeric|max_digits:4',
            'selesai' => 'required|numeric|max_digits:4',
        ]);

        $insert = TahunAjar::create([
            'id_tahun_ajar' => Uuid::uuid(),
            'semester' => $request->semester,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
            'is_active' => 0,
            'created_at' => Carbon::now('Asia/Makassar'),
            'updated_at' => null
        ]);

        if($insert)
        {
            return back()->with([
                'notif_status' => 'success',
                'message' => 'Data tahun ajar berhasil ditambahkan!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Data tahun ajar gagal ditambahkan!',
                'notif_show' => true,
            ]);
        }
    }

    public function viewDataTahunAjar($id_tahun_ajar)
    {
        $dataTahunAjar = TahunAjar::where('id_tahun_ajar', $id_tahun_ajar)->select('id_tahun_ajar','semester','mulai','selesai')->get();
        return Inertia::render('Admin/TahunAjar/View', [
            'dataTahunAjar' => $dataTahunAjar
        ]);
    }

    public function tahunAjarActive($id_tahun_ajar)
    {
        $active = TahunAjar::where('id_tahun_ajar',$id_tahun_ajar)->update(['is_active' => 1]);
        $unactive = TahunAjar::where('id_tahun_ajar', '!=',$id_tahun_ajar)->update(['is_active' => 0]);

        if($active && $unactive)
        {
            return back()->with([
                'notif_status' => 'success',
                'message' => 'Tahun ajar berhasil diaktifkan!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Tahun ajar gagal diaktifkan!',
                'notif_show' => true,
            ]);
        }
    }

    public function updateDataTahunAjar(Request $request,$id_tahun_ajar)
    {
        $updateData = [
            'semester' => $request->semester,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
            'is_active' => $request->is_active,
            'updated_at' => Carbon::now('Asia/Makassar')
        ];

        $insert = TahunAjar::where('id_tahun_ajar', $id_tahun_ajar)->update($updateData);

        if($insert)
        {
            return redirect()->route('admin.tahun_ajar.index')->with([
                'notif_status' => 'success',
                'message' => 'Data tahun ajar berhasil diupdate!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Data tahun ajar gagal diupdate!',
                'notif_show' => true,
            ]);
        }
    }

    public function deleteDataTahunAjar($id_tahun_ajar)
    {
        
        $jadwal = Jadwal::where('id_tahun_ajar',$id_tahun_ajar)->delete();
        $tahunAjar = TahunAjar::destroy($id_tahun_ajar);

        if($jadwal || $tahunAjar)
        {
            return redirect()->route('admin.tahun_ajar.index')->with([
                'notif_status' => 'success',
                'message' => 'Data tahun ajar berhasil dihapus!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Data tahun ajar gagal dihapus!',
                'notif_show' => true,
            ]);
        }
    }
}
