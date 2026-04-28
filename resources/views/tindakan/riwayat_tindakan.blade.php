@extends('layout.page')

@section('page_title', 'Riwayat Tindakan')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap');

    :root {
        --ink:        #0f1117;
        --ink-soft:   #4a4f5e;
        --surface:    #f5f6fa;
        --card:       #ffffff;
        --border:     #e4e6f0;
        --accent:     #2563eb;
        --accent-dim: #dbeafe;
        --green:      #16a34a;
        --green-dim:  #dcfce7;
        --amber:      #d97706;
        --amber-dim:  #fef3c7;
        --slate:      #64748b;
        --slate-dim:  #f1f5f9;
        --line:       #cbd5e1;
        --radius:     12px;
        --shadow-sm:  0 1px 4px rgba(15,17,23,.06), 0 0 1px rgba(15,17,23,.08);
        --shadow-md:  0 4px 16px rgba(15,17,23,.08), 0 0 1px rgba(15,17,23,.06);
    }

    body { font-family: 'DM Sans', sans-serif; background: var(--surface); color: var(--ink); }

    .rw-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .rw-header-left h1 {
        font-family: 'Syne', sans-serif;
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -.5px;
        margin: 0 0 4px;
        color: var(--ink);
    }
    .rw-header-left p {
        margin: 0;
        font-size: .875rem;
        color: var(--ink-soft);
        font-weight: 300;
    }

    .filter-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
    }
    .filter-label {
        font-family: 'Syne', sans-serif;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--ink-soft);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .filter-row {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        flex: 1;
        min-width: 160px;
    }
    .filter-group label {
        font-size: .8rem;
        font-weight: 500;
        color: var(--ink-soft);
    }
    .filter-group input[type="date"] {
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: 8px 12px;
        font-family: 'DM Sans', sans-serif;
        font-size: .875rem;
        color: var(--ink);
        background: var(--surface);
        transition: border-color .15s;
        outline: none;
    }
    .filter-group input[type="date"]:focus {
        border-color: var(--accent);
        background: #fff;
    }
    .btn-filter {
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 9px 20px;
        font-family: 'Syne', sans-serif;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background .15s, transform .1s;
        white-space: nowrap;
    }
    .btn-filter:hover  { background: #1d4ed8; transform: translateY(-1px); }
    .btn-filter:active { transform: translateY(0); }
    .btn-reset {
        background: transparent;
        color: var(--slate);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: 8px 16px;
        font-family: 'Syne', sans-serif;
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: border-color .15s, color .15s;
        white-space: nowrap;
    }
    .btn-reset:hover { border-color: var(--slate); color: var(--ink); }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 14px;
        margin-bottom: 28px;
    }
    .stat-chip {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 14px 18px;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .95rem;
        flex-shrink: 0;
    }
    .stat-icon.all    { background: var(--accent-dim); color: var(--accent); }
    .stat-icon.done   { background: var(--green-dim);  color: var(--green);  }
    .stat-icon.onproc { background: var(--amber-dim);  color: var(--amber);  }
    .stat-icon.pend   { background: var(--slate-dim);  color: var(--slate);  }
    .stat-info strong {
        display: block;         
        font-size: 1.4rem;
        font-weight: 800;
        line-height: 1;
        color: var(--ink);
        margin-bottom: 3px;
    }

    .stat-info span {
        display: block;
        font-size: .72rem;
        color: var(--ink-soft);
    }

    .empty-state {
        text-align: center;
        padding: 56px 24px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
    }
    .empty-state .es-icon {
        font-size: 2.5rem;
        margin-bottom: 14px;
        opacity: .35;
    }
    .empty-state h3 {
        font-family: 'Syne', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0 0 6px;
        color: var(--ink);
    }
    .empty-state p { font-size: .875rem; color: var(--ink-soft); margin: 0; }

    .timeline-section { position: relative; }

    .day-group { margin-bottom: 36px; }

    .day-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 20px;
    }

    .day-bubble {
        background: var(--ink);
        color: #fff;
        font-family: 'Syne', sans-serif;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .day-line {
        flex: 1;
        height: 1px;
        background: var(--line);
    }
    .day-count {
        font-size: .75rem;
        color: var(--ink-soft);
        font-weight: 500;
        white-space: nowrap;
    }

    .tl-items {
        position: relative;
        padding-left: 36px;
    }
    .tl-items::before {
        content: '';
        position: absolute;
        left: 14px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border);
        border-radius: 1px;
    }

    .tl-item {
        position: relative;
        margin-bottom: 16px;
        animation: fadeUp .35s ease both;
    }
    .tl-item:nth-child(2) { animation-delay: .04s; }
    .tl-item:nth-child(3) { animation-delay: .08s; }
    .tl-item:nth-child(4) { animation-delay: .12s; }
    .tl-item:nth-child(5) { animation-delay: .16s; }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .tl-dot {
        position: absolute;
        left: -29px;
        top: 16px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid var(--card);
        box-shadow: 0 0 0 2px var(--border);
        background: var(--slate);
    }
    .tl-dot.selesai    { background: var(--green); box-shadow: 0 0 0 2px var(--green-dim); }
    .tl-dot.dalam-proses { background: var(--amber); box-shadow: 0 0 0 2px var(--amber-dim); }
    .tl-dot.pending    { background: var(--slate); box-shadow: 0 0 0 2px var(--slate-dim); }

    .tl-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 16px 18px;
        box-shadow: var(--shadow-sm);
        transition: box-shadow .2s, transform .2s;
    }
    .tl-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .tl-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    .tl-room {
        font-family: 'Syne', sans-serif;
        font-size: .95rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 2px;
    }
    .tl-pengadu {
        font-size: .78rem;
        color: var(--ink-soft);
        font-weight: 400;
    }
    .tl-pengadu i { margin-right: 4px; opacity: .6; }

    .tl-badges { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }

    .badge-status {
        font-family: 'Syne', sans-serif;
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .badge-status.selesai     { background: var(--green-dim); color: var(--green); }
    .badge-status.dalam-proses { background: var(--amber-dim); color: var(--amber); }
    .badge-status.pending     { background: var(--slate-dim); color: var(--slate); }

    .tl-time {
        font-size: .72rem;
        color: var(--ink-soft);
        font-weight: 400;
        white-space: nowrap;
    }
    .tl-time i { margin-right: 3px; opacity: .6; }

    .tl-divider {
        height: 1px;
        background: var(--border);
        margin: 10px 0;
    }

    .tl-kondisi {
        font-size: .83rem;
        color: var(--ink);
        line-height: 1.55;
        font-style: italic;
        position: relative;
        padding-left: 12px;
    }
    .tl-kondisi::before {
        content: '';
        position: absolute;
        left: 0;
        top: 4px;
        bottom: 4px;
        width: 3px;
        border-radius: 2px;
        background: var(--accent-dim);
    }

    .tl-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    .tl-meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: .75rem;
        color: var(--ink-soft);
    }
    .tl-meta-item i { opacity: .55; font-size: .7rem; }

    @media (max-width: 576px) {
        .rw-header { flex-direction: column; align-items: flex-start; }
        .tl-card-top { flex-direction: column; }
        .filter-row { flex-direction: column; }
    }
</style>

<div class="filter-card">
    <div class="filter-label">
        <i class="far fa-calendar-alt"></i> Filter Periode
    </div>
    <form action="{{ url('/tindakan/riwayat_tindakan') }}" method="get">
        <div class="filter-row">
            <div class="filter-group">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $tanggalMulai ?? '' }}">
            </div>
            <div class="filter-group">
                <label for="tanggal_akhir">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggalAkhir ?? '' }}">
            </div>
            <button type="submit" class="btn-filter">
                <i class="fas fa-search"></i> Tampilkan
            </button>
            @if($tanggalMulai || $tanggalAkhir)
            <a href="{{ url('/tindakan/riwayat_tindakan') }}" class="btn-reset">
                <i class="fas fa-times"></i> Reset
            </a>
            @endif
        </div>
    </form>
</div>

@php
    $allItems     = $group->flatten();
    $totalAll     = $allItems->count();
    $totalSelesai = $allItems->where('status', 'Selesai')->count();
    $totalProses  = $allItems->where('status', 'Dalam Proses')->count();
    $totalPending = $allItems->where('status', 'Pending')->count();
@endphp

<div class="stats-row">
    <div class="stat-chip">
        <div class="stat-icon all"><i class="fas fa-list-ul"></i></div>
        <div class="stat-info">
            <strong>{{ $totalAll }}</strong>
            <span>Total Tindakan</span>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-icon done"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <strong>{{ $totalSelesai }}</strong>
            <span>Selesai</span>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-icon onproc"><i class="fas fa-spinner"></i></div>
        <div class="stat-info">
            <strong>{{ $totalProses }}</strong>
            <span>Dalam Proses</span>
        </div>
    </div>
    <div class="stat-chip">
        <div class="stat-icon pend"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <strong>{{ $totalPending }}</strong>
            <span>Pending</span>
        </div>
    </div>
</div>

@if($group->isEmpty())
    <div class="empty-state">
        <div class="es-icon"><i class="fas fa-history"></i></div>
        <h3>Belum ada riwayat tindakan</h3> 
        <p>Coba ubah rentang tanggal atau pastikan data sudah tersedia.</p>
    </div>
@else
    <div class="timeline-section">
        @foreach($group as $tanggal => $items)
        @php
            $tgl = \Carbon\Carbon::parse($tanggal);
            $labelTgl = $tgl->isToday() ? 'Hari Ini'
                : ($tgl->isYesterday() ? 'Kemarin'
                : $tgl->translatedFormat('d F Y'));
        @endphp

        <div class="day-group">
            <div class="day-header">
                <div class="day-bubble">{{ $labelTgl }}</div>
                <div class="day-line"></div>
                <div class="day-count">{{ $items->count() }} tindakan</div>
            </div>

            <div class="tl-items">
                @foreach($items as $item)
                @php
                    $statusClass = match($item->status) {
                        'Selesai'      => 'selesai',
                        'Dalam Proses' => 'dalam-proses',
                        default        => 'pending',
                    };
                @endphp

                <div class="tl-item">
                    <div class="tl-dot {{ $statusClass }}"></div>
                    <div class="tl-card">

                        <div class="tl-card-top">
                            <div>
                                <p class="tl-room">
                                    <i class="fas fa-door-open" style="color:var(--accent);margin-right:5px;font-size:.85rem;"></i>
                                    {{ $item->nama_ruangan }}
                                </p>
                                <span class="tl-pengadu">
                                    <i class="far fa-user"></i> {{ $item->nama_pengadu }}
                                </span>
                            </div>
                            <div class="tl-badges">
                                <span class="badge-status {{ $statusClass }}">{{ $item->status }}</span>
                                <span class="tl-time">
                                    <i class="far fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Jakarta')->format('H:i') }}
                                </span>
                            </div>
                        </div>

                        @if($item->deskripsi_masalah)
                        <div class="tl-meta" style="margin-bottom:8px;margin-top:0;">
                            <div class="tl-meta-item">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ Str::limit($item->deskripsi_masalah, 80) }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="tl-divider"></div>

                        <div class="tl-kondisi">
                            {{ $item->kondisi ?? '-' }}
                        </div>

                        <div class="tl-meta">
                            <div class="tl-meta-item">
                                <i class="fas fa-user-cog"></i>
                                <span>{{ $item->nama_teknisi ?? '-' }}</span>
                            </div>
                            <div class="tl-meta-item">
                                <i class="fas fa-hashtag"></i>
                                <span>Tindakan #{{ $item->id }}</span>
                            </div>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection