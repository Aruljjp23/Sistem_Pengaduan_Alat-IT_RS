@extends('layout.page')

@section('page_title', 'Kategori Perangkat')

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

    .search-btn:hover { background-color: #2563eb; }
    .search-btn i { font-size: 14px; }

    .table-modern {
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    .table-modern thead th {
        background-color: #3b82f6 !important;
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
    .table-modern tbody td:last-child  { border-radius: 0 12px 12px 0; }

    .kat-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--brand-soft);
        color: var(--brand-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
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
        .kat-avatar { display: none; }
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

{{-- Toolbar --}}
<div class="user-card mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-12 col-md-6">
            <form id="formSearch" action="{{ url()->current() }}" method="GET">
                <div class="input-group">
                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control"
                        placeholder="Cari kategori..."
                        value="{{ request('search') }}"
                        autocomplete="off">

                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-6 text-md-end">
            <button class="btn btn-primary px-4 py-2 shadow-sm" style="border-radius: 10px;"
                data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                <i class="fa fa-plus-circle me-2"></i>Tambah Kategori
            </button>
        </div>
    </div>
</div>

{{-- Tabel --}}
<div class="table-responsive" style="overflow: visible;">
    <table class="table table-modern">
        <thead>
            <tr class="text-center">
                <th width="80">No</th>
                <th class="text-center">Nama Kategori</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody id="kategoriTable">
            @forelse ($data_kategori as $index => $item)
            <tr class="text-center">
                <td data-label="No">{{ $data_kategori->firstItem() + $index }}</td>

                <td data-label="Kategori" class="text-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fw-bold text-dark" style="font-size: 0.95rem; line-height: 1;">
                            {{ $item->nama_kategori }}
                        </div>
                    </div>
                </td>

                <td data-label="Aksi">
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-warning btn-sm btn-edit rounded-3 shadow-sm px-3"
                            data-id="{{ $item->id_kategori }}"
                            data-nama="{{ $item->nama_kategori }}">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-hapus rounded-3 shadow-sm px-3"
                            data-id="{{ $item->id_kategori }}"
                            data-nama="{{ $item->nama_kategori }}"
                            data-url="{{ url('kategori/data_kategori/' . $item->id_kategori . '/delete') }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center py-5 text-muted">
                    <i class="fa fa-box-open d-block mb-2 fs-2"></i>
                    Belum ada kategori perangkat.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="user-card mt-3">
    <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <span class="small text-muted fw-500">
                Menampilkan <b>{{ $data_kategori->firstItem() }}</b> –
                <b>{{ $data_kategori->lastItem() }}</b> dari
                <b>{{ $data_kategori->total() }}</b> kategori
            </span>
        </div>
        <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
            {{ $data_kategori->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <form action="{{ url('kategori/data_kategori') }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3"
                            name="nama_kategori" id="floatNama"
                            placeholder="Nama Kategori" required>
                        <label for="floatNama">Nama Kategori</label>
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

{{-- Modal Edit --}}
<div class="modal fade" id="modalEditKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <form id="formEditKategori" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold text-warning">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3"
                            id="edit_nama_kategori" name="nama_kategori"
                            placeholder="Nama Kategori" required>
                        <label>Nama Kategori</label>
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

<script src="{{ asset('js/data_kategori.js') }}"></script>
@endsection