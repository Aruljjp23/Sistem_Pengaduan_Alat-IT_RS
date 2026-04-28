<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\Builder\Builder;

class PerangkatCtrl extends Controller
{
    public function data_perangkat(Request $request)
    {
        $search = $request->search;
        $id_ruangan = $request->id_ruangan;
        $kategori = $request->kategori; 

        $perangkat = DB::table('perangkat')
            ->join('ruangan', 'perangkat.id_ruangan', '=', 'ruangan.id')
            ->select('perangkat.*','ruangan.nama_ruangan as nama_ruangan')
            ->where('perangkat.id_ruangan', $id_ruangan);

        if ($kategori) {
            $perangkat->where('kategori_perangkat', $kategori);
        }

        if ($search) {
            $perangkat->where(function($q) use ($search) {
                $q->where('kode_perangkat', 'LIKE', '%' . $search . '%')
                ->orWhere('ip_jaringan', 'LIKE', '%' . $search . '%')
                ->orWhere('merek', 'LIKE', '%' . $search . '%')
                ->orWhere('kategori_perangkat', 'LIKE', '%' . $search . '%');
            });
        }

        $perangkat->orderBy('perangkat.id', 'asc');

        $data_perangkat = $perangkat->paginate(10);

        $ruangan = DB::table('ruangan')->where('id', $id_ruangan)->first();

        return view('perangkat/data_perangkat', compact(
            'data_perangkat',
            'id_ruangan',
            'ruangan',
            'kategori',
        ));
    }

    public function store(Request $request)
    {
        DB::table('perangkat')->insert([
            'id_ruangan'         => $request->id_ruangan,
            'kode_perangkat'     => $request->kode_perangkat,
            'ip_jaringan'        => $request->ip_jaringan,
            'merek'              => $request->merek,
            'kategori_perangkat' => $request->kategori_perangkat,
        ]);

        return redirect('perangkat/data_perangkat?id_ruangan=' . $request->id_ruangan)->with('success', 'Data perangkat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        DB::table('perangkat')->where('id', '=', $id)->update([
            'kode_perangkat'     => $request->kode_perangkat,
            'ip_jaringan'        => $request->ip_jaringan,
            'merek'              => $request->merek,
            'kategori_perangkat' => $request->kategori_perangkat,
        ]);

        return redirect('/perangkat/data_perangkat?id_ruangan=' . $request->id_ruangan)->with('success', 'Data perangkat berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        DB::table('perangkat')->where('id', '=', $id)->delete();

        return redirect('perangkat/data_perangkat?id_ruangan=' . $request->id_ruangan)->with('success', 'Data perangkat berhasil dihapus.');
    }

    public function qr_png($id)
    {
        $perangkat = DB::table('perangkat')->where('id',$id)->first();

        $hasil = Builder::create()
            ->data($perangkat->kode_perangkat)
            ->size(400)
            ->build();

        return response($hasil->getString())
            ->header('Content-Type', $hasil->getMimeType())
            ->header(
                'Content-Disposition',
                'attachment; filename="QR_'.$perangkat->kode_perangkat.'.png"'
            );
    }
}