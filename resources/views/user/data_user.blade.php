@extends('layout.page')

@section('page_title', 'Data User')

@section('content')
<style>

    .table-responsive-custom { margin-top: 20px; }
    .action-btns .btn { margin: 2px; }

    @media (max-width: 768px) {
        .table-responsive-custom {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .search-container, .btn-add-user {
            width: 100% !important;
            margin-bottom: 15px;
        }
        .pagination { 
            justify-content: center;
            flex-wrap: wrap; 
        }
        .form-mobile-lg { font-size: 16px; } 
    }
</style>

<br>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row mb-4 align-items-end">
    <div class="col-md-5 search-container">
        <form id="formSearch" action="{{ url()->current() }}" method="GET">
            <label class="form-label d-none d-md-block">Cari Pengguna</label>
            <div class="input-group">
                <input id="searchInput" type="text" class="form-control" 
                    placeholder="Cari nama, ruangan, role..." 
                    name="search" value="{{ request('search') }}" autocomplete="off">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </form>
    </div>
    <div class="col-md-7 text-md-end">
        <button class="btn btn-primary btn-add-user" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="fa fa-plus-circle me-1"></i> Tambah User
        </button>
    </div>
</div>

<div class="table-responsive-custom">
    <table class="table table-bordered table-hover shadow-sm">
        <thead class="table-primary text-center">
            <tr>
                <th width="50">No</th>
                <th>Username</th>
                <th>Role</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody id="userTable">
            <tr id="noUserData" style="display: none;">
                <td colspan="4" class="text-center text-muted">
                    Data tidak ditemukan
                </td>
            </tr>
            @forelse ($data_user as $index => $user)
            <tr class="text-center align-middle">
                <td>{{ $data_user->firstItem() + $index }}</td>
                <td class="text-start ps-3">{{ $user->name }}</td>
                <td>
                    @php
                        $badgeClass = [
                            'admin' => 'bg-info',
                            'teknisi' => 'bg-success',
                            'pengadu' => 'bg-secondary'
                        ][$user->role] ?? 'bg-dark';
                    @endphp
                    <span class="badge {{ $badgeClass }} text-capitalize">{{ $user->role }}</span>
                </td>
                <td class="action-btns">
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
            @empty
            <tr>
                <td colspan="4" class="text-center">Data tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mt-3">
    <div class="text-muted small">
        Showing {{ $data_user->firstItem() }} to {{ $data_user->lastItem() }} of {{ $data_user->total() }} entries
    </div>
    {{ $data_user->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>

<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <form action="{{ url('user/data_user') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTambahLabel">
                        <i class="fa fa-user-plus me-2"></i>Tambah User Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control" name="name" placeholder="Masukkan username" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ruangan</label>
                        <select name="id_ruangan" class="form-select" id="id_ruangan">
                            <option value="" selected>-- Pilih Ruangan (Opsional) --</option>
                            @foreach($data_ruangan as $ruangan)
                                <option value="{{$ruangan->id}}">{{$ruangan->nama_ruangan}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control" name="password" placeholder="Maksimal 10 karakter" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role Access</label>
                        <select class="form-select" name="role" required>
                            <option value="" disabled selected>Pilih Role...</option>
                            <option value="admin">Admin</option>
                            <option value="teknisi">Teknisi</option>
                            <option value="pengadu">Pengadu</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa fa-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <form id="formEditUser" method="POST">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold" id="modalEditLabel">
                        <i class="fa fa-edit me-2"></i>Edit Informasi User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ruangan</label>
                        <select name="id_ruangan" class="form-select" id="edit_id_ruangan">
                            <option value="">-- Tidak Ada --</option>
                            @foreach($data_ruangan as $ruangan)
                                <option value="{{$ruangan->id}}">{{$ruangan->nama_ruangan}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru</label>
                        <input type="password" class="form-control" id="edit_password" name="password" placeholder="Kosongkan jika tidak ingin diubah">
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="form-text text-muted small">
                            <i class="fa fa-info-circle me-1"></i> Biarkan kosong jika tetap menggunakan password lama.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Role Access</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="teknisi">Teknisi</option>
                            <option value="pengadu">Pengadu</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold">
                        <i class="fa fa-sync me-1"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHapusUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white justify-content-center">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="fa fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="mb-0">Anda yakin ingin menghapus user:</p>
                <h5 class="fw-bold mt-2" id="hapus_name"></h5>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                <a id="btnHapusKonfirmasi" href="#" class="btn btn-danger px-4 shadow-sm">
                    <i class="fa fa-trash me-1"></i> Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>
@if ($errors->any())
<script>
    var tambahModal = new bootstrap.Modal(document.getElementById('modalTambahUser'));
    tambahModal.show();
</script>
@endif
@if(session('edit_error'))
<script>
    var editModal = new bootstrap.Modal(document.getElementById('modalEditUser'));
    editModal.show();
</script>
@endif
<script  src="{{ asset('js/data_user.js') }}"></script>
@endsection