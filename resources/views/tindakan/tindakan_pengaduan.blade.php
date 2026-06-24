@extends('layout.page')

@section('page_title', 'Status Tindakan')

@section('content')

<style>
    :root {
        --primary: #4f46e5;
        --primary-soft: #e0e7ff;
        --success: #10b981;
        --success-soft: #d1fae5;
        --warning: #f59e0b;
        --warning-soft: #fef3c7;
        
        --surface: #ffffff;
        --background: #f8fafc;
        --border-light: #e2e8f0;
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --text-light: #94a3b8;

        --radius-md: 12px;
        --radius-lg: 16px;
        --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .status-container {
        max-width: 850px;
        margin: 0 auto;
    }

    .status-header {
        background: var(--surface);
        padding: 24px;
        border-radius: var(--radius-lg);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.01);
        margin-bottom: 24px;
        border: 1px solid var(--border-light);
    }

    .filter-pills {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
        margin-top: 20px;
    }
    .filter-pills::-webkit-scrollbar { display: none; }

    .btn-filter {
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        transition: var(--transition);
        border: 1px solid var(--border-light);
        background: var(--surface);
        color: var(--text-muted);
        white-space: nowrap;
        text-decoration: none;
    }
    .btn-filter:hover {
        border-color: var(--text-dark);
        color: var(--text-dark);
    }
    .btn-filter.active {
        background: var(--text-dark) !important;
        color: #ffffff !important;
        border-color: var(--text-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);
    }

    .action-card {
        background: var(--surface);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    .action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        border-color: #cbd5e1;
    }

    .action-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
    }
    .border-menunggu::before { background: #6c757d; }
    .border-diterima::before { background: #1c87e4; }
    .border-pending::before { background: var(--warning); }
    .border-proses::before { background: var(--primary); }
    .border-selesai::before { background: var(--success); }

    .info-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-light);
        letter-spacing: 0.05em;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .status-badge-modern {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        letter-spacing: 0.02em;
    }
    .badge-menunggu { background: #e9ecef; color: #495057; border: 1px solid #ced4da; }
    .badge-diterima { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .badge-pending { background: var(--warning-soft); color: #b40909; border: 1px solid #fde68a; }
    .badge-proses { background: var(--primary-soft); color: #d8d21d; border: 1px solid #bfdbfe; }
    .badge-selesai { background: var(--success-soft); color: #047857; border: 1px solid #a7f3d0; }

    .content-box {
        background: var(--background);
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.6;
    }
    .content-box.tindakan {
        background: var(--success-soft);
        border-color: #a7f3d0;
        color: #065f46;
        font-weight: 500;
    }

    .device-list-box {
        background: var(--surface);
        border-radius: 8px;
        padding: 8px 12px;
        border: 1px solid var(--border-light);
        display: inline-flex;
        align-items: center;
        font-size: 12px;
        font-weight: 600;
        color: var(--text-dark);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 2px dashed var(--border-light);
    }

    #tindakan-list {
        transition: opacity 0.3s ease-in-out;
    }
    #tindakan-list.loading {
        opacity: 0.6;
    }
</style>

<div class="status-container py-4">

    <div class="status-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1" style="font-size: 20px; letter-spacing: -0.02em;">Status Tindakan</h4>
                <p class="text-muted small mb-0 fw-medium">Memantau progres perbaikan oleh tim teknis</p>
            </div>
            <div class="text-md-end">
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-sm" style="font-size: 13px; font-weight: 600;">
                    <i class="fa-solid fa-user me-2 text-primary"></i>{{ $namaPengadu }}
                </span>
            </div>
        </div>

        <div class="filter-pills">
            @php
                $filters = [
                    'semua' => 'Semua Laporan',
                    'menunggu' => 'Menunggu',
                    'diterima' => 'Diterima',
                    'pending' => 'Tertunda',
                    'proses' => 'Diproses',
                    'selesai' => 'Selesai'
                ];
            @endphp
            @foreach($filters as $val => $label)
            <a href="{{ route('tindakan.tindakan_pengaduan', ['status' => $val]) }}"
               class="btn-filter {{ $statusFilter === $val ? 'active' : '' }}">
                 {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    <div id="tindakan-list">
        @forelse($tindakans as $item)
            @php
                $statusStr = $item->status ?? 'Dipending'; 
                $statusClass = 'pending';
                $badgeClass = 'badge-pending';

                if ($statusStr === 'Diproses') {
                    $statusClass = 'proses';
                    $badgeClass = 'badge-proses';

                } elseif ($statusStr === 'Selesai') {
                    $statusClass = 'selesai';
                    $badgeClass = 'badge-selesai';

                } elseif ($statusStr === 'Menunggu') {
                    $statusClass = 'menunggu';
                    $badgeClass = 'badge-menunggu';

                } elseif ($statusStr === 'Diterima') {
                    $statusClass = 'diterima';
                    $badgeClass = 'badge-diterima';

                } elseif ($statusStr === 'Dipending' || $statusStr === 'Pending') {
                    $statusClass = 'pending';
                    $badgeClass = 'badge-pending';
                }
            @endphp

            <div class="action-card mb-4 border-{{ $statusClass }}">
                <div class="card-body p-4">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="text-muted" style="font-size: 12px; font-weight: 600;">#{{ $item->id ?? 'DOC' }}</div>
                        <span class="status-badge-modern {{ $badgeClass }}">
                            <i class="fa-solid fa-circle-dot small"></i> {{ $statusStr }}
                        </span>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6 col-12">
                            <div class="info-label">Ruangan</div>
                            <div class="info-value">
                                <i class="fa-solid fa-door-open text-primary opacity-75"></i> 
                                {{ $item->nama_ruangan }}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="info-label">Lokasi Detail</div>
                            <div class="info-value">
                                <i class="fa-solid fa-location-dot text-danger opacity-75"></i> 
                                {{ $item->lokasi }}
                            </div>
                        </div>
                    </div>

                    @if($item->perangkat_list && $item->perangkat_list !== '-')
                    <div class="mb-4">
                        <div class="info-label">Inventaris Perangkat</div>
                        <div class="device-list-box">
                            <i class="fa-solid fa-desktop me-2 text-primary opacity-75"></i>
                            {{ $item->perangkat_list }}
                        </div>
                    </div>
                    @endif

                    <div class="mb-4">
                        <div class="info-label">Deskripsi Masalah</div>
                        <div class="content-box">
                            {{ $item->deskripsi_masalah }}
                        </div>
                    </div>

                    @if(isset($item->deskripsi_tindakan) && $item->deskripsi_tindakan !== '-')
                    <div class="mb-4">
                        <div class="info-label">Deskripsi Tindakan</div>
                        <div class="content-box tindakan">
                            <i class="fa-solid fa-check-circle me-2 opacity-75"></i>
                            {{ $item->deskripsi_tindakan }}
                        </div>
                    </div>
                    @endif

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pt-4 mt-2 border-top gap-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle me-3" style="width: 40px; height: 40px; background: var(--background); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-light);">
                                <i class="fa-solid fa-user-gear text-primary"></i>
                            </div>
                            <div>
                                <div class="info-label" style="margin-bottom: 2px;">Teknisi</div>
                                <div class="fw-bold text-dark" style="font-size: 13px;">{{ $item->teknisi ?? 'Menunggu Teknisi' }}</div>
                            </div>
                        </div>
                        <div class="text-md-end text-start">
                            <div class="info-label" style="margin-bottom: 2px;">Pembaruan Terakhir</div>
                            <div class="text-muted fw-medium" style="font-size: 12px;">
                                <i class="fa-solid fa-clock me-1 opacity-50"></i>
                                {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') : '-' }} WIB
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fa-solid fa-folder-open text-muted opacity-25 mb-3" style="font-size: 48px;"></i>
                <h5 class="text-dark fw-bold mb-1" style="font-size: 16px;">Tidak ada histori tindakan ditemukan</h5>
                <p class="text-muted small m-0">Silakan pilih kategori status lain atau belum ada data masuk.</p>
            </div>
        @endforelse
    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const listContainer = document.getElementById('tindakan-list');
    let autoRefreshInterval;

    if (!listContainer) return;

    const fetchUpdates = async (url, isFilterClick = false) => {
        if(isFilterClick) listContainer.classList.add('loading');

        try {
            const targetUrl = new URL(url, window.location.origin);
            targetUrl.searchParams.set('_t', new Date().getTime());

            const response = await fetch(targetUrl.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Cache-Control': 'no-cache'
                }
            });
            
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContentDiv = doc.getElementById('tindakan-list');
            
            if (newContentDiv) {
                listContainer.innerHTML = newContentDiv.innerHTML;
            }
            
        } catch (error) {
            console.error('Terjadi kesalahan fetch AJAX:', error);
        } finally {
            if(isFilterClick) listContainer.classList.remove('loading');
        }
    };

    document.querySelectorAll('.btn-filter').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const targetUrl = this.href;

            document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            window.history.pushState({path: targetUrl}, '', targetUrl);
            fetchUpdates(targetUrl, true);
        });
    });

    const startAutoRefresh = () => {
        autoRefreshInterval = setInterval(() => {
            fetchUpdates(window.location.href, false);
        }, 5000); 
    };

    startAutoRefresh();
});
</script>
@endsection