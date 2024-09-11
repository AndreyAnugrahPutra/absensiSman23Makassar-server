<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\apiResource;
use App\Models\Kelas;
use Illuminate\Http\Request;

class jadwalSiswaController extends Controller
{
    public function index()
    {
        //
        $dataKelas = Kelas::all();
        return new apiResource(true,'berhasil ambil data',$dataKelas);
    }

    public function show(Request $request)
    {
        $dataKelas = Kelas::where('nama_kelas', $request->nama_kelas)->join('tb_jadwal','tb_kelas.id_kelas','=','tb_jadwal.id_kelas')->join('tb_mapel','tb_jadwal.id_mapel','=','tb_mapel.id_mapel')->select('tb_jadwal.hari','tb_mapel.nama_mapel', 'tb_jadwal.id_jadwal','tb_mapel.nama_guru','tb_mapel.waktu_mulai','tb_mapel.waktu_selesai','tb_kelas.*')->orderBy('tb_jadwal.hari','DESC')->orderBy('tb_mapel.mulai','ASC')->get();
        
        return new apiResource(true, 'Data Jadwal Kelas', $dataKelas);
    }

    public function filterHari(String $Hari, Request $request)
    {
        $dataKelas = Kelas::where('nama_kelas', $request->nama_kelas)->where('hari', $Hari)->join('tb_jadwal','tb_kelas.id_kelas','=','tb_jadwal.id_kelas')->join('tb_mapel','tb_jadwal.id_mapel','=','tb_mapel.id_mapel')->select('tb_jadwal.hari','tb_mapel.nama_mapel', 'tb_jadwal.id_jadwal','tb_mapel.nama_guru','tb_mapel.waktu_mulai','tb_mapel.waktu_selesai','tb_kelas.*')->orderBy('tb_jadwal.hari','DESC')->orderBy('tb_mapel.mulai','ASC')->get();
        
        return new apiResource(true, 'Data Jadwal Kelas', $dataKelas);
    }
}
