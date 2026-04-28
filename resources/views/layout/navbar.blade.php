<nav class="sb-topnav navbar navbar-expand navbar-dark" style="background-color: #0d934a !important; border-bottom: 2px solid #096e37;">
    <a class="navbar-brand ps-3 d-none d-sm-block d-flex align-items-center gap-2" href="{{ url('/dashboard') }}" style="color: #ffffff !important; font-weight: 700;">
    <img src="{{ asset('/image/logo-sipitrs.png') }}" alt="Logo" style="height: 55px; width: auto; object-fit: contain; background-color: #0d934a; border-radius: 4px;">
    Sipitrs
    </a>
    <a class="navbar-brand ps-3 d-block d-sm-none d-flex align-items-center gap-2" href="{{ url('/dashboard') }}" style="color: #ffffff !important; font-weight: 700;">
        <img src="{{ asset('/image/logo-sipitrs.png') }}" alt="Logo" style="height: 50px; width: auto; object-fit: contain; background-color: #0d934a; border-radius: 4px;">
        Sipitrs
    </a>

    <button class="btn btn-link btn-sm order-1 order-lg-0 me-2 me-lg-0" id="sidebarToggle" href="#!">
        <i class="fas fa-bars" style="color: #ffffff;"></i>
    </button>

    <ul class="navbar-nav ms-auto me-0 pe-2 pe-sm-3 align-items-center gap-1 gap-sm-2">

        <!-- Dark Mode Toggle -->
        <li class="nav-item">
            <button id="darkModeToggle"
                title="Toggle dark/light mode"
                class="btn btn-sm d-flex align-items-center gap-1 gap-sm-2"
                style="
                    background: rgba(0,0,0,0.08);
                    border: 1px solid rgba(0,0,0,0.25);
                    border-radius: 50px;
                    color: #000000;
                    padding: 5px 10px;
                    font-size: 0.8rem;
                    transition: background .25s ease;
                ">
                <span class="toggle-icon" style="font-size: 1rem; transition: transform .4s ease;">🌙</span>
                <span class="toggle-label d-none d-md-inline" style="color: #000000;">Mode Malam</span>
            </button>
        </li>

        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1 gap-sm-2"
                id="navbarDropdown" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false"
                style="color: #000000;">
                <i class="fas fa-user-circle fs-5" style="color: #ffffff;"></i>
                <div class="d-none d-sm-flex flex-column lh-sm text-end">
                    <span class="fw-semibold" style="font-size: 0.85rem; color: #000000;">{{ Auth::user()->name }}</span>
                    <span style="font-size: 0.72rem; color: rgba(0,0,0,0.6);">{{ Auth::user()->role }}</span>
                </div>
            </a>

            <!-- Dropdown Menu -->
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown"
                style="min-width: 200px;">
                <li>
                    <div class="px-3 py-2" style="border-bottom: 1px solid var(--border-color);">
                        <div class="fw-semibold" style="font-size: 0.85rem; color: var(--text-primary);">
                            {{ Auth::user()->name }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                            <i class="fas fa-user-tag me-1"></i>{{ Auth::user()->role }}
                        </div>
                    </div>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 text-danger py-2" href="{{ url('/logout') }}">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</nav>