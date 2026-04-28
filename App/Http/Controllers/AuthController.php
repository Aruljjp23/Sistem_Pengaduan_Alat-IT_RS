<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function form_login(){
        return view('auth/login');
    }

    public function login(Request $request){

        $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('name', 'password');
        $user = DB::table('users')->where('name', $request->name)->first();

        if (!$user) 
        {
            return redirect()->back()->with('error', 'Login gagal, pengguna tidak ditemukan');
        }

        if (Auth::attempt($credentials)) 
        {
            $role = Auth::user()->role;

            if (is_null($role) || $role === '') {
                Auth::logout();
                return redirect()->back()->with('error', 'Akun Anda belum memiliki role. Silakan hubungi Admin.');
            }

            if ($role == 'admin') 
            {
                return redirect('dashboard')->with('pesan', 'Login Sebagai Admin Success');

            } elseif ($role == 'teknisi') {
                return redirect('/home')->with('pesan', 'Login Sebagai Pegawai Success');

            } elseif ($role == 'pengadu') {
                $id_ruangan = Auth::user()->id_ruangan;
                return redirect('/pengaduan/form_pengaduan/' . $id_ruangan)->with('pesan', 'Login Sebagai Pengaduan Success');    
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Role tidak dikenali. Silakan hubungi Admin.');
            }

        } else {
            return redirect()->back()->with('error', 'Login gagal, password salah');
        }
    }

    public function form_register(){
        return view('auth/register');
    }

    public function register(Request $request){
        
        $request->validate([
            'name' => 'required',
            'password' => 'required|min:6'
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'password' => bcrypt($request->input('password')),
            'role' => null,      
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat, menunggu persetujuan admin');
    }
    
    public function logout(){
        
        Auth::logout();

        return redirect()->route('login')->with('error','Anda sedang logout');
    }
}