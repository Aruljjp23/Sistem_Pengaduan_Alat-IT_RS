<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserCtrl extends Controller
{
    public function data_user(Request $request)
    {
        $search = $request->input('search');

        $data_user = DB::table('users')
            ->leftJoin('ruangan', 'users.id_ruangan', '=', 'ruangan.id')
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
            'name'     => 'required|string|max:255',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,teknisi,pengadu',
        ]);

        User::create([
            'name'     => $request->name,
            'password' => bcrypt($request->input('password')),
            'role'     => $request->role,
            'id_user'  => $request->id_user,
            'id_ruangan' => $request->id_ruangan ?: null,
        ]);

        return redirect('user/data_user')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            // 'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'role'     => 'required|in:admin,teknisi,pengadu',
        ]);

        $data = [
            'name'       => $request->name,
            // 'email'      => $request->email,
            'role'       => $request->role,
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
