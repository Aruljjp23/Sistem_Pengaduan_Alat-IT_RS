@extends('layout.page')

@section('page_title', 'Data Ruangan')

@section('content')
<link rel="stylesheet" href="{{ asset('css/data_ruang.css') }}">
<br>

@if(session('success'))
    <div class="alert alert-success fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form id="formSearch">
  <div class="search col-md-5">
    <div class="input-group">
        <input id="search" type="text" class="form-control" placeholder="search" name="search" value="{{ request()->get('search', '') }}">
        <button type="button" class="btn btn-primary" onclick="navigateWithParams()">Submit</button>
    </div>
  </div>
</form>
<br>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahruangan">
    <i class="bi bi-person-plus-fill"></i> Add ruangan
</button>
<table class="table table-bordered table-hover" style="margin-top:20px">
    <thead class="table" style="background-color: skyblue">
        <tr>
            <th class="text-center" scope="col">No</th>
            <th class="text-center" scope="col">Nama Ruangan</th>
            <th class="text-center" scope="col">Lokasi</th>
            <th class="text-center" scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @if($data_ruangan->count() == 0)
            <tr>
                <td colspan="5" class="text-center text-muted">Tidak ada data ruangan</td>
            </tr>
        @else
            @foreach ($data_ruangan as $index => $ruangan)
            <tr id="row-{{ $ruangan->id}}">
                <td class="text-center">{{ $offset + (($currentLantaiPage - 1) * $perPage) + $index + 1 }}</td>
                <td class="text-center">
                    <a href="{{ url('/perangkat/data_perangkat?id_ruangan=' . $ruangan->id) }}">
                        {{ $ruangan->nama_ruangan }}
                    </a>
                </td>
                <td class="text-center">{{ $ruangan->lokasi }}</td>
                <td class="text-center">
                    <button class="btn btn-warning btn-sm btn-edit"
                        data-id="{{ $ruangan->id }}"
                        data-nama_ruangan="{{ $ruangan->nama_ruangan }}"
                        data-lantai="{{ $ruangan->lokasi }}">
                        <i class="fa-solid fa-pencil"></i>
                    </button>
                    <button class="btn btn-danger btn-sm btn-hapus"
                        data-id="{{ $ruangan->id }}"
                        data-nama_ruangan="{{ $ruangan->nama_ruangan }}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>

@php $search = request()->get('search', ''); @endphp

<nav aria-label="Page navigation">
    <ul class="pagination">

        <li class="page-item {{ $data_ruangan->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $data_ruangan->previousPageUrl() ? $data_ruangan->previousPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        @for ($i = 1; $i <= $data_ruangan->lastPage(); $i++)
            <li class="page-item {{ $data_ruangan->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $data_ruangan->url($i) . '&' . http_build_query(request()->except('page')) }}">
                    @if ($search || !isset($halamanLantai1))
                        {{ $i }}
                    @elseif ($i <= $halamanLantai1)
                        Lantai 1 ({{ $i }})
                    @elseif ($i <= $halamanLantai1 + $halamanLantai2)
                        Lantai 2 ({{ $i - $halamanLantai1 }})
                    @else
                        Lantai 3 ({{ $i - $halamanLantai1 - $halamanLantai2 }})
                    @endif
                </a>
            </li>
        @endfor

        <li class="page-item {{ !$data_ruangan->hasMorePages() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $data_ruangan->nextPageUrl() ? $data_ruangan->nextPageUrl() . '&' . http_build_query(request()->except('page')) : '#' }}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>

    </ul>
</nav>

<div class="modal fade" id="modalTambahruangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ url('ruang/data_ruang') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Ruangan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Ruangan</label>
                        <input type="text" class="form-control" name="nama_ruangan" placeholder="Masukkan nama ruangan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lantai</label>
                        <select class="form-control" name="lantai" aria-label="Default select example">
                            <option selected><---- Pilih Lantai ----></option>
                            <option value="Lantai 1">Lantai 1</option>
                            <option value="Lantai 2">Lantai 2</option>
                            <option value="Lantai 3">Lantai 3</option>
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

<div class="modal fade" id="modalEditruangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditruangan" method="POST">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit ruangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Ruangan</label>
                        <input type="text" class="form-control" id="edit_ruangan" name="nama_ruangan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lantai</label>
                        <select class="form-control" name="lokasi" id="edit_lokasi" aria-label="Default select example">
                            <option selected><---- Pilih Lantai ----></option>
                            <option value="Lantai 1">Lantai 1</option>
                            <option value="Lantai 2">Lantai 2</option>
                            <option value="Lantai 3">Lantai 3</option>
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

<div class="modal fade" id="modalHapusruangan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Hapus ruangan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2rem;"></i>
                <p class="mt-2">Apakah Anda yakin ingin menghapus ruangan <strong id="hapus_nama_ruangan"></strong>?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="formHapus" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const baseUrl = "{{ url('/') }}";
</script>   

<script src="{{ asset('/js/data_ruang.js') }}"></script>

@endsection