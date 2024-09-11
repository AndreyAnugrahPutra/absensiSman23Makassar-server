<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\apiResource;
use App\Models\Kelas;
use App\Models\Level;
use App\Models\Orangtua;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function index()
    {
        //
        $levelUser = Level::where('nama_level','siswa')->orWhere('nama_level','orangtua')->select('id_level','nama_level')->get();

        return new apiResource(true, 'Selamat Datang! ', $levelUser);
    }

    public function loginPage()
    {
        //
        return response()->json('oke');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
            'level' => 'required',
        ]);

        $level = Level::find($request->level);

        $customGuard = $level->nama_level;

        if($token = auth($customGuard)->attempt($credentials))
        {
            if($customGuard === 'siswa')
            {
                $data = Siswa::where('email', $credentials['email'])->where('last_login',NULL)->select('nama','email','id_kelas as kelas','last_logout')->first();
                
                if($data !== null)
                {
                    $last_logout = $data->last_logout ? Carbon::parse($data->last_logout)->addHours(24)->format('Y-m-d H:i:s') : Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s');

                    $now = Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s');

                    if($now > $last_logout || $last_logout == Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s'))
                    {
                        $update = Siswa::where('email', $credentials['email']);
                        $updateData = [
                            'last_login' => Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s'), 
                            'last_logout' => null,
                        ];
                        if($update->update($updateData))
                        {
                            $kelas = Kelas::where('id_kelas', $data->kelas)->first();
                            $data->kelas = $kelas->nama_kelas;
                            $data->token = $token;
                            $data->level = $customGuard;
                            return new apiResource(true, 'berhasil login', $data);
                        }
                    }
                    else { return new apiResource(true, 'Gagal Login! Silahkan Login pada waktu '.$last_logout, null); }
                }
                else
                {
                    return new apiResource(true, 'Gagal Login!', null);
                }
            }
            else if($customGuard === 'orangtua')
            {
                $data = Orangtua::where('email', $credentials['email'])->where('last_login',NULL)->select('nama','email')->first();
                
                if($data !== null)
                {
                   
                    $update = Orangtua::where('email', $credentials['email']);
                    $updateData = [
                        'last_login' => Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s'), 
                        'last_logout' => null,
                    ];
                    if($update->update($updateData))
                    {
                        $data->token = $token;
                        $data->level = $customGuard;
                        return new apiResource(true, 'berhasil login', $data);
                    }
                    else 
                    { return new apiResource(false, 'Gagal Login!', null); }
                }
                else
                {
                    return new apiResource(false, 'Gagal Login!', null);
                }
                
            }
        }
        else return new apiResource(false, 'Pengguna tidak terdaftar!', null);
    }

    public function logout(Request $request)
    {    
        if($request->level === 'siswa')
        {
            $data = Siswa::where('email', $request->email)->where('last_logout',NULL)->first();
            $updateData = [
                'last_login' => NULL, 
                'last_logout' => Carbon::now('Asia/Makassar')
            ];
            $data->update($updateData);
        }
        else if($request->level === 'orangtua')
        {
            $data = Orangtua::where('email', $request->email)->first();
            $updateData = [
            'last_login' => NULL,
            'last_logout' => Carbon::now('Asia/Makassar')];
            $data->update($updateData);
        }

        $removeToken = JWTAuth::invalidate(JWTAuth::getToken($request->token));

        if($removeToken) {
            //return response JSON
            return response()->json([
                'success' => true,
                'message' => 'Logout Berhasil!',  
            ]);
        }

        return response()->json('berhasil logout');
    }
}
