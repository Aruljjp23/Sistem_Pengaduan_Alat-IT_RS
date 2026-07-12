@extends('layout.page')

@section('page_title', 'Data Ruangan')

@section('content')
<style>
    .room-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .search-container {
        display: flex;
        align-items: stretch;
        background-color: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden; 
        transition: all 0.3s ease;
    }

    .search-container:focus-within {
        background-color: #fff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .search-container .form-control {
        border: none !important; 
        background: transparent;
        height: 45px;
        padding-left: 15px;
        box-shadow: none !important;
    }

    .search-btn {
        background-color: #3b82f6; 
        color: white;
        border: none;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
        cursor: pointer;
    }

    .search-btn:hover {
        background-color: #2563eb;
    }

    .search-btn i {
        font-size: 14px;
    }

    .table-modern { border-collapse: separate; border-spacing: 0 8px; }
    .table-modern thead th {
        background: transparent !important;
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
    }
    .table-modern tbody tr { background: #fff; transition: transform 0.2s; }
    .table-modern tbody tr:hover { transform: scale(1.01); background: #f1f5f9 !important; }
    .table-modern tbody td { padding: 15px; border: none; vertical-align: middle; }
    .table-modern tbody td:first-child { border-radius: 12px 0 0 12px; }
    .table-modern tbody td:last-child { border-radius: 0 12px 12px 0; }

    .room-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: #eff6ff;
        color: #3b82f6;
        display: flex; align-items: center; justify-content: center;
    }

    @media (max-width: 768px) {
        .table-modern thead { display: none; }
        .table-modern tbody tr { display: block; margin-bottom: 15px; border-radius: 15px !important; border: 1px solid #e2e8f0; }
        .table-modern tbody td { 
            display: flex; justify-content: space-between; align-items: center; 
            text-align: right; border-bottom: 1px solid #f1f5f9; 
        }
        .table-modern tbody td::before { content: attr(data-label); font-weight: bold; color: #64748b; }
        .room-icon { display: none; }
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
        });
    });
</script>
@endif

<div class="room-card mb-4 mt-3">
    <div class="row g-3 align-items-center">
        <div class="col-12 col-md-5">
            <form id="formSearch" action="{{ url()->current() }}" method="GET">
                <div class="search-container shadow-sm">
                    <input id="search" 
                        type="text" 
                        class="form-control" 
                        placeholder="Cari data..." 
                        name="search" 
                        value="{{ request('search') }}" 
                        autocomplete="off">
                    
                    <button type="submit" class="search-btn">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-7 text-md-end">
            <button class="btn btn-primary px-4 py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahruangan">
                <i class="fa fa-plus-circle me-2"></i>Tambah Ruangan
            </button>
        </div>
    </div>
</div>

<div class="table-responsive" style="overflow: visible;">
    <table class="table table-modern">
        <thead>
            <tr class="text-center">
                <th width="70">No</th>
                <th class="text-start">Nama Ruangan</th>
                <th>Lokasi</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody id="ruanganTable">
            @forelse ($data_ruangan as $index => $ruangan)
            <tr class="text-center shadow-sm">
                <td data-label="No">{{ $data_ruangan->firstItem() + $index }}</td>
                <td data-label="Ruangan" class="text-start">
                    <div class="d-flex align-items-center gap-3">
                        <div class="room-icon"><i class="fa fa-door-open"></i></div>
                        <a href="{{ url('/perangkat/data_perangkat?id_ruangan=' . $ruangan->id_ruangan) }}" class="fw-bold text-dark text-decoration-none">
                            {{ $ruangan->nama_ruangan }}
                        </a>
                    </div>
                </td>
                <td data-label="Lokasi">
                    <span class="badge bg-light text-primary border-primary border px-3 py-2 rounded-pill">
                        <i class="fa fa-layer-group me-1"></i> {{ $ruangan->lokasi }}
                    </span>
                </td>
                <td data-label="Aksi">
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-warning btn-sm btn-edit rounded-3 px-3 shadow-sm"
                            data-id="{{ $ruangan->id_ruangan }}"
                            data-nama_ruangan="{{ $ruangan->nama_ruangan }}"
                            data-lantai="{{ $ruangan->lokasi }}">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-hapus rounded-3 px-3 shadow-sm"
                            data-id="{{ $ruangan->id_ruangan }}"
                            data-nama_ruangan="{{ $ruangan->nama_ruangan }}"
                            data-url="{{ url('ruang/data_ruang/' . $ruangan->id_ruangan . '/delete') }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $data_ruangan->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>

<div class="modal fade" id="modalTambahruangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <form action="{{ url('ruang/data_ruang') }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold"><i class="fa fa-plus-circle me-2 text-primary"></i>Tambah Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3" name="nama_ruangan" placeholder="Nama Ruangan" required>
                        <label>Nama Ruangan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select rounded-3" name="lokasi" required>
                            <option value="" hidden>Pilih Lantai</option>
                            <option value="Lt. 1">Lantai 1</option>
                            <option value="Lt. 2">Lantai 2</option>
                            <option value="Lt. 3">Lantai 3</option>
                        </select>
                        <label>Lokasi Lantai</label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Ruangan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditruangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <form id="formEditruangan" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold text-warning"><i class="fa fa-edit me-2"></i>Edit Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3" id="edit_ruangan" name="nama_ruangan" required>
                        <label>Nama Ruangan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select rounded-3" name="lokasi" id="edit_lokasi" required>
                            <option value="Lt. 1">Lantai 1</option>
                            <option value="Lt. 2">Lantai 2</option>
                            <option value="Lt. 3">Lantai 3</option>
                        </select>
                        <label>Lokasi Lantai</label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-3 px-4 fw-bold">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>const baseUrl = "{{ url('/') }}";</script>
<script src="{{ asset('/js/data_ruang.js') }}"></script>
@endsection