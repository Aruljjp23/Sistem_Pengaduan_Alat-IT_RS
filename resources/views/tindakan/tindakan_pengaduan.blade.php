@extends('layout.page')

@section('page_title', 'Status Tindakan')

@section('content')

<div class="container py-3" style="max-width:700px;">

    <div class="d-flex align-items-center gap-2 mb-3">
        <h5 class="mb-0">Status Tindakan</h5>
        <span class="text-muted">— {{ $namaPengadu }}</span>
    </div>

    <div class="d-flex gap-2 mb-3 flex-wrap">
        @foreach(['semua' => 'Semua', 'pending' => 'Pending', 'proses' => 'Dalam Proses', 'selesai' => 'Selesai'] as $val => $label)
        <a href="{{ route('tindakan.tindakan_pengaduan', ['status' => $val]) }}"
           class="btn btn-sm {{ $statusFilter === $val ? 'btn-dark' : 'btn-outline-secondary' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    @forelse($tindakan as $item)
    <div class="card mb-3 shadow-sm">
        <div class="card-body">

            {{-- Header: ID Pengaduan & Status --}}
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <small class="text-muted d-block">ID Pengaduan</small>
                    @foreach(explode(',', $item->id_pengaduan_list) as $idP)
                        <span class="badge bg-secondary me-1">#{{ trim($idP) }}</span>
                    @endforeach
                </div>
                @php
                    $badge = match($item->status) {
                        'Pending'      => 'warning',
                        'Dalam Proses' => 'primary',
                        'Selesai'      => 'success',
                        default        => 'secondary',
                    };
                @endphp
                <span class="badge bg-{{ $badge }}">{{ $item->status ?? 'Pending' }}</span>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-6">
                    <small class="text-muted d-block">Ruangan</small>
                    <span>{{ $item->nama_ruangan }}</span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Lokasi</small>
                    <span>{{ $item->lokasi }}</span>
                </div>

                @if($item->perangkat_list && $item->perangkat_list !== '-')
                <div class="col-12">
                    <small class="text-muted d-block">Perangkat</small>
                    <span class="small">{{ $item->perangkat_list }}</span>
                </div>
                @endif

                <div class="col-6">
                    <small class="text-muted d-block">Kondisi</small>
                    @php
                        $kondisiBadge = match($item->kondisi) {
                            'Baik'   => 'success',
                            'Rusak'  => 'danger',
                            default  => 'secondary',
                        };
                    @endphp
                    <span class="badge bg-{{ $kondisiBadge }}">
                        {{ $item->kondisi ?? '-' }}
                    </span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Teknisi</small>
                    <span>{{ $item->teknisi ?? '-' }}</span>
                </div>
                <div class="col-12">
                    <small class="text-muted d-block">Deskripsi</small>
                    <span class="small">{{ $item->deskripsi_masalah }}</span>
                </div>
            </div>

            <div class="text-end">
                <small class="text-muted">
                    {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') : '-' }}
                </small>
            </div>

        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <i class="fa-solid fa-inbox fa-2x mb-2"></i>
        <p>Tidak ada data tindakan untuk filter ini.</p>
    </div>
    @endforelse

</div>

@endsection