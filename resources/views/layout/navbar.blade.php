@php
    $user     = Auth::user();
    $logo_url = url('/dashboard');

    if ($user) {
        if ($user->role == 'pengadu') {
            $user_ruang = DB::table('ruangan')->where('id_ruangan', $user->id_ruangan)->first();
            $logo_url   = $user_ruang ? url('/pengaduan/form_pengaduan/' . $user_ruang->id_ruangan) : url('#');
        } elseif ($user->role == 'teknisi') {
            $logo_url = url('homepage');
        }
    }
@endphp

<nav class="sb-topnav navbar navbar-expand navbar-dark">

    <button class="btn btn-link btn-sm ms-3 order-1 order-lg-0" id="sidebarToggle">
        <i class="fas fa-bars-staggered fs-5"></i>
    </button>

    <a class="navbar-brand d-flex align-items-center gap-2 ms-2" href="{{ $logo_url }}">
        <img src="{{ asset('/image/logo-sipitrs.png') }}" alt="Logo"
             style="height:35px;width:auto;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
        <span class="brand-text d-none d-sm-inline">SIPITRS</span>
    </a>

    <div class="ms-auto"></div>

    <ul class="navbar-nav align-items-center gap-2 me-3">

        <button id="darkModeToggle" class="btn btn-theme-toggle d-flex align-items-center gap-2">
            <span class="toggle-icon">🌙</span>
            <span class="toggle-label d-none d-md-inline">Mode Malam</span>
        </button>

        <li class="nav-item dropdown">
            <a class="nav-link profile-dropdown-toggle d-flex align-items-center gap-2"
               id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown">
                <div class="profile-info d-none d-md-block text-end">
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-role">{{ strtoupper($user->role) }}</div>
                </div>
                <div class="profile-avatar">
                    <i class="fas fa-user-circle fs-3 text-white-50"></i>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 animate slideIn"
                aria-labelledby="navbarDropdown">
                <li class="dropdown-header d-md-none text-center p-3 border-bottom mb-2">
                    <div class="fw-bold">{{ $user->name }}</div>
                    <div class="small text-muted">{{ $user->role }}</div>
                </li>
                {{-- <li>
                    <a class="dropdown-item py-2 px-3" href="#">
                        <i class="fas fa-user-gear me-2 opacity-50"></i> Profil Saya
                    </a>
                </li> --}}
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item py-2 px-3 text-danger" href="{{ url('/logout') }}">
                        <i class="fas fa-power-off me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</nav>