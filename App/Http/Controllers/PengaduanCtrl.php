<?php

namespace App\Http\Controllers;

use App\Services\WhatsappService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PengaduanCtrl extends Controller
{
    protected WhatsappService $wa;

    public function __construct(WhatsappService $wa)
    {
        $this->wa = $wa;
    }

    public function data_pengaduan(Request $request)
    {
        $tindakanTerbaru = DB::table('tindakan')
        ->select('id_pengaduan', 'kondisi', 'teknisi', 'status', 'updated_at')->whereIn('id', function ($sub) {
            $sub->select(DB::raw('MAX(id)'))->from('tindakan')->groupBy('id_pengaduan');
        });

        $pengaduan = DB::table('pengaduan as a')
        ->join('ruangan as b', 'a.id_ruangan', '=', 'b.id_ruangan')
        ->leftJoin('perangkat as c', 'a.id_perangkat', '=', 'c.id_perangkat')
        ->leftJoinSub($tindakanTerbaru, 'tl', 'tl.id_pengaduan', '=', 'a.id')
        ->leftJoin('users as u', 'u.id', '=', 'tl.teknisi')
        ->select(
            'a.*',
            'b.nama_ruangan as ruangan',
            'b.lokasi as nama_lokasi',
            'c.kode_inventaris',
            'c.kategori_perangkat',
            DB::raw("COALESCE(tl.status, 'Pending') as status_tindakan"),
            'tl.kondisi',
            'tl.updated_at as tindakan_updated_at',
            'u.name as nama_teknisi',
        );

        if ($request->search) {
            $pengaduan->where(function ($data_pengaduan) use ($request) {
                $data_pengaduan->where('a.nama_pengadu', 'like', "%{$request->search}%")
                    ->orWhere('b.nama_ruangan', 'like', "%{$request->search}%")
                    ->orWhere('b.lokasi', 'like', "%{$request->search}%");
            });
        }

        if ($request->created_at) {
            $pengaduan->whereDate('a.created_at', $request->created_at);
        }

        $data_pengaduan = $pengaduan->orderByDesc('a.created_at')->paginate(10);

        return view('pengaduan/data_pengaduan', compact('data_pengaduan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pengadu'      => 'required|string|max:255',
            'created_at'           => 'required|date',
            'deskripsi_masalah' => 'required|string',
        ]);

        $exists = DB::table('pengaduan')->where('id', $id)->exists();
        if (!$exists) {
            return back()->withErrors(['id' => 'Pengaduan tidak ditemukan.']);
        }

        DB::table('pengaduan')->where('id', $id)->update([
            'nama_pengadu'      => $request->nama_pengadu,
            'created_at'           => $request->created_at,
            'deskripsi_masalah' => $request->deskripsi_masalah,
            'updated_at'        => now(),
        ]);

        return redirect('/pengaduan/data_pengaduan')->with('success', 'Pengaduan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $exists = DB::table('pengaduan')->where('id', $id)->exists();
        if (!$exists) {
            return back()->withErrors(['id' => 'Pengaduan tidak ditemukan.']);
        }

        DB::table('tindakan')->where('id_pengaduan', $id)->delete();

        DB::table('pengaduan')->where('id', $id)->delete();

        return redirect('/pengaduan/data_pengaduan')->with('success', 'Pengaduan berhasil dihapus.');
    }

    public function form_pengaduan(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'pengadu') {
            return redirect('dashboard')
                ->with('error', 'Akses ditolak! Halaman ini hanya untuk user dengan role Pengadu.');
        }

        $ruangan = DB::table('ruangan')
            ->where('id_ruangan', $id)
            ->first();

        if (!$ruangan) {
            abort(404, 'Ruangan tidak ditemukan');
        }

        return view('pengaduan/form_pengaduan', compact('ruangan'));
    }

    public function simpan_pengaduan(Request $request)
    {
        $request->validate([
            'nama_pengadu'      => 'required|string|max:255',
            'deskripsi_masalah' => 'required|string',
            'id_ruangan'        => 'required|integer|exists:ruangan,id_ruangan',
        ]);

        $created_at = now('Asia/Jakarta');
        $idPerangkats = $request->input('id_perangkat', []);

        $ruangan = DB::table('ruangan')
            ->where('id_ruangan', $request->id_ruangan)
            ->first();

        $namaRuangan   = $ruangan->nama_ruangan ?? '-';
        $lokasiRuangan = $ruangan->lokasi ?? '-';

        $SIMANTIS_API_URL    = env('SIMANTIS_API_URL') . '/terima-pengaduan';
        $SIMANTIS_SECRET_KEY = env('SIMANTIS_SECRET_KEY');

        $idPengaduanList = [];
        $daftarPerangkat = '';

        DB::transaction(function () use (
            $request,
            $created_at,
            $idPerangkats,
            &$idPengaduanList,
            &$daftarPerangkat
        ) {

            if (!empty($idPerangkats)) {

                $perangkats = DB::table('perangkat')
                    ->leftJoin(
                        'kategori_perangkat',
                        'perangkat.id_kategori',
                        '=',
                        'kategori_perangkat.id_kategori'
                    )
                    ->whereIn('id_perangkat', $idPerangkats)
                    ->select(
                        'perangkat.*',
                        'kategori_perangkat.nama_kategori'
                    )
                    ->get();

                foreach ($perangkats as $i => $p) {

                    $idPengaduan = DB::table('pengaduan')->insertGetId([
                        'id_perangkat'      => $p->id_perangkat,
                        'id_ruangan'        => $request->id_ruangan,
                        'nama_pengadu'      => $request->nama_pengadu,
                        'deskripsi_masalah' => $request->deskripsi_masalah,
                        'created_at'        => $created_at,
                        'updated_at'        => $created_at,
                    ]);

                    DB::table('tindakan')->insert([
                        'id_pengaduan'       => $idPengaduan,
                        'id_ruangan'         => $request->id_ruangan,
                        'id_perangkat'       => $p->id_perangkat,
                        'kode_inventaris'    => $p->kode_inventaris,
                        'kategori_perangkat' => $p->nama_kategori,
                        'merek_perangkat'    => $p->merek,
                        'status'             => 'Menunggu',
                        'deskripsi_tindakan' => null,
                        'teknisi'            => null,
                        'created_at'         => $created_at,
                        'updated_at'         => $created_at,
                    ]);

                    $idPengaduanList[] = $idPengaduan;

                    $no = $i + 1;

                    $daftarPerangkat .= "{$no}. {$p->kode_inventaris} - {$p->nama_kategori} ({$p->merek})\n";
                }

            } else {

                $idPengaduan = DB::table('pengaduan')->insertGetId([
                    'id_perangkat'      => null,
                    'id_ruangan'        => $request->id_ruangan,
                    'nama_pengadu'      => $request->nama_pengadu,
                    'deskripsi_masalah' => $request->deskripsi_masalah,
                    'created_at'        => $created_at,
                    'updated_at'        => $created_at,
                ]);

                DB::table('tindakan')->insert([
                    'id_pengaduan'       => $idPengaduan,
                    'id_pengaduan_masuk' => null,
                    'id_ruangan'         => $request->id_ruangan,
                    'id_perangkat'       => null,
                    'status'             => 'Menunggu',
                    'deskripsi_tindakan' => null,
                    'teknisi'            => null,
                    'created_at'         => $created_at,
                    'updated_at'         => $created_at,
                ]);

                $idPengaduanList[] = $idPengaduan;
            }
        });

        if (!empty($idPerangkats)) {

            $perangkats = DB::table('perangkat')
            ->leftJoin(
                'kategori_perangkat',
                'perangkat.id_kategori',
                '=',
                'kategori_perangkat.id_kategori'
            )
            ->whereIn('id_perangkat', $idPerangkats)
            ->select(
                'perangkat.*',
                'kategori_perangkat.nama_kategori'
            )
            ->get();

            foreach ($perangkats as $index => $p) {

                try {

                    Http::timeout(5)
                    ->connectTimeout(3)
                    ->withToken($SIMANTIS_SECRET_KEY)
                    ->post($SIMANTIS_API_URL, [
                        'id_pengaduan'       => $idPengaduanList[$index], 
                        'id_ruangan'         => $request->id_ruangan,
                        'nama_ruangan'       => $namaRuangan,
                        'id_kategori'        => $p->id_kategori,
                        'id_perangkat'       => $p->id_perangkat,
                        'kode_inventaris'    => $p->kode_inventaris,
                        'kategori_perangkat' => $p->nama_kategori,
                        'merek_perangkat'    => $p->merek,
                        'created_at'         => $created_at->format('Y-m-d H:i:s'),
                        'deskripsi_masalah'  => $request->deskripsi_masalah,
                    ]);

                } catch (\Exception $e) {

                    Log::error('SIMANTIS ERROR', [
                        'message' => $e->getMessage()
                    ]);
                }
            }
        }

        $idRange = '#' . implode(', #', $idPengaduanList);

        $pesan =
            "🚨 PENGADUAN BARU\n\n" .
            "🆔 ID Pengaduan : {$idRange}\n" .
            "👤 Pelapor      : {$request->nama_pengadu}\n" .
            "🏢 Ruangan      : {$namaRuangan}\n" .
            "📍 Lokasi       : {$lokasiRuangan}\n" .
            "📅 Tanggal      : " . $created_at->format('d/m/Y H:i') . "\n\n" .
            (!empty($daftarPerangkat)
                ? "💻 DAFTAR PERANGKAT\n{$daftarPerangkat}\n"
                : '') .
            "📝 DESKRIPSI MASALAH\n" .
            $request->deskripsi_masalah;

        try {

            $groupWa = env('WA_GROUP');

            $this->wa->sendMessage(
                $groupWa,
                $pesan
            );

        } catch (\Exception $e) {

            Log::error('WA ERROR', [
                'message' => $e->getMessage()
            ]);
        }

        session([
            'nama_pengadu'      => $request->nama_pengadu,
            'id_pengaduan_baru' => $idPengaduanList,
        ]);

        return redirect('/tindakan/tindakan_pengaduan')
            ->with('success', 'Pengaduan berhasil dikirim.');
    }

    public function laporan_pengaduan(Request $request)
    {
        $data_pengaduan = DB::table('pengaduan')
            ->join('ruangan', 'pengaduan.id_ruangan', '=', 'ruangan.id_ruangan')
            ->leftJoin('perangkat', 'pengaduan.id_perangkat', '=', 'perangkat.id_perangkat') 
            ->leftJoin('kategori_perangkat', 'perangkat.id_kategori', '=', 'kategori_perangkat.id_kategori')
            ->join('tindakan', 'pengaduan.id', '=', 'tindakan.id_pengaduan')
            ->where('tindakan.status', '=', 'Selesai') 
            ->select(
                'pengaduan.id as pengaduan_id',
                'pengaduan.created_at',
                'pengaduan.nama_pengadu',
                'pengaduan.deskripsi_masalah',
                'ruangan.nama_ruangan',
                'perangkat.kode_inventaris',
                'kategori_perangkat.nama_kategori as kategori_perangkat',
                'tindakan.teknisi', 
                'tindakan.deskripsi_tindakan' 
            );

        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $data_pengaduan->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(pengaduan.nama_pengadu) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(pengaduan.deskripsi_masalah) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(ruangan.nama_ruangan) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(ruangan.lokasi) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(kategori_perangkat.nama_kategori) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(perangkat.kode_inventaris) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(tindakan.teknisi) LIKE ?', ["%{$search}%"]) 
                ->orWhereRaw('LOWER(tindakan.deskripsi_tindakan) LIKE ?', ["%{$search}%"]) 
                ->orWhereDate('pengaduan.created_at', $search);
            });
        }

        if ($request->filled('created_at')) {
            $parts = explode('-', $request->created_at);
            if (count($parts) == 2) {
                $tahun = $parts[0];
                $bulan = $parts[1];

                $data_pengaduan->whereYear('pengaduan.created_at', $tahun)->whereMonth('pengaduan.created_at', $bulan);
            }
        }

        $pengaduan = $data_pengaduan->orderByDesc('pengaduan.created_at')->paginate(5)->appends($request->query());

        return view('pengaduan.laporan_pengaduan', compact('pengaduan'));
    }

    public function cetak_pdf(Request $request)
    {
        $data_pengaduan = DB::table('pengaduan')
        ->join('ruangan', 'pengaduan.id_ruangan', '=', 'ruangan.id_ruangan')
        ->join('tindakan', 'pengaduan.id', '=', 'tindakan.id_pengaduan')
        ->leftJoin('perangkat', 'pengaduan.id_perangkat', '=', 'perangkat.id_perangkat') 
        ->leftJoin('kategori_perangkat', 'perangkat.id_kategori', '=', 'kategori_perangkat.id_kategori')
        ->where('tindakan.status', 'Selesai')
        ->select(
            'pengaduan.created_at',
            'pengaduan.nama_pengadu',
            'pengaduan.deskripsi_masalah',
            'ruangan.nama_ruangan',
            'ruangan.lokasi',
            'kategori_perangkat.nama_kategori as kategori_perangkat',
            'tindakan.created_at as created_at_tindakan',
            'tindakan.status',
            'tindakan.teknisi',
            'tindakan.deskripsi_tindakan' 
        );

        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $data_pengaduan->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(pengaduan.nama_pengadu) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(pengaduan.deskripsi_masalah) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(ruangan.nama_ruangan) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(ruangan.lokasi) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(kategori_perangkat.nama_kategori) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(tindakan.teknisi) LIKE ?', ["%{$search}%"]) 
                ->orWhereRaw('LOWER(tindakan.deskripsi_tindakan) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('created_at')) {
            $parts = explode('-', $request->created_at);

            if (count($parts) == 2) {
                $tahun = $parts[0];
                $bulan = $parts[1];

                $data_pengaduan->whereYear('pengaduan.created_at', $tahun)->whereMonth('pengaduan.created_at', $bulan);
            }
        }

        $pengaduan = $data_pengaduan->get();

        foreach ($pengaduan as $item) {
            if (empty($item->kategori_perangkat)) {
                $item->kategori_perangkat = 'Non-Perangkat / Fasilitas';
            }
        }

        return view('pengaduan/cetak_pdf', compact('pengaduan'));
    }

    public function export_excel(Request $request)
    {
        $data = DB::table('pengaduan')
            ->join('ruangan', 'pengaduan.id_ruangan', '=', 'ruangan.id_ruangan')
            ->join('tindakan', 'pengaduan.id', '=', 'tindakan.id_pengaduan') 
            ->leftJoin('perangkat', 'pengaduan.id_perangkat', '=', 'perangkat.id_perangkat')
            ->leftJoin('kategori_perangkat', 'perangkat.id_kategori', '=', 'kategori_perangkat.id_kategori')
            ->where('tindakan.status', 'Selesai')
            ->select(
                'pengaduan.created_at',
                'pengaduan.nama_pengadu',
                'pengaduan.deskripsi_masalah',
                'ruangan.nama_ruangan',
                'perangkat.kode_inventaris',
                'kategori_perangkat.nama_kategori',
                'tindakan.teknisi', 
                'tindakan.deskripsi_tindakan' 
            );

        if ($request->filled('search')) {
            $search = strtolower($request->search);

            $data->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(pengaduan.nama_pengadu) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(pengaduan.deskripsi_masalah) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(ruangan.nama_ruangan) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(kategori_perangkat.nama_kategori) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(perangkat.kode_inventaris) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(tindakan.teknisi) LIKE ?', ["%{$search}%"])
                ->orWhereRaw('LOWER(tindakan.deskripsi_tindakan) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('created_at')) {
            $parts = explode('-', $request->created_at);

            if (count($parts) == 2) {
                $data->whereYear('pengaduan.created_at', $parts[0])
                    ->whereMonth('pengaduan.created_at', $parts[1]);
            }
        }

        $data = $data->get();

        $fileName = "laporan_pengaduan_selesai_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'Tanggal',
            'Nama Pengadu',
            'Ruangan',
            'Kode Inventaris',
            'Kategori',
            'Deskripsi Masalah',
            'Teknisi',
            'Tindakan / Solusi'
        ];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            foreach ($data as $row) {
                fputcsv($file, [
                    date('d-m-Y H:i', strtotime($row->created_at)),
                    $row->nama_pengadu,
                    $row->nama_ruangan,
                    $row->kode_inventaris ?? '-',
                    $row->nama_kategori ?? 'Fasilitas Umum',
                    $row->deskripsi_masalah,
                    $row->teknisi, 
                    $row->deskripsi_tindakan 
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function apiCariManual(Request $request)
    {
        $keyword    = trim($request->input('keyword', ''));
        $id_ruangan = $request->filled('id_ruangan')
            ? (int) $request->id_ruangan
            : null;

        if (strlen($keyword) < 2) {
            return response()->json([], 200);
        }
 
        $query = DB::table('perangkat')
            ->join('kategori_perangkat', 'kategori_perangkat.id_kategori', '=', 'perangkat.id_kategori')
            ->select(
                'perangkat.id_perangkat as id',
                'perangkat.kode_inventaris',
                'perangkat.merek',
                'perangkat.alamat_ip',
                'perangkat.id_ruangan',
                'kategori_perangkat.id_kategori',
                'kategori_perangkat.nama_kategori as kategori_perangkat'
            );
 
        $query->where(function ($q) use ($keyword) {
            $q->where('perangkat.kode_inventaris', 'LIKE', "%{$keyword}%")
              ->orWhere('perangkat.merek',          'LIKE', "%{$keyword}%")
              ->orWhere('perangkat.alamat_ip',       'LIKE', "%{$keyword}%")
              ->orWhere('kategori_perangkat.nama_kategori', 'LIKE', "%{$keyword}%");
        });
 
        if ($id_ruangan) {
            $query->where('perangkat.id_ruangan', $id_ruangan);
        }
 
        $results = $query
            ->orderBy('perangkat.kode_inventaris', 'asc')
            ->limit(20)           
            ->get();
 
        return response()->json($results);
    }

    public function riwayat_pengaduan(Request $request)
    {
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalAkhir = $request->query('tanggal_akhir');

        $tindakan = DB::table('tindakan as t')
            ->join('pengaduan as p', 'p.id', '=', 't.id_pengaduan')
            ->join('ruangan as r', 'r.id_ruangan', '=', 't.id_ruangan')
            ->where('t.status', 'Selesai')
            ->select(
                't.id',
                't.id_pengaduan',
                't.status',
                't.deskripsi_tindakan',
                't.created_at',
                'p.nama_pengadu',
                'p.deskripsi_masalah',
                'r.nama_ruangan',
                't.teknisi as nama_teknisi'
            );

        if ($tanggalMulai) {
            $tindakan->whereDate('t.created_at', '>=', $tanggalMulai);
        }

        if ($tanggalAkhir) {
            $tindakan->whereDate('t.created_at', '<=', $tanggalAkhir);
        }

        $tindakans = $tindakan->orderByDesc('t.created_at')->paginate(5)->appends(request()->query());

        $group = collect($tindakans->items())->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
        });

        return view(
            'pengaduan.riwayat_pengaduan',
            compact(
                'group',
                'tindakans',
                'tanggalMulai',
                'tanggalAkhir'
            )
        );
    }
}