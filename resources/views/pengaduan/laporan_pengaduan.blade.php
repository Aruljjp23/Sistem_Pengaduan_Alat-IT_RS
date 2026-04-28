@extends('layout.page')

@section('page_title', 'Laporan Pengaduan')

@section('content')

<style>
    .th-lp th {
        background-color: darkorange;
        color: white;
    }
    .modal-header-detail {
        background-color: darkorange;
        color: white;
    }
    .detail-label {
        font-weight: 600;
        color: #555;
        width: 120px;
        display: inline-block;
    }

    @media print {
        .no-print { display: none !important; }
        .print-header { display: block !important; }
        body { font-size: 12px; }
    }
    .print-header { display: none; }
</style>

@if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Form Filter --}}
<form action="{{ url('/pengaduan/laporan_pengaduan') }}" method="GET" class="mb-3 no-print">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Cari</label>
            <input type="text" class="form-control" name="search"
                placeholder="Nama pengadu, ruangan, lokasi..."
                value="{{ request('search') }}"
                id="inputSearch"
                autocomplete="off">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control" name="tanggal"
                value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search"></i> Cari
            </button>
            <a href="{{ url('/pengaduan/laporan_pengaduan') }}" class="btn btn-secondary w-100">
                <i class="fas fa-times-circle"></i> Reset
            </a>
        </div>
    </div>
</form>

<div class="d-flex gap-2 mb-3 no-print">
    <a href="{{ url('/pengaduan/cetak_pdf') }}?search={{ request('search') }}&tanggal={{ request('tanggal') }}" target="_blank" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Cetak PDF
    </a>
    <button onclick="eksporExcel()" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Export Excel
    </button>
</div>

<div class="print-header text-center mb-3">
    <h4 class="fw-bold">Laporan Pengaduan</h4>
    <p class="mb-0">Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y H:i') }}</p>
    @if(request('search'))
        <p class="mb-0">Filter Pencarian: {{ request('search') }}</p>
    @endif
    @if(request('tanggal'))
        <p class="mb-0">Filter Tanggal: {{ \Carbon\Carbon::parse(request('tanggal'))->locale('id')->translatedFormat('d F Y') }}</p>
    @endif
    <hr>
</div>

<table class="table table-bordered table-hover" id="tabelLaporan">
    <thead class="th-lp">
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Tanggal</th>
            <th class="text-center">Pengadu</th>
            <th class="text-center">Ruangan</th>
            <th class="text-center">Lokasi</th>
            <th class="text-center">Status</th>
            <th class="text-center no-print">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pengaduan as $index => $item)
        <tr class="text-center align-middle">
            <td>{{ $index + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
            <td>{{ $item->nama_pengadu }}</td>
            <td>{{ $item->nama_ruangan }}</td>
            <td>{{ $item->lokasi }}</td>
            <td>
                <span class="badge bg-success text-white">Selesai</span>
            </td>
            <td class="no-print">
                <button
                    class="btn btn-info btn-sm text-white"
                    data-bs-toggle="modal"
                    data-bs-target="#modalDetail"
                    data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal_tindakan)->locale('id')->translatedFormat('d F Y') }}"
                    data-pengadu="{{ $item->nama_pengadu }}"
                    data-ruangan="{{ $item->nama_ruangan }}"
                    data-kondisi="{{ $item->kondisi }}"
                    data-teknisi="{{ $item->teknisi }}"
                >
                    <i class="fa fa-eye"></i> Detail
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted py-4">
                Tidak ada data laporan pengaduan.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-detail">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-list me-2"></i> Detail Tindakan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td><span class="detail-label">Pengadu</span></td>
                        <td>: <span id="detail-pengadu">-</span></td>
                    </tr>
                    <tr>
                        <td><span class="detail-label">Ruangan</span></td>
                        <td>: <span id="detail-ruangan">-</span></td>
                    </tr>
                    <tr>
                        <td><span class="detail-label">Tanggal</span></td>
                        <td>: <span id="detail-tanggal">-</span></td>
                    </tr>
                    <tr>
                        <td><span class="detail-label">Kondisi</span></td>
                        <td>: <span id="detail-kondisi">-</span></td>
                    </tr>
                    <tr>
                        <td><span class="detail-label">Teknisi</span></td>
                        <td>: <span id="detail-teknisi">-</span></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>

<script>
    document.getElementById('modalDetail').addEventListener('show.bs.modal', function (event) {
        const btn = event.relatedTarget;
        document.getElementById('detail-pengadu').textContent = btn.getAttribute('data-pengadu') || '-';
        document.getElementById('detail-ruangan').textContent = btn.getAttribute('data-ruangan') || '-';
        document.getElementById('detail-tanggal').textContent = btn.getAttribute('data-tanggal') || '-';
        document.getElementById('detail-kondisi').textContent = btn.getAttribute('data-kondisi') || '-';
        document.getElementById('detail-teknisi').textContent = btn.getAttribute('data-teknisi') || '-';
    });

    function eksporExcel() {
        const headers = ['No', 'Tanggal', 'Pengadu', 'Ruangan', 'Lokasi', 'Status'];
        const rows = [];

        document.querySelectorAll('#tabelLaporan tbody tr').forEach(tr => {
            const cols = tr.querySelectorAll('td');
            if (cols.length > 1) {
                rows.push([
                    cols[0].innerText.trim(),
                    cols[1].innerText.trim(),
                    cols[2].innerText.trim(),
                    cols[3].innerText.trim(),
                    cols[4].innerText.trim(),
                    'Selesai',
                ]);
            }
        });

        const styleHeader = {
            fill: { fgColor: { rgb: 'FF8C00' } },
            font: { bold: true, color: { rgb: 'FFFFFF' }, sz: 11 },
            alignment: { horizontal: 'center', vertical: 'center', wrapText: true },
            border: {
                top:    { style: 'thin', color: { rgb: '000000' } },
                bottom: { style: 'thin', color: { rgb: '000000' } },
                left:   { style: 'thin', color: { rgb: '000000' } },
                right:  { style: 'thin', color: { rgb: '000000' } },
            }
        };

        const styleGanjil = {
            fill: { fgColor: { rgb: 'FFFFFF' } },
            font: { color: { rgb: '000000' }, sz: 10 },
            alignment: { horizontal: 'center', vertical: 'center' },
            border: {
                top:    { style: 'thin', color: { rgb: 'CCCCCC' } },
                bottom: { style: 'thin', color: { rgb: 'CCCCCC' } },
                left:   { style: 'thin', color: { rgb: 'CCCCCC' } },
                right:  { style: 'thin', color: { rgb: 'CCCCCC' } },
            }
        };

        const styleGenap = {
            fill: { fgColor: { rgb: 'FFE0B2' } },
            font: { color: { rgb: '000000' }, sz: 10 },
            alignment: { horizontal: 'center', vertical: 'center' },
            border: {
                top:    { style: 'thin', color: { rgb: 'CCCCCC' } },
                bottom: { style: 'thin', color: { rgb: 'CCCCCC' } },
                left:   { style: 'thin', color: { rgb: 'CCCCCC' } },
                right:  { style: 'thin', color: { rgb: 'CCCCCC' } },
            }
        };

        const styleSelesai = {
            fill: { fgColor: { rgb: '198754' } },
            font: { bold: true, color: { rgb: 'FFFFFF' }, sz: 10 },
            alignment: { horizontal: 'center', vertical: 'center' },
            border: {
                top:    { style: 'thin', color: { rgb: 'CCCCCC' } },
                bottom: { style: 'thin', color: { rgb: 'CCCCCC' } },
                left:   { style: 'thin', color: { rgb: 'CCCCCC' } },
                right:  { style: 'thin', color: { rgb: 'CCCCCC' } },
            }
        };

        const ws = {};

        // Tulis header
        headers.forEach((h, c) => {
            const cellRef = XLSX.utils.encode_cell({ r: 0, c });
            ws[cellRef] = { v: h, t: 's', s: styleHeader };
        });

        // Tulis data rows
        rows.forEach((row, rowIdx) => {
            const r = rowIdx + 1;
            const isGenap = rowIdx % 2 !== 0;

            row.forEach((val, c) => {
                const cellRef = XLSX.utils.encode_cell({ r, c });

                // Kolom Status pakai style hijau
                if (c === 5) {
                    ws[cellRef] = { v: val, t: 's', s: styleSelesai };
                } else {
                    ws[cellRef] = { v: val, t: 's', s: isGenap ? styleGenap : styleGanjil };
                }
            });
        });

        // Set range worksheet
        ws['!ref'] = XLSX.utils.encode_range({
            s: { r: 0, c: 0 },
            e: { r: rows.length, c: headers.length - 1 }
        });

        // Lebar kolom
        ws['!cols'] = [
            { wch: 5  },  // No
            { wch: 18 },  // Tanggal
            { wch: 25 },  // Pengadu
            { wch: 25 },  // Ruangan
            { wch: 20 },  // Lokasi
            { wch: 12 },  // Status
        ];

        // Tinggi baris header
        ws['!rows'] = [{ hpt: 20 }];

        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Laporan Pengaduan');

        const today = new Date().toISOString().slice(0, 10);
        XLSX.writeFile(wb, `Laporan_Pengaduan_${today}.xlsx`);
    }
</script>

@endsection