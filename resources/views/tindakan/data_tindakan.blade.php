@extends('layout.page')

@section('page_title', 'Data Tindakan')

@section('content')

@if(session('success'))
    <div class="alert alert-success fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<style>
    .tindakan-wrapper { background: #f4f6f9; padding: 10px 0; }
    .page-title h2 { font-size: 1.8rem; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; margin-left: 20px; }
    .page-title p  { color: #6c757d; font-size: 0.95rem; margin-bottom: 24px; margin-left: 20px; }
    .card-panel { background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); height: 100%; }
    .card-panel-title { font-size: 1.1rem; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }
    .card-panel-subtitle { font-size: 0.85rem; color: #6c757d; margin-bottom: 24px; }
    .form-label-custom { font-weight: 600; font-size: 0.9rem; color: #1a1a2e; margin-bottom: 8px; display: block; }
    .form-select-custom, .form-control-custom { border: 1.5px solid #e0e4ea; border-radius: 10px; padding: 12px 16px; font-size: 0.9rem; color: #333; background: #fff; width: 100%; transition: border-color 0.2s; appearance: auto; }
    .form-select-custom:focus, .form-control-custom:focus { border-color: #4f7ef8; outline: none; box-shadow: 0 0 0 3px rgba(79,126,248,0.1); }
    .info-box { background: #f0f4ff; border-radius: 10px; padding: 14px 16px; margin-bottom: 20px; font-size: 0.88rem; }
    .info-box .info-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; }
    .info-box .label { color: #888; }
    .info-box .value { color: #1a1a2e; font-weight: 500; }
    .badge-status { padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
    .badge-dalam-proses { background: #e0ebff; color: #2563eb; }
    .badge-pending { background: #fff3cd; color: #b45309; }
    .badge-selesai { background: #d1fae5; color: #065f46; }
    .teknisi-info { background: #f8f9fa; border: 1.5px solid #e0e4ea; border-radius: 10px; padding: 12px 16px; font-size: 0.9rem; color: #333; display: flex; align-items: center; gap: 8px; }
    .btn-simpan { background: #1a1a2e; color: #fff; border: none; border-radius: 10px; padding: 14px; font-size: 0.95rem; font-weight: 600; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: background 0.2s, transform 0.1s; margin-top: 8px; }
    .btn-simpan:hover { background: #2d2d4e; transform: translateY(-1px); }
    .pengaduan-item { border-bottom: 1px solid #f0f0f0; padding: 16px 0; cursor: pointer; }
    .pengaduan-item:last-child { border-bottom: none; }
    .pengaduan-item .nomor { font-weight: 700; font-size: 0.95rem; color: #1a1a2e; }
    .pengaduan-item .deskripsi { font-size: 0.83rem; color: #6c757d; margin-top: 2px; }
    .pengaduan-item-header { display: flex; align-items: center; justify-content: space-between; }
    .pengaduan-item .chevron { color: #aaa; font-size: 1rem; transition: transform 0.2s; margin-left: 8px; }
    .pengaduan-detail { background: #f8f9ff; border-radius: 10px; padding: 14px 16px; margin-top: 12px; font-size: 0.85rem; display: none; }
    .pengaduan-detail.show { display: block; }
    .pengaduan-detail .detail-row { margin-bottom: 5px; color: #555; }
    .pengaduan-detail .detail-row span { font-weight: 600; color: #1a1a2e; }
    textarea.form-control-custom { resize: vertical; min-height: 110px; }
    .is-invalid-custom { border-color: #dc3545 !important; }
    .invalid-feedback-custom { color: #dc3545; font-size: 0.82rem; margin-top: 4px; }
    .border-success {
        border: 2px solid #198754 !important;
    }
</style>

<div class="tindakan-wrapper">
    <div class="page-title">
        <h2>Tindakan Pengaduan</h2>
        <p>Kelola tindakan dan update status pengaduan</p>
    </div>

    <div class="row g-4">

        <div class="col-lg-6">
            <div class="card-panel">
                <div class="card-panel-title">
                    <i class="fas fa-plus-circle"></i> Tambah Tindakan Baru
                </div>
                <div class="card-panel-subtitle">Catat tindakan yang telah dilakukan untuk pengaduan</div>
                <hr>
                <form action="/tindakan/data_tindakan/simpan" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label-custom">Pilih Pengaduan</label>
                        <div class="mb-3">
                            <input type="text" id="searchRuangan" class="form-control" placeholder="Cari nama ruangan..." autocomplete="off">
                        </div>
                        <div id="selectedList" class="mb-3" style="display:none;">
                            <strong>Pengaduan Dipilih:</strong>
                            <div id="listDipilih" class="mt-2"></div>
                        </div>
                        <div id="selected_pengaduan"></div>
                        <div class="row">
                            @foreach($pengaduanAktif as $p)
                                <div class="col-md-6 mb-3 card-pengaduan" data-ruangan="{{ $p->nama_ruangan }}">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body">

                                            <h6 class="fw-bold">
                                                #{{ $p->id }} — {{ $p->nama_pengadu }}
                                            </h6>

                                            <p class="mb-1">
                                                <strong>Ruangan:</strong> {{ $p->nama_ruangan }}
                                            </p>

                                            <p class="mb-1">
                                                <strong>Kode:</strong> {{ $p->kode_perangkat ?? '-' }}
                                            </p>

                                            <p class="mb-1">
                                                <strong>Jenis:</strong> {{ $p->kategori_perangkat ?? '-' }}
                                            </p>

                                            <button type="button"
                                                class="btn btn-success btn-sm mt-2 pilihPengaduan"
                                                data-id="{{ $p->id }}">
                                                Pilih
                                            </button>

                                        </div>
                                    </div>
                                </div>

                            @endforeach
                            </div>
                        @error('id_pengaduan')
                            <div class="invalid-feedback-custom">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="info-box mb-3" id="infoBox" style="display:none;">
                        <div class="info-row">
                            <span class="label">Status Saat Ini:</span>
                            <span class="badge-status badge-pending" id="infoStatus">-</span>
                        </div>
                        <div class="label" style="margin-bottom:4px;">Masalah:</div>
                        <div class="value" id="infoMasalah">-</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Teknisi</label>
                        <div class="teknisi-info">
                            <i class="bi bi-person-badge"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <span class="ms-auto badge bg-secondary" style="font-size:0.75rem;">
                                {{ Auth::user()->role ?? 'Teknisi' }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Tanggal &amp; Waktu</label>
                        <input type="datetime-local" class="form-control" name="tanggal_waktu" id="tanggal_waktu">
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Kondisi / Deskripsi Tindakan</label>
                        <textarea class="form-control-custom @error('kondisi') is-invalid-custom @enderror"
                            name="kondisi" rows="4"
                            placeholder="Jelaskan tindakan yang telah dilakukan..."
                            required>{{ old('kondisi') }}</textarea>
                        @error('kondisi')
                            <div class="invalid-feedback-custom">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Update Status</label>
                        <select class="form-select-custom @error('status') is-invalid-custom @enderror"
                                name="status" required>
                            <option value="Pending"      {{ old('status') == 'Pending'      ? 'selected' : '' }}>Pending</option>
                            <option value="Dalam Proses" {{ old('status','Dalam Proses') == 'Dalam Proses' ? 'selected' : '' }}>Dalam Proses</option>
                            <option value="Selesai"      {{ old('status') == 'Selesai'      ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback-custom">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-simpan">
                        <i class="bi bi-floppy"></i> Simpan Tindakan
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-panel">
                <div class="card-panel-title">
                    <i class="far fa-clipboard"></i> Pengaduan Aktif
                </div>
                <div class="card-panel-subtitle">Pengaduan yang sedang dalam proses penanganan</div>
                <hr>
                @forelse($pengaduanAktif as $p)
                <div class="pengaduan-item" onclick="toggleDetail('detail-{{ $loop->index }}', this)">
                    <div class="pengaduan-item-header">
                        <div>
                            <div class="nomor">{{ $p->nama_ruangan }}</div>
                            <div class="deskripsi">
                                <span style="color:#1a1a2e;font-weight:600;">{{ $p->nama_pengadu }}</span>
                                &nbsp;·&nbsp;
                                @if($p->kode_perangkat && $p->kode_perangkat !== '-')
                                    {{ $p->kode_perangkat }}
                                @else
                                    Tanpa perangkat
                                @endif
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span class="badge-status
                                @if($p->status_terakhir === 'Dalam Proses') badge-dalam-proses
                                @elseif($p->status_terakhir === 'Selesai')  badge-selesai
                                @else                                        badge-pending
                                @endif">
                                {{ $p->status_terakhir }}
                            </span>
                            <i class="bi bi-chevron-down chevron"></i>
                        </div>
                    </div>

                    <div class="pengaduan-detail" id="detail-{{ $loop->index }}">
                        <div class="detail-row">
                            ID Pengaduan: <span>#{{ $p->id }}</span>
                        </div>
                        <div class="detail-row">Pelapor: <span>{{ $p->nama_pengadu ?? '-' }}</span></div>
                        <div class="detail-row">Ruangan: <span>{{ $p->nama_ruangan }}</span></div>
                        @if($p->kode_perangkat && $p->kode_perangkat !== '-')
                        <div class="detail-row">Perangkat: <span>{{ $p->kode_perangkat }}</span></div>
                        <div class="detail-row">Merek: <span>{{ $p->merek }}</span></div>
                        <div class="detail-row">Kategori: <span>{{ $p->kategori_perangkat }}</span></div>
                        @endif
                        <div class="detail-row">Masalah: <span>{{ $p->deskripsi_masalah ?? '-' }}</span></div>
                        <div class="detail-row">Tanggal: <span>{{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}</span></div>
                        @if($p->kondisi_terakhir)
                            <hr style="margin:8px 0;border-color:#e0e4ea;">
                            <div class="detail-row" style="font-weight:600;color:#1a1a2e;">Tindakan Terakhir:</div>
                            <div class="detail-row">{{ $p->kondisi_terakhir }}</div>
                            <div class="detail-row">Teknisi: <span>{{ $p->nama_teknisi_terakhir }}</span></div>
                        @endif
                    </div>
                </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3"></i>
                        <p class="mt-2">Tidak ada pengaduan aktif</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>

    let search = document.getElementById('searchRuangan');
    if (search) {
        search.addEventListener('input', function () {
            let keyword = this.value.toLowerCase();
            let items   = document.querySelectorAll('.card-pengaduan');

            items.forEach(item => {
                let ruangan = item.dataset.ruangan.toLowerCase();
                item.style.display = ruangan.includes(keyword) ? '' : 'none';
            });
        });
    }

    function toggleDetail(id, el) {
        const detail  = document.getElementById(id);
        const chevron = el.querySelector('.chevron');

        detail.classList.toggle('show');
        chevron.style.transform = detail.classList.contains('show')
            ? 'rotate(180deg)'
            : 'rotate(0)';
    }

    let pengaduanDipilih = [];

    document.querySelectorAll('.pilihPengaduan').forEach(btn => {
        btn.addEventListener('click', function (e) {

            e.preventDefault(); 

            let id   = this.dataset.id;
            let card = this.closest('.card');
            let nama = card.querySelector('h6').innerText;

            if (pengaduanDipilih.find(p => p.id == id)) {
                alert('Pengaduan sudah dipilih!');
                return;
            }

            pengaduanDipilih.push({ id, nama });

            card.classList.add('border-success');

            renderPengaduanDipilih();
        });
    });

    function renderPengaduanDipilih() {
        let container = document.getElementById('listDipilih');
        let hidden    = document.getElementById('selected_pengaduan');

        document.getElementById('selectedList').style.display = 'block';

        container.innerHTML = '';
        hidden.innerHTML    = '';

        pengaduanDipilih.forEach((p, index) => {

            container.innerHTML += `
                <span class="badge bg-success me-2 mb-2 p-2" style="color: white !important;">
                    ${p.nama}
                    <button type="button"
                        class="btn-close btn-close-white ms-2"
                        onclick="hapusPengaduan(${index})">
                    </button>
                </span>
            `;

            hidden.innerHTML += `
                <input type="hidden" name="id_pengaduan[]" value="${p.id}">
            `;
        });

        if (pengaduanDipilih.length === 0) {
            document.getElementById('selectedList').style.display = 'none';
        }
    }

    function hapusPengaduan(index) {
        pengaduanDipilih.splice(index, 1);

        renderPengaduanDipilih();

        document.querySelectorAll('.card').forEach(card => {
            card.classList.remove('border-success');
        });

        pengaduanDipilih.forEach(p => {
            let btn = document.querySelector(`.pilihPengaduan[data-id="${p.id}"]`);
            if (btn) btn.closest('.card').classList.add('border-success');
        });
    }
</script>

@endsection