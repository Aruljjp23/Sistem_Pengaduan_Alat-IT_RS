<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuanganCtrl extends Controller
{
    public function data_ruangan(Request $request){

        $search=$request->search;
        $page = (int) request()->get('page', 1);
        $perPage = 10;
        $offset = 0;
        $currentLantaiPage = 1;


        if ($search) {

            $keyword = strtolower(trim($search));

            $data_ruangan = DB::table('ruangan')
                ->where(function ($q) use ($keyword) {

                    $q->whereRaw('LOWER(nama_ruangan) LIKE ?', ["%{$keyword}%"]);

                    if (in_array($keyword, ['lt 1', 'lt. 1', 'lantai 1'])) {
                        $q->orWhere('lokasi', 'Lt. 1');
                    } elseif (in_array($keyword, ['lt 2', 'lt. 2', 'lantai 2'])) {
                        $q->orWhere('lokasi', 'Lt. 2');
                    } elseif (in_array($keyword, ['lt 3', 'lt. 3', 'lantai 3'])) {
                        $q->orWhere('lokasi', 'Lt. 3');
                    } else {
                        $q->orWhereRaw('LOWER(lokasi) LIKE ?', ["%{$keyword}%"]);
                    }

                })
                ->orderBy('nama_ruangan', 'asc')
                ->paginate($perPage);
        } else {
            $totalLantai1 = DB::table('ruangan')->where('lokasi', 'Lt. 1')->count();
            $totalLantai2 = DB::table('ruangan')->where('lokasi', 'Lt. 2')->count();
            $totalLantai3 = DB::table('ruangan')->where('lokasi', 'Lt. 3')->count();

        
            $halamanLantai1 = ceil($totalLantai1 / $perPage);
            $halamanLantai2 = ceil($totalLantai2 / $perPage);
            $halamanLantai3 = ceil($totalLantai3 / $perPage);

            if ($page <= $halamanLantai1) {
                $lantai = 'Lt. 1';
                $currentLantaiPage = $page;
            } elseif ($page <= $halamanLantai1 + $halamanLantai2) {
                $lantai = 'Lt. 2';
                $currentLantaiPage = $page - $halamanLantai1;
            } else {
                $lantai = 'Lt. 3';
                $currentLantaiPage = $page - $halamanLantai1 - $halamanLantai2;
            }

            $totalPages = $halamanLantai1 + $halamanLantai2 + $halamanLantai3;

            $items = DB::table('ruangan')->where('lokasi', $lantai)->orderBy('nama_ruangan', 'asc')->forPage($currentLantaiPage, $perPage)->get();

            $offsetLantai1 = 0;
            $offsetLantai2 = DB::table('ruangan')->where('lokasi', 'Lt. 1')->count();
            $offsetLantai3 = DB::table('ruangan')->where('lokasi', 'Lt. 1')->orWhere('lokasi', 'Lt. 2')->count();

            if ($lantai == 'Lt. 1') {
                $offset = 0;
            } elseif ($lantai == 'Lt. 2') {
                $offset = DB::table('ruangan')->where('lokasi', 'Lt. 1')->count();
            } elseif ($lantai == 'Lt. 3') {
                $offset = DB::table('ruangan')->whereIn('lokasi', ['Lt. 1', 'Lt. 2'])->count();
            }
            $data_ruangan = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $totalPages * $perPage,
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }
        // if(count($request->all())>0){
        //     $data_ruangan = DB::table('ruangan')
        //     ->orderBy('nama_ruangan', 'asc')
        //     ->when($search,function($q,$search){
        //         return $q->where('nama_ruangan',$search);
        //     })
        //     ->paginate(5);
        // }else{
        //     $data_ruangan = DB::table('ruangan')->orderBy('nama_ruangan', 'asc')->paginate(10);
        // }

        return view('ruang/data_ruang',compact('data_ruangan','offset','currentLantaiPage','perPage'));
    }

    public function store(Request $request)
    {

        DB::table('ruangan')->insert([
            'nama_ruangan'=>$request->nama_ruangan,
            'lokasi'=>$request->lokasi,
        ]);

        return redirect('ruang/data_ruang')->with('success', 'Data Ruangan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        DB::table('ruangan')->where('id_ruangan','=',$id)->update([
            'nama_ruangan'=>$request->nama_ruangan,
            'lokasi'=>$request->lokasi,
        ]);

        return back()->with('success', 'Data Ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::table('ruangan')->where('id_ruangan', '=', $id)->delete();
        return redirect('ruang/data_ruang')->with('success', 'Data Ruangan berhasil dihapus.');
    }
}
