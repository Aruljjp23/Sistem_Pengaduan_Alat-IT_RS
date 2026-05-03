@extends('layout.page')

@section('page_title', 'Laporan Pengaduan')

@section('content')

<style>
    :root {
        --primary-orange: #ff8c00;
        --primary-dark: #e67e00;
        --soft-orange: #fff5e6;
        --border-color: #e0e4ea;
        --text-muted: #6c757d;
    }

    .report-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .table thead {
        background-color: var(--primary-orange);
        color: white;
    }

    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border: none;
        padding: 15px;
    }

    .modal-header-detail {
        background: linear-gradient(45deg, var(--primary-orange), var(--primary-dark));
        color: white;
        border-bottom: none;
    }

    .detail-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .detail-label {
        font-weight: 700;
        color: #444;
        width: 140px;
        flex-shrink: 0;
    }

    .badge-selesai {
        background-color: #d1fae5;
        color: #065f46;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
    }

    @media print {
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        .report-card { box-shadow: none; border: none; }
        body { background: white; padding: 0; }
    }
    .print-only { display: none; }
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 no-print">
    <div>
        <h4 class="fw-bold mb-1">Daftar Laporan</h4>
        <p class="text-muted small mb-0">Kelola dan ekspor data pengaduan yang telah selesai.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ url('/pengaduan/cetak_pdf') }}?search={{ request('search') }}&tanggal={{ request('tanggal') }}" 
           target="_blank" class="btn btn-outline-danger shadow-sm">
            <i class="fas fa-file-pdf me-2"></i> PDF
        </a>
        <button onclick="eksporExcel()" class="btn btn-success shadow-sm">
            <i class="fas fa-file-excel me-2"></i> Excel
        </button>
    </div>
</div>

<div class="report-card no-print p-4">
    <form action="{{ url('/pengaduan/laporan_pengaduan') }}" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-bold small text-muted">CARI INFORMASI</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" name="search"
                        placeholder="Nama pengadu, ruangan, lokasi..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-muted">FILTER TANGGAL</label>
                <input type="date" class="form-control" name="tanggal" value="{{ request('tanggal') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Filter Data</button>
                <a href="{{ url('/pengaduan/laporan_pengaduan') }}" class="btn btn-light border">Reset</a>
            </div>
        </div>
    </form>
</div>

{{-- Print Header --}}
<div class="print-only text-center mb-5">
    <h2 class="fw-bold">LAPORAN DATA PENGADUAN</h2>
    <p>Periode laporan: {{ request('tanggal') ? \Carbon\Carbon::parse(request('tanggal'))->format('d/m/Y') : 'Semua Data' }}</p>
    <hr>
</div>

<div class="report-card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tabelLaporan">
            <thead>
                <tr>
                    <th class="text-center" width="60">No</th>
                    <th>Tanggal</th>
                    <th>Nama Pengadu</th>
                    <th>Informasi Ruangan</th>
                    <th>Lokasi</th>
                    <th class="text-center">Status</th>
                    <th class="text-center no-print" width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengaduan as $index => $item)
                <tr>
                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                    <td>
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</div>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($item->tanggal)->format('H:i') }} WIB</small>
                    </td>
                    <td>{{ $item->nama_pengadu }}</td>
                    <td>
                        <span class="d-block fw-bold text-primary">{{ $item->nama_ruangan }}</span>
                        <small class="text-muted">{{ $item->kode_perangkat ?? '-' }}</small>
                    </td>
                    <td><i class="fas fa-map-marker-alt text-danger me-1 small"></i>{{ $item->lokasi }}</td>
                    <td class="text-center">
                        <span class="badge-selesai">Selesai</span>
                    </td>
                    <td class="text-center no-print">
                        <button class="btn btn-sm btn-light border shadow-sm px-3"
                            data-bs-toggle="modal" data-bs-target="#modalDetail"
                            data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_tindakan)->translatedFormat('d F Y') }}"
                            data-pengadu="{{ $item->nama_pengadu }}"
                            data-ruangan="{{ $item->nama_ruangan }}"
                            data-kondisi="{{ $item->kondisi }}"
                            data-teknisi="{{ $item->teknisi }}">
                            <i class="fa fa-eye me-1"></i> Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3">
                        <p class="text-muted fw-bold">Ups! Data tidak ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header modal-header-detail">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-info-circle me-2"></i> Detail Penanganan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="detail-row">
                    <span class="detail-label">Pengadu</span>
                    <span id="detail-pengadu"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Ruangan</span>
                    <span id="detail-ruangan"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tgl. Selesai</span>
                    <span id="detail-tanggal" class="text-success fw-bold"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tindakan</span>
                    <span id="detail-kondisi" class="fst-italic text-muted"></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Teknisi</span>
                    <span class="badge bg-warning" id="detail-teknisi"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
<script>
    const modalDetail = document.getElementById('modalDetail');
    modalDetail.addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        const fields = ['pengadu', 'ruangan', 'tanggal', 'kondisi', 'teknisi'];
        
        fields.forEach(field => {
            document.getElementById(`detail-${field}`).textContent = btn.getAttribute(`data-${field}`) || '-';
        });
    });

    function eksporExcel() {
        const headers = ['No', 'Tanggal', 'Pengadu', 'Ruangan', 'Lokasi', 'Status'];
        const dataRows = [];

        document.querySelectorAll('#tabelLaporan tbody tr').forEach(tr => {
            const cols = tr.querySelectorAll('td');
            if (cols.length > 1) {
                dataRows.push([
                    cols[0].innerText.trim(),
                    cols[1].querySelector('.fw-bold').innerText.trim(),
                    cols[2].innerText.trim(),
                    cols[3].querySelector('.fw-bold').innerText.trim(),
                    cols[4].innerText.trim(),
                    'SELESAI'
                ]);
            }
        });

        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet([headers, ...dataRows]);

        // Style the Header
        const range = XLSX.utils.decode_range(ws['!ref']);
        for (let C = range.s.c; C <= range.e.c; ++C) {
            const address = XLSX.utils.encode_cell({r: 0, c: C});
            if (!ws[address]) continue;
            ws[address].s = {
                fill: { fgColor: { rgb: "FF8C00" } },
                font: { bold: true, color: { rgb: "FFFFFF" } },
                alignment: { horizontal: "center" },
                border: { top: {style: "thin"}, bottom: {style: "thin"}, left: {style: "thin"}, right: {style: "thin"} }
            };
        }

        ws['!cols'] = [{wch:5}, {wch:15}, {wch:25}, {wch:25}, {wch:20}, {wch:15}];
        
        XLSX.utils.book_append_sheet(wb, ws, "Laporan");
        XLSX.writeFile(wb, `Laporan_Pengaduan_${new Date().toLocaleDateString('id-ID')}.xlsx`);
    }
</script>

@endsection