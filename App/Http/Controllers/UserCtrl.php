<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserCtrl extends Controller
{
    public function data_user(Request $request)
    {
        $search = $request->input('search');

        $data_user = DB::table('users')
            ->leftJoin('ruangan', 'users.id_ruangan', '=', 'ruangan.id_ruangan')
            ->select('users.*', 'ruangan.nama_ruangan')
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('users.name', 'like', "%$search%")
                    ->orWhere('ruangan.nama_ruangan', 'like', "%$search%")
                    ->orWhere('users.role', 'like', "%$search%");
                });
            })
            ->paginate(10);

        $data_ruangan = DB::table('ruangan')->get();

        return view('user/data_user', compact('data_user', 'data_ruangan', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'max:10',
                'unique:users,name',
                'regex:/^[A-Z][a-zA-Z0-9]*$/'
            ],
            'password' => 'required|max:10',
            'role' => 'required|in:admin,teknisi,pengadu',
        ], [
            'name.required' => 'Username wajib diisi',
            'name.max' => 'Username maksimal 10 karakter',
            'name.unique' => 'Username sudah digunakan',
            'name.regex' => 'Username harus diawali huruf besar dan hanya huruf/angka',
            'password.required' => 'Password wajib diisi',
            'password.max' => 'Password maksimal 10 karakter',
        ]);

        if (filter_var($request->name, FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors(['name' => 'Username tidak boleh berupa email'])->withInput();
        }

        User::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'id_ruangan' => $request->id_ruangan ?: null,
        ]);

        return redirect('user/data_user')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:10',
                'unique:users,name,' . $id,
                'regex:/^[A-Z][a-zA-Z0-9]*$/'
            ],
            'password' => 'nullable|min:6|max:10',
            'role' => 'required|in:admin,teknisi,pengadu',
        ], [
            'name.required' => 'Username wajib diisi',
            'name.max' => 'Username maksimal 10 karakter',
            'name.unique' => 'Username sudah digunakan',
            'name.regex' => 'Username harus diawali huruf besar dan tanpa simbol',
            'password.min' => 'Password minimal 6 karakter',
            'password.max' => 'Password maksimal 10 karakter',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('edit_error', true); 
        }

        if (filter_var($request->name, FILTER_VALIDATE_EMAIL)) {
            return back()
                ->withErrors(['name' => 'Username tidak boleh berupa email'])
                ->withInput()
                ->with('edit_error', true);
        }

        $data = [
            'name' => $request->name,
            'role' => $request->role,
            'id_ruangan' => $request->id_ruangan ?: null,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect('/user/data_user')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect('user/data_user')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        User::destroy($id);

        return redirect('user/data_user')->with('success', 'User berhasil dihapus.');
    }
}
