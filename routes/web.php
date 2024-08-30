<?php

// use App\Http\Controller\admin\adminController as adminController;

use App\Http\Controllers\admin\adminAbsenController;
use App\Http\Controllers\admin\adminController;
use App\Http\Controllers\admin\adminGuruController;
use App\Http\Controllers\admin\adminJadwalController;
use App\Http\Controllers\admin\adminKelasController;
use App\Http\Controllers\admin\adminLokasiController;
use App\Http\Controllers\admin\adminMapelController;
use App\Http\Controllers\admin\adminOrtuController;
use App\Http\Controllers\admin\adminSiswaController;
use App\Http\Controllers\admin\adminTahunAjarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\guru\GuruAbsenController;
use App\Http\Controllers\guru\guruController;
use App\Http\Controllers\guru\guruFormAbsenController;
use App\Http\Controllers\guru\GuruJadwalController;
use App\Http\Controllers\guru\GuruKelasController;
use App\Http\Controllers\guru\guruMapelController;

Route::middleware('guest')->group(function ()
{
    Route::get('/', [Authentication::class, 'loginPage'])->name('login');
    Route::post('/login', [Authentication::class, 'login'])->name('user.login');
});

Route::middleware(['auth', 'isAdmin'])->group(function ()
{
    Route::get('/admin/dashboard', [adminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/profile', [adminController::class, 'profilePage'])->name('admin.profile');

    Route::post('/admin/profile/{user_id}/update', [adminController::class, 'updateProfile']);

    Route::get('/admin/absensi', [adminAbsenController::class, 'absensiPage'])->name('admin.absensi.index');
    Route::get('admin/absensi/daftar_absen/{id_jadwal}', [adminAbsenController::class, 'daftarAbsen'])->name('admin.absensi.daftar');
    Route::get('admin/absensi/daftar_absen/view/{id_form}', [adminAbsenController::class, 'viewAbsen'])->name('admin.absensi.view');

    Route::get('/admin/lokasi', [adminLokasiController::class, 'lokasiPage'])->name('admin.lokasi.index');

    Route::post('/admin/lokasi', [adminLokasiController::class, 'createDataLokasi'])->name('admin.lokasi.create');

    Route::get('/admin/guru', [adminGuruController::class, 'guruPage'])->name('admin.guru.index');
    Route::get('/admin/guru/{user_id}', [adminGuruController::class, 'viewDataGuru'])->name('admin.guru.view');
    Route::post('/admin/guru', [adminGuruController::class, 'createDataGuru'])->name('admin.guru.create');
    Route::post('/admin/guru/{user_id}/update', [adminGuruController::class, 'updateDataGuru'])->name('admin.guru.update');
    Route::post('/admin/guru/{user_id}/delete', [adminGuruController::class, 'deleteDataGuru'])->name('admin.guru.delete');

    Route::get('/admin/kelas', [adminKelasController::class, 'kelasPage'])->name('admin.kelas.index');
    Route::get('/admin/kelas/{id_kelas}', [adminKelasController::class, 'viewDataKelas'])->name('admin.kelas.view');
    Route::get('/admin/kelas/view/{id_kelas}', [adminKelasController::class, 'viewDataSiswaKelas'])->name('admin.kelas.view');
    Route::post('/admin/kelas', [adminKelasController::class, 'createDataKelas'])->name('admin.kelas.create');
    Route::post('/admin/kelas/{id_kelas}/update', [adminKelasController::class, 'updateDataKelas'])->name('admin.kelas.update');
    Route::post('/admin/kelas/{id_kelas}/delete', [adminKelasController::class, 'deleteDataKelas'])->name('admin.kelas.delete');

    Route::get('/admin/mapel', [adminMapelController::class, 'mapelPage'])->name('admin.mapel.index');
    Route::get('/admin/mapel/{id_mapel}', [adminMapelController::class, 'viewDataMapel'])->name('admin.mapel.view');
    Route::post('/admin/mapel', [adminMapelController::class, 'createDataMapel'])->name('admin.mapel.create');
    Route::post('/admin/mapel/{id_mapel}/update', [adminMapelController::class, 'updateDataMapel'])->name('admin.mapel.update');
    Route::post('/admin/mapel/{id_mapel}/delete', [adminMapelController::class, 'deleteDataMapel'])->name('admin.mapel.delete');

    Route::get('/admin/siswa', [adminSiswaController::class, 'siswaPage'])->name('admin.siswa.index');
    Route::get('/admin/siswa/{user_id}', [adminSiswaController::class, 'viewDataSiswa'])->name('admin.siswa.view');
    Route::post('/admin/siswa', [adminSiswaController::class, 'createDataSiswa'])->name('admin.siswa.create');
    Route::post('/admin/siswa/{user_id}/update', [adminSiswaController::class, 'updateDatasiswa'])->name('admin.siswa.update');

    Route::get('/admin/orangtua', [adminOrtuController::class, 'ortuPage'])->name('admin.orangtua.index');
    Route::get('/admin/orangtua/{user_id}', [adminOrtuController::class, 'viewDataOrtu'])->name('admin.orangtua.view');
    Route::post('/admin/orangtua/', [adminOrtuController::class, 'createDataOrtu'])->name('admin.orangtua.create');
    Route::post('/admin/orangtua/{user_id}/update', [adminOrtuController::class, 'updateDataOrtu']);
    Route::post('/admin/orangtua/{user_id}/delete', [adminOrtuController::class, 'deleteDataOrtu']);

    Route::get('admin/tahun_ajar', [adminTahunAjarController::class, 'indexPage'])->name('admin.tahun_ajar.index');
    Route::post('admin/tahun_ajar', [adminTahunAjarController::class, 'createDataTahunAjar'])->name('admin.tahun_ajar.create');
    Route::get('admin/tahun_ajar/{id_tahun_ajar}', [adminTahunAjarController::class, 'viewDataTahunAjar'])->name('admin.tahun_ajar.view');
    Route::post('admin/tahun_ajar/{id_tahun_ajar}/update', [adminTahunAjarController::class, 'updateDataTahunAjar'])->name('admin.tahun_ajar.update');
    Route::post('admin/tahun_ajar/{id_tahun_ajar}/delete', [adminTahunAjarController::class, 'deleteDataTahunAjar'])->name('admin.tahun_ajar.delete');
    Route::post('admin/tahun_ajar/{id_tahun_ajar}/active', [adminTahunAjarController::class, 'tahunAjarActive']);

    Route::get('admin/jadwal', [adminJadwalController::class, 'indexPage'])->name('admin.jadwal.index');
    Route::post('admin/jadwal', [adminJadwalController::class, 'createDataJadwal'])->name('admin.jadwal.create');
    Route::get('admin/jadwal/{id_jadwal}', [adminJadwalController::class, 'viewDataJadwal'])->name('admin.jadwal.view');
    Route::post('admin/jadwal/{id_jadwal}/update', [adminJadwalController::class, 'updateDataJadwal'])->name('admin.jadwal.update');
    Route::post('admin/jadwal/{id_jadwal}/delete', [adminJadwalController::class, 'deleteDataJadwal'])->name('admin.jadwal.delete');

}); 

Route::middleware('auth', 'isGuru')->group(function()
{
    Route::get('/guru/dashboard', [guruController::class, 'dashboard'])->name('guru.dashboard');

    Route::get('/guru/profile', [guruController::class, 'profilePage'])->name('guru.profile');

    Route::post('/guru/profile/{user_id}/update', [guruController::class, 'updateProfile']);

    Route::get('/guru/mapel', [guruMapelController::class, 'index'])->name('guru.mapel.index');
    Route::post('/guru/mapel', [guruFormAbsenController::class, 'createAbsen'])->name('guru.absen.create');
    Route::get('/guru/mapel/{id_jadwal}', [guruMapelController::class, 'detailMapel'])->name('guru.mapel.detail');

    Route::get('/guru/mapel/absensi/{id_form}', [guruFormAbsenController::class, 'daftarAbsen'])->name('guru.absen.form');
    Route::post('/guru/mapel/absensi/{id_form}/delete', [guruFormAbsenController::class, 'deleteFormAbsen']);

    Route::get('/guru/absensi/daftar_absen/{id_form}/view', [GuruAbsenController::class, 'viewAbsen'])->name('guru.absensi.view');
    Route::get('/guru/jadwal/', [GuruJadwalController::class, 'index'])->name('guru.jadwal.index');

    Route::get('/guru/kelas', [GuruKelasController::class, 'kelasPage'])->name('guru.kelas.index');

    Route::get('/guru/kelas/{id_kelas}', [guruController::class, 'viewDataKelas'])->name('guru.kelas.view');
});

Route::get('/logout', [Authentication::class, 'logout'])->name('user.logout')->middleware('auth');