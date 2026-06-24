@extends('layout.page')
@section('page_title', 'Dashboard')

@section('content')
@php
    $pengaduanPerBulan = DB::table('pengaduan')->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')->whereYear('created_at', date('Y'))->groupBy('bulan')->orderBy('bulan')->get();

    $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $chartData = [];
    $chartLabels = [];

    for ($i = 1; $i <= 12; $i++) {
        $chartLabels[] = $namaBulan[$i - 1];
        $found = $pengaduanPerBulan->firstWhere('bulan', $i);
        $chartData[] = $found ? $found->total : 0;
    }
@endphp

<style>
    .dashboard-container { padding: 1rem 0; }

    .card-custom {
        border: none;
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    
    .card-custom:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.12) !important;
    }

    .stat-icon-box {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border-radius: 14px;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.2;
        letter-spacing: -1px;
    }

    .stat-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 6px;
    }

    .card-footer-custom {
        background: rgba(0,0,0,0.12) !important;
        border: none;
        padding: 12px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #fff !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chart-card {
        border: none;
        border-radius: 24px;
        background: #ffffff;
        box-shadow: 0 20px 40px rgba(0,0,0,0.03);
    }

    @media (max-width: 576px) {
        .stat-value { font-size: 1.4rem; }
        .row.g-3 > div { padding: 5px; }
    }
</style>

<div class="dashboard-container">
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card card-custom h-100" style="background: linear-gradient(135deg, #00b4db, #0083b0);">
                <div class="card-body p-3 p-md-4">
                    <div class="stat-label">Users</div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-value">{{ $users }}</div>
                        <div class="stat-icon-box"><i class="fas fa-users text-white"></i></div>
                    </div>
                </div>
                <a href="{{ url('/user/data_user') }}" class="card-footer-custom text-decoration-none">
                    <span>Lihat Detail</span> <i class="fas fa-chevron-right small"></i>
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card card-custom h-100" style="background: linear-gradient(135deg, #f9d423, #ff4e50);">
                <div class="card-body p-3 p-md-4">
                    <div class="stat-label">Ruangan</div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-value">{{ $ruangan }}</div>
                        <div class="stat-icon-box"><i class="fas fa-hospital text-white"></i></div>
                    </div>
                </div>
                <a href="{{ url('/ruang/data_ruang') }}" class="card-footer-custom text-decoration-none">
                    <span>Lihat Detail</span> <i class="fas fa-chevron-right small"></i>
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card card-custom h-100" style="background: linear-gradient(135deg, #42e695, #3bb2b8);">
                <div class="card-body p-3 p-md-4">
                    <div class="stat-label">Pengaduan</div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-value">{{ $pengaduan }}</div>
                        <div class="stat-icon-box"><i class="fas fa-bullhorn text-white"></i></div>
                    </div>
                </div>
                <a href="{{ url('/tindakan/riwayat_tindakan') }}" class="card-footer-custom text-decoration-none">
                    <span>Lihat Detail</span> <i class="fas fa-chevron-right small"></i>
                </a>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card card-custom h-100" style="background: linear-gradient(135deg, #8e2de2, #4a00e0);">
                <div class="card-body p-3 p-md-4">
                    <div class="stat-label">Kategori</div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="stat-value">{{ $kategori }}</div>
                        <div class="stat-icon-box"><i class="fas fa-check-double text-white"></i></div>
                    </div>
                </div>
                <a href="{{ url('kategori_perangkat/data_kategori') }}" class="card-footer-custom text-decoration-none">
                    <span>Lihat Detail</span> <i class="fas fa-chevron-right small"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card chart-card p-3 p-md-4">
                <div class="d-flex align-items-center justify-content-between mb-4 px-2">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Statistik Pengaduan</h5>
                        <p class="text-muted small mb-0">Total laporan masuk per bulan</p>
                    </div>
                    <div class="bg-light p-2 rounded-3">
                        <i class="fas fa-calendar-alt text-muted"></i> <small class="fw-bold">{{ date('Y') }}</small>
                    </div>
                </div>
                <div style="position: relative; height: 350px;">
                    <canvas id="myModernBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('myModernBarChart').getContext('2d');
        
        const barGradient = ctx.createLinearGradient(0, 0, 0, 400);
        barGradient.addColorStop(0, '#42e695'); 
        barGradient.addColorStop(1, '#3bb2b8'); 

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Total Pengaduan',
                    data: @json($chartData),
                    backgroundColor: barGradient,
                    hoverBackgroundColor: '#26ef22', 
                    borderRadius: 10, 
                    borderSkipped: false,
                    barThickness: 25, 
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 10,
                        titleFont: { size: 14, weight: 'bold' },
                        displayColors: false
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { 
                            color: '#f0f0f0', 
                            drawBorder: false 
                        },
                        ticks: { 
                            stepSize: 1,
                            font: { size: 12, weight: '500' }
                        }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 12, weight: '500' } }
                    }
                }
            }
        });
    });
</script>
@endsection