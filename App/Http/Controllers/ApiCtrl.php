<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiCtrl extends Controller
{
    private function cekToken(Request $request): bool
    {
        $token = $request->bearerToken();
        return $token === env('SIMANTIS_SECRET_KEY', 'darmayu123');
    }

    public function updateTindakan(Request $request)
    {
        Log::info('UPDATE TINDAKAN MASUK', [
            'data'  => $request->all(),
            'token' => $request->bearerToken(),
        ]);

        if (!$this->cekToken($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'id_pengaduan'       => 'required|integer',
            'id_pengaduan_masuk' => 'required|integer',
            'id_ruangan'         => 'required|integer',
            'id_perangkat'       => 'nullable|integer',
            'kode_inventaris'    => 'nullable|string',
            'kategori_perangkat' => 'nullable|string',
            'merek_perangkat'    => 'nullable|string',
            'status'             => 'required|in:Menunggu,Diterima,Pending,Dipending,Diproses,Selesai',
            'deskripsi_tindakan' => 'nullable|string|max:500',
            'teknisi'            => 'required|string',
            'created_at'         => 'required|date',
            'updated_at'         => 'required|date',
        ]);

        $tindakan = DB::table('tindakan')->where('id_pengaduan', $validated['id_pengaduan'])->first();

        $data = [
            'id_ruangan'         => $validated['id_ruangan'],
            'status'             => $validated['status'],
            'deskripsi_tindakan' => $validated['deskripsi_tindakan'] ?? null,
            'teknisi'            => $validated['teknisi'],
            'created_at'         => $validated['created_at'] ?? now(),
            'updated_at'         => $validated['updated_at'] ?? now(),
        ];

        if (!empty($validated['id_pengaduan'])) {
            $data['id_pengaduan'] = $validated['id_pengaduan'];
        }

        if (!$tindakan) {
            $data['id_pengaduan']       = $validated['id_pengaduan'];
            $data['id_pengaduan_masuk'] = $validated['id_pengaduan_masuk'];
            $data['id_perangkat']       = $validated['id_perangkat'] ?? null;
            $data['kode_inventaris']    = $validated['kode_inventaris'] ?? '-';
            $data['kategori_perangkat'] = $validated['kategori_perangkat'] ?? '-';
            $data['merek_perangkat']    = $validated['merek_perangkat'] ?? '-';
            $data['tanggal']            = now()->toDateString();
            $data['created_at']         = now()->toDateString();
            $data['updated_at']         = now()->toDateString();

            DB::table('tindakan')->insert($data);
            Log::info('INSERT TINDAKAN BERHASIL', ['id_pengaduan' => $validated['id_pengaduan']]);
        } else {
            $data['id_perangkat']       = $validated['id_perangkat'] ?? $tindakan->id_perangkat;
            $data['kode_inventaris']    = $validated['kode_inventaris'] ?? $tindakan->kode_inventaris;
            $data['kategori_perangkat'] = $validated['kategori_perangkat'] ?? $tindakan->kategori_perangkat;
            $data['merek_perangkat']    = $validated['merek_perangkat'] ?? $tindakan->merek_perangkat;

            $updated = DB::table('tindakan')->where('id_pengaduan', $validated['id_pengaduan'])->update($data);

            Log::info('HASIL UPDATE', [
                'id_pengaduan'  => $validated['id_pengaduan'],
                'affected_rows' => $updated, 
                'status_baru'   => $validated['status'],
            ]);
        }

        return response()->json([
            'message' => 'Status tindakan berhasil diperbarui di SIPITRS',
            'status'  => $validated['status'],
        ]);
    }

    public function storePerangkat(Request $request)
    {
        Log::info('API TAMBAH PERANGKAT MASUK', ['data' => $request->all()]);

        if (!$this->cekToken($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        DB::table('perangkat')->insert([
            'id_ruangan'      => $request->id_ruangan,
            'kode_inventaris' => $request->kode_inventaris,
            'alamat_ip'       => $request->alamat_ip,
            'merek'           => $request->merek,
            'id_kategori'     => $request->id_kategori,
        ]);

        return response()->json(['message' => 'Perangkat berhasil ditambahkan di SIPITRS'], 201);
    }

    public function updatePerangkat(Request $request)
    {
        Log::info('API UPDATE PERANGKAT MASUK', ['data' => $request->all()]);

        if (!$this->cekToken($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        DB::table('perangkat')
            ->where('kode_inventaris', $request->old_kode_inventaris)
            ->update([
                'id_ruangan'      => $request->id_ruangan,
                'kode_inventaris' => $request->kode_inventaris,
                'alamat_ip'       => $request->alamat_ip,
                'merek'           => $request->merek,
                'id_kategori'     => $request->id_kategori,
            ]);

        return response()->json(['message' => 'Perangkat berhasil diperbarui di SIPITRS']);
    }

    public function destroyPerangkat(Request $request)
    {
        Log::info('API HAPUS PERANGKAT MASUK', ['data' => $request->all()]);

        if (!$this->cekToken($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        DB::table('perangkat')->where('kode_inventaris', $request->kode_inventaris)->delete();

        return response()->json(['message' => 'Perangkat berhasil dihapus di SIPITRS']);
    }

    public function movePerangkat(Request $request)
    {
        Log::info('API PINDAH PERANGKAT MASUK', ['data' => $request->all()]);

        if (!$this->cekToken($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        DB::table('perangkat')
            ->where('kode_inventaris', $request->kode_inventaris)
            ->update([
                'id_ruangan' => $request->id_ruangan_tujuan,
            ]);

        return response()->json(['message' => 'Lokasi perangkat berhasil diperbarui di SIPITRS']);
    }
}