@extends('layout.page')

@section('page_title', 'Laporan Pengaduan')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --brand-primary: #0d934a;
        --brand-dark: #096e37;
        --brand-success: #10b981;
        --brand-info: #0ea5e9;
        --surface-color: #ffffff;
        --bg-soft: #f8fafc;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-soft: #e2e8f0;
        --radius: 12px;
        --shadow-subtle: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
    }

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-soft); color: var(--text-main); }

    .report-container {
        background: var(--surface-color);
        border-radius: var(--radius);
        border: 1px solid var(--border-soft);
        box-shadow: var(--shadow-subtle);
        overflow: hidden;
    }

    .modern-table { margin-bottom: 0; }
    .modern-table thead {
        background-color: var(--brand-primary);
        border-bottom: 2px solid var(--border-soft);
    }
    .modern-table thead th {
        color: white !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 16px;
        border: none;
    }
    .modern-table tbody tr { transition: all 0.2s; border-bottom: 1px solid var(--border-soft); }
    .modern-table tbody tr:hover { background-color: #f8fafc; }
    .modern-table td { padding: 16px; vertical-align: middle; }

    .filter-panel {
        background: var(--surface-color);
        border-radius: var(--radius);
        border: 1px solid var(--border-soft);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-control-custom {
        border: 1.5px solid var(--border-soft);
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .form-control-custom:focus {
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    .btn-brand {
        background: var(--brand-primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        padding: 0.6rem 1.25rem;
        transition: all 0.2s;
    }
    .btn-brand:hover { background: var(--brand-dark); color: white; }

    .btn-outline-custom {
        border: 1.5px solid var(--border-soft);
        background: white;
        color: var(--text-main);
        border-radius: 8px;
        padding: 0.6rem 1.25rem;
    }
    .btn-outline-custom:hover { background: #f1f5f9; border-color: var(--text-muted); }

    .custom-pagination {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 6px;
        margin-top: 15px;
    }

    .page-btn {
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid var(--border-soft);
        text-decoration: none;
        color: var(--text-main);
        font-size: 0.85rem;
        transition: 0.2s;
        background: white;
    }

    .page-btn:hover { background: #f1f5f9; }

    .page-btn.active {
        background: var(--brand-primary);
        color: white;
        border-color: var(--brand-primary);
    }

    /* Styling tombol detail */
    .btn-detail {
        background: var(--brand-info);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        transition: 0.2s;
    }
    .btn-detail:hover { background: #0284c7; color: white; transform: translateY(-1px); }

    @media print {
        .no-print { display: none !important; }
        .report-container { border: none; box-shadow: none; }
    }

    @media (max-width: 768px) {
        .filter-panel .row > div { margin-bottom: 10px; }
    }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 no-print">
    <div class="d-flex gap-2">
        <a href="{{ url('/pengaduan/cetak_pdf') }}?search={{ request('search') }}&created_at={{ request('created_at') }}" 
           target="_blank" class="btn btn-outline-danger shadow-sm px-3">
            <i class="fas fa-file-pdf me-2"></i> PDF
        </a>
        
        <a href="{{ url('/pengaduan/export_excel') }}?search={{ request('search') }}&created_at={{ request('created_at') }}" 
           class="btn btn-success shadow-sm px-3">
            <i class="fas fa-file-excel me-2"></i> Excel
        </a>
    </div>
</div>

<div class="filter-panel no-print">
    <form id="formSearch" action="{{ url('/pengaduan/laporan_pengaduan') }}" method="GET">
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label fw-semibold small text-muted">KATA KUNCI</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                    <input type="text" id="search" class="form-control form-control-custom border-start-0 ps-0" name="search" placeholder="Nama, ruangan, deskripsi, tindakan..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted">FILTRASI BULANAN</label>
                <input type="month" id="created_at" class="form-control form-control-custom" name="created_at" value="{{ request('created_at') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-brand flex-grow-1">Terapkan Filter</button>
                <a href="{{ url('/pengaduan/laporan_pengaduan') }}" class="btn btn-outline-custom text-nowrap">Reset</a>
            </div>
        </div>
    </form>
</div>

<div class="report-container">
    <div class="table-responsive">
        <table class="table modern-table align-middle" id="tabelLaporan">
            <thead>
                <tr>
                    <th class="text-center" width="60">No</th>
                    <th width="150">Waktu</th>
                    <th width="150">Pelapor</th>
                    <th width="200">Ruangan & Perangkat</th>
                    <th width="200">Masalah</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengaduan as $index => $item)
                <tr>
                    <td class="text-center text-muted fw-medium">{{ $pengaduan->firstItem() + $index }}</td>
                    <td>
                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}</div>
                        <div class="text-muted small">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</div>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $item->nama_pengadu }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $item->nama_ruangan }}</div>
                        <div class="text-primary small fw-medium" style="font-size: 0.75rem;">
                            {{ $item->kode_inventaris ? $item->kode_inventaris . ' - ' : '' }}{{ $item->kategori_perangkat ?? 'Fasilitas Umum' }}
                        </div>
                    </td>
                    <td class="text-secondary small">
                        {{ $item->deskripsi_masalah }}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn-detail" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->pengaduan_id }}">
                            <i class="fas fa-info-circle me-1"></i> Detail
                        </button>
                        <span class="d-none text-excel-tindakan">{{ $item->deskripsi_tindakan }}</span>
                    </td>
                </tr>

                <div class="modal fade" id="detailModal{{ $item->pengaduan_id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->pengaduan_id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title fs-6 fw-bold" id="detailModalLabel{{ $item->pengaduan_id }}">
                                    <i class="fas fa-clipboard-check me-2"></i> Detail Tindakan
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold mb-1">Status Pengerjaan</label>
                                    <div><span class="badge bg-success">Selesai</span></div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small fw-bold mb-1">Dikerjakan Oleh (Teknisi)</label>
                                    <div class="fw-semibold text-dark"><i class="fas fa-user-cog text-muted me-2"></i>{{ $item->teknisi }}</div>
                                </div>
                                <div class="mb-0">
                                    <label class="text-muted small fw-bold mb-1">Deskripsi Tindakan / Solusi</label>
                                    <div class="p-3 bg-light rounded text-secondary" style="font-size: 0.9rem; white-space: pre-line;">
                                        {{ $item->deskripsi_tindakan ?? 'Tidak ada deskripsi yang dilampirkan.' }}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 bg-light">
                                <button type="button" class="btn btn-secondary shadow-sm" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="py-4">
                            <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                            <h5 class="text-muted fw-normal">Data tidak ditemukan dalam database</h5>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($pengaduan->lastPage() > 1)
        <div class="custom-pagination d-flex justify-content-center gap-2 py-3 no-print">
            @for ($i = 1; $i <= $pengaduan->lastPage(); $i++)
                <a href="{{ $pengaduan->appends(request()->query())->url($i) }}"
                class="page-btn {{ $pengaduan->currentPage() == $i ? 'active' : '' }}">
                    {{ $i }}
                </a>
            @endfor
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formSearch = document.getElementById('formSearch');
        const searchInput = document.getElementById('search');
        const monthInput = document.getElementById('created_at');
        let timeout;

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    formSearch.submit();
                }, 800); 
            });
        }

        if (monthInput) {
            monthInput.addEventListener('change', function () {
                formSearch.submit();
            });
        }
    });

    function eksporExcel() {
        const headers = [['NO', 'TANGGAL & WAKTU', 'NAMA PENGADU', 'UNIT / RUANGAN', 'KATEGORI PERANGKAT', 'DESKRIPSI MASALAH', 'TEKNISI', 'TINDAKAN / SOLUSI']];
        const dataRows = [];

        document.querySelectorAll('#tabelLaporan tbody tr').forEach((tr, index) => {
            const cols = tr.querySelectorAll('td');
            if (cols.length > 1) { 
                const created_atText = cols[1].querySelectorAll('div')[0].innerText.trim();
                const jamText = cols[1].querySelectorAll('div')[1].innerText.trim();
                const waktuLengkap = `${created_atText} (${jamText})`;

                const ruanganText = cols[3].querySelectorAll('div')[0].innerText.trim();
                const perangkatText = cols[3].querySelectorAll('div')[1].innerText.trim();

                const tindakanText = cols[6].querySelector('.text-excel-tindakan').innerText.trim();

                dataRows.push([
                    index + 1,
                    waktuLengkap,
                    cols[2].innerText.trim(),
                    ruanganText,
                    perangkatText,
                    cols[4].innerText.trim(),
                    cols[5].innerText.trim(),
                    tindakanText 
                ]);
            }
        });

        if (dataRows.length === 0) {
            alert('Tidak ada data yang bisa diekspor ke Excel!');
            return;
        }

        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet([...headers, ...dataRows]);

        const headerRange = XLSX.utils.decode_range(ws['!ref']);
        for (let C = headerRange.s.c; C <= headerRange.e.c; ++C) {
            const cellAddress = XLSX.utils.encode_cell({r: 0, c: C});
            if (!ws[cellAddress]) continue;
            ws[cellAddress].s = {
                fill: { fgColor: { rgb: "0D934A" } },
                font: { bold: true, color: { rgb: "FFFFFF" }, sz: 11, name: "Segoe UI" },
                alignment: { horizontal: "center", vertical: "center", wrapText: true },
                border: {
                    bottom: { style: "medium", color: { rgb: "096E37" } },
                    right: { style: "thin", color: { rgb: "10B981" } }
                }
            };
        }

        for (let R = 1; R <= headerRange.e.r; ++R) {
            for (let C = headerRange.s.c; C <= headerRange.e.c; ++C) {
                const cellAddress = XLSX.utils.encode_cell({r: R, c: C});
                if (!ws[cellAddress]) continue;
                
                const alignmentStyle = (C === 0 || C === 1) ? "center" : "left";
                
                ws[cellAddress].s = {
                    font: { sz: 10, name: "Segoe UI" },
                    alignment: { horizontal: alignmentStyle, vertical: "center", wrapText: true },
                    border: {
                        bottom: { style: "thin", color: { rgb: "E2E8F0" } },
                        right: { style: "thin", color: { rgb: "E2E8F0" } }
                    }
                };
            }
        }

        ws['!cols'] = [
            { wch: 6 },  
            { wch: 22 }, 
            { wch: 20 }, 
            { wch: 22 }, 
            { wch: 28 }, 
            { wch: 30 }, 
            { wch: 18 }, 
            { wch: 35 }  
        ];
        
        XLSX.utils.book_append_sheet(wb, ws, "Arsip Pengaduan Selesai");
        XLSX.writeFile(wb, `Laporan_Pengaduan_Selesai_${new Date().toISOString().split('T')[0]}.xlsx`);
    }
</script>

@endsection