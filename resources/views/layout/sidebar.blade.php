<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion" style="background-color: #0d934a; border-right: 2px solid #096e37;">
        <div class="sb-sidenav-menu">
            <div class="nav">

                @if(Auth::user()->role == "admin")

                    <a class="nav-link" href="{{ url('dashboard') }}" style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-line" style="color: #ffffff;"></i></div>
                        Dashboard
                    </a>

                    <a class="nav-link collapsed" href="#"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapsePages"
                        aria-expanded="false"
                        aria-controls="collapsePages"
                        style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="sb-nav-link-icon"><i class="fas fa-database" style="color: #ffffff;"></i></div>
                        Data Master
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down" style="color: ##ffffff;"></i></div>
                    </a>

                    <div class="collapse" id="collapsePages"
                        aria-labelledby="headingTwo"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">

                            <a class="nav-link collapsed" href="#"
                                data-bs-toggle="collapse"
                                data-bs-target="#pagesCollapseAuth"
                                aria-expanded="false"
                                aria-controls="pagesCollapseAuth"
                                style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                                <div class="sb-nav-link-icon"><i class="fas fa-users" style="color: #ffffff;"></i></div>
                                User
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down" style="color: #ffffff;"></i></div>
                            </a>
                            <div class="collapse" id="pagesCollapseAuth"
                                data-bs-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ url('/user/data_user') }}" style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                                        <i class="fas fa-circle nav-icon-sm me-1" style="color: #ffffff;"></i> Data User
                                    </a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#"
                                data-bs-toggle="collapse"
                                data-bs-target="#pagesCollapseRuangan"
                                aria-expanded="false"
                                aria-controls="pagesCollapseRuangan"
                                style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                                <div class="sb-nav-link-icon"><i class="far fa-hospital" style="color: #ffffff;"></i></div>
                                Ruangan
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down" style="color: #ffffff;"></i></div>
                            </a>
                            <div class="collapse" id="pagesCollapseRuangan"
                                data-bs-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="{{ url('/ruang/data_ruang') }}" style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                                        <i class="fas fa-circle nav-icon-sm me-1" style="color: #ffffff;"></i> Data Ruangan
                                    </a>
                                </nav>
                            </div>

                        </nav>
                    </div>

                    <div class="sb-sidenav-menu-heading" style="color: #ffffff !important;">Pengaduan</div>
                    <a class="nav-link" href="{{ url('/tindakan/data_tindakan') }}" style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="sb-nav-link-icon"><i class="fas fa-exclamation-triangle" style="color: #ffffff;"></i></div>
                        Data Pengaduan
                    </a>

                    <div class="sb-sidenav-menu-heading" style="color: #ffffff !important;">Tindakan Pengaduan</div>
                     <a class="nav-link" href="{{ url('/pengaduan/data_pengaduan') }}" style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="sb-nav-link-icon"><i class="fas fa-comments" style="color: #ffffff;"></i></div>
                        Edit Pengaduan
                    </a>
                    <a class="nav-link" href="{{ url('/tindakan/riwayat_tindakan') }}" style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="sb-nav-link-icon"><i class="fas fa-history" style="color: #ffffff;"></i></div>
                        Riwayat Pengaduan
                    </a>
                    <a class="nav-link" href="{{ url('/pengaduan/laporan_pengaduan') }}" style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="sb-nav-link-icon"><i class="fas fa-book" style="color: #ffffff;"></i></div>
                        Laporan Pengaduan
                    </a>

                @elseif(Auth::user()->role == "teknisi")

                    <a class="nav-link" href="{{ url('homepage') }}" style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-line" style="color: #ffffff;"></i></div>
                        Homepage
                    </a>

                    <div class="sb-sidenav-menu-heading" style="color: #ffffff !important;">Pengaduan</div>
                    <a class="nav-link" href="{{ url('/tindakan/data_tindakan') }}" style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="sb-nav-link-icon"><i class="fas fa-exclamation-triangle" style="color: #ffffff;"></i></div>
                        Data Pengaduan
                    </a>

                    <div class="sb-sidenav-menu-heading" style="color: #ffffff !important;">Tindakan Pengaduan</div>
                     <a class="nav-link" href="{{ url('/pengaduan/data_pengaduan') }}" style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="sb-nav-link-icon"><i class="fas fa-comments" style="color: #ffffff;"></i></div>
                        Edit Pengaduan
                    </a>
                    <a class="nav-link" href="{{ url('/tindakan/riwayat_tindakan') }}" style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="sb-nav-link-icon"><i class="fas fa-history" style="color: #ffffff;"></i></div>
                        Riwayat Pengaduan
                    </a>

                @elseif(Auth::user()->role == "pengadu")

                    @php
                        $user_ruang = DB::table('ruangan')->where('id', Auth::user()->id_ruangan)->first();
                    @endphp

                    <a class="nav-link collapsed" href="#"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapsePages1"
                        aria-expanded="false"
                        aria-controls="collapsePages1"
                        style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <div class="sb-nav-link-icon"><i class="fas fa-comments" style="color: #ffffff;"></i></div>
                        Pengaduan
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down" style="color: #ffffff;"></i></div>
                    </a>
                    <div class="collapse" id="collapsePages1"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ url('/pengaduan/form_pengaduan/' . $user_ruang->id) }}" style="color: #ffffff !important; border-bottom: 1px solid rgba(0,0,0,0.1);">
                                <i class="fas fa-circle nav-icon-sm me-1" style="color: #ffffff;"></i> {{ $user_ruang->nama_ruangan }}
                            </a>
                        </nav>
                    </div>

                @endif

            </div>
        </div>
    </nav>
</div>