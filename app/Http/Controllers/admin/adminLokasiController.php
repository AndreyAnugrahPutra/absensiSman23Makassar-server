<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiAbsen;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class adminLokasiController extends adminController
{
    //
    public function lokasiPage()
    {    
        $dataLokasi = LokasiAbsen::select('id_lokasi','nama_lokasi','alamat','latitude','longitude','radius')->first();
        return Inertia::render('Admin/Lokasi/Index', ['dataLokasi' => $dataLokasi]);
    }

    public function createDataLokasi(Request $request)
    {
       
        $lokasiTersedia = LokasiAbsen::latest()->first();

        if($lokasiTersedia->count() > 0)
        {
            $updateData = [
                'nama_lokasi' => $request->nama_lokasi,
                'alamat' => $request->alamat,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'radius' => $request->radius,
                'updated_at' => Carbon::now('Asia/Makassar'),
            ];

            $updateLokasi = $lokasiTersedia->update($updateData);

            if($updateLokasi)
            {
                return back()->with([
                    'notif_status' => 'success',
                    'message' => 'Data Lokasi berhasil diupdate',
                    'notif_show' => true,
                ]);
            }
            else
            {
                return back()->with([
                    'notif_status' => 'failed',
                    'message' => 'Data Lokasi gagal diupdate!',
                    'notif_show' => true,
                ]);
            }
        } 
        else
        {

            $request->validate([
                'nama_lokasi' => 'required|string',
                'alamat' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'radius' => 'required|numeric',
            ]);
    
            $insert = LokasiAbsen::create([
                'id_lokasi' => Uuid::uuid(),
                'nama_lokasi' => $request->nama_lokasi,
                'alamat' => $request->alamat,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'radius' => $request->radius,
                'created_at' => Carbon::now('Asia/Makassar'),
            ]);
    
            if($insert)
            {
                return redirect()->back()->with([
                    'notif_status' => 'success',
                    'message' => 'Data Lokasi berhasil ditambahkan!',
                    'notif_show' => true,
                ]);
            }
            else
            {
                return redirect()->back()->with([
                    'notif_status' => 'failed',
                    'message' => 'Data Lokasi gagal ditambahkan!',
                    'notif_show' => true,
                ]);
            }
        }

    }
}
