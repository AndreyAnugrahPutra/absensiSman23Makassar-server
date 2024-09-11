<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\adminController;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjar;
use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class adminKelasController extends adminController
{
    //
    public function kelasPage()
    {
        $tahunAjarActive = TahunAjar::where('is_active',1)->first();
        $tahunAjarFix = $tahunAjarActive->mulai.'/'.$tahunAjarActive->selesai;

        $dataKelas = Kelas::where('tahun_ajaran',$tahunAjarFix)->withCount('siswa')->get();
        $dataGuru = User::where('level', 2)->whereNotIn('user_id', function($query) {
            $query->select('id_wali_kelas')->from('tb_kelas');
        })->select('user_id','nama')->get();
        $dataTahunAjar = TahunAjar::select('mulai','selesai')->get();
        
        return Inertia::render('Admin/Kelas/Index', [
            'dataGuru' => $dataGuru, 
            'dataKelas' => $dataKelas,
            'dataTahunAjar' => $dataTahunAjar
        ]);
    }

    public function viewDataKelas($id_kelas)
    {
        $dataKelas = Kelas::where('id_kelas', $id_kelas)->select('id_kelas','nama_kelas','id_wali_kelas', 'nama_wali_kelas','tahun_ajaran')->get();
        $dataGuru = User::where('level', 2)->select('user_id','nama')->get();
        $dataTahunAjar = TahunAjar::select('mulai','selesai')->get();
        // $dataGuru = User::where('level', 2)->select('user_id','nama')->get();
        return Inertia::render('Admin/Kelas/View', [
            'dataKelas' => $dataKelas,
            'dataGuru' => $dataGuru,
            'dataTahunAjar' => $dataTahunAjar
        ]); 
    }

    public function viewDataSiswaKelas($id_kelas)
    {
        $dataKelas = Kelas::where('tb_kelas.id_kelas', $id_kelas)->join('tb_siswa','tb_kelas.id_kelas','=','tb_siswa.id_kelas')->select('tb_kelas.*','tb_siswa.induk','tb_siswa.nisn','tb_siswa.nama','tb_siswa.jkel','tb_siswa.foto_path')->get();
       
        return Inertia::render('Admin/Kelas/DataSiswa', [
            'dataKelas' => $dataKelas,
        ]); 
    }

    public function createDataKelas(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:tb_kelas',
            'id_wali_kelas' => 'required|unique:tb_kelas',
            'tahun_ajar' => 'required'
        ]);

        $nama_wali_kelas = User::where('user_id', $request->id_wali_kelas)->select('nama')->get();

        $insert = Kelas::insert([
            'id_kelas' => Uuid::uuid(),
            'nama_kelas' => $request->nama_kelas,
            'id_wali_kelas' => $request->id_wali_kelas,
            'nama_wali_kelas' => $nama_wali_kelas[0]['nama'],
            'tahun_ajaran' => $request->tahun_ajar,
            'created_at' => Carbon::now('Asia/Makassar')
        ]);

        if($insert)
        {
            return back()->with([
                'notif_status' => 'success',
                'message' => 'Data kelas berhasil ditambahkan!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return back()->with([
                'notif_status' => 'failed',
                'message' => 'Data kelas gagal ditambahkan!',
                'notif_show' => true,
            ]);
        }
    }

    public function updateDataKelas(Request $request, $id_kelas)
    {
        $nama_wali_kelas = User::where('user_id', $request->id_wali_kelas)->select('nama')->get();

        $updateData = [
            'nama_kelas' => $request->nama_kelas,
            'id_wali_kelas' => $request->id_wali_kelas,
            'nama_wali_kelas' => $nama_wali_kelas[0]['nama'],
            'updated_at' => Carbon::now('Asia/Makassar')
        ];

        $insert = Kelas::where('id_kelas', $id_kelas)->update($updateData);
        
        if($insert)
        {
            return redirect()->route('admin.kelas.index')->with([
                'notif_status' => 'success',
                'message' => 'Data Kelas berhasil diupdate!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return redirect()->route('admin.kelas.index')->with([
                'notif_status' => 'failed',
                'message' => 'Data Kelas gagal diupdate!',
                'notif_show' => true,
            ]);
        }
    }

    public function deleteDataKelas($id_kelas)
    {
        $insert = Kelas::destroy($id_kelas);

        if($insert)
        {
            return redirect()->route('admin.kelas.index')->with([
                'notif_status' => 'success',
                'message' => 'Data kelas berhasil dihapus!',
                'notif_show' => true,
            ]);
        }
        else
        {
            return redirect()->route('admin.kelas.index')->with([
                'notif_status' => 'failed',
                'message' => 'Data kelas gagal dihapus!',
                'notif_show' => true,
            ]);
        }
    }
}
