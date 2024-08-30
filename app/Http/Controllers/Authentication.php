<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class Authentication extends Controller
{
    //
    public function loginPage()
    {  
        if(auth()->check())
        {
            redirect()->back();
        }
        else
        {
            $levelUser = Level::where('nama_level','guru')->orWhere('nama_level','admin')->select('id_level','nama_level')->get();
            return Inertia::render('Auth/Login', [
                'levelUser' => $levelUser,
            ]);
        }
    }

    public function login(Request $req)
    {
        $credentials = $req->validate([
            'email' => ['required'],
            'password' => ['required'],
            'level' => ['required'],
        ]);
        if(Auth::attempt($credentials))
        {
            $user = User::where('email', $req->email)->select('user_id','nip','nama','email','level')->first();

            $userLevel = Level::where('id_level', $req->level)->first();

            $req->session()->regenerate();
            $user->last_login = Carbon::now('Asia/Makassar');
            $user->save();

            $notification = [
                'notif_status' => 'success',
                'message' => 'Selamat Datang, '.auth()->user()->nama,
                'notif_show' => true,
            ];
            
            switch($userLevel->nama_level)
            {
                case 'admin' : return redirect()->route('admin.dashboard')->with($notification);
                    break;
                case 'guru' : return redirect()->route('guru.dashboard')->with($notification);
                    break;
                default : return response()->json('anda tidak terdaftar!');

            };
        }
        else
        {
            $notification = [
                'notif_status' => 'failed',
                'message' => 'Terjadi kesalahan',
                'notif_show' => true,
            ];

            return redirect()->back()->with($notification);
        }
    }

    protected function redirectLogin($level, $notification)
    {
        if($level === 'admin')
        {
            return redirect()->intended(route('admin.dashboard'))->with($notification);
        }
        else if ($level === 'guru') 
        {
            return redirect()->intended(route('guru.dashboard'))->with($notification);
        }
        else return back()->with('anda tidak terdaftar!');
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/');
    }
}

