@extends('layout.page')

@section('page_title', 'Data Pengaduan')

@section('content')

<style>
    .badge-status { 
        display: inline-block; 
        font-size: .7rem; 
        font-weight: 700; 
        letter-spacing: .04em; 
        text-transform: uppercase; 
        padding: 3px 10px; 
        border-radius: 20px; 
        white-space: nowrap; 
    }
    .badge-status.selesai      { background: #dcfce7; color: #16a34a; } 
    .badge-status.dalam-proses { background: #dbeafe; color: #2563eb; } 
    .badge-status.pending      { background: #fef9c3; color: #ca8a04; } 

 
    .detail-section-title {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #64748b;
        margin: 16px 0 8px;
        padding-bottom: 4px;
        border-bottom: 1px solid #e4e6f0;
    }
    .detail-row {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
        font-size: .875rem;
    }
    .detail-label {
        width: 140px;
        flex-shrink: 0;
        color: #64748b;
        font-weight: 500;
    }
    .detail-value { color: #0f1117; }

    .kondisi-box {
        background: #f8fafc;
        border-left: 3px solid #2563eb;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: .85rem;
        font-style: italic;
        color: #0f1117;
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .row.g-2.align-items-end .col-md-4,
        .row.g-2.align-items-end .col-md-3 { width: 100% !important; max-width: 100% !important; }
        .row.g-2.align-items-end .col-md-3.d-flex { flex-direction: row; gap: 8px; }
        table.table { display: block; overflow-x: auto; -webkit-overflow-scrolling: touch; white-space: nowrap; }
        thead th, tbody td { font-size: .82rem; padding: 6px 8px; }
        td .btn-sm { display: inline-block; margin-bottom: 4px; }
        .pagination { flex-wrap: wrap; gap: 4px; }
        .pagination .page-link { padding: 4px 10px; font-size: .85rem; }
        .modal-dialog { margin: 10px; max-width: calc(100% - 20px); }
        .modal-body .mb-3 { margin-bottom: .6rem !important; }
        .modal-body input.form-control,
        .modal-body select.form-control { font-size: 16px; }
    }
    @media (max-width: 400px) {
        thead th, tbody td { font-size: .75rem; padding: 5px 6px; }
        .btn-sm { font-size: .72rem; padding: 3px 7px; }
        .modal-body input.form-control,
        .modal-body select.form-control { font-size: 15px; }
    }
</style>

@if(session('success'))
    <div class="alert alert-success fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ url('/pengaduan/data_pengaduan') }}" method="GET" class="mb-3">
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
                <i class="bi bi-search"></i> Cari
            </button>
            <a href="{{ url('/pengaduan/data_pengaduan') }}" class="btn btn-secondary w-100">
                <i class="bi bi-x-circle"></i> Reset
            </a>
        </div>
    </div>
</form>

<table class="table table-bordered table-hover" style="margin-top:20px">
    <thead style="background-color: skyblue">
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Tanggal</th>
            <th class="text-center">Pengadu</th>
            <th class="text-center">Ruangan</th>
            <th class="text-center">Lokasi</th>
            <th class="text-center">Status</th>
            <th class="text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @if($data_pengaduan->isEmpty())
            <tr>
                <td colspan="7" class="text-center text-muted">Tidak ada data pengaduan</td>
            </tr>
        @else
            @foreach($data_pengaduan as $index => $pengaduan)
                @php
                    $statusClass = match($pengaduan->status_tindakan) {
                        'Selesai'      => 'selesai',
                        'Dalam Proses' => 'dalam-proses',
                        default        => 'pending',
                    };
                @endphp
                <tr id="row-{{ $pengaduan->id }}">
                    <td class="text-center">{{ $data_pengaduan->firstItem() + $index }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pengaduan->tanggal)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $pengaduan->nama_pengadu }}</td>
                    <td class="text-center">{{ $pengaduan->ruangan }}</td>
                    <td class="text-center">{{ $pengaduan->nama_lokasi }}</td>
                    <td class="text-center">
                        <span class="badge-status {{ $statusClass }}">{{ $pengaduan->status_tindakan }}</span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-info btn-sm btn-detail"
                            data-id="{{ $pengaduan->id }}"
                            data-tanggal="{{ $pengaduan->tanggal }}"
                            data-nama_pengadu="{{ $pengaduan->nama_pengadu }}"
                            data-ruangan="{{ $pengaduan->ruangan }}"
                            data-lokasi="{{ $pengaduan->nama_lokasi }}"
                            data-kode_perangkat="{{ $pengaduan->kode_perangkat ?? '-' }}"
                            data-kategori_perangkat="{{ $pengaduan->kategori_perangkat ?? '-' }}"
                            data-deskripsi="{{ $pengaduan->deskripsi_masalah }}"
                            data-status="{{ $pengaduan->status_tindakan }}"
                            data-kondisi="{{ $pengaduan->kondisi ?? '' }}"
                            data-teknisi="{{ $pengaduan->nama_teknisi ?? '' }}"
                            data-selesai_at="{{ $pengaduan->tindakan_updated_at ?? '' }}">
                            Detail
                        </button>
                        <button class="btn btn-warning btn-sm btn-edit"
                            data-id="{{ $pengaduan->id }}"
                            data-nama_pengadu="{{ $pengaduan->nama_pengadu }}"
                            data-tanggal="{{ $pengaduan->tanggal }}"
                            data-deskripsi_masalah="{{ $pengaduan->deskripsi_masalah }}">
                            Edit
                        </button>
                        <button class="btn btn-danger btn-sm btn-hapus"
                            data-id="{{ $pengaduan->id }}"
                            data-nama_pengadu="{{ $pengaduan->nama_pengadu }}">
                            Hapus
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<nav aria-label="Page navigation">
    <ul class="pagination">
        <li class="page-item {{ $data_pengaduan->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $data_pengaduan->previousPageUrl() ? $data_pengaduan->previousPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        @for($i = 1; $i <= $data_pengaduan->lastPage(); $i++)
            <li class="page-item {{ $data_pengaduan->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $data_pengaduan->url($i) . '&' . http_build_query(request()->except('page')) }}">{{ $i }}</a>
            </li>
        @endfor
        <li class="page-item {{ !$data_pengaduan->hasMorePages() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $data_pengaduan->nextPageUrl() ? $data_pengaduan->nextPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

<div class="modal fade" id="modalDetailPengaduan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-file-text-fill me-2"></i>Detail Pengaduan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="detail-section-title"><i class="bi bi-person me-1"></i> Informasi Pengadu</div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal Lapor</span>
                    <span class="detail-value" id="detail_tanggal">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nama Pengadu</span>
                    <span class="detail-value" id="detail_nama_pengadu">-</span>
                </div>

                <div class="detail-section-title"><i class="bi bi-geo-alt me-1"></i> Lokasi</div>
                <div class="detail-row">
                    <span class="detail-label">Nama Ruangan</span>
                    <span class="detail-value" id="detail_ruangan">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Lokasi</span>
                    <span class="detail-value" id="detail_lokasi">-</span>
                </div>

                <div class="detail-section-title"><i class="bi bi-pc-display me-1"></i> Perangkat</div>
                <div class="detail-row">
                    <span class="detail-label">Kode Perangkat</span>
                    <span class="detail-value" id="detail_kode_perangkat">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Kategori</span>
                    <span class="detail-value" id="detail_kategori_perangkat">-</span>
                </div>

                <div class="detail-section-title"><i class="bi bi-exclamation-circle me-1"></i> Masalah</div>
                <div class="detail-row">
                    <span class="detail-label">Deskripsi</span>
                    <span class="detail-value" id="detail_deskripsi">-</span>
                </div>

                <div id="section_tindakan" style="display:none;">
                    <div class="detail-section-title"><i class="bi bi-tools me-1"></i> Tindakan Teknisi</div>
                    <div class="detail-row">
                        <span class="detail-label">Teknisi</span>
                        <span class="detail-value" id="detail_teknisi">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Kondisi</span>
                        <span class="detail-value" style="flex:1;">
                            <div class="kondisi-box" id="detail_kondisi">-</div>
                        </span>
                    </div>
                </div>

                <div id="section_selesai" style="display:none;">
                    <div class="detail-section-title"><i class="bi bi-check-circle me-1"></i> Penyelesaian</div>
                    <div class="detail-row">
                        <span class="detail-label">Tanggal Selesai</span>
                        <span class="detail-value" id="detail_selesai_at">-</span>
                    </div>
                </div>

                <div class="detail-section-title"><i class="bi bi-info-circle me-1"></i> Status</div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" id="detail_status_badge">-</span>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEditPengaduan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditPengaduan" method="POST">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit Pengaduan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Pengadu</label>
                        <input type="text" class="form-control" name="nama_pengadu" id="edit_nama_pengadu" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal &amp; Waktu</label>
                        <input type="datetime-local" class="form-control" name="tanggal" id="edit_tanggal">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Masalah</label>
                        <textarea class="form-control" name="deskripsi_masalah" id="edit_deskripsi_masalah" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-lg"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHapusPengaduan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus Pengaduan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:2rem;"></i>
                <p class="mt-2">Apakah Anda yakin ingin menghapus pengaduan dari <strong id="hapus_nama_pengadu"></strong>?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="formHapusPengaduan" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash-fill"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('/js/data_pengaduan.js') }}"></script>

@endsection