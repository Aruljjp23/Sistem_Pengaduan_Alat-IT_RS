@extends('layout.page')

@section('page_title', 'Form Pengaduan')

<link rel="stylesheet" href="{{ asset('css/form_pengaduan.css') }}">

@section('content')

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

<div id="submitLoading" class="loading-overlay">
    <div class="loader-box">
        <div class="custom-spinner"></div>
        <div class="loading-title">Sedang Mengirim Laporan...</div>
        <div class="loading-subtitle">Mohon tunggu, sedang memproses data &amp; mengirim notifikasi.</div>
    </div>
</div>

<div class="form-wrapper py-3 py-md-4">

    <div class="card card-modern mb-4" id="card_scan">
        <div class="card-header-modern">
            <div class="header-icon"><i class="fa-solid fa-qrcode"></i></div>
            <div>
                <h5 class="mb-0 fw-bold">Scan QR Code Perangkat</h5>
                <p class="text-muted small mb-0">Arahkan kamera ke label qr code pada perangkat</p>
            </div>
        </div>

        <div class="card-body p-4 text-center">

            <div class="scanner-container" id="scanner_container">
                <video id="reader-video" autoplay muted playsinline webkit-playsinline></video>
                <canvas id="decode-canvas"></canvas>
                <div class="scanner-overlay">
                    <div class="scanner-frame">
                        <div class="scan-line"></div>
                        <div class="corner-br"></div>
                        <div class="corner-bl"></div>
                    </div>
                </div>
            </div>

            <div id="mobile_qr_upload" style="display:none;" class="mt-3">
                <label class="btn btn-primary w-100 py-3">
                    <i class="fa-solid fa-camera me-2"></i>
                    Ambil Foto QR Code
                    <input type="file" id="qr_image_input" accept="image/*" hidden>
                </label>
            </div>

            <div id="camera_error_box">
                <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
                <div id="camera_error_msg"></div>
            </div>

            <div id="scan_alert_success" class="alert alert-success mt-2 py-2 d-none" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> Perangkat berhasil ditambahkan!
            </div>

            <div class="row mt-3 mt-md-4 text-start g-2 g-md-3 scan-info-row">
                <div class="col-12 col-md-4">
                    <label class="form-label-custom">TERAKHIR DI-SCAN</label>
                    <input type="text" id="kode_scan" class="form-control-modern bg-light" readonly placeholder="-">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label-custom">MEREK</label>
                    <input type="text" id="merek_scan" class="form-control-modern bg-light" readonly placeholder="-">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label-custom">KATEGORI</label>
                    <input type="text" id="kategori_perangkat_scan" class="form-control-modern bg-light" readonly placeholder="-">
                </div>
            </div>

            <div class="mt-4 text-start" id="section_manual_search">

                <div class="d-flex align-items-center gap-2 mb-3">
                    <div style="flex:1;height:1px;background:#e5e7eb;"></div>
                    <span class="text-muted small fw-semibold px-2" style="white-space:nowrap;">
                        <i class="fa-solid fa-keyboard me-1"></i> CARI MANUAL
                    </span>
                    <div style="flex:1;height:1px;background:#e5e7eb;"></div>
                </div>

                {{-- Input pencarian --}}
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    </span>
                    <input
                        type="text"
                        id="manual_search_input"
                        class="form-control border-start-0 ps-0"
                        placeholder="Cari kode, merek, IP, atau kategori perangkat..."
                        autocomplete="off"
                        style="border-radius:0;">
                    <button
                        type="button"
                        class="btn btn-primary px-4"
                        id="btn_manual_search"
                        onclick="doManualSearch()">
                        Cari
                    </button>
                </div>
                <small class="text-muted mt-1 d-block">
                    Masukkan kata kunci: kode inventaris, merek, alamat IP, atau nama kategori.
                </small>

                <div id="manual_search_loading" class="text-center py-3 d-none">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    <span class="text-muted small">Mencari perangkat...</span>
                </div>

                <div id="manual_search_empty" class="d-none mt-3">
                    <div class="alert alert-warning py-2 mb-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span id="manual_search_empty_msg">Perangkat tidak ditemukan.</span>
                    </div>
                </div>

                <div id="manual_search_results" class="mt-3 d-none">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label-custom mb-0">
                            HASIL PENCARIAN
                            <span id="manual_result_count"
                                  class="badge bg-primary ms-1" style="font-size:0.7rem;">0</span>
                        </label>
                        <button type="button"
                                class="btn btn-sm btn-outline-secondary"
                                onclick="tutupHasilCari()">
                            <i class="fa-solid fa-xmark me-1"></i>Tutup
                        </button>
                    </div>
                    <div class="table-responsive rounded border shadow-sm">
                        <table class="table table-hover align-middle mb-0" style="font-size:0.875rem;">
                            <thead style="background:#f0fdf4;">
                                <tr>
                                    <th class="border-0 ps-3">No</th>
                                    <th class="border-0">Kode Inventaris</th>
                                    <th class="border-0">IP Jaringan</th>
                                    <th class="border-0">Merek</th>
                                    <th class="border-0">Kategori</th>
                                    <th class="border-0 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="manual_result_tbody"></tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="col-12 mt-3 mt-md-4 text-start" id="section_scan_list" style="display:none;">
                <div class="p-3 border-dashed rounded-3 bg-white">
                    <label class="form-label-custom d-block mb-2">DAFTAR PERANGKAT YANG TER-SCAN</label>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 scan-table">
                            <thead class="bg-soft-indigo">
                                <tr>
                                    <th class="border-0">No</th>
                                    <th class="border-0">Kode</th>
                                    <th class="border-0">Kategori</th>
                                    <th class="border-0">Merek</th>
                                    <th class="border-0">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="body_scan_list"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card card-modern shadow-lg" id="form_pengaduan" style="display:none;">
        <div class="card-header-modern bg-indigo text-white">
            <div class="header-icon bg-white text-indigo"><i class="fa-solid fa-file-signature"></i></div>
            <div>
                <h5 class="mb-0 fw-bold">Form Pengaduan</h5>
                <p class="text-white-50 small mb-0">Lengkapi data kerusakan di bawah ini</p>
            </div>
        </div>

        <div class="card-body p-4">
            <form action="{{ url('/pengaduan/simpan') }}" method="POST" id="mainForm">
                @csrf
                <div id="hidden_perangkat_ids"></div>
                <input type="hidden" name="id_ruangan" value="{{ $ruangan->id_ruangan }}">

                <div class="row g-3 g-md-4">
                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">NAMA PENGADU</label>
                        <div class="input-group-modern">
                            <span class="input-icon"><i class="fa-solid fa-user"></i></span>
                            <input type="text" class="form-control-modern" name="nama_pengadu"
                                   value="{{ Auth::user()->name }}" readonly>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">RUANGAN</label>
                        <input type="text" class="form-control-modern bg-light"
                               value="{{ $ruangan->nama_ruangan }}" readonly>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label-custom">LOKASI</label>
                        <input type="text" class="form-control-modern bg-light"
                               value="{{ $ruangan->lokasi }}" readonly>
                    </div>

                    <div class="col-12" id="section_perangkat_dipilih" style="display:none;">
                        <div class="p-3 border rounded-3 bg-light">
                            <label class="form-label-custom d-block mb-2">
                                PERANGKAT TERPILIH (<span id="form_count">0</span>)
                            </label>
                            <div id="badge_perangkat_container" class="d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label-custom">DESKRIPSI KERUSAKAN</label>
                        <textarea name="deskripsi_masalah" class="form-control-modern" rows="5"
                                  placeholder="Jelaskan detail masalah"
                                  required></textarea>
                    </div>

                    <div class="col-12 mt-2">
                        <div class="d-flex flex-column flex-md-row gap-2 gap-md-3 btn-action-row">
                            <button type="submit" class="btn btn-indigo py-3 fw-bold shadow">
                                <i class="fa-solid fa-paper-plane me-2"></i>Kirim Pengaduan
                            </button>
                            <button type="button" class="btn btn-light border py-3 px-4" onclick="kembaliScan()">
                                <i class="fa-solid fa-rotate-left me-2"></i>Tambah Perangkat Lagi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script src="{{ asset('js/form_pengaduan.js') }}"></script>

@endsection