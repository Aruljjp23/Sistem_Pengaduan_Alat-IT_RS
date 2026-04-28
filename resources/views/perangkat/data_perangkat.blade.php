@extends('layout.page')

@section('page_title', 'Data Perangkat')

@section('content')
<style>
    @media (max-width: 768px) {

        .search.col-md-5 {
            width: 100% !important;
            max-width: 100% !important;
        }

        .search .input-group {
            width: 100%;
        }

        .btn[data-bs-target="#modalTambahperangkat"] {
            display: block;
            width: 100%;
            margin-top: 6px;
        }

        .btn.btn-secondary[href*="data_ruang"] {
            display: block;
            width: 100%;
            margin-bottom: 6px;
        }

        table.table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            white-space: nowrap;
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

        .modal-body input.form-control {
            font-size: 16px;
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

        .modal-body input.form-control {
            font-size: 15px;
        }
    }
</style>
<br>

<h5>Ruangan: <strong>{{ $ruangan->nama_ruangan ?? '-' }}</strong></h5>

@if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{url('/perangkat/data_perangkat/cari')}}" method="GET" id="formSearch">
<form id="formSearch">
  <div class="search col-md-5">
    <div class="input-group">
        <input type="hidden" id="id_ruangan" value="{{ $id_ruangan }}">
        <input id="search" type="text" class="form-control" placeholder="search" name="search" value="{{ request()->get('search', '') }}">
        <button type="button" class="btn btn-primary" onclick="navigateWithParams()">Submit</button>
    </div>
  </div>
</form>
<br>

<div class="col-md-12">

    <a href="{{ url('/perangkat/data_perangkat?id_ruangan='.$id_ruangan) }}"
    class="btn btn-secondary btn-sm">
    Semua
    </a>

    <a href="{{ url('/perangkat/data_perangkat?id_ruangan='.$id_ruangan.'&kategori=LAPTOP') }}"
    class="btn btn-primary btn-sm {{ $kategori == 'Laptop' ? 'active' : '' }}">
    Laptop
    </a>

    <a href="{{ url('/perangkat/data_perangkat?id_ruangan='.$id_ruangan.'&kategori=PRINTER') }}"
    class="btn btn-success btn-sm {{ $kategori == 'Printer' ? 'active' : '' }}">
    Printer
    </a>

    <a href="{{ url('/perangkat/data_perangkat?id_ruangan='.$id_ruangan.'&kategori=PC') }}"
    class="btn btn-warning btn-sm {{ $kategori == 'PC' ? 'active' : '' }}">
    PC
    </a>

    <a href="{{ url('/perangkat/data_perangkat?id_ruangan='.$id_ruangan.'&kategori=HANDPHONE') }}"
    class="btn btn-info btn-sm {{ $kategori == 'PC' ? 'active' : '' }}">
    Handphone
    </a>
    
    <div class="col-md-3" style="float:inline-end">
        <a href="{{ url('/ruang/data_ruang') }}" class="btn btn-secondary mb-0">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahperangkat">
            <i class="bi bi-person-plus-fill"></i> Add Perangkat
        </button>
    </div>
</div>

<table class="table table-bordered table-hover" style="margin-top:20px">
    <thead class="table" style="background-color:orange">
        <tr class="text-center">
            <th scope="col">No</th>
            <th scope="col">No Register</th>
            <th scope="col">IP Jaringan</th>
            <th scope="col">Merk</th>
            <th scope="col">Kategori Perangkat</th>
            <th scope="col">Ruangan</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @if($data_perangkat->isEmpty())
            <tr>
                <td colspan="5" class="text-center text-muted">Tidak ada data perangkat</td>
            </tr>
        @else
            @foreach ($data_perangkat as $index => $perangkat)
            <tr>
                <td>{{ $data_perangkat->firstItem() + $index }}</td> 
                <td>{{ $perangkat->kode_perangkat }}</td>
                <td>{{ $perangkat->ip_jaringan }}</td>
                <td>{{ $perangkat->merek }}</td>
                <td>{{ $perangkat->kategori_perangkat }}</td>
                <td>{{ $perangkat->nama_ruangan ?? '-' }}</td>
                <td>
                    <a href="{{ url('/perangkat/qr_png/'.$perangkat->id) }}" 
                        class="btn btn-success btn-sm">
                        <i class="fa-solid fa-download"></i>
                    </a>
                    <button class="btn btn-warning btn-sm btn-edit"
                        data-id="{{ $perangkat->id }}"
                        data-kode_perangkat="{{ $perangkat->kode_perangkat }}"
                        data-ip_jaringan="{{ $perangkat->ip_jaringan }}"
                        data-merek="{{ $perangkat->merek }}"
                        data-kategori_perangkat="{{ $perangkat->kategori_perangkat }}">
                        <i class="fa-solid fa-pencil"></i>
                    </button>
                    <button class="btn btn-danger btn-sm btn-hapus"
                        data-id="{{ $perangkat->id }}"
                        data-ip_jaringan="{{ $perangkat->ip_jaringan }}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>

<nav aria-label="Page navigation">
    <ul class="pagination">

        <li class="page-item {{ $data_perangkat->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link"
               href="{{ $data_perangkat->previousPageUrl() ? $data_perangkat->appends(request()->except('page'))->previousPageUrl() : '#' }}">
                &laquo;
            </a>
        </li>

        @for ($i = 1; $i <= $data_perangkat->lastPage(); $i++)
            <li class="page-item {{ $data_perangkat->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link"
                   href="{{ $data_perangkat->appends(request()->except('page'))->url($i) }}">
                    {{ $i }}
                </a>
            </li>
        @endfor

        <li class="page-item {{ !$data_perangkat->hasMorePages() ? 'disabled' : '' }}">
            <a class="page-link"
               href="{{ $data_perangkat->nextPageUrl() ? $data_perangkat->appends(request()->except('page'))->nextPageUrl() : '#' }}">
                &raquo;
            </a>
        </li>

    </ul>
</nav>

<div class="modal fade" id="modalTambahperangkat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('perangkat/data_perangkat') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah perangkat</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nomer Register</label>
                        <input type="text" class="form-control" name="kode_perangkat" placeholder="Masukkan nomer register" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">IP Jaringan</label>
                        <input type="text" class="form-control" name="ip_jaringan" placeholder="Masukkan ip jaringan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Merek</label>
                        <input type="text" class="form-control" name="merek" placeholder="Masukkan merek" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Perangkat</label>
                        <input type="text" class="form-control" name="kategori_perangkat" placeholder="Masukkan kategori perangkat" required>
                    </div>
                </div>
                <input type="hidden" name="id_ruangan" value="{{ $id_ruangan }}">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditperangkat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditperangkat" method="POST">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit perangkat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nomer Register</label>
                        <input type="text" class="form-control" id="edit_kode_perangkat" name="kode_perangkat" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">IP Jaringan</label>
                        <input type="text" class="form-control" id="edit_perangkat" name="ip_jaringan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Merek</label>
                        <input type="text" class="form-control" id="edit_merek" name="merek" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Perangkat</label>
                        <input type="text" class="form-control" id="edit_kategori_perangkat" name="kategori_perangkat" required>
                    </div>
                </div>
                <input type="hidden" id="edit_id_ruangan" name="id_ruangan" value="{{ $id_ruangan }}">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHapusperangkat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus Perangkat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2rem;"></i>
                <p class="mt-2">Apakah anda yakin ingin menghapus perangkat <strong id="hapus_ip_jaringan"></strong>?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="formHapus" method="POST">
                    @csrf
                    <input type="hidden" name="id_ruangan" value="{{ $id_ruangan }}">
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const baseUrl = "{{ url('') }}";
    const id_ruangan = "{{ $id_ruangan }}";
</script>
<script src="{{ asset('js/data_perangkat.js') }}"></script>

@endsection