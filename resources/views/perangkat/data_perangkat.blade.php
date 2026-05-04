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
</style>

<br>

<div class="container-fluid px-0">
    <div class="d-flex align-items-center mb-4 mt-3">
        <a href="{{ url('/ruang/data_ruang') }}" class="btn btn-outline-secondary btn-sm me-3">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h5 class="mb-0">Ruangan: <span class="badge bg-primary fs-6">{{ $ruangan->nama_ruangan ?? '-' }}</span></h5>
    </div>

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

    {{-- @if ($errors->any())
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
                var tambahModal = new bootstrap.Modal(document.getElementById('modalTambahperangkat'));
                tambahModal.show();
            });
        });
    </script>
    @endif --}}

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
                class="btn btn-sm btn-outline-dark {{ !$kategori ? 'active' : '' }}">Semua</a>
            
            @foreach(['Laptop' => 'primary', 'Printer' => 'success', 'PC' => 'warning', 'Handphone' => 'info'] as $kat => $color)
                <a href="{{ url('/perangkat/data_perangkat?id_ruangan='.$id_ruangan.'&kategori='.strtoupper($kat)) }}" 
                    class="btn btn-sm btn-outline-{{ $color }} {{ strtoupper($kategori) == strtoupper($kat) ? 'active' : '' }}">
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
                    <th>No Register</th>
                    <th>IP Jaringan</th>
                    <th>Merk</th>
                    <th>Kategori</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data_perangkat as $index => $perangkat)
                <tr class="text-center">
                    <td>{{ $data_perangkat->firstItem() + $index }}</td>
                    <td class="fw-bold">{{ $perangkat->kode_perangkat }}</td>
                    <td><code>{{ $perangkat->ip_jaringan }}</code></td>
                    <td>{{ $perangkat->merek }}</td>
                    <td>
                        <span class="badge bg-secondary-subtle text-secondary border">{{ $perangkat->kategori_perangkat }}</span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ url('/perangkat/qr_png/'.$perangkat->id) }}" class="btn btn-outline-success btn-sm" title="Download QR">
                                <i class="fa fa-qrcode"></i>
                            </a>
                            <button class="btn btn-outline-warning btn-sm btn-edit" 
                                data-id="{{ $perangkat->id }}"
                                data-kode_perangkat="{{ $perangkat->kode_perangkat }}"
                                data-ip_jaringan="{{ $perangkat->ip_jaringan }}"
                                data-merek="{{ $perangkat->merek }}"
                                data-kategori_perangkat="{{ $perangkat->kategori_perangkat }}">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm btn-hapus"
                                data-id="{{ $perangkat->id }}"
                                data-kategori_perangkat="{{ $perangkat->kategori_perangkat }}"
                                data-url="{{ url('perangkat/data_perangkat/' . $perangkat->id . '/delete') }}">
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
                        <label class="form-label fw-bold">Nomer Register</label>
                        <input type="text" class="form-control" name="kode_perangkat" placeholder="Contoh: REG-102938" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">IP Jaringan</label>
                        <input type="text" class="form-control" name="ip_jaringan" placeholder="192.168.1.xx" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Merek</label>
                        <input type="text" class="form-control" name="merek" placeholder="Contoh: Dell / HP / Epson" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Perangkat</label>
                        <select class="form-select" name="kategori_perangkat" required>
                            <option value="" hidden>-- Pilih Kategori --</option>
                            <option value="LAPTOP">Laptop</option>
                            <option value="PRINTER">Printer</option>
                            <option value="PC">PC</option>
                            <option value="HANDPHONE">Handphone</option>
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
                    <h5 class="modal-title fw-bold"><i class="fa fa-edit me-2"></i>Edit Data Perangkat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomer Register</label>
                        <input type="text" class="form-control" id="edit_kode_perangkat" name="kode_perangkat" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">IP Jaringan</label>
                        <input type="text" class="form-control" id="edit_perangkat" name="ip_jaringan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Merek</label>
                        <input type="text" class="form-control" id="edit_merek" name="merek" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Perangkat</label>
                        <select class="form-select" id="edit_kategori_perangkat" name="kategori_perangkat" required>
                            <option value="LAPTOP">Laptop</option>
                            <option value="PRINTER">Printer</option>
                            <option value="PC">PC</option>
                            <option value="HANDPHONE">Handphone</option>
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
    const baseUrl     = "{{ url('') }}";
    const id_ruangan  = "{{ $id_ruangan }}";
</script>
<script src="{{ asset('js/data_perangkat.js') }}"></script>

@endsection