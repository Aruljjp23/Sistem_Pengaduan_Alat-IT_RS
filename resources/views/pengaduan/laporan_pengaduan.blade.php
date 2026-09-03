@extends('layout.page')

@section('page_title', 'Laporan Pengaduan')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    :root {
        --primary: #0d8a45;
        --primary-dark: #086b35;
        --primary-soft: #eaf8f0;

        --blue: #0ea5e9;
        --blue-soft: #e0f2fe;

        --text-main: #1e293b;
        --text-muted: #64748b;

        --border: #e2e8f0;
        --background: #f8fafc;

        --radius: 14px;
    }

    .laporan-wrapper {
        font-family: 'Inter', sans-serif;
        color: var(--text-main);
    }

    .laporan-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }

    .laporan-title {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .laporan-subtitle {
        margin-top: 5px;
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .btn-modern {
        border-radius: 10px;
        padding: 10px 18px;
        font-weight: 600;
        transition: all .2s ease;
    }

    .btn-pdf {
        background: #fff;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .btn-pdf:hover {
        background: #fef2f2;
        color: #b91c1c;
        transform: translateY(-1px);
    }

    .btn-filter {
        background: var(--primary);
        color: white;
        border: none;
    }

    .btn-filter:hover {
        background: var(--primary-dark);
        color: white;
    }

    .btn-reset {
        background: white;
        border: 1px solid var(--border);
        color: var(--text-main);
    }

    .btn-reset:hover {
        background: #f1f5f9;
    }

    .filter-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 22px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .03);
    }

    .filter-label {
        display: block;
        margin-bottom: 7px;
        font-size: .82rem;
        font-weight: 600;
        color: var(--text-muted);
    }

    .form-modern {
        height: 48px;
        border-radius: 10px;
        border: 1px solid var(--border);
        font-size: .92rem;
    }

    .form-modern:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(13, 138, 69, .10);
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--text-main);
    }

    .rekap-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 18px;
        height: 100%;
        transition: all .2s ease;
    }

    .rekap-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, .06);
    }

    .rekap-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: var(--primary-soft);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .rekap-title {
        font-size: .82rem;
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .rekap-total {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .table-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .03);
    }

    .modern-table {
        margin-bottom: 0;
    }

    .modern-table thead {
        background: var(--primary);
    }

    .modern-table thead th {
        color: white;
        border: none;
        padding: 16px;
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .05em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 17px 16px;
        vertical-align: middle;
        border-color: var(--border);
    }

    .modern-table tbody tr {
        transition: background .2s ease;
    }

    .modern-table tbody tr:hover {
        background: #f8fafc;
    }

    .badge-ruangan {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary-soft);
        color: var(--primary-dark);
        padding: 5px 9px;
        border-radius: 6px;
        font-size: .72rem;
        font-weight: 600;
    }

    .badge-perangkat {
        color: #2563eb;
        font-size: .78rem;
        font-weight: 600;
        margin-top: 5px;
    }

    .btn-detail {
        background: var(--blue);
        border: none;
        color: white;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: .84rem;
        font-weight: 600;
        transition: all .2s ease;
    }

    .btn-detail:hover {
        background: #0284c7;
        color: white;
        transform: translateY(-1px);
    }

    .pagination-modern {
        display: flex;
        justify-content: center;
        gap: 6px;
        padding: 20px;
    }

    .pagination-modern a {
        min-width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid var(--border);
        color: var(--text-main);
        text-decoration: none;
        background: white;
    }

    .pagination-modern a.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    @media (max-width: 768px) {

        .laporan-header {
            flex-direction: column;
            align-items: flex-start;
        }

    }
</style>


<div class="laporan-wrapper">

    <div class="laporan-header">


        <a href="{{ url('/pengaduan/cetak_pdf') }}?search={{ request('search') }}&created_at={{ request('created_at') }}&id_ruangan={{ request('id_ruangan') }}" target="_blank" class="btn btn-modern btn-pdf">
            <i class="fas fa-file-pdf me-2"></i>
            Cetak PDF
        </a>

    </div>


    <div class="filter-card">

        <form id="formSearch" action="{{ url('/pengaduan/laporan_pengaduan') }}" method="GET">

            <div class="row g-3">

                <div class="col-md-4">

                    <label class="filter-label">
                        Pencarian
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>

                        <input type="text" id="search" name="search" class="form-control form-modern border-start-0" placeholder="Nama, ruangan, perangkat..." value="{{ request('search') }}">
                    </div>

                </div>


                <div class="col-md-3">

                    <label class="filter-label">
                        Filter Bulan
                    </label>

                    <input type="month" id="created_at" name="created_at" class="form-control form-modern" value="{{ request('created_at') }}">

                </div>


                <div class="col-md-3">

                    <label class="filter-label">
                        Filter Ruangan
                    </label>

                    <select id="id_ruangan" name="id_ruangan" class="form-select form-modern">

                        <option value="">
                            Semua Ruangan
                        </option>

                        @foreach($ruangan as $itemRuangan)

                            <option value="{{ $itemRuangan->id_ruangan }}" {{ request('id_ruangan') == $itemRuangan->id_ruangan ? 'selected' : '' }}>
                                {{ $itemRuangan->nama_ruangan }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-2 d-flex align-items-end">

                    <a href="{{ url('/pengaduan/laporan_pengaduan') }}" class="btn btn-modern btn-reset w-100 text-center">
                        <i class="fas fa-rotate-left me-1"></i>
                        Reset
                    </a>

                </div>
            </div>
        </form>
    </div>


    @if($rekap_ruangan->count() > 0)

        <div class="mb-4">

            <div class="section-title">
                <i class="fas fa-building me-2 text-success"></i>
                Rekap Pengaduan Berdasarkan Ruangan
            </div>

            <div class="row g-3">

                @foreach($rekap_ruangan as $rekap)

                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <div class="rekap-card">

                            <div class="d-flex align-items-center gap-3">

                                <div class="rekap-icon">
                                    <i class="fas fa-door-open"></i>
                                </div>

                                <div>

                                    <div class="rekap-title">
                                        {{ $rekap->nama_ruangan }}
                                    </div>

                                    <div class="rekap-total">

                                        {{ $rekap->jumlah_pengaduan }}

                                        <span style="font-size: .8rem; font-weight: 500;">
                                            Pengaduan
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif


    <div class="table-card">

        <div class="table-responsive">

            <table class="table modern-table">

                <thead>

                    <tr>
                        <th class="text-center">No</th>
                        <th>Tanggal</th>
                        <th>Pengadu</th>
                        <th>Ruangan & Perangkat</th>
                        <th>Deskripsi Masalah</th>
                        <th class="text-center">Aksi</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($pengaduan as $index => $item)

                        <tr>

                            <td class="text-center text-muted fw-semibold">

                                {{ $pengaduan->firstItem() + $index }}

                            </td>


                            <td>

                                <div class="fw-semibold">

                                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}

                                </div>

                                <small class="text-muted">

                                    <i class="far fa-clock me-1"></i>

                                    {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                    WIB

                                </small>

                            </td>


                            <td>

                                <div class="fw-semibold">

                                    <i class="fas fa-user text-muted me-1"></i>

                                    {{ $item->nama_pengadu }}

                                </div>

                            </td>


                            <td>

                                <div class="fw-bold">

                                    {{ $item->nama_ruangan }}

                                </div>

                                <div class="badge-ruangan mt-1">

                                    <i class="fas fa-location-dot"></i>

                                    {{ $item->lokasi ?? '-' }}

                                </div>


                                <div class="badge-perangkat">

                                    @if($item->kode_inventaris)

                                        {{ $item->kode_inventaris }} -

                                    @endif

                                    {{ $item->kategori_perangkat ?? 'Fasilitas Umum' }}

                                </div>

                            </td>


                            <td>

                                <div
                                    class="text-secondary"
                                    style="max-width: 260px;"
                                >

                                    {{ \Illuminate\Support\Str::limit($item->deskripsi_masalah, 100) }}

                                </div>

                            </td>


                            <td class="text-center">

                                <button
                                    type="button"
                                    class="btn-detail"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailModal{{ $item->pengaduan_id }}"
                                >

                                    <i class="fas fa-circle-info me-1"></i>

                                    Detail

                                </button>

                            </td>

                        </tr>


                        <div
                            class="modal fade"
                            id="detailModal{{ $item->pengaduan_id }}"
                            tabindex="-1"
                        >

                            <div class="modal-dialog modal-dialog-centered">

                                <div class="modal-content border-0 shadow">

                                    <div class="modal-header bg-success text-white">

                                        <h5 class="modal-title">

                                            <i class="fas fa-clipboard-check me-2"></i>

                                            Detail Penanganan Pengaduan

                                        </h5>

                                        <button
                                            type="button"
                                            class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"
                                        ></button>

                                    </div>


                                    <div class="modal-body p-4">

                                        <div class="mb-4">

                                            <small class="text-muted fw-semibold">
                                                DATA RUANGAN
                                            </small>

                                            <div class="mt-2 fw-semibold">

                                                <i class="fas fa-building text-success me-2"></i>

                                                {{ $item->nama_ruangan }}

                                            </div>

                                            <div class="text-muted small">

                                                {{ $item->lokasi ?? '-' }}

                                            </div>

                                        </div>


                                        <div class="mb-4">

                                            <small class="text-muted fw-semibold">
                                                TEKNISI
                                            </small>

                                            <div class="mt-2 fw-semibold">

                                                <i class="fas fa-user-gear me-2 text-primary"></i>

                                                {{ $item->teknisi ?? '-' }}

                                            </div>

                                        </div>


                                        <div>

                                            <small class="text-muted fw-semibold">
                                                TINDAKAN / SOLUSI
                                            </small>

                                            <div
                                                class="bg-light rounded-3 p-3 mt-2 text-secondary"
                                                style="white-space: pre-line;"
                                            >

                                                {{ $item->deskripsi_tindakan ?? 'Tidak ada deskripsi tindakan.' }}

                                            </div>

                                        </div>

                                    </div>


                                    <div class="modal-footer">

                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal"
                                        >
                                            Tutup
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5"
                            >

                                <i class="fas fa-folder-open fa-3x text-muted opacity-25 mb-3"></i>

                                <div class="text-muted">
                                    Data laporan belum ditemukan
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($pengaduan->lastPage() > 1)

            <div class="pagination-modern">

                @for($i = 1; $i <= $pengaduan->lastPage(); $i++)

                    <a
                        href="{{ $pengaduan->appends(request()->query())->url($i) }}"
                        class="{{ $pengaduan->currentPage() == $i ? 'active' : '' }}"
                    >

                        {{ $i }}
                    </a>

                @endfor

            </div>

        @endif

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const formSearch = document.getElementById('formSearch');
        const searchInput = document.getElementById('search');
        const monthInput = document.getElementById('created_at');
        const ruanganInput = document.getElementById('id_ruangan');

        let timeout;

        searchInput.addEventListener('input', function () {

            clearTimeout(timeout);

            timeout = setTimeout(function () {

                formSearch.submit();

            }, 700);

        });


        monthInput.addEventListener('change', function () {

            formSearch.submit();

        });


        ruanganInput.addEventListener('change', function () {

            formSearch.submit();

        });

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