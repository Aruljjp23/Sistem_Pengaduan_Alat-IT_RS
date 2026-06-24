<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

class TindakanCtrl extends Controller
{
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }

    public function data_tindakan(Request $request)
    {
        $search = $request->search;

        $pengaduanAktif = DB::table('pengaduan as p')
        ->join('ruangan as r', 'r.id', '=', 'p.id_ruangan')
        ->leftJoin('perangkat as pr', 'pr.id', '=', 'p.id_perangkat')

        ->leftJoinSub(
            DB::table('tindakan')
                ->select('id_pengaduan', 'status', 'kondisi', 'teknisi')
                ->whereIn('id', function ($q) {
                    $q->select(DB::raw('MAX(id)'))
                      ->from('tindakan')
                      ->groupBy('id_pengaduan');
                }),
            't',
            't.id_pengaduan',
            '=',
            'p.id'
        )

        ->where(function ($q) {
            $q->where('t.status', '!=', 'Selesai')
              ->orWhereNull('t.status');
        })

        ->when($search, function ($q) use ($search) {
            $q->where('r.nama_ruangan', 'like', '%' . $search . '%');
        })

        ->select(
            'p.id',
            'p.nama_pengadu',
            'p.deskripsi_masalah',
            'p.created_at',

            'r.nama_ruangan',

            'pr.kode_inventaris',
            'pr.kategori_perangkat',
            'pr.merek',

            DB::raw("COALESCE(t.status, 'Pending') as status_terakhir"),
            't.kondisi as kondisi_terakhir',
            't.teknisi as teknisi_terakhir_id'
        )

        ->orderBy('p.created_at', 'desc')
        ->get();

        $teknisiIds = $pengaduanAktif->pluck('teknisi_terakhir_id')->filter()->unique()->values();
        $teknisiMap = DB::table('users')->whereIn('id', $teknisiIds)->pluck('name', 'id');

        $pengaduanAktif = $pengaduanAktif->map(function ($p) {
            $p->nama_teknisi_terakhir = $p->teknisi_terakhir_id ?? '-'; 
            return $p;
        });

        return view('tindakan.data_tindakan', compact('pengaduanAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pengaduan'   => 'required|array',
            'id_pengaduan.*' => 'integer',
            'kondisi'        => 'required|string|max:1000',
            'status'         => 'required|in:Pending,Dalam Proses,Selesai',
        ]);

        $pengaduan = DB::table('pengaduan')
            ->where('id', $request->id_pengaduan)
            ->select('id', 'id_ruangan')  
            ->first();

        if (!$pengaduan) {
            return back()->withErrors(['id_pengaduan' => 'Pengaduan tidak ditemukan.'])->withInput();
        }

        foreach ($request->id_pengaduan as $id) {

            $pengaduan = DB::table('pengaduan')
                ->where('id', $id)
                ->select('id', 'id_ruangan')
                ->first();

            if ($pengaduan) {

                DB::table('tindakan')->insert([
                    'id_ruangan'   => $pengaduan->id_ruangan,
                    'id_pengaduan' => $pengaduan->id,
                    'tanggal'      => $request->tanggal_waktu,
                    'kondisi'      => $request->kondisi,
                    'teknisi'      => Auth::user()->name,
                    'status'       => $request->status,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

            }
        }

        return redirect('/tindakan/data_tindakan')->with('success', 'Tindakan berhasil disimpan.');
    }

    public function tindakan_pengaduan(Request $request)
    {
        $statusFilter = $request->query('status', 'semua');
        
        $user = Auth::user(); 

        $tindakan = DB::table('tindakan')
            ->leftJoin('pengaduan', 'tindakan.id_pengaduan', '=', 'pengaduan.id')
            ->leftJoin('ruangan', 'tindakan.id_ruangan', '=', 'ruangan.id_ruangan')
            ->where('pengaduan.id_ruangan', $user->id_ruangan)
            ->where('pengaduan.nama_pengadu', $user->name)
            
            ->select(
                'tindakan.*', 
                'ruangan.nama_ruangan', 
                'ruangan.lokasi',
                'pengaduan.deskripsi_masalah',
                DB::raw("CASE 
                    WHEN tindakan.kode_inventaris IS NOT NULL AND tindakan.kode_inventaris != '-' 
                    THEN CONCAT(tindakan.kode_inventaris, ' - ', tindakan.kategori_perangkat, ' (', tindakan.merek_perangkat, ')')
                    ELSE '-' 
                END as perangkat_list")
            );

        if ($statusFilter !== 'semua') {
            $statusMap = [
                'menunggu'=> ['Menunggu', 'menunggu'],
                'diterima'=> ['Diterima', 'diterima'],
                'pending' => ['Pending', 'Dipending', 'pending'], 
                'proses'  => ['Diproses', 'diproses'], 
                'selesai' => ['Selesai', 'selesai']
            ];

            if (isset($statusMap[$statusFilter])) {
                $tindakan->whereIn('tindakan.status', $statusMap[$statusFilter]);
            }
        }

        $tindakans = $tindakan->orderByDesc('tindakan.updated_at')->get();

        return view('tindakan.tindakan_pengaduan', [
            'tindakans' => $tindakans,
            'statusFilter' => $statusFilter,
            'namaPengadu' => $user->name ?? 'Pengguna' 
        ]);
    }
}