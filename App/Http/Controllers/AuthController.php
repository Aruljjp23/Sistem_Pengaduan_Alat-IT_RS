<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuthController extends Controller
{
    public function form_login(){
        return view('auth/login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', $request->name)->first();

        if (!$user) {
            return back()->with('error', 'Username tidak ditemukan');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password salah');
        }

        Auth::login($user);
        $request->session()->regenerate(); 

        switch ($user->role) {

            case 'admin':
                return redirect('dashboard')
                    ->with('pesan', 'Login sebagai Admin berhasil');

            case 'pengadu':
                if (!$user->id_ruangan) {
                    Auth::logout();
                    return back()->with('error', 'Akun pengadu belum memiliki ruangan');
                }

                return redirect('/pengaduan/form_pengaduan/' . $user->id_ruangan)
                    ->with('pesan', 'Login sebagai Pengadu berhasil');

            default:
                Auth::logout();
                abort(403, 'Role tidak valid');
        }
    }

    public function form_register(){
        $ruangan = DB::table('ruangan')->get(); 
    
        return view('auth/register', compact('ruangan'));
    }

    public function register(Request $request)
    {
        $role = User::count() == 0 ? 'admin' : 'pengadu';

        $request->validate([
            'name' => [
                'required',
                'max:20',
                'unique:users,name',
                'regex:/^[A-Z][a-zA-Z0-9]*$/'
            ],
            'password' => 'required|max:10',
            'id_ruangan' => $role === 'pengadu'
                ? 'required|exists:ruangan,id_ruangan'
                : 'nullable'
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

        $data = [
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'role' => $role,
            'created_at' => now(),
            'updated_at' => now()
        ];

        if ($role === 'pengadu') {
            $data['id_ruangan'] = $request->id_ruangan;
        } else {
            $data['id_ruangan'] = null;
        }

        DB::table('users')->insert($data);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat, menunggu persetujuan admin');
    }
    
    public function logout(){
        
        Auth::logout();

        return redirect()->route('login')->with('error','Anda sedang logout');
    }
}