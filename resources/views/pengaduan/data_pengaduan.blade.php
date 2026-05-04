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
    .custom-input {
        margin: 5px 0 10px 0 !important;
        height: 42px;
        border-radius: 10px !important;
        font-size: 14px;
        border: 1px solid #e2e8f0 !important;
    }

    .custom-textarea {
        margin-top: 5px !important;
        border-radius: 10px !important;
        font-size: 14px;
        min-height: 100px;
        border: 1px solid #e2e8f0 !important;
    }

    .swal2-popup {
        border-radius: 16px !important;
        padding: 20px !important;
    }

    .detail-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 15px;
        border: 1px solid #e2e8f0;
    }

    .detail-card-title {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #334155;
        display: flex;
        align-items: center;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        margin-bottom: 5px;
        color: #475569;
    }

    .detail-row span:first-child {
        color: #94a3b8;
    }

    .modal-content {
        animation: fadeInUp 0.3s ease;
    }


    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            position: 'top-end',
            toast: true,
            background: '#f0fdf4',
            iconColor: '#16a34a',
            customClass: {
                popup: 'border border-success shadow'
            }
        });
    });
</script>
@endif

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

<div class="modal fade" id="modalDetailPengaduan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Detail Pengaduan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 pb-4">

                <div class="text-center mb-4">
                    <div id="detail_status_badge"></div>
                </div>

                <div class="detail-card mb-3">
                    <div class="detail-card-title">
                        <i class="fas fa-user me-2"></i>Informasi Pelapor
                    </div>

                    <div class="detail-row">
                        <span>Nama</span>
                        <strong id="detail_nama_pengadu"></strong>
                    </div>

                    <div class="detail-row">
                        <span>Tanggal</span>
                        <span id="detail_tanggal"></span>
                    </div>
                </div>

                <div class="detail-card mb-3">
                    <div class="detail-card-title">
                        <i class="fas fa-map-marker-alt me-2"></i>Lokasi
                    </div>

                    <div class="fw-bold text-primary mb-1" id="detail_ruangan"></div>
                    <div class="text-muted small" id="detail_lokasi"></div>
                </div>

                <div class="detail-card border-danger-subtle bg-danger-subtle mb-3">
                    <div class="detail-card-title text-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>Masalah
                    </div>

                    <p class="mb-0 small text-dark" id="detail_deskripsi"></p>
                </div>

                <div id="section_tindakan" class="detail-card border-primary-subtle bg-primary-subtle" style="display:none;">
                    <div class="detail-card-title text-primary">
                        <i class="fas fa-tools me-2"></i>Tindakan Teknisi
                    </div>

                    <div class="mb-2">
                        <small class="text-muted">Teknisi</small>
                        <div class="fw-bold" id="detail_teknisi"></div>
                    </div>

                    <div>
                        <small class="text-muted">Kondisi / Catatan</small>
                        <div class="p-2 bg-white rounded shadow-sm small" id="detail_kondisi"></div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalEditPengaduan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formEditPengaduan" method="POST">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold">
                        <i class="fa fa-edit me-2"></i>Edit Pengaduan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Pengadu</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama_pengadu" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <input type="date" class="form-control" id="edit_tanggal" name="tanggal" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Masalah</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi_masalah" rows="3" required></textarea>
                    </div>

                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('/js/data_pengaduan.js') }}"></script>

@endsection