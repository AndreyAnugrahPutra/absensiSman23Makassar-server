<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjar;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class adminJadwalController extends Controller
{
    //
    public function indexPage()
    {
        $tahunAjarActive = TahunAjar::where('is_active',1)->first();
        $dataJadwal = Jadwal::where('tb_jadwal.id_tahun_ajar', $tahunAjarActive->id_tahun_ajar)->join('tb_mapel','tb_jadwal.id_mapel','=','tb_mapel.id_mapel')->join('tb_tahun_ajar','tb_jadwal.id_tahun_ajar','tb_tahun_ajar.id_tahun_ajar')->orderBy('hari', 'DESC')->orderBy('waktu_mulai','ASC')->get();
        $dataKelas = Kelas::select('id_kelas','nama_kelas')->get();
        $dataMapel = Mapel::select('id_mapel','nama_mapel','waktu_mulai','waktu_selesai','nama_guru')->get();
        $dataTahunAjar = TahunAjar::select('id_tahun_ajar', 'semester','mulai','selesai')->orderBy('mulai','ASC')->get();  
        return Inertia::render('Admin/Jadwal/Index', [
            'dataJadwal' => $dataJadwal,
            'dataKelas' => $dataKelas,
            'dataMapel' => $dataMapel,
            'dataTahunAjar' => $dataTahunAjar,
        ]);
    }

    public function createDataJadwal(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'id_kelas' => 'required',
            'id_mapel' => 'required',
            'id_tahun_ajar' => 'required',
        ]);

        $kelas = Kelas::select('nama_kelas')->where('id_kelas',$request->id_kelas)->get();
        $mapel = Mapel::select('nama_mapel')->where('id_mapel',$request->id_mapel)->get();

        $insert = Jadwal::create([
            'id_jadwal' => Uuid::uuid(),
            'hari' => $request->hari,
            'id_kelas' => $request->id_kelas,
            'kelas' => $kelas[0]['nama_kelas'],
            'id_mapel' => $request->id_mapel,
            'mapel' => $mapel[0]['nama_mapel'],
            'id_tahun_ajar' => $request->id_tahun_ajar,
            'created_at' => Carbon::now('Asia/Makassar'),
            'updated_at' => null
        ]);

        if($insert)
        {
            return back()->with([
                'notif_status' => 'success',
                'message' => 'Data jadwal berhasil dibuat!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Data jadwal gagal dibuat!',
                'notif_show' => true,
            ]);
        }

    }

    public function viewDataJadwal($id_jadwal)
    {
        $dataJadwal = Jadwal::where('id_jadwal', $id_jadwal)->join('tb_mapel','tb_jadwal.id_mapel','=','tb_mapel.id_mapel')->join('tb_tahun_ajar','tb_jadwal.id_tahun_ajar','tb_tahun_ajar.id_tahun_ajar')->orderBy('hari', 'DESC')->get();
        $dataKelas = Kelas::select('id_kelas','nama_kelas')->get();
        $dataMapel = Mapel::select('id_mapel','nama_mapel')->get();
        $dataTahunAjar = TahunAjar::select('id_tahun_ajar', 'semester','mulai','selesai')->orderBy('mulai','ASC')->get();  
        return Inertia::render('Admin/Jadwal/View', [
            'dataJadwal' => $dataJadwal,
            'dataKelas' => $dataKelas,
            'dataMapel' => $dataMapel,
            'dataTahunAjar' => $dataTahunAjar,
        ]);
    }

    public function updateDataJadwal(Request $request, $id_jadwal)
    {
        $kelas = Kelas::select('nama_kelas')->where('id_kelas',$request->id_kelas)->get();
        $mapel = Mapel::select('nama_mapel')->where('id_mapel',$request->id_mapel)->get();

        $updateData = [
            'hari' => $request->hari,
            'id_kelas' => $request->id_kelas,
            'kelas' => $kelas[0]['nama_kelas'],
            'id_mapel' => $request->id_mapel,
            'mapel' => $mapel[0]['nama_mapel'],
            'id_tahun_ajar' => $request->id_tahun_ajar,
            'updated_at' => Carbon::now('Asia/Makassar'),
        ];

        $insert = Jadwal::where('id_jadwal', $id_jadwal)->update($updateData);

        if($insert)
        {
            return redirect()->route('admin.jadwal.index')->with([
                'notif_status' => 'success',
                'message' => 'Data jadwal berhasil diubah!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return redirect()->route('admin.jadwal.index')->with([
                'notif_status' => 'failed',
                'message' => 'Data jadwal gagal diubah!',
                'notif_show' => true,
            ]);
        }
    }

    public function deleteDataJadwal($id_jadwal)
    {
        $insert = Jadwal::destroy($id_jadwal);

        if($insert)
        {
            return redirect()->route('admin.jadwal.index')->with([
                'notif_status' => 'success',
                'message' => 'Data jadwal berhasil dihapus!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return redirect()->route('admin.jadwal.index')->with([
                'notif_status' => 'failed',
                'message' => 'Data jadwal gagal dihapus!',
                'notif_show' => true,
            ]);
        }
    }
}
