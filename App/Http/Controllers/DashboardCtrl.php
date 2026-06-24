<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Dashboard()
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        $users = DB::table('users')->count();
        $ruangan = DB::table('ruangan')->count();
        $pengaduan = DB::table('pengaduan')->count();
        $kategori = DB::table('kategori_perangkat')->count();

        $pengaduanPerBulan = DB::table('pengaduan')
            ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        $chartData = [];
        $chartLabels = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = $namaBulan[$i - 1];
            $found = $pengaduanPerBulan->firstWhere('bulan', $i);
            $chartData[] = $found ? $found->total : 0;
        }

        return view('dashboard', compact(
            'users',
            'ruangan',
            'pengaduan',
            'kategori',
            'chartData',
            'chartLabels'
        ));
    }
}
