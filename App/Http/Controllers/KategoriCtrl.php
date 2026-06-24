<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriCtrl extends Controller
{
    public function data_kategori(Request $request)
    {
        $search = trim($request->search);
        
        $data_kategori = DB::table('kategori_perangkat')
            ->when($search, function ($q) use ($search) {
                $search = strtolower($search);

                $q->whereRaw(
                    'LOWER(nama_kategori) LIKE ?',
                    ["%{$search}%"]
                );
            })
            ->orderBy('nama_kategori', 'asc')
            ->paginate(10)
            ->appends($request->query());

        return view('kategori_perangkat.data_kategori', compact(
            'data_kategori',
            'search'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_perangkat,nama_kategori',
        ]);

        DB::table('kategori_perangkat')->insert([
            'nama_kategori' => ucwords(strtolower(trim($request->nama_kategori))),
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => "required|string|max:100|unique:kategori_perangkat,nama_kategori,{$id},id_kategori",
        ]);

        DB::table('kategori_perangkat')
            ->where('id_kategori', $id)
            ->update([
                'nama_kategori' => ucwords(strtolower(trim($request->nama_kategori))),
            ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dipakai = DB::table('perangkat')
            ->join('kategori_perangkat', 'perangkat.id_kategori', '=', 'kategori_perangkat.id_kategori')
            ->where('perangkat.id_kategori', $id)
            ->exists();

        if ($dipakai) {
            return redirect()->back()
                ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh data perangkat.');
        }

        DB::table('kategori_perangkat')->where('id_kategori', $id)->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}