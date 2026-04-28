@extends('layout.page')

@section('page_title', 'Form Pengaduan')

<link rel="stylesheet" href="{{ asset('css/form_pengaduan.css') }}">

@section('content')

<br>

<div class="card mb-3" id="card_scan">
    <div class="card-header">
        <h5><i class="fa-solid fa-barcode"></i> Scan Barcode Perangkat</h5>
        <small>Arahkan kamera ke barcode perangkat</small>
    </div>

    <div class="card-body text-center">
        <div id="reader"></div>

        <div class="row mt-3 text-start">
            <div class="col-md-4 mb-2">
                <label>Kode Perangkat</label>
                <input type="text" id="kode_scan" class="form-control" readonly>
            </div>
            <div class="col-md-4 mb-2">
                <label>Merek</label>
                <input type="text" id="merek_scan" class="form-control" readonly>
            </div>
            <div class="col-md-4 mb-2">
                <label>Kategori Perangkat</label>
                <input type="text" id="kategori_perangkat_scan" class="form-control" readonly>
            </div>
        </div>

        <div class="text-center mt-3">
            <button type="button" class="btn btn-warning" onclick="lewatiScan()">
                <i class="fa-solid fa-forward"></i> Lewati Scan Barcode
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPilihPerangkat" tabindex="-1"
     aria-labelledby="modalPilihPerangkatLabel" aria-hidden="true"
     data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihPerangkatLabel">
                    <i class="fa-solid fa-desktop"></i> Pilih Perangkat
                </h5>
            </div>

            <div class="modal-body">
                <p class="text-muted small mb-3">
                    Pilih satu atau lebih perangkat yang bermasalah.
                    Kosongkan pilihan jika perangkat tidak terdaftar.
                </p>

                <div class="mb-3">
                    <input type="text" id="searchPerangkat" class="form-control"
                           placeholder="🔍 Cari kode / merek / kategori...">
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tabelPerangkat">
                        <thead class="table-primary">
                            <tr>
                                <th style="width:40px;">
                                <input type="checkbox" id="checkAll" title="Pilih semua">
                                </th>
                                <th>Kode</th>
                                <th>Kategori</th>
                                <th>Merek</th>
                            </tr>
                        </thead>
                        <tbody id="bodyPerangkat">
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                    <span class="ms-2 text-muted">Memuat data perangkat...</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <span id="jumlahDipilih" class="text-muted small">0 perangkat dipilih</span>
                <div>
                    <button type="button" class="btn btn-secondary me-2"
                            onclick="lanjutTanpaPerangkat()">
                        <i class="fa-solid fa-ban"></i> Tanpa Perangkat
                    </button>
                    <button type="button" class="btn btn-primary"
                            onclick="konfirmasiPilihPerangkat()">
                        <i class="fa-solid fa-check"></i> Konfirmasi
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="card" id="form_pengaduan" style="display:none;">

    <div class="card-body">
        <form action="{{ url('/pengaduan/simpan') }}" method="POST">
            @csrf

            <div id="hidden_perangkat_ids"></div>
            <input type="hidden" name="id_ruangan" value="{{ $ruangan->id }}">

            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label class="mb-1">Pengadu</label>
                    <input type="text" class="form-control" name="nama_pengadu" placeholder="Nama lengkap">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 mb-3">
                    <label class="mb-1">Ruangan</label>
                    <input type="text" class="form-control"
                           value="{{ $ruangan->nama_ruangan }}" readonly>
                </div>
                <div class="col-sm-6 mb-3">
                    <label class="mb-1">Lokasi</label>
                    <input type="text" class="form-control"
                           value="{{ $ruangan->lokasi }}" readonly>
                </div>
            </div>

            <div class="mb-3" id="section_perangkat_dipilih" style="display:none;">
                <label class="mb-1 fw-semibold">Perangkat yang Dilaporkan</label>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Kategori</th>
                                <th>Merek</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="body_perangkat_dipilih"></tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-1"
                        onclick="bukaModalPerangkat()">
                    <i class="fa-solid fa-pen"></i> Ubah Pilihan Perangkat
                </button>
            </div>

            <div class="mb-3" id="section_tanpa_perangkat" style="display:none;">
                <div class="alert alert-warning py-2 mb-2">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Tidak ada perangkat terdaftar yang dipilih.
                    <button type="button" class="btn btn-sm btn-link p-0 ms-2"
                            onclick="bukaModalPerangkat()">Pilih perangkat</button>
                </div>
                <div class="row">
                    <div class="col-sm-4 mb-2">
                        <label>Kode Perangkat <span class="fw-normal text-muted">(opsional)</span></label>
                        <input type="text" class="form-control" name="kode_perangkat"
                               placeholder="Kode manual">
                    </div>
                    <div class="col-sm-4 mb-2">
                        <label>Merek <span class="fw-normal text-muted">(opsional)</span></label>
                        <input type="text" class="form-control" name="merek"
                               placeholder="Merek manual">
                    </div>
                    <div class="col-sm-4 mb-2">
                        <label>Kategori <span class="fw-normal text-muted">(opsional)</span></label>
                        <input type="text" class="form-control" name="kategori_perangkat"
                               placeholder="Kategori manual">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="mb-1">Tanggal &amp; Waktu</label>
                <input type="datetime-local" class="form-control" name="tanggal_waktu" id="tanggal_waktu" style="max-width:320px;">
            </div>

            <div class="mb-3">
                <label class="mb-1">Deskripsi Masalah</label>
                <textarea name="deskripsi_masalah" class="form-control" rows="6"
                          placeholder="Jelaskan masalah secara singkat dan jelas..."></textarea>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
                <button type="button" class="btn btn-secondary"
                        onclick="kembaliScan()">
                    <i class="fa-solid fa-barcode"></i> Kembali Scan
                </button>
            </div>

        </form>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    const id_ruangan = "{{ $ruangan->id }}";
</script>
<script src="{{ asset('js/form_pengaduan.js') }}"></script>

@endsection