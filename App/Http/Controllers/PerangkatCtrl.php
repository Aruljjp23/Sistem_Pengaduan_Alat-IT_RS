<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\Builder\Builder;

class PerangkatCtrl extends Controller
{
    public function data_perangkat(Request $request)
    {
        $search     = $request->search;
        $id_ruangan = $request->id_ruangan;
        $kategori   = $request->kategori;

        $kategori_perangkat = DB::table('kategori_perangkat')->get();

        $perangkat = DB::table('perangkat')
            ->join('ruangan', 'perangkat.id_ruangan', '=', 'ruangan.id_ruangan')
            ->join('kategori_perangkat', 'kategori_perangkat.id_kategori', '=', 'perangkat.id_kategori')
            ->select(
                'perangkat.*',
                'ruangan.nama_ruangan',
                'kategori_perangkat.nama_kategori as kategori_perangkat',
                'kategori_perangkat.id_kategori as id_kategori_perangkat'
            )
            ->where('perangkat.id_ruangan', $id_ruangan);

        if ($kategori) {
            $perangkat->whereRaw(
                'UPPER(kategori_perangkat.nama_kategori) = ?',
                [strtoupper($kategori)]
            );
        }

        if (!empty($search)) {
            $search = strtolower(trim($search));
            $perangkat->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(perangkat.kode_inventaris) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(perangkat.alamat_ip) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(perangkat.merek) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(kategori_perangkat.nama_kategori) LIKE ?', ["%{$search}%"]);
            });
        }

        $perangkat->orderBy('perangkat.id_perangkat', 'asc');

        $data_perangkat = $perangkat->paginate(10);
        $ruangan        = DB::table('ruangan')->where('id_ruangan', $id_ruangan)->first();

        return view('perangkat/data_perangkat', compact(
            'data_perangkat',
            'id_ruangan',
            'ruangan',
            'kategori',
            'kategori_perangkat',
        ));
    }

    public function store(Request $request)
    {
        DB::table('perangkat')->insert([
            'id_ruangan'      => $request->id_ruangan,
            'kode_inventaris' => $request->kode_inventaris,
            'alamat_ip'       => $request->alamat_ip,
            'merek'           => $request->merek,
            'id_kategori'     => $request->id_kategori,
        ]);

        return redirect('perangkat/data_perangkat?id_ruangan=' . $request->id_ruangan)
            ->with('success', 'Data perangkat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $lama = DB::table('perangkat')
            ->join('kategori_perangkat', 'kategori_perangkat.id_kategori', '=', 'perangkat.id_kategori')
            ->select(
                'perangkat.*',
                'kategori_perangkat.nama_kategori',
                'kategori_perangkat.id_kategori as id_kat'
            )
            ->where('perangkat.id_perangkat', $id)
            ->first();

        $kategori_baru = DB::table('kategori_perangkat')->where('id_kategori', $request->id_kategori)->first();

        $changes = [];

        if ((string) $lama->kode_inventaris !== (string) $request->kode_inventaris) {
            $changes['kode_inventaris'] = [
                'lama' => $lama->kode_inventaris,
                'baru' => $request->kode_inventaris,
            ];
        }

        if ((string) $lama->alamat_ip !== (string) $request->alamat_ip) {
            $changes['alamat_ip'] = [
                'lama' => $lama->alamat_ip,
                'baru' => $request->alamat_ip,
            ];
        }

        if ((string) $lama->merek !== (string) $request->merek) {
            $changes['merek'] = [
                'lama' => $lama->merek,
                'baru' => $request->merek,
            ];
        }

        if ((string) $lama->id_kat !== (string) $request->id_kategori) {
            $changes['kategori_perangkat'] = [
                'lama' => $lama->nama_kategori,
                'baru' => $kategori_baru->nama_kategori,
            ];
        }

        DB::table('perangkat')->where('id_perangkat', $id)->update([
            'kode_inventaris' => $request->kode_inventaris,
            'alamat_ip'       => $request->alamat_ip,
            'merek'           => $request->merek,
            'id_kategori'     => $request->id_kategori,
        ]);

        $updateResult = [
            'id'      => (int) $id,
            'changes' => $changes,
        ];

        return redirect('/perangkat/data_perangkat?id_ruangan=' . $request->id_ruangan)
            ->with('success', $updateResult);
    }

    public function destroy(Request $request, $id)
    {
        DB::table('perangkat')->where('id_perangkat', $id)->delete();

        return redirect('perangkat/data_perangkat?id_ruangan=' . $request->id_ruangan)
            ->with('success', 'Data perangkat berhasil dihapus.');
    }

    public function qr_png($id)
    {
        $perangkat = DB::table('perangkat')->where('id_perangkat', $id)->first();

        $hasil = Builder::create()
            ->data($perangkat->kode_inventaris)
            ->size(400)
            ->build();

        return response($hasil->getString())
            ->header('Content-Type', $hasil->getMimeType())
            ->header('Content-Disposition', 'attachment; filename="QR_' . $perangkat->kode_inventaris . '.png"');
    }
}