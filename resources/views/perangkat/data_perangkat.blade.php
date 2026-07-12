@extends('layout.page')

@section('page_title', 'Data Perangkat')

@section('content')
<style>
    .table code {
        color: #e83e8c;
        background-color: #f8f9fa;
        padding: 2px 4px;
        border-radius: 4px;
    }

    .filter-wrapper {
        background-color: #fbfbfb !important;
        border: 1px solid #eee;
    }

    .btn-group .btn {
        border-width: 1px;
        padding: 0.4rem 0.6rem;
    }

    .btn-outline-primary.active,
    .btn-outline-success.active,
    .btn-outline-warning.active,
    .btn-outline-info.active,
    .btn-outline-dark.active {
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
        font-weight: bold;
    }

    .pagination {
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border-radius: 8px;
    }

    @media (max-width: 768px) {
        .filter-wrapper {
            overflow-x: auto;
            white-space: nowrap;
        }
        .filter-wrapper::-webkit-scrollbar {
            height: 3px;
        }
        .table thead th {
            font-size: 0.75rem;
        }
        .table tbody td {
            font-size: 0.8rem;
        }
    }

    .swal2-popup {
        border-radius: 16px !important;
        font-family: inherit !important;
    }
    .swal2-confirm, .swal2-cancel {
        border-radius: 8px !important;
        font-weight: 600 !important;
        padding: 10px 28px !important;
    }

    tr.row-updated {
        animation: highlightRow 3s ease forwards;
    }
    @keyframes highlightRow {
        0%   { background-color: #fff3cd; }
        80%  { background-color: #fff3cd; }
        100% { background-color: transparent; }
    }

    .change-badge {
        display: inline-block;
        font-size: 0.7rem;
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
        border-radius: 4px;
        padding: 1px 5px;
        margin-left: 4px;
        vertical-align: middle;
        white-space: nowrap;
    }
</style>

@if(session('success') && is_array(session('success')))
    <script>
        window.updateResultData = @json(session('success'));
    </script>
@endif

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ is_array(session('success')) ? 'Data perangkat berhasil diperbarui.' : session('success') }}",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
            });
        });
    </script>
@endif

<br>

<div class="container-fluid px-0">
    <div class="d-flex align-items-center mb-4 mt-3">
        <a href="{{ url('/ruang/data_ruang') }}" class="btn btn-outline-secondary btn-sm me-3">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h5 class="mb-0">Ruangan: <span class="badge bg-primary fs-6">{{ $ruangan->nama_ruangan ?? '-' }}</span></h5>
    </div>

    <div class="row g-3 mb-4 align-items-center">
        <div class="col-md-5">
            <form id="formSearch" class="input-group shadow-sm">
                <input type="hidden" id="id_ruangan" value="{{ $id_ruangan }}">
                <input id="search" type="text" class="form-control"
                    placeholder="Cari kode atau IP..."
                    name="search" value="{{ request()->get('search', '') }}">
                <button type="button" class="btn btn-primary px-4" onclick="navigateWithParams()">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>
        <div class="col-md-7 text-md-end">
            <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahperangkat">
                <i class="fa fa-plus-circle me-1"></i> Tambah Perangkat
            </button>
        </div>
    </div>

    <div class="filter-wrapper mb-4 p-3 bg-light rounded shadow-sm">
        <p class="small text-muted fw-bold mb-2 text-uppercase">Filter Kategori:</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ url('/perangkat/data_perangkat?id_ruangan='.$id_ruangan) }}"
               class="btn btn-sm btn-outline-dark {{ empty($kategori) ? 'active' : '' }}">
                Semua
            </a>
            @foreach(['Laptop' => 'primary', 'Printer' => 'success', 'PC' => 'warning', 'Handphone' => 'info'] as $kat => $color)
                <a href="{{ url('/perangkat/data_perangkat?id_ruangan='.$id_ruangan.'&kategori='.strtoupper($kat)) }}"
                   class="btn btn-sm btn-outline-{{ $color }} {{ strtoupper($kategori ?? '') == strtoupper($kat) ? 'active' : '' }}">
                    {{ $kat }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="table-responsive shadow-sm rounded border">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-success">
                <tr class="text-center">
                    <th width="50">No</th>
                    <th>Kode Inventaris</th>
                    <th>IP Address</th>
                    <th>Merek</th>
                    <th>Kategori</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data_perangkat as $index => $perangkat)
                <tr class="text-center" data-id="{{ $perangkat->id_perangkat }}">
                    <td>{{ $data_perangkat->firstItem() + $index }}</td>

                    <td class="fw-bold" data-col="kode_inventaris">
                        {{ $perangkat->kode_inventaris }}
                    </td>

                    <td data-col="alamat_ip">
                        <code>{{ $perangkat->alamat_ip }}</code>
                    </td>

                    <td data-col="merek">
                        {{ $perangkat->merek }}
                    </td>

                    <td data-col="kategori_perangkat">
                        <span class="badge bg-secondary-subtle text-secondary border">
                            {{ $perangkat->kategori_perangkat }}
                        </span>
                    </td>

                    <td>
                        <div class="btn-group">
                            <button class="btn btn-outline-warning btn-sm btn-edit"
                                data-id_perangkat="{{ $perangkat->id_perangkat }}"
                                data-kode_inventaris="{{ $perangkat->kode_inventaris }}"
                                data-alamat_ip="{{ $perangkat->alamat_ip }}"
                                data-merek="{{ $perangkat->merek }}"
                                data-id_kategori="{{ $perangkat->id_kategori_perangkat }}"
                                data-nama_kategori="{{ $perangkat->kategori_perangkat }}">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm btn-hapus"
                                data-id_perangkat="{{ $perangkat->id_perangkat }}"
                                data-kategori_perangkat="{{ $perangkat->kategori_perangkat }}"
                                data-url="{{ url('perangkat/data_perangkat/' . $perangkat->id_perangkat . '/delete') }}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fa fa-box-open d-block mb-2 fs-2"></i>
                        Belum ada perangkat di ruangan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $data_perangkat->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambahperangkat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ url('perangkat/data_perangkat') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa fa-plus-circle me-2"></i>Tambah Perangkat</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kode Inventaris</label>
                        <input type="text" class="form-control" name="kode_inventaris" placeholder="Contoh: REG-102938" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat IP Jaringan</label>
                        <input type="text" class="form-control" name="alamat_ip" placeholder="192.168.1.xx" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Merek</label>
                        <input type="text" class="form-control" name="merek" placeholder="Contoh: Dell / HP / Epson" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Perangkat</label>
                        <select class="form-select" name="id_kategori" required>
                            <option value="" hidden>-- Pilih Kategori --</option>
                            @foreach($kategori_perangkat as $kat)
                                <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" name="id_ruangan" value="{{ $id_ruangan }}">
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditperangkat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formEditperangkat" method="POST">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold">
                        <i class="fa fa-edit me-2"></i>Edit Data Perangkat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kode Inventaris</label>
                        <input type="text" class="form-control" id="edit_kode_inventaris" name="kode_inventaris" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">IP Jaringan</label>
                        <input type="text" class="form-control" id="edit_alamat_ip" name="alamat_ip" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Merek</label>
                        <input type="text" class="form-control" id="edit_merek" name="merek" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Perangkat</label>
                        <select class="form-select" id="edit_id_kategori" name="id_kategori" required>
                            @foreach($kategori_perangkat as $kat)
                                <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" id="edit_id_ruangan" name="id_ruangan" value="{{ $id_ruangan }}">
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold">Update Perangkat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const BASE_URL = "{{ url('/') }}";
</script>
<script src="{{ asset('js/data_perangkat.js') }}"></script>

@endsection