<?php

use App\Http\Controllers\Api\absenSiswaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\jadwalSiswaController;
use App\Http\Controllers\api\OrangtuaController;
use App\Http\Resources\apiResource;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:siswa');

Route::get('/testApi', function ()
{
    return response()->json('PANGGIL API BERHASIL!');
});

Route::post('/refreshUserToken', [AuthController::class, 'refreshJWT']);

Route::middleware('auth:siswa')->group(function ()
{
    Route::get('/tahun_ajar', function()
    {
        $tahunAjar = TahunAjar::where('is_active', 1)->first();
        return new apiResource(true, 'Tahun Ajaran', $tahunAjar);        
    });
    Route::get('/jadwal', [jadwalSiswaController::class, 'index']);

    Route::post('/jadwal/kelas/', [jadwalSiswaController::class, 'show']);
    Route::post('/jadwal/kelas/{Hari}', [jadwalSiswaController::class, 'filterHari']);

    Route::get('/absen/daftar_absen/{id_jadwal}', [absenSiswaController::class, 'formList']);
    Route::get('/absen/data/{email}/{id_jadwal}', [absenSiswaController::class, 'historyAbsensi']);

    Route::post('/absen/submit/{id_form}', [absenSiswaController::class, 'store']);
});

Route::middleware('auth:orangtua')->group(function ()
{
    Route::get('/orangtua/data_anak/{email}', [OrangtuaController::class, 'index']);
    Route::get('/orangtua/jadwal_anak/{id_kelas}', [OrangtuaController::class, 'jadwalAnak']);
    Route::get('/orangtua/absen_anak/{id_jadwal}/{id_anak}', [OrangtuaController::class, 'absenAnak']);
});

Route::middleware('guest')->group(function()
{
    Route::get('/fetchLevel', [AuthController::class, 'index']);

    Route::post('/clientLogin', [AuthController::class, 'login'])->name('client.login');
});

Route::post('/logout', [AuthController::class, 'logout']);