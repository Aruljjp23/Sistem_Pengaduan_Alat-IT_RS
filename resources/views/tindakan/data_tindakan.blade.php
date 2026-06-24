@extends('layout.page')

@section('page_title', 'Data Tindakan')

@section('content')

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

<style>
    :root {
        --primary-dark: #1e293b;
        --accent-blue: #3b82f6;
        --bg-main: #f8fafc;
        --border-color: #e2e8f0;
    }

    .tindakan-wrapper { background: var(--bg-main); padding: 20px 0; }
    
    .page-header { margin-bottom: 2rem; padding-left: 10px; }
    .page-header h2 { font-size: 1.75rem; font-weight: 800; color: var(--primary-dark); letter-spacing: -0.025em; }
    .page-header p { color: #64748b; font-size: 0.95rem; }

    .card-modern { 
        background: #fff; 
        border: none;
        border-radius: 20px; 
        padding: 24px; 
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.01);
        height: 100%; 
        transition: all 0.3s ease;
    }

    .section-title { 
        font-size: 1.1rem; 
        font-weight: 700; 
        color: var(--primary-dark); 
        margin-bottom: 1.5rem; 
        display: flex; 
        align-items: center; 
        gap: 10px; 
    }

    .search-box-custom {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 10px 15px;
        transition: all 0.2s;
    }

    .search-box-custom:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .card-pengaduan-item {
        border: 2px solid var(--border-color);
        border-radius: 15px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
    }

    .card-pengaduan-item.active-select {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .form-label-bold { 
        font-weight: 700; 
        font-size: 0.85rem; 
        color: #475569; 
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
    }

    .form-control-modern {
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .form-control-modern:focus {
        background: #fff;
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .teknisi-pill {
        background: #fff;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 12px;
        display: flex;
        align-items: center;
    }

    .btn-submit-modern {
        background: var(--primary-dark);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 16px;
        font-weight: 700;
        width: 100%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }

    .btn-submit-modern:hover {
        background: #0f172a;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    .list-item-aktif {
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 12px;
        background: #fff;
        border: 1px solid var(--border-color);
        transition: transform 0.2s;
    }

    .list-item-aktif:hover {
        transform: scale(1.01);
        border-color: var(--accent-blue);
    }

    .status-badge-modern {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .badge-p-dalam-proses { background: #dbeafe; color: #1e40af; }
    .badge-p-pending { background: #fef3c7; color: #92400e; }
    .badge-p-selesai { background: #d1fae5; color: #065f46; }

    .mobile-detail-area {
        background: #f8fafc;
        border-radius: 12px;
        padding: 15px;
        margin-top: 10px;
        border-left: 4px solid var(--accent-blue);
    }

    .scroll-container {
        max-height: 500px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .scroll-container::-webkit-scrollbar { width: 5px; }
    .scroll-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="tindakan-wrapper">
    <div class="container-fluid">
        <div class="page-header">
            <h2>Tindakan Laporan</h2>
            <p>Selesaikan kendala dan perbarui status penanganan perangkat.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card-modern">
                    <div class="section-title">
                        <i class="fas fa-tools text-primary"></i> Form Input Tindakan
                    </div>

                    <form action="/tindakan/data_tindakan/simpan" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label-bold">Cari Ruangan / Lokasi</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-transparent border-end-0 border-2 rounded-start-3"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" id="searchRuangan" class="form-control border-start-0 border-2 shadow-none search-box-custom rounded-end-3" placeholder="Contoh: Ruang Server, R.302...">
                            </div>
                            
                            <div id="selectedList" class="mb-3" style="display:none;">
                                <label class="form-label-bold text-success"><i class="fas fa-check-double me-1"></i> Pengaduan Terpilih:</label>
                                <div id="listDipilih" class="d-flex flex-wrap gap-2"></div>
                            </div>

                            <div id="selected_pengaduan"></div>

                            <div class="scroll-container px-1 mt-3">
                                <div class="row g-3">
                                    @foreach($pengaduanAktif as $p)
                                    <div class="col-md-6 card-pengaduan" data-ruangan="{{ $p->nama_ruangan }}">
                                        <div class="card-pengaduan-item shadow-sm">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span class="fw-bold text-primary">#{{ $p->id }}</span>
                                                <span class="status-badge-modern badge-p-{{ Str::slug($p->status_terakhir) }}">{{ $p->status_terakhir }}</span>
                                            </div>
                                            <h6 class="mb-1 fw-bold text-dark">{{ Str::limit($p->nama_ruangan, 20) }}</h6>
                                            <p class="small text-muted mb-3">{{ Str::limit($p->deskripsi_masalah, 40) }}</p>
                                            <button type="button" class="btn btn-outline-primary btn-sm w-100 rounded-3 fw-bold pilihPengaduan" data-id="{{ $p->id }}">
                                                Pilih Laporan
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label-bold">Nama Teknisi</label>
                                <div class="teknisi-pill">
                                    <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">{{ Auth::user()->name }}</div>
                                        {{-- <div class="text-muted" style="font-size: 11px;">ID: #{{ Auth::user()->id }}</div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label-bold">Waktu Eksekusi</label>
                                <input type="datetime-local" class="form-control-modern w-100 shadow-none" name="tanggal_waktu" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-bold">Detail Perbaikan / Kondisi</label>
                            <textarea class="form-control-modern w-100 shadow-none" name="kondisi" rows="4" placeholder="Jelaskan langkah-langkah teknis yang dilakukan..." required>{{ old('kondisi') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label-bold">Update Status Akhir</label>
                            <div class="d-flex gap-3">
                                @foreach(['Pending', 'Dalam Proses', 'Selesai'] as $st)
                                <div class="flex-fill">
                                    <input type="radio" class="btn-check" name="status" id="status{{ $st }}" value="{{ $st }}" {{ $st == 'Dalam Proses' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-secondary w-100 py-3 rounded-4 fw-bold" for="status{{ $st }}">{{ $st }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn-submit-modern">
                            <i class="fas fa-paper-plane me-2"></i> Simpan Catatan Tindakan
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card-modern">
                    <div class="section-title">
                        <i class="fas fa-list-ul text-primary"></i> Pantauan Pengaduan Aktif
                    </div>

                    <div class="scroll-container">
                        @forelse($pengaduanAktif as $p)
                        <div class="list-item-aktif" onclick="toggleDetail('detail-{{ $loop->index }}', this)">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light p-2 rounded-3">
                                        <i class="fas fa-building text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $p->nama_ruangan }}</div>
                                        <div class="small text-muted">{{ $p->nama_pengadu }}</div>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down text-muted transition-all chevron-icon"></i>
                            </div>

                            <div class="mobile-detail-area" id="detail-{{ $loop->index }}" style="display:none;">
                                <div class="row g-2">
                                    <div class="col-6"><small class="text-muted d-block">ID Lap.</small> <span class="fw-bold small">#{{ $p->id }}</span></div>
                                    <div class="col-6"><small class="text-muted d-block">Tgl. Pengaduan</small> <span class="fw-bold small">{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/y') }}</span></div>
                                    <div class="col-12"><hr class="my-2 opacity-50"></div>
                                    <div class="col-12"><small class="text-muted d-block">Kendala Utama</small> <span class="small">{{ $p->deskripsi_masalah }}</span></div>
                                    @if($p->kondisi_terakhir)
                                    <div class="col-12 mt-2 p-2 bg-white rounded border border-warning-subtle">
                                        <small class="text-warning fw-bold d-block"><i class="fas fa-history me-1"></i> Update Terakhir:</small>
                                        <span class="small italic text-muted">{{ $p->kondisi_terakhir }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <img src="https://illustrations.popsy.co/slate/waiting-for-response.svg" style="width: 150px;" alt="empty">
                            <p class="text-muted mt-3 fw-bold">Semua laporan sudah teratasi.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchRuangan').addEventListener('input', function () {
        let keyword = this.value.toLowerCase();
        document.querySelectorAll('.card-pengaduan').forEach(item => {
            let ruangan = item.dataset.ruangan.toLowerCase();
            item.style.display = ruangan.includes(keyword) ? '' : 'none';
        });
    });

    function toggleDetail(id, el) {
        const detail = document.getElementById(id);
        const icon = el.querySelector('.chevron-icon');
        const isShowing = detail.style.display === 'block';
        
        detail.style.display = isShowing ? 'none' : 'block';
        icon.style.transform = isShowing ? 'rotate(0deg)' : 'rotate(180deg)';
    }

    let pengaduanDipilih = [];

    document.querySelectorAll('.pilihPengaduan').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            let id = this.dataset.id;
            let cardItem = this.closest('.card-pengaduan-item');
            let nama = cardItem.querySelector('h6').innerText;

            if (pengaduanDipilih.find(p => p.id == id)) return;

            pengaduanDipilih.push({ id, nama });
            cardItem.classList.add('active-select');
            this.innerText = 'Terpilih';
            this.classList.replace('btn-outline-primary', 'btn-success');
            
            renderPengaduanDipilih();
        });
    });

    function renderPengaduanDipilih() {
        let container = document.getElementById('listDipilih');
        let hidden = document.getElementById('selected_pengaduan');
        let wrap = document.getElementById('selectedList');

        wrap.style.display = pengaduanDipilih.length > 0 ? 'block' : 'none';
        container.innerHTML = '';
        hidden.innerHTML = '';

        pengaduanDipilih.forEach((p, index) => {
            container.innerHTML += `
                <div class="badge bg-success py-2 px-3 rounded-pill d-flex align-items-center gap-2">
                    <span class="small">${p.nama}</span>
                    <i class="fas fa-times-circle" style="cursor:pointer" onclick="hapusPengaduan(${index})"></i>
                </div>
            `;
            hidden.innerHTML += `<input type="hidden" name="id_pengaduan[]" value="${p.id}">`;
        });
    }

    function hapusPengaduan(index) {
        const p = pengaduanDipilih[index];
        const btn = document.querySelector(`.pilihPengaduan[data-id="${p.id}"]`);
        
        if(btn) {
            const card = btn.closest('.card-pengaduan-item');
            card.classList.remove('active-select');
            btn.innerText = 'Pilih Laporan';
            btn.classList.replace('btn-success', 'btn-outline-primary');
        }

        pengaduanDipilih.splice(index, 1);
        renderPengaduanDipilih();
    }
</script>
@endsection