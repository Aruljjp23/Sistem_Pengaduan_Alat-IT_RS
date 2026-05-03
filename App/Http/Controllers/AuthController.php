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

    public function login(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'max:10',
                'regex:/^[A-Z][a-zA-Z0-9]*$/'
            ],
            'password' => 'required|max:10'
        ], [
            'name.required' => 'Username wajib diisi',
            'name.max' => 'Username maksimal 10 karakter',
            'name.regex' => 'Username harus diawali huruf besar dan tanpa simbol',
            'password.required' => 'Password wajib diisi',
            'password.max' => 'Password maksimal 10 karakter'
        ]);

        if (filter_var($request->name, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors(['name' => 'Username tidak boleh berupa email']);
        }

        $user = DB::table('users')->where('name', $request->name)->first();

        if (!$user) {
            return back()->with('error', 'Login gagal, username tidak ditemukan');
        }

        if (Auth::attempt($request->only('name', 'password'))) {

            $role = Auth::user()->role;

            if (!$role) {
                Auth::logout();
                return back()->with('error', 'Akun belum memiliki role, hubungi admin');
            }

            switch ($role) {
                case 'admin':
                    return redirect('dashboard')->with('pesan', 'Login sebagai Admin berhasil');

                case 'teknisi':
                    return redirect('homepage')->with('pesan', 'Login sebagai Teknisi berhasil');

                case 'pengadu':
                    $id_ruangan = Auth::user()->id_ruangan;
                    return redirect('/pengaduan/form_pengaduan/' . $id_ruangan)
                        ->with('pesan', 'Login sebagai Pengadu berhasil');

                default:
                    Auth::logout();
                    return back()->with('error', 'Role tidak dikenali');
            }
        }

        return back()->with('error', 'Login gagal, password salah');
    }

    public function form_register(){
        return view('auth/register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'max:20',
                'unique:users,name',
                'regex:/^[A-Z][a-zA-Z0-9]*$/'
            ],
            'password' => 'required|max:10'
        ], [
            'name.required' => 'Username wajib diisi',
            'name.max' => 'Username maksimal 20 karakter',
            'name.unique' => 'Username sudah digunakan',
            'name.regex' => 'Username harus diawali huruf besar dan hanya boleh huruf & angka',
            'password.required' => 'Password wajib diisi',
            'password.max' => 'Password maksimal 10 karakter'
        ]);

        if (filter_var($request->name, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors([
                'name' => 'Username tidak boleh menggunakan format email (contoh: user123)'
            ]);
        }

        DB::table('users')->insert([
            'name' => $request->name,
            'password' => bcrypt($request->password),
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