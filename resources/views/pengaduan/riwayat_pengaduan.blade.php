@extends('layout.page')

@section('page_title', 'Riwayat Pengaduan')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap');

    :root {
        --primary: #2563eb;
        --primary-soft: #eff6ff;
        --dark: #0f172a;
        --dark-soft: #475569;
        --bg: #f8fafc;
        --card: #ffffff;
        --border: #e2e8f0;
        --success: #10b981;
        --success-soft: #ecfdf5;
        --warning: #f59e0b;
        --warning-soft: #fffbeb;
        --slate: #64748b;
        --slate-soft: #f1f5f9;
        --radius-lg: 16px;
        --radius-md: 12px;
        --shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.05);
    }

    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background: var(--bg); 
        color: var(--dark); 
    }

    .header-section {
        margin-bottom: 2rem;
    }

    .header-section h1 {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        font-size: 1.75rem;
        color: var(--dark);
        letter-spacing: -0.02em;
    }

    .filter-modern {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow);
        margin-bottom: 2rem;
    }

    .filter-title {
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--slate);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-control-modern {
        border: 1.5px solid var(--border);
        border-radius: var(--radius-md);
        padding: 0.6rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s;
        background: var(--bg);
        width: 100%;
    }

    .form-control-modern:focus {
        border-color: var(--primary);
        background: #fff;
        outline: none;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .btn-action {
        padding: 0.6rem 1.5rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        cursor: pointer;
        border: none;
    }

    .btn-search { background: var(--dark); color: #fff; }
    .btn-search:hover { background: #000; transform: translateY(-1px); }

    .btn-reset-modern { 
        background: transparent; 
        color: var(--slate); 
        border: 1.5px solid var(--border);
        text-decoration: none;
    }
    .btn-reset-modern:hover { background: var(--slate-soft); color: var(--dark); }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: var(--shadow);
    }

    .stat-val { font-size: 1.5rem; font-weight: 800; display: block; line-height: 1.2; }
    .stat-lbl { font-size: 0.75rem; color: var(--dark-soft); font-weight: 500; }

    .icon-box {
        width: 45px; height: 45px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }

    .icon-all { background: var(--primary-soft); color: var(--primary); }
    .icon-done { background: var(--success-soft); color: var(--success); }
    .icon-process { background: var(--warning-soft); color: var(--warning); }

    .timeline-wrapper { position: relative; padding-left: 1rem; }

    .date-divider {
        display: flex; align-items: center; gap: 15px; margin: 2rem 0 1.5rem;
    }

    .date-label {
        background: var(--dark);
        color: #fff;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .date-line { flex: 1; height: 1px; background: var(--border); }

    .history-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 5px solid var(--slate);
    }

    .history-card:hover {
        transform: translateX(5px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    .history-card.selesai { border-left-color: var(--success); }
    .history-card.dalam-proses { border-left-color: var(--warning); }

    .card-top {
        display: flex; justify-content: space-between; align-items: flex-start;
        flex-wrap: wrap; gap: 10px; margin-bottom: 1rem;
    }

    .room-name { font-weight: 800; font-size: 1.1rem; color: var(--dark); margin: 0; }
    .reporter { font-size: 0.85rem; color: var(--dark-soft); display: flex; align-items: center; gap: 5px; }

    .status-pill {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-selesai { background: var(--success-soft); color: var(--success); }
    .status-proses { background: var(--warning-soft); color: var(--warning); }
    .status-pending { background: var(--slate-soft); color: var(--slate); }

    .issue-text {
        background: #f1f5f9;
        padding: 12px;
        border-radius: 10px;
        font-size: 0.875rem;
        color: var(--dark-soft);
        margin-bottom: 1rem;
        border-left: 3px solid var(--border);
    }

    .action-result {
        font-size: 0.95rem;
        color: var(--dark);
        line-height: 1.6;
        padding: 0 5px;
        font-weight: 500;
    }

    .meta-footer {
        display: flex; align-items: center; gap: 20px;
        margin-top: 1.25rem; padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.75rem; color: var(--slate);
        font-weight: 600;
    }

    .meta-item i { font-size: 0.9rem; color: var(--primary); }

    .custom-pagination{
        display:flex;
        justify-content:center;
        gap:8px;
        margin-top:30px;
        margin-bottom: 30px;
    }

    .page-btn{
        width:40px;
        height:40px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:10px;
        border:1px solid var(--border);
        background:#fff;
        color:var(--dark);
        text-decoration:none;
        font-weight:700;
        transition:.2s;
    }

    .page-btn:hover{
        background:var(--primary-soft);
        border-color:var(--primary);
        color:var(--primary);
    }

    .page-btn.active{
        background:var(--primary);
        color:#fff;
        border-color:var(--primary);
    }

    .page-title {
        color: #0f172a !important;
    }

    .filter-modern,
    .stat-card,
    .history-card {
        background: #ffffff;
        color: #0f172a;
    }

    .filter-title {
        color: #64748b !important;
    }

    .form-control-modern {
        background: #f8fafc;
        color: #0f172a;
    }

    .form-control-modern:focus {
        background: #ffffff;
        color: #0f172a;
    }

    .form-control-modern::placeholder {
        color: #64748b;
    }

    .room-name {
        color: #0f172a !important;
    }

    .reporter {
        color: #475569 !important;
    }

    .issue-text {
        background: #f1f5f9;
        color: #475569;
    }

    .action-result {
        color: #0f172a !important;
    }

    .meta-item {
        color: #64748b !important;
    }

    .date-line {
        background: #e2e8f0;
    }

    .text-dark-soft {
        color: #475569 !important;
    }

    .text-slate {
        color: #64748b !important;
    }

    .page-btn {
        background: #ffffff;
        color: #0f172a;
        border-color: #e2e8f0;
    }

    html.dark-mode .page-title {
        color: #f8fafc !important;
    }

    html.dark-mode .filter-modern,
    html.dark-mode .stat-card,
    html.dark-mode .history-card {
        background: #1e293b;
        color: #f1f5f9;
        border-color: #334155;
    }

    html.dark-mode .filter-title {
        color: #94a3b8 !important;
    }

    html.dark-mode .form-control-modern {
        background: #0f172a;
        color: #f1f5f9;
        border-color: #334155;
    }

    html.dark-mode .form-control-modern:focus {
        background: #0f172a;
        color: #f1f5f9;
        border-color: #2563eb;
    }

    html.dark-mode .form-control-modern::placeholder {
        color: #94a3b8;
    }

    html.dark-mode .room-name {
        color: #f1f5f9 !important;
    }

    html.dark-mode .reporter {
        color: #cbd5e1 !important;
    }

    html.dark-mode .issue-text {
        background: #0f172a;
        color: #cbd5e1;
        border-left-color: #334155;
    }

    html.dark-mode .action-result {
        color: #f1f5f9 !important;
    }

    html.dark-mode .meta-item {
        color: #94a3b8 !important;
    }

    html.dark-mode .date-line {
        background: #334155;
    }

    html.dark-mode .text-dark-soft {
        color: #cbd5e1 !important;
    }

    html.dark-mode .text-slate {
        color: #94a3b8 !important;
    }

    html.dark-mode .page-btn {
        background: #1e293b;
        color: #f1f5f9;
        border-color: #334155;
    }

    html.dark-mode .page-btn:hover {
        background: #334155;
        color: #60a5fa;
    }

    html.dark-mode .page-btn.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
    }

    html.dark-mode input[type="date"] {
        color-scheme: dark;
    }

    @media (max-width: 768px) {
        .history-card { padding: 1.25rem; }
        .meta-footer { gap: 10px; }
    }
</style>

<div class="filter-modern">
    <div class="filter-title"><i class="fas fa-sliders-h"></i> Filter Tanggal Riwayat Pengaduan</div>
    <form id="formFilterTanggal" action="{{ url('/pengaduan/riwayat_pengaduan') }}" method="get">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="small fw-bold mb-2 text-dark-soft">Mulai Dari</label>
                <input type="date"
                    id="tanggal_mulai"
                    name="tanggal_mulai"
                    class="form-control-modern"
                    value="{{ $tanggalMulai ?? '' }}">
            </div>

            <div class="col-md-4">
                <label class="small fw-bold mb-2 text-dark-soft">Sampai Dengan</label>
                <input type="date"
                    id="tanggal_akhir"
                    name="tanggal_akhir"
                    class="form-control-modern"
                    value="{{ $tanggalAkhir ?? '' }}">
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn-action btn-search flex-grow-1">
                    <i class="fas fa-filter"></i> Filter Data
                </button>

                @if($tanggalMulai || $tanggalAkhir)
                <a href="{{ url('/pengaduan/riwayat_pengaduan') }}" class="btn-action btn-reset-modern">
                    <i class="fas fa-sync-alt"></i>
                </a>
                @endif
            </div>
        </div>
    </form>
</div>

@php
    $allItems = $group->flatten();

    $totalPengaduanSelesai = $allItems->unique('id_pengaduan')->count();
@endphp

<div class="stats-container">
    <div class="stat-card">
        <div class="icon-box icon-all">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div>
            <span class="stat-val">{{ $totalPengaduanSelesai }}</span>
            <span class="stat-lbl">Total Pengaduan</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="icon-box icon-done">
            <i class="fas fa-check-double"></i>
        </div>
        <div>
            <span class="stat-val text-success">{{ $totalPengaduanSelesai }}</span>
            <span class="stat-lbl">Status Selesai</span>
        </div>
    </div>
</div>

@if($group->isEmpty())
    <div class="text-center py-5">
        <img src="https://illustrations.popsy.co/slate/no-results.svg" style="width: 200px; opacity: 0.6;" alt="empty">
        <p class="mt-4 fw-bold text-slate">Tidak ada data riwayat pengaduan pada periode ini.</p>
    </div>
@else
    <div class="timeline-wrapper">
        @foreach($group as $tanggal => $items)
            <div class="date-divider">
                <div class="date-label">
                    {{ \Carbon\Carbon::parse($items->first()->created_at)->translatedFormat('d M Y H:i') }}
                </div>
                <div class="date-line"></div>
                <div class="small fw-bold text-slate">
                    {{ $items->unique('id_pengaduan')->count() }} Pengaduan
                </div>
            </div>

            @foreach($items as $item)
            @php
                $statusSlug = match($item->status) {
                    'Selesai' => 'selesai',
                    'Dalam Proses' => 'dalam-proses',
                    default => 'pending'
                };
            @endphp
            <div class="history-card {{ $statusSlug }}">
                <div class="card-top">
                    <div>
                        <h3 class="room-name">{{ $item->nama_ruangan }}</h3>
                        <div class="reporter">
                            <i class="fas fa-user-edit"></i> Pengadu : {{ $item->nama_pengadu }}
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="status-pill status-{{ $statusSlug == 'dalam-proses' ? 'proses' : $statusSlug }}">
                            {{ $item->status }}
                        </span> 
                        <div class="meta-item">
                            <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                        </div>
                    </div>
                </div>

                @if($item->deskripsi_masalah)
                <div class="issue-text">
                    <strong>Deskripsi Masalah:</strong> {{ $item->deskripsi_masalah }}
                </div>
                @endif

                <div class="action-result">
                    <i class="fas fa-tools me-2 text-primary"></i>
                    {{ $item->deskripsi_pengaduan ?? 'Tidak ada detail pengaduan' }}
                </div>

                <div class="meta-footer">
                    <div class="meta-item">
                        <i class="fas fa-user-cog"></i> 
                        <span>Teknisi: {{ $item->nama_teknisi ?? 'System' }}</span>
                    </div>
                    @if(isset($item->kategori_perangkat))
                        <div class="meta-item ms-auto d-none d-sm-flex">
                            <i class="fas fa-microchip"></i>
                            <span>{{ $item->kategori_perangkat }}</span>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        @endforeach
        @if ($tindakans->lastPage() > 1)
            <div class="custom-pagination">
                @for ($i = 1; $i <= $tindakans->lastPage(); $i++)
                    <a href="{{ $tindakans->appends(request()->query())->url($i) }}"
                    class="page-btn {{ $tindakans->currentPage() == $i ? 'active' : '' }}">
                        {{ $i }}
                    </a>
                @endfor
            </div>
        @endif
    </div>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('formFilterTanggal');
        const mulai = document.getElementById('tanggal_mulai');
        const akhir = document.getElementById('tanggal_akhir');

        function autoSubmit() {
            if (mulai.value || akhir.value) {
                form.submit();
            }
        }

        mulai.addEventListener('change', autoSubmit);
        akhir.addEventListener('change', autoSubmit);

    });
</script>
@endsection