@php
    $user = Auth::user();
@endphp

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-custom" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav px-2 mt-3">

                @if($user->role == 'admin')

                    <div class="menu-label">Utama</div>
                    <a class="nav-link modern-link" href="{{ url('dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-grip-vertical"></i></div>
                        Dashboard
                    </a>

                    <div class="menu-label">Manajemen</div>
                    <a class="nav-link modern-link collapsed" href="#"
                       data-bs-toggle="collapse" data-bs-target="#collapsePages">
                        <div class="sb-nav-link-icon"><i class="fas fa-server"></i></div>
                        Data Master
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-chevron-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePages" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav custom-sub-menu">
                            <a class="nav-link sub-link" href="{{ url('/user/data_user') }}">
                                <i class="fas fa-minus me-2 opacity-25"></i> Data User
                            </a>
                            <a class="nav-link sub-link" href="{{ url('/ruang/data_ruang') }}">
                                <i class="fas fa-minus me-2 opacity-25"></i> Data Ruangan
                            </a>
                            <a class="nav-link sub-link" href="{{ url('/kategori_perangkat/data_kategori') }}">
                                <i class="fas fa-minus me-2 opacity-25"></i> Data Kategori
                            </a>
                        </nav>
                    </div>

                    <div class="menu-label">Pengaduan</div>
                    <a class="nav-link modern-link" href="{{ url('/pengaduan/riwayat_pengaduan') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-clock-rotate-left"></i></div>
                        Riwayat Pengaduan
                    </a>
                    <a class="nav-link modern-link" href="{{ url('/pengaduan/laporan_pengaduan') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-invoice"></i></div>
                        Laporan
                    </a>

                @elseif($user->role == 'teknisi')

                    <a class="nav-link modern-link" href="{{ url('homepage') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-house"></i></div>
                        Homepage
                    </a>
                    <div class="menu-label">Tugas</div>
                    <a class="nav-link modern-link" href="{{ url('/tindakan/data_tindakan') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-triangle-exclamation"></i></div>
                        Daftar Pengaduan
                    </a>
                    <a class="nav-link modern-link" href="{{ url('/pengaduan/riwayat_pengaduan') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                        Riwayat Kerja
                    </a>

                @elseif($user->role == 'pengadu')

                    @php
                        $user_ruang = DB::table('ruangan')->where('id_ruangan', $user->id_ruangan)->first();
                    @endphp
                    <div class="menu-label">Layanan</div>
                    <a class="nav-link modern-link" href="{{ url('/pengaduan/form_pengaduan/' . $user_ruang->id_ruangan) }}">
                        <div class="sb-nav-link-icon"><i class="far fa-building"></i></div>
                        {{ $user_ruang->nama_ruangan }}
                    </a>
                    <a class="nav-link modern-link" href="{{ url('/tindakan/tindakan_pengaduan') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-paper-plane"></i></div>
                        Tindakan Pengaduan
                    </a>

                @endif

            </div>
        </div>
    </nav>
</div>