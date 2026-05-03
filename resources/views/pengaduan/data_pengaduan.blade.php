@extends('layout.page')

@section('page_title', 'Data Pengaduan')

@section('content')

<style>
    .content-wrapper { background-color: #f4f7fa; }
    
    .badge-status { 
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.75rem; font-weight: 700; padding: 6px 12px; border-radius: 50px;
    }
    .badge-status.selesai      { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; } 
    .badge-status.dalam-proses { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; } 
    .badge-status.pending      { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; } 

    .table-container {
        background: white; border-radius: 15px; border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden;
    }
    .table-modern thead { background-color: #0cdbff; }
    .table-modern thead th {
        color: #ffffff; font-weight: 500; font-size: 0.75rem;
        text-transform: uppercase; letter-spacing: 0.05em; padding: 15px; border: none;
    }
    .table-modern tbody td { padding: 15px; vertical-align: middle; color: #475569; border-bottom: 1px solid #f1f5f9; }

    .btn-action {
        width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; transition: all 0.2s; border: 1px solid transparent;
    }
    
    .btn-view { background: #f1f5f9; color: #475569; }
    .btn-view:hover { background: #475569; color: white; }
    .btn-edit { background: #fff7ed; color: #ea580c; }
    .btn-edit:hover { background: #ea580c; color: white; }
    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: white; }

    .filter-card { background: white; border-radius: 15px; border: 1px solid #e2e8f0; padding: 20px; margin-bottom: 20px; }
</style>

<div class="filter-card shadow-sm">
    <div class="row g-3">
        <div class="col-md-5">
            <label class="form-label small fw-bold text-muted">PENCARIAN</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="inputSearch" class="form-control border-start-0 ps-0" placeholder="Nama pengadu atau ruangan..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">TANGGAL</label>
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-4 d-flex align-items-end gap-2">
            <a href="{{ url('/pengaduan/data_pengaduan') }}" class="btn btn-outline-secondary w-100 fw-bold">
                <i class="fas fa-sync me-1"></i> Reset
            </a>
        </div>
    </div>
</div>

<div class="table-container">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th class="text-center" width="50">No</th>
                    <th>Informasi Pengadu</th>
                    <th>Detail Lokasi</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data_pengaduan as $index => $pengaduan)
                    @php
                        $statusClass = match($pengaduan->status_tindakan) {
                            'Selesai'      => 'selesai',
                            'Dalam Proses' => 'dalam-proses',
                            default        => 'pending',
                        };
                        $statusIcon = match($pengaduan->status_tindakan) {
                            'Selesai'      => 'fa-check-circle',
                            'Dalam Proses' => 'fa-repeat',
                            default        => 'fa-history',
                        };
                    @endphp
                    <tr>
                        <td class="text-center text-muted small">{{ $data_pengaduan->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $pengaduan->nama_pengadu }}</div>
                            <small class="text-muted"><i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($pengaduan->tanggal)->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-primary">{{ $pengaduan->ruangan }}</div>
                            <small class="text-muted"><i class="fa fa-layer-group me-1"></i>{{ $pengaduan->nama_lokasi }}</small>
                        </td>
                        <td class="text-center">
                            <span class="badge-status {{ $statusClass }}">
                                <i class="fas {{ $statusIcon }}"></i> {{ $pengaduan->status_tindakan }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn-action btn-view btn-detail" 
                                    data-id="{{ $pengaduan->id }}"
                                    data-tanggal="{{ $pengaduan->tanggal }}"
                                    data-nama_pengadu="{{ $pengaduan->nama_pengadu }}"
                                    data-ruangan="{{ $pengaduan->ruangan }}"
                                    data-lokasi="{{ $pengaduan->nama_lokasi }}"
                                    data-deskripsi="{{ $pengaduan->deskripsi_masalah }}"
                                    data-status="{{ $pengaduan->status_tindakan }}"
                                    data-teknisi="{{ $pengaduan->nama_teknisi }}"
                                    data-kondisi="{{ $pengaduan->kondisi }}"
                                    title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-edit btn-edit-action" 
                                    data-id="{{ $pengaduan->id }}"
                                    data-nama_pengadu="{{ $pengaduan->nama_pengadu }}"
                                    data-deskripsi_masalah="{{ $pengaduan->deskripsi_masalah }}"
                                    data-tanggal="{{ $pengaduan->tanggal }}"
                                    title="Edit">
                                    <i class="fas fa-pencil-square"></i>
                                </button>
                                <button class="btn-action btn-delete btn-hapus" 
                                    data-id="{{ $pengaduan->id }}"
                                    data-nama_pengadu="{{ $pengaduan->nama_pengadu }}"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="modalDetailPengaduan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Detail Laporan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4 text-center">
                    <div id="detail_status_badge"></div>
                </div>
                <div class="detail-section">
                    <div class="detail-title">Informasi Pelapor</div>
                    <div class="d-flex justify-content-between mb-1 small">
                        <span class="text-muted">Nama</span>
                        <span class="fw-bold" id="detail_nama_pengadu"></span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-muted">Tanggal</span>
                        <span id="detail_tanggal"></span>
                    </div>
                </div>
                <div class="detail-section">
                    <div class="detail-title">Lokasi & Ruang</div>
                    <div class="fw-bold text-primary mb-1" id="detail_ruangan"></div>
                    <div class="text-muted small" id="detail_lokasi"></div>
                </div>
                <div class="detail-section bg-white border border-danger-subtle">
                    <div class="detail-title text-danger">Keluhan/Masalah</div>
                    <p class="mb-0 small text-dark" id="detail_deskripsi"></p>
                </div>
                <div id="section_tindakan" class="detail-section bg-primary-subtle border border-primary-subtle" style="display:none;">
                    <div class="detail-title text-primary">Respon Teknisi</div>
                    <div class="small fw-bold mb-1" id="detail_teknisi"></div>
                    <div class="p-2 bg-white rounded small shadow-sm" id="detail_kondisi"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('/js/data_pengaduan.js') }}"></script>

@endsection