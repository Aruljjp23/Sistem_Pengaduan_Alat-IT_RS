@extends('layout.page')

@section('page_title', 'Data Ruangan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/data_ruang.css') }}">
<br>

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
 
{{-- @if(session('edit_error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            title: 'Gagal Update!',
            text: '{{ session('edit_error') }}',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Tutup',
            customClass: { popup: 'shadow' }
        }).then(() => {
            var editModal = new bootstrap.Modal(document.getElementById('modalEditruangan'));
            editModal.show();
        });
    });
</script>
@endif --}}
 
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'warning',
            title: 'Validasi Gagal',
            html: `<ul class="text-start text-danger mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>`,
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'Perbaiki',
            customClass: { popup: 'shadow' }
        }).then(() => {
            var tambahModal = new bootstrap.Modal(document.getElementById('modalTambahruangan'));
            tambahModal.show();
        });
    });
</script>
@endif
 
<div class="row mb-4 mt-3 align-items-end">
    <div class="col-md-5">
        <form id="formSearch" action="{{ url()->current() }}" method="GET">
            <div class="input-group shadow-sm">
                <input id="search" type="text" class="form-control" 
                    placeholder="Cari nama ruangan atau lokasi..." 
                    name="search" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>
    </div>
    <div class="col-md-7 text-md-end mt-3 mt-md-0">
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahruangan">
            <i class="fa fa-plus-circle me-1"></i> Tambah Ruangan
        </button>
    </div>
</div>
 
<div class="table-responsive shadow-sm rounded">
    <table class="table table-bordered table-hover mb-0">
        <thead class="table-primary text-center">
            <tr>
                <th width="60">No</th>
                <th>Nama Ruangan</th>
                <th>Lokasi / Lantai</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody id="ruanganTable">
            <tr id="noRoomData" style="display: none;">
                <td colspan="4" class="text-center text-muted">
                    Data tidak ditemukan
                </td>
            </tr>
            @forelse ($data_ruangan as $index => $ruangan)
            <tr id="row-{{ $ruangan->id }}" class="align-middle text-center">
                <td>{{ $data_ruangan->firstItem() + $index }}</td>
                <td class="text-start ps-4">
                    <a href="{{ url('/perangkat/data_perangkat?id_ruangan=' . $ruangan->id) }}" class="fw-bold text-decoration-none">
                        <i class="fa fa-door-open me-2 text-secondary"></i>{{ $ruangan->nama_ruangan }}
                    </a>
                </td>
                <td>
                    <span class="badge bg-light text-dark border">
                        <i class="fa fa-layer-group me-1"></i>{{ $ruangan->lokasi }}
                    </span>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-warning btn-sm btn-edit"
                            data-id="{{ $ruangan->id }}"
                            data-nama_ruangan="{{ $ruangan->nama_ruangan }}"
                            data-lantai="{{ $ruangan->lokasi }}">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-hapus"
                            data-id="{{ $ruangan->id }}"
                            data-nama_ruangan="{{ $ruangan->nama_ruangan }}"
                            data-url="{{ url('ruang/data_ruang/' . $ruangan->id . '/delete') }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4 text-muted">
                    <i class="fa fa-folder-open d-block mb-2" style="font-size: 2rem;"></i>
                    Tidak ada data ruangan ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
 
<div class="mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center shadow-sm">
            <li class="page-item {{ $data_ruangan->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $data_ruangan->appends(request()->query())->previousPageUrl() ?? '#' }}">
                    &laquo;
                </a>
            </li>
 
            @for ($i = 1; $i <= $data_ruangan->lastPage(); $i++)
                <li class="page-item {{ $data_ruangan->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $data_ruangan->url($i) . '&' . http_build_query(request()->except('page')) }}">
                        @php
                            $search = request('search');
                            $label = $i;
                            if (!$search && isset($halamanLantai1)) {
                                if ($i <= $halamanLantai1) $label = "Lantai 1 ($i)";
                                elseif ($i <= ($halamanLantai1 + $halamanLantai2)) $label = "Lantai 2 (" . ($i - $halamanLantai1) . ")";
                                else $label = "Lantai 3 (" . ($i - $halamanLantai1 - $halamanLantai2) . ")";
                            }
                        @endphp
                        {{ $label }}
                    </a>
                </li>
            @endfor
 
            <li class="page-item {{ !$data_ruangan->hasMorePages() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $data_ruangan->appends(request()->query())->nextPageUrl() ?? '#' }}">
                    &raquo;
                </a>
            </li>
        </ul>
    </nav>
</div>
 
<div class="modal fade" id="modalTambahruangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ url('ruang/data_ruang') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa fa-plus-circle me-2"></i>Tambah Ruangan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Ruangan</label>
                        <input type="text" class="form-control" name="nama_ruangan" placeholder="Contoh: Ruang Farmasi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Lokasi Lantai</label>
                        <select class="form-select" name="lantai" required>
                            <option value="" hidden>-- Pilih Lantai --</option>
                            <option value="Lantai 1">Lantai 1</option>
                            <option value="Lantai 2">Lantai 2</option>
                            <option value="Lantai 3">Lantai 3</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Ruangan</button>
                </div>
            </form>
        </div>
    </div>
</div>
 
<div class="modal fade" id="modalEditruangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formEditruangan" method="POST">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold"><i class="fa fa-edit me-2"></i>Edit Ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Ruangan</label>
                        <input type="text" class="form-control" id="edit_ruangan" name="nama_ruangan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Lokasi Lantai</label>
                        <select class="form-select" name="lokasi" id="edit_lokasi" required>
                            <option value="" hidden>-- Pilih Lantai --</option>
                            <option value="Lantai 1">Lantai 1</option>
                            <option value="Lantai 2">Lantai 2</option>
                            <option value="Lantai 3">Lantai 3</option>
                        </select>
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
 
<script>
    const baseUrl = "{{ url('/') }}";
</script>
 
<script src="{{ asset('/js/data_ruang.js') }}"></script>
 
@endsection