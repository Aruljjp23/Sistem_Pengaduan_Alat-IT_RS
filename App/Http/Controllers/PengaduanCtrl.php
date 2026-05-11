<?php

namespace App\Http\Controllers;

use App\Services\WhatsappService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        ->join('ruangan as b', 'a.id_ruangan', '=', 'b.id')
        ->leftJoin('perangkat as c', 'a.id_perangkat', '=', 'c.id')
        ->leftJoinSub($tindakanTerbaru, 'tl', 'tl.id_pengaduan', '=', 'a.id')
        ->leftJoin('users as u', 'u.id', '=', 'tl.teknisi')
        ->select(
            'a.*',
            'b.nama_ruangan as ruangan',
            'b.lokasi as nama_lokasi',
            'c.kode_perangkat',
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

        if ($request->tanggal) {
            $pengaduan->whereDate('a.tanggal', $request->tanggal);
        }

        $data_pengaduan = $pengaduan->orderByDesc('a.tanggal')->paginate(10);

        return view('pengaduan/data_pengaduan', compact('data_pengaduan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pengadu'      => 'required|string|max:255',
            'tanggal'           => 'required|date',
            'deskripsi_masalah' => 'required|string',
        ]);

        $exists = DB::table('pengaduan')->where('id', $id)->exists();
        if (!$exists) {
            return back()->withErrors(['id' => 'Pengaduan tidak ditemukan.']);
        }

        DB::table('pengaduan')->where('id', $id)->update([
            'nama_pengadu'      => $request->nama_pengadu,
            'tanggal'           => $request->tanggal,
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
        $ruangan = DB::table('ruangan')->where('id', $id)->first();

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
            'tanggal_waktu'     => 'required|date',
            'id_ruangan'        => 'required|integer|exists:ruangan,id',
        ]);

        $tanggal      = new DateTime($request->tanggal_waktu);
        $idPerangkats = $request->input('id_perangkat', []);

        $ruangan = DB::table('ruangan')->where('id', $request->id_ruangan)->first();
        $namaRuangan = $ruangan->nama_ruangan ?? '-';
        $lokasiRuangan = $ruangan->lokasi ?? '-';

        $pesan = "";

        if (!empty($idPerangkats)) {

            $perangkats = DB::table('perangkat')->whereIn('id', $idPerangkats)->get();

            $idPengaduanList = [];
            $daftarPerangkat = "";

            foreach ($perangkats as $i => $p) {

                $idPengaduan = DB::table('pengaduan')->insertGetId([
                    'id_perangkat'      => $p->id,
                    'id_ruangan'        => $request->id_ruangan,
                    'nama_pengadu'      => $request->nama_pengadu,
                    'deskripsi_masalah' => $request->deskripsi_masalah,
                    'tanggal'           => $tanggal->format('Y-m-d H:i:s'),
                ]);

                $idPengaduanList[] = $idPengaduan;

                $no = $i + 1;
                $daftarPerangkat .= "{$no}. {$p->kode_perangkat} - {$p->kategori_perangkat} ({$p->merek})\n";
            }

            $idRange = implode(', #', $idPengaduanList);

            $pesan =
            "📢 PENGADUAN #{$idRange}\n\n".
            "👤 {$request->nama_pengadu}\n".
            "📍 {$namaRuangan} - {$lokasiRuangan}\n".
            "📅 ".$tanggal->format('d/m/Y H:i')."\n\n".
            "💻 Perangkat:\n{$daftarPerangkat}\n".
            "📝 {$request->deskripsi_masalah}";

        } else {

            $idPengaduan = DB::table('pengaduan')->insertGetId([
                'id_perangkat'      => null,
                'id_ruangan'        => $request->id_ruangan,
                'nama_pengadu'      => $request->nama_pengadu,
                'deskripsi_masalah' => $request->deskripsi_masalah,
                'tanggal'           => $tanggal->format('Y-m-d H:i:s'),
            ]);

            $pesan =
            "📢 PENGADUAN #{$idPengaduan}\n\n".
            "👤 {$request->nama_pengadu}\n".
            "📍 {$namaRuangan} - {$lokasiRuangan}\n".
            "📅 ".$tanggal->format('d/m/Y H:i')."\n\n".
            "📝 {$request->deskripsi_masalah}";
        }

        $groupWa = env('WA_GROUP');

        $responseWa = $this->wa->sendMessage($groupWa, $pesan);

        Log::info('HASIL WA', [
            'response' => $responseWa
        ]);

        if (!empty($idPerangkats)) {

            session([
                'nama_pengadu'      => $request->nama_pengadu,
                'id_pengaduan_baru' => $idPengaduanList,
            ]);

        } else {

            session([
                'nama_pengadu'      => $request->nama_pengadu,
                'id_pengaduan_baru' => [$idPengaduan],
            ]);
        }

        return redirect('/tindakan/tindakan_pengaduan')->with('success', 'Pengaduan berhasil dikirim dan notifikasi WA terkirim.');
    }

    public function laporan_pengaduan(Request $request)
    {
        $data_pengaduan = DB::table('pengaduan')
            ->join('ruangan', 'pengaduan.id_ruangan', '=', 'ruangan.id')
            ->join('tindakan', 'pengaduan.id', '=', 'tindakan.id_pengaduan')
            ->where('tindakan.status', 'selesai')
            ->select(
                'pengaduan.tanggal',
                'pengaduan.nama_pengadu',
                'ruangan.nama_ruangan',
                'ruangan.lokasi',
                'tindakan.tanggal as tanggal_tindakan',
                'tindakan.status',
                'tindakan.kondisi',
                'tindakan.teknisi'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $data_pengaduan->where(function($q) use ($search) {
                $q->where('pengaduan.nama_pengadu', 'like', "%$search%")
                ->orWhere('ruangan.nama_ruangan', 'like', "%$search%")
                ->orWhere('ruangan.lokasi', 'like', "%$search%");
            });
        }

        if ($request->filled('tanggal')) {
            $data_pengaduan->whereDate('pengaduan.tanggal', $request->tanggal);
        }

        $pengaduan = $data_pengaduan->get();

        return view('pengaduan/laporan_pengaduan', compact('pengaduan'));
    }

    public function cetak_pdf(Request $request)
    {
        $data_pengaduan = DB::table('pengaduan')
            ->join('ruangan', 'pengaduan.id_ruangan', '=', 'ruangan.id')
            ->join('tindakan', 'pengaduan.id', '=', 'tindakan.id_pengaduan')
            ->where('tindakan.status', 'selesai')
            ->select(
                'pengaduan.tanggal',
                'pengaduan.nama_pengadu',
                'ruangan.nama_ruangan',
                'ruangan.lokasi',
                'tindakan.tanggal as tanggal_tindakan',
                'tindakan.status',
                'tindakan.kondisi',
                'tindakan.teknisi'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $data_pengaduan->where(function($q) use ($search) {
                $q->where('pengaduan.nama_pengadu', 'like', "%$search%")
                ->orWhere('ruangan.nama_ruangan', 'like', "%$search%")
                ->orWhere('ruangan.lokasi', 'like', "%$search%");
            });
        }

        if ($request->filled('tanggal')) {
            $data_pengaduan->whereDate('pengaduan.tanggal', $request->tanggal);
        }

        $pengaduan = $data_pengaduan->get();

        return view('pengaduan/cetak_pdf', compact('pengaduan'));
    }
}