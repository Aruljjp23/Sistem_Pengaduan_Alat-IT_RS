<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TindakanCtrl extends Controller
{
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

            'pr.kode_perangkat',
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

    public function tindakan_pengadu(Request $request)
    {
        $namaPengadu     = session('nama_pengadu');
        $idPengaduanBaru = session('id_pengaduan_baru', []);

        if (!$namaPengadu || empty($idPengaduanBaru)) {
            abort(403, 'Sesi tidak valid.');
        }

        $statusFilter = $request->query('status', 'semua');

        $tindakanTerbaru = DB::table('tindakan')
            ->select('id_pengaduan', 'kondisi', 'teknisi', 'status', 'created_at')
            ->whereIn('id', function ($sub) {
                $sub->select(DB::raw('MAX(id)'))
                    ->from('tindakan')
                    ->groupBy('id_pengaduan');
            });

        $pengaduan = DB::table('pengaduan as p')->join('ruangan as r', 'r.id', '=', 'p.id_ruangan')->leftJoin('perangkat as pr', 'pr.id', '=', 'p.id_perangkat')->leftJoinSub($tindakanTerbaru, 'tl', 'tl.id_pengaduan', '=', 'p.id')->whereIn('p.id', $idPengaduanBaru)
        ->groupBy(
            'p.id_ruangan',
            'r.nama_ruangan',
            'r.lokasi',
            'p.deskripsi_masalah',
            'tl.kondisi',
            'tl.teknisi',
            'tl.created_at',
            'tl.status',
        )
        ->select(
            'p.id_ruangan',
            'r.nama_ruangan',
            'r.lokasi',
            'p.deskripsi_masalah',
            'tl.kondisi',
            'tl.teknisi',
            'tl.created_at',
            DB::raw("COALESCE(tl.status, 'Pending') as status"),
            DB::raw("GROUP_CONCAT(p.id ORDER BY p.id SEPARATOR ',') as id_pengaduan_list"),
            DB::raw("GROUP_CONCAT(COALESCE(pr.kode_perangkat, '-') ORDER BY p.id SEPARATOR ', ') as perangkat_list"),
        );

        if ($statusFilter !== 'semua') {
            $statusMap = [
                'pending' => 'Pending',
                'proses'  => 'Dalam Proses',
                'selesai' => 'Selesai',
            ];
            $pengaduan->having(
                DB::raw("COALESCE(tl.status, 'Pending')"),
                $statusMap[$statusFilter] ?? $statusFilter
            );
        }

        $tindakan = $pengaduan->orderByDesc('p.id_ruangan')->get();

        return view('tindakan.tindakan_pengaduan', compact('tindakan', 'statusFilter', 'namaPengadu'));
    }

    public function riwayat_tindakan(Request $request)
    {
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalAkhir = $request->query('tanggal_akhir');

        $query = DB::table('tindakan as t')->join('pengaduan as p', 'p.id', '=', 't.id_pengaduan')->join('ruangan as r', 'r.id', '=', 't.id_ruangan')->leftJoin('users as u', 'u.id', '=', 't.teknisi') 
        ->select(
            't.id',
            't.kondisi',
            't.status',
            't.created_at',
            'p.nama_pengadu',
            'p.deskripsi_masalah',
            'r.nama_ruangan',
            't.teknisi as nama_teknisi' 
        )
        ->orderByDesc('t.created_at');

        if ($tanggalMulai) {
            $query->whereDate('t.created_at', '>=', $tanggalMulai);
        }
        if ($tanggalAkhir) {
            $query->whereDate('t.created_at', '<=', $tanggalAkhir);
        }

        $group = $query->get()->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
        });

        return view('tindakan.riwayat_tindakan', compact('group', 'tanggalMulai', 'tanggalAkhir'));
    }
}