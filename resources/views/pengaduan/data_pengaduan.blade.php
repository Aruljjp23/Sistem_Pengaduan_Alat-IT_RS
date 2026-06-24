@extends('layout.page')
@section('page_title', 'Data Pengaduan')

@section('content')

<style>
    .content-wrapper { background-color: #f8fafc; }
    
    .badge-status { 
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.7rem; font-weight: 700; padding: 5px 12px; border-radius: 8px;
        text-transform: uppercase;
    }
    .badge-status.selesai { background: #ecfdf5; color: #059669; border: 1px solid #10b98133; } 
    .badge-status.dalam-proses { background: #eff6ff; color: #2563eb; border: 1px solid #3b82f633; } 
    .badge-status.pending { background: #fffbeb; color: #d97706; border: 1px solid #f59e0b33; } 

    .table-container {
        background: white; border-radius: 16px; border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;
    }
    .table-modern thead { background-color: #00ff6e; }
    .table-modern thead th {
        color: #000000; font-weight: 600; font-size: 0.75rem;
        text-transform: uppercase; letter-spacing: 0.025em; padding: 16px; border: none;
    }
    .table-modern tbody td { padding: 16px; vertical-align: middle; color: #334155; border-bottom: 1px solid #f1f5f9; }

    .btn-action {
        width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;
        border-radius: 10px; transition: all 0.2s; border: none;
    }
    .btn-view { background: #f1f5f9; color: #475569; }
    .btn-view:hover { background: #334155; color: white; }
    .btn-edit { background: #fff7ed; color: #ea580c; }
    .btn-edit:hover { background: #ea580c; color: white; }
    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: white; }

    .x-small {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-input, .custom-textarea {
        border: 1.5px solid #e2e8f0 !important;
        transition: all 0.3s ease-in-out;
        font-size: 14px;
    }

    .custom-input:focus, .custom-textarea:focus {
        border-color: #f6ad55 !important; 
        background-color: #fffaf0;
    }

    .custom-textarea {
        resize: none;
    }

    .detail-card {
        transition: transform 0.2s;
    }

    .bg-success-subtle {
        background-color: #f0fdf4 !important;
    }

    .bg-warning-subtle {
        background-color: #fffbeb !important;
    }

    .input-group-text {
        color: #94a3b8;
        background-color: #f8fafc;
    }

    @media (max-width: 768px) {
        .table-modern thead { display: none; }
        .table-modern tbody tr {
            display: block; padding: 15px; margin-bottom: 12px;
            background: white; border: 1px solid #e2e8f0; border-radius: 12px;
        }
        .table-modern tbody td {
            display: flex; justify-content: space-between; align-items: center;
            padding: 8px 0; border: none; width: 100%; text-align: right;
        }
        .table-modern tbody td::before {
            content: attr(data-label); font-weight: 600; color: #64748b;
            font-size: 0.8rem; text-align: left;
        }
        .table-modern tbody td:last-child { justify-content: center; margin-top: 10px; border-top: 1px dashed #e2e8f0; padding-top: 15px; }
    }

    .filter-card { background: white; border-radius: 16px; border: 1px solid #e2e8f0; padding: 20px; margin-bottom: 20px; }
    .custom-search {
        background: #f8fafc !important; border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important; height: 45px;
    }
</style>

<div class="filter-card shadow-sm">
    <div class="row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label small fw-bold text-secondary">CARI PENGADUAN</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 custom-search"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="inputSearch" class="form-control border-start-0 ps-0 custom-search" 
                       placeholder="Ketik nama atau ruangan..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-secondary">FILTER TANGGAL</label>
            <input type="date" name="tanggal" class="form-control custom-search" value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-4">
            <a href="{{ url('/pengaduan/data_pengaduan') }}" class="btn btn-light border w-100 fw-bold custom-search d-flex align-items-center justify-content-center gap-2">
                <i class="fas fa-sync-alt"></i> Reset Filter
            </a>
        </div>
    </div>
</div>

<div class="table-container">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th class="text-center" width="60">No</th>
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
                            'Selesai'      => 'fa-check-double',
                            'Dalam Proses' => 'fa-spinner fa-spin-pulse',
                            default        => 'fa-clock',
                        };
                    @endphp
                    <tr>
                        <td class="text-center text-muted small" data-label="No">{{ $data_pengaduan->firstItem() + $index }}</td>
                        <td data-label="Pengadu">
                            <div class="fw-bold text-dark">{{ $pengaduan->nama_pengadu }}</div>
                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($pengaduan->tanggal)->format('d M Y') }}</small>
                        </td>
                        <td data-label="Lokasi">
                            <div class="fw-bold text-primary">{{ $pengaduan->ruangan }}</div>
                            <div class="small text-secondary">{{ $pengaduan->nama_lokasi }}</div>
                        </td>
                        <td class="text-center" data-label="Status">
                            <span class="badge-status {{ $statusClass }}">
                                <i class="fas {{ $statusIcon }}"></i> {{ $pengaduan->status_tindakan }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn-action btn-view btn-detail" 
                                    data-nama_pengadu="{{ $pengaduan->nama_pengadu }}"
                                    data-tanggal="{{ $pengaduan->tanggal }}"
                                    data-ruangan="{{ $pengaduan->ruangan }}"
                                    data-lokasi="{{ $pengaduan->nama_lokasi }}"
                                    data-deskripsi="{{ $pengaduan->deskripsi_masalah }}"
                                    data-status="{{ $pengaduan->status_tindakan }}"
                                    data-teknisi="{{ $pengaduan->nama_teknisi }}"
                                    data-kondisi="{{ $pengaduan->kondisi }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-edit btn-edit-action" 
                                    data-id="{{ $pengaduan->id }}"
                                    data-nama_pengadu="{{ $pengaduan->nama_pengadu }}"
                                    data-tanggal="{{ $pengaduan->tanggal }}"
                                    data-deskripsi_masalah="{{ $pengaduan->deskripsi_masalah }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action btn-delete btn-hapus" 
                                    data-id="{{ $pengaduan->id }}"
                                    data-nama_pengadu="{{ $pengaduan->nama_pengadu }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 opacity-20"></i>
                                <p>Belum ada data pengaduan saat ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $data_pengaduan->links('pagination::bootstrap-5') }}
</div>

<div class="modal fade" id="modalDetailPengaduan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-light border-0 py-3 px-4">
                <h5 class="modal-title d-flex align-items-center fw-bold text-dark">
                    <span class="bg-primary text-white rounded-3 p-2 me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    Detail Pengaduan
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div id="detail_status_badge"></div>
                </div>

                <div class="detail-card mb-3 p-3 border rounded-3 bg-white">
                    <div class="detail-card-title text-secondary small text-uppercase fw-bold mb-3 d-flex align-items-center">
                        <i class="fas fa-user-circle me-2 text-primary"></i> Pelapor
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Nama Lengkap</span>
                        <span class="fw-bold text-dark" id="detail_nama_pengadu"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Waktu Laporan</span>
                        <span class="text-dark small" id="detail_tanggal"></span>
                    </div>
                </div>

                <div class="detail-card mb-3 p-3 border rounded-3 bg-white">
                    <div class="detail-card-title text-secondary small text-uppercase fw-bold mb-2 d-flex align-items-center">
                        <i class="fas fa-map-marker-alt me-2 text-danger"></i> Lokasi
                    </div>
                    <div class="p-2 rounded-3 bg-light">
                        <div class="fw-bold text-primary" id="detail_ruangan"></div>
                        <div class="text-muted small" id="detail_lokasi"></div>
                    </div>
                </div>

                <div class="detail-card mb-3 p-3 border-start border-4 border-warning rounded-3 bg-white shadow-sm">
                    <div class="detail-card-title text-warning small text-uppercase fw-bold mb-2">
                        Deskripsi Masalah
                    </div>
                    <p class="mb-0 text-dark small lh-base" id="detail_deskripsi"></p>
                </div>

                <div id="section_tindakan" style="display:none;">
                    <div class="detail-card p-3 border-start border-4 border-success rounded-3 bg-success-subtle">
                        <div class="detail-card-title text-success small text-uppercase fw-bold mb-3 d-flex align-items-center">
                            <i class="fas fa-check-shield me-2"></i> Hasil Tindakan
                        </div>
                        <div class="mb-3">
                            <label class="text-muted x-small d-block mb-1">Teknisi Bertugas</label>
                            <div class="fw-bold text-dark" id="detail_teknisi"></div>
                        </div>
                        <div>
                            <label class="text-muted x-small d-block mb-1">Catatan Perbaikan</label>
                            <div class="p-2 bg-white rounded-2 border text-dark small" id="detail_kondisi"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light w-100 rounded-3 fw-bold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditPengaduan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form id="formEditPengaduan" method="POST">
                @csrf
                <div class="modal-header border-0 py-3 px-4 bg-warning-subtle rounded-top-4">
                    <h5 class="modal-title fw-bold text-warning-emphasis d-flex align-items-center">
                        <span class="bg-warning text-white rounded-3 p-2 me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                            <i class="fa fa-edit"></i>
                        </span>
                        Perbarui Data
                    </h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">NAMA PENGADU</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0 custom-input shadow-none" id="edit_nama" name="nama_pengadu" placeholder="Masukkan nama pelapor" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">TANGGAL LAPORAN</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-calendar-day"></i></span>
                            <input type="date" class="form-control border-start-0 ps-0 custom-input shadow-none" id="edit_tanggal" name="tanggal" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">DESKRIPSI MASALAH</label>
                        <textarea class="form-control custom-textarea shadow-none border-2" id="edit_deskripsi" name="deskripsi_masalah" rows="4" placeholder="Jelaskan detail kendala..." required></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0 p-4 pt-0">
                    <div class="d-flex w-100 gap-2">
                        <button type="button" class="btn btn-light fw-bold px-4 py-2 flex-grow-1 border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-white fw-bold px-4 py-2 flex-grow-1 shadow-sm">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('/js/data_pengaduan.js') }}"></script>

@endsection