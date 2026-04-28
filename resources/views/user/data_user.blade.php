@extends('layout.page')

@section('page_title', 'Data User')

@section('content')
<style>
    @media (max-width: 768px) {
 
    table.table {
        display: block;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
    }
 
    .search.col-md-5 {
        width: 100% !important;
        max-width: 100% !important;
    }

    .search .input-group {
        width: 100%;
    }
 
    button.btn.btn-primary[data-bs-target="#modalTambahUser"] {
        width: 100%;
        margin-bottom: 10px;
    }
 
    .btn[data-bs-toggle="modal"] {
        display: block;
        width: 100%;
    }
 
    td .btn-sm {
        display: inline-block;
        margin-bottom: 4px;
    }
 
    .pagination {
        flex-wrap: wrap;
        gap: 4px;
    }
 
    .pagination .page-link {
        padding: 4px 10px;
        font-size: 0.85rem;
    }
 
    .modal-dialog {
        margin: 10px;
        max-width: calc(100% - 20px);
    }
 
    .modal-body .mb-3 {
        margin-bottom: 0.6rem !important;
    }
 
    .modal-body input.form-control,
    .modal-body select.form-control,
    .modal-body select.form-select {
        font-size: 16px;
    }
 
    thead th {
        font-size: 0.82rem;
        padding: 6px 8px;
    }
 
    tbody td {
        font-size: 0.82rem;
        padding: 6px 8px;
        vertical-align: middle;
    }
 
    .badge {
        font-size: 0.72rem;
        padding: 4px 7px;
    }
}
 
@media (max-width: 400px) {
 
    thead th,
    tbody td {
        font-size: 0.75rem;
        padding: 5px 6px;
    }
 
    .btn-sm {
        font-size: 0.72rem;
        padding: 3px 7px;
    }
 
    .modal-body input.form-control,
    .modal-body select.form-control,
    .modal-body select.form-select {
        font-size: 15px;
    }
}
 
</style>
<br>

@if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

<form id="formSearch">
    <div class="search col-md-5 mb-3">
        <div class="input-group">
            <input id="searchInput" type="text" class="form-control" 
                placeholder="Cari nama, ruangan, role..." 
                name="search" 
                value="{{ request()->get('search', '') }}"
                autocomplete="off">
            <button type="button" class="btn btn-primary" onclick="navigateWithParams()">Submit</button>
        </div>
    </div>
</form>

<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
    <i class="fa fa-plus"></i> Add User
</button>

<table class="table table-bordered table-hover" style="margin-top:20px">
    <thead class="table-primary">
        <tr class="text-center">
            <th scope="col">No</th>
            <th scope="col">Username</th>
            <th scope="col">Role</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data_user as $index => $user)
        <tr class="text-center">
            <td>{{ $index + 1 }}</td>
            <td>{{ $user->name }}</td>
            <td>
                @if($user->role === 'admin')
                    <span class="badge bg-info">Admin</span>
                @elseif($user->role === 'teknisi')
                    <span class="badge bg-success">Teknisi</span>
                @else
                    <span class="badge bg-secondary">Pengadu</span>
                @endif
            </td>
            <td>
                <button class="btn btn-warning btn-sm btn-edit"
                    data-id="{{ $user->id }}"
                    data-name="{{ $user->name }}"
                    data-role="{{ $user->role }}"
                    data-id_ruangan="{{ $user->id_ruangan }}">
                    <i class="fa-solid fa-pencil"></i>
                </button>
                <button class="btn btn-danger btn-sm btn-hapus"
                    data-id="{{ $user->id }}"
                    data-name="{{ $user->name }}">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<nav aria-label="Page navigation">
    <ul class="pagination">
        <li class="page-item {{ $data_user->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $data_user->previousPageUrl() ? $data_user->previousPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        @for ($i = 1; $i <= $data_user->lastPage(); $i++)
            <li class="page-item {{ $data_user->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $data_user->url($i) . '&' . http_build_query(request()->except('page')) }}">
                    {{ $i }}
                </a>
            </li>
        @endfor

        <li class="page-item {{ !$data_user->hasMorePages() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $data_user->nextPageUrl() ? $data_user->nextPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('user/data_user') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="name" placeholder="Masukkan username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruangan</label>
                        <select name="id_ruangan" class="form-control" id="id_ruangan">
                            <option value=""><-- Tidak Ada --></option>
                            @foreach($data_ruangan as $ruangan)
                            <option value="{{$ruangan->id}}">{{$ruangan->nama_ruangan}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="text" class="form-control" name="password" placeholder="Masukkan password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="" disabled>Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="teknisi">Teknisi</option>
                            <option value="pengadu">Pengadu</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditUser" method="POST">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    {{-- <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div> --}}
                    <div class="mb-3">
                        <option value="">-- Tidak Ada --</option>
                        <select name="id_ruangan" class="form-control" id="edit_id_ruangan">
                            <option value=""><-- Tidak Ada --></option>
                            @foreach($data_ruangan as $ruangan)
                            <option value="{{$ruangan->id}}">{{$ruangan->nama_ruangan}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                        <input type="text" class="form-control" id="edit_password" name="password" placeholder="Password baru">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="teknisi">Teknisi</option>
                            <option value="pengadu">Pengadu</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHapusUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2rem;"></i>
                <p class="mt-2">Apakah Anda yakin ingin menghapus user <strong id="hapus_name"></strong>?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a id="btnHapusKonfirmasi" href="#" class="btn btn-danger">Ya, Hapus</a>
            </div>
        </div>
    </div>
</div>

<script  src="{{ asset('js/data_user.js') }}"></script>

@endsection