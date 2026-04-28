@extends('layout.page')
@section('page_title', 'Dashboard')

@section('content')
@if(session('pesan'))
<div id="toast-login" style="
   position:fixed; top:20px; right:20px; z-index:9999;
   background:#2ecc71; color:#fff;
   padding:14px 22px; border-radius:10px;
   box-shadow:0 4px 20px rgba(0,0,0,0.2);
   display:flex; align-items:center; gap:10px;
   font-size:15px; font-weight:500;
   animation: slideIn 0.4s ease;">
   <i class="fas fa-check-circle" style="font-size:20px;"></i>
   <span>{{ session('pesan') }}</span>
   <button onclick="this.parentElement.remove()" style="
      background:none; border:none; color:#fff;
      font-size:20px; cursor:pointer; margin-left:8px; line-height:1;">
      &times;
   </button>
</div>
@endif
<div class="row">
   <div class="col-xl-3 col-md-6">
        <div class="card mb-4 shadow-sm" style="border: none; border-radius: 12px; background: linear-gradient(135deg, #23daff, #23daff);">
            @php
                $user = DB::table('users')->get();
            @endphp
            <div class="card-body d-flex align-items-center justify-content-between px-4 py-3">
                <div>
                    <div style="font-size: 0.75rem; color: rgba(0, 0, 0, 0.75); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Total User</div>
                    <div style="font-size: 2rem; font-weight: 700; color: #000000; line-height: 1.2;">
                        {{ $user->count()}}
                    </div>
                </div>
                <div style="background: rgba(255,255,255,0.15); border-radius: 50%; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users" style="font-size: 1.5rem; color: #000000;"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between px-4 py-2" 
                style="background: rgba(0,0,0,0.1); border-top: 1px solid rgba(255,255,255,0.15); border-radius: 0 0 12px 12px;">
                <a href="{{ url('/user/data_user') }}" style="font-size: 0.8rem; color: rgba(0, 0, 0, 0.85); text-decoration: none;">
                    Lihat Detail
                </a>
                <i class="fas fa-arrow-right" style="font-size: 0.8rem; color: rgba(0, 0, 0, 0.85);"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4 shadow-sm" style="border: none; border-radius: 12px; background: linear-gradient(135deg, #f2ff00, #f2ff00);">
            @php
                $ruang = DB::table('ruangan')->get();
            @endphp
            <div class="card-body d-flex align-items-center justify-content-between px-4 py-3">
                <div>
                    <div style="font-size: 0.75rem; color: rgba(0, 0, 0, 0.75); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Total Ruang</div>
                    <div style="font-size: 2rem; font-weight: 700; color: #000000; line-height: 1.2;">
                        {{ $ruang->count()}}
                    </div>
                </div>
                <div style="background: rgba(255,255,255,0.15); border-radius: 50%; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-hospital" style="font-size: 1.5rem; color: #000000;"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between px-4 py-2" 
                style="background: rgba(0,0,0,0.1); border-top: 1px solid rgba(255,255,255,0.15); border-radius: 0 0 12px 12px;">
                <a href="{{ url('/ruang/data_ruang') }}" style="font-size: 0.8rem; color: rgba(0, 0, 0, 0.85); text-decoration: none;">
                    Lihat Detail
                </a>
                <i class="fas fa-arrow-right" style="font-size: 0.8rem; color: rgba(0, 0, 0, 0.85);"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4 shadow-sm" style="border: none; border-radius: 12px; background: linear-gradient(135deg, #26ef22, #26ef22);">
            @php
                $pengaduan = DB::table('pengaduan')->get();
            @endphp
            <div class="card-body d-flex align-items-center justify-content-between px-4 py-3">
                <div>
                    <div style="font-size: 0.75rem; color: rgba(0, 0, 0, 0.75); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Total Pengaduan</div>
                    <div style="font-size: 2rem; font-weight: 700; color: #000000; line-height: 1.2;">
                        {{ $pengaduan->count()}}
                    </div>
                </div>
                <div style="background: rgba(255,255,255,0.15); border-radius: 50%; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-archive" style="font-size: 1.5rem; color: #000000;"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between px-4 py-2" 
                style="background: rgba(0,0,0,0.1); border-top: 1px solid rgba(255,255,255,0.15); border-radius: 0 0 12px 12px;">
                <a href="{{ url('/pengaduan/data_pengaduan') }}" style="font-size: 0.8rem; color: rgba(0, 0, 0, 0.85); text-decoration: none;">
                    Lihat Detail
                </a>
                <i class="fas fa-arrow-right" style="font-size: 0.8rem; color: rgba(0, 0, 0, 0.85);"></i>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4 shadow-sm" style="border: none; border-radius: 12px; background: linear-gradient(135deg, #ef2222, #ef2222);">
            @php
                $tindakan = DB::table('tindakan')->get();
            @endphp
            <div class="card-body d-flex align-items-center justify-content-between px-4 py-3">
                <div>
                    <div style="font-size: 0.75rem; color: rgba(0, 0, 0, 0.75); text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Total Tindakan</div>
                    <div style="font-size: 2rem; font-weight: 700; color: #000000; line-height: 1.2;">
                        {{ $tindakan->count()}}
                    </div>
                </div>
                <div style="background: rgba(255,255,255,0.15); border-radius: 50%; width: 55px; height: 55px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-pencil-alt" style="font-size: 1.5rem; color: #000000;"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between px-4 py-2" 
                style="background: rgba(0,0,0,0.1); border-top: 1px solid rgba(255,255,255,0.15); border-radius: 0 0 12px 12px;">
                <a href="{{ url('tindakan/data_tindakan') }}" style="font-size: 0.8rem; color: rgba(0, 0, 0, 0.85); text-decoration: none;">
                    Lihat Detail
                </a>
                <i class="fas fa-arrow-right" style="font-size: 0.8rem; color: rgba(0, 0, 0, 0.85);"></i>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Total Pengaduan Per Bulan ({{ date('Y') }})
            </div>
            <div class="card-body">
                <canvas id="myBarChart" width="100%" height="40"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @php
        $pengaduanPerBulan = DB::table('pengaduan')
            ->selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
            ->whereYear('tanggal', date('Y'))
            ->whereMonth('tanggal', '<=', date('n'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $chartData = [];
        $chartLabels = [];

        $bulanSekarang = (int) date('n');
        $bulanMulai = max(1, $bulanSekarang - 4); 

        for ($i = $bulanMulai; $i <= $bulanSekarang; $i++) {
            $chartLabels[] = $namaBulan[$i - 1];
            $found = $pengaduanPerBulan->firstWhere('bulan', $i);
            $chartData[] = $found ? $found->total : 0;
        }
    @endphp

    const labels = @json($chartLabels);
    const data   = @json($chartData);

    const ctx = document.getElementById('myBarChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Pengaduan',
                data: data,
                backgroundColor: 'rgba(38, 239, 34, 0.6)',
                borderColor: 'rgba(38, 239, 34, 1)',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} Pengaduan`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    title: {
                        display: true,
                        text: 'Jumlah Pengaduan'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            }
        }
    });
</script>
@endsection