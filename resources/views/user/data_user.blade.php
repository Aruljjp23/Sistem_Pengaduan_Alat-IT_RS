@extends('layout.page')

@section('page_title', 'Data User')

@section('content')
<style>
    .user-card {
        background: var(--bg-card);
        border-radius: 16px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-subtle);
        padding: 20px;
        transition: all 0.3s ease;
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

    .table-modern {
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    .table-modern thead th {
        background-color: #3b82f6 !important;
        /* background: transparent !important; */
        /* color: var(--text-muted); */
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        border: none;
        padding: 15px;
    }
    .table-modern tbody tr {
        background: var(--bg-card);
        transition: transform 0.2s;
    }
    .table-modern tbody tr:hover {
        transform: scale(1.005);
        background: var(--brand-soft) !important;
    }
    .table-modern tbody td {
        padding: 15px;
        border: none;
        vertical-align: middle;
        font-size: 0.9rem;
    }
    .table-modern tbody td:first-child { border-radius: 12px 0 0 12px; }
    .table-modern tbody td:last-child { border-radius: 0 12px 12px 0; }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--brand-soft);
        color: var(--brand-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }
    .badge-pill {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    @media (max-width: 768px) {
        .table-modern thead { display: none; }
        .table-modern tbody tr {
            display: block;
            margin-bottom: 15px;
            border-radius: 16px !important;
            border: 1px solid var(--border-light);
            padding: 10px;
        }
        .table-modern tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right;
            padding: 8px 15px;
            border-bottom: 1px solid var(--border-light);
        }
        .table-modern tbody td:last-child { border-bottom: none; }
        .table-modern tbody td::before {
            content: attr(data-label);
            font-weight: 700;
            text-align: left;
            color: var(--text-muted);
        }
        .user-avatar { display: none; }
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

<div class="user-card mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-12 col-md-6">
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
        <div class="col-12 col-md-6 text-md-end">
            <button class="btn btn-primary px-4 py-2 shadow-sm" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                <i class="fa fa-plus-circle me-2"></i>Tambah User
            </button>
        </div>
    </div>
</div>

<div class="table-responsive" style="overflow: visible;">
    <table class="table table-modern">
        <thead>
            <tr class="text-center">
                <th width="80">No</th>
                <th class="text-start">Nama</th>
                <th>Role</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody id="userTable">
            @forelse ($data_user as $index => $user)
            <tr class="text-center">
                <td data-label="No">{{ $data_user->firstItem() + $index }}</td>
                
                <td data-label="User" class="text-start">
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-avatar shadow-sm" style="width: 35px; height: 35px; flex-shrink: 0;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="fw-bold text-dark" style="font-size: 0.95rem; line-height: 1;">
                            {{ $user->name }}
                        </div>
                    </div>
                </td>

                <td data-label="Role">
                    @php
                        $badgeStyle = [
                            'admin'    => 'bg-info text-dark',
                            'teknisi'  => 'bg-success text-white',
                            'pengadu'  => 'bg-secondary text-white'
                        ][$user->role] ?? 'bg-dark text-white';
                    @endphp
                    <span class="badge badge-pill {{ $badgeStyle }} text-capitalize">
                        {{ $user->role }}
                    </span>
                </td>

                <td data-label="Aksi">
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-warning btn-sm btn-edit rounded-3 shadow-sm px-3"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-role="{{ $user->role }}"
                            data-id_ruangan="{{ $user->id_ruangan }}">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-hapus rounded-3 shadow-sm px-3"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-url="{{ url('user/data_user/' . $user->id . '/delete') }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr id="noUserData" style="display:none;">
                <td colspan="4" class="text-center text-muted">
                    Data tidak ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="user-card mt-3">
    <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <span class="small text-muted fw-500">
                Menampilkan <b>{{ $data_user->firstItem() }}</b> - <b>{{ $data_user->lastItem() }}</b> dari <b>{{ $data_user->total() }}</b> user
            </span>
        </div>
        <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
            {{ $data_user->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <form action="{{ url('user/data_user') }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold">Tambah Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3" name="name" id="floatName" placeholder="Username" required>
                        <label for="floatName">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select name="id_ruangan" class="form-select rounded-3" id="floatRuang">
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($data_ruangan as $ruangan)
                                <option value="{{ $ruangan->id_ruangan }}">{{ $ruangan->nama_ruangan }}</option>
                            @endforeach
                        </select>
                        <label for="floatRuang">Ruangan (Opsional)</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control rounded-3" name="password" id="floatPass" placeholder="Password" required>
                        <label for="floatPass">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select rounded-3" name="role" required>
                            <option value="" disabled selected>Pilih Role...</option>
                            <option value="admin">Admin</option>
                            <option value="teknisi">Teknisi</option>
                            <option value="pengadu">Pengadu</option>
                        </select>
                        <label>Hak Akses (Role)</label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <form id="formEditUser" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold text-warning">Edit Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3" id="edit_name" name="name" placeholder="Name" required>
                        <label>Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select name="id_ruangan" class="form-select rounded-3" id="edit_id_ruangan">
                            <option value="">-- Tidak Ada --</option>
                            @foreach($data_ruangan as $ruangan)
                                <option value="{{ $ruangan->id_ruangan }}">{{ $ruangan->nama_ruangan }}</option>
                            @endforeach
                        </select>
                        <label>Ruangan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control rounded-3" name="password" placeholder="Password">
                        <label>Password Baru (Opsional)</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select rounded-3" id="edit_role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="teknisi">Teknisi</option>
                            <option value="pengadu">Pengadu</option>
                        </select>
                        <label>Hak Akses</label>
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

<script src="{{ asset('js/data_user.js') }}"></script>
@endsection