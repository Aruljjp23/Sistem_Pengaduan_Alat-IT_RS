<!DOCTYPE html>
<html lang="en" id="html-root">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        {{-- <meta http-equiv="refresh" content="60"> --}}
        <title>Dashboard</title>

        <style>
            :root {
                --bg-body:      #f8f9fa;
                --bg-navbar:    #212529;
                --bg-sidebar:   #212529;
                --bg-content:   #ffffff;
                --bg-footer:    #f8f9fa;
                --text-primary: #212529;
                --text-muted:   #6c757d;
                --border-color: #dee2e6;
                --card-bg:      #ffffff;
                --input-bg:     #ffffff;
                --input-border: #ced4da;
                --link-color:   #0d6efd;
                --shadow:       0 2px 8px rgba(0,0,0,.08);
            }

            html.dark-mode {
                --bg-body:      #121212;
                --bg-navbar:    #1e1e2e;
                --bg-sidebar:   #161622;
                --bg-content:   #1e1e2e;
                --bg-footer:    #1a1a2a;
                --text-primary: #e2e8f0;
                --text-muted:   #94a3b8;
                --border-color: #2d2d3f;
                --card-bg:      #252535;
                --input-bg:     #2a2a3d;
                --input-border: #3d3d55;
                --link-color:   #7dd3fc;
                --shadow:       0 2px 12px rgba(0,0,0,.4);
            }

            body {
                background-color: var(--bg-body) !important;
                color: var(--text-primary) !important;
                transition: background-color .3s ease, color .3s ease;
            }

            .sb-topnav {
                background-color: var(--bg-navbar) !important;
                transition: background-color .3s ease;
            }

            .sb-topnav {
                background-color: #0d934a !important;
            }

            #sidenavAccordion,
            .sb-sidenav,
            .sb-sidenav-menu,
            .sb-sidenav.accordion {
                background-color: #0d934a !important;
            }

            .sb-sidenav-menu .nav-link {
                color: rgba(255, 255, 255, 0.85) !important;
            }

            .sb-sidenav-menu .nav-link:hover {
                color: #ffffff !important;
                background-color: rgba(255, 255, 255, 0.12) !important;
            }

            .sb-sidenav-menu-heading {
                color: rgba(255, 255, 255, 0.5) !important;
            }

            .sb-sidenav-footer {
                background-color: #0d934a !important;
            }

            nav.sb-topnav.navbar {
                background-color: #0d934a !important;
            }

            #sidenavAccordion .collapse,
            #sidenavAccordion .collapsing {
                background-color: #0d934a !important;
            }

            #layoutSidenav_nav,
            .sb-sidenav {
                background-color: var(--bg-sidebar) !important;
                transition: background-color .3s ease;
            }

            #layoutSidenav_content > main {
                background-color: var(--bg-content) !important;
                transition: background-color .3s ease;
            }

            footer.py-4 {
                background-color: var(--bg-footer) !important;
                border-top: 1px solid var(--border-color);
                transition: background-color .3s ease;
            }
            footer .text-muted { color: var(--text-muted) !important; }
            footer a           { color: var(--link-color) !important; }

            .card {
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) !important;
                box-shadow: var(--shadow);
                transition: background-color .3s ease;
            }

            .table             { color: var(--text-primary) !important; }
            .table thead th,
            .table td          { border-color: var(--border-color) !important; }

            .dropdown-menu {
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) !important;
            }
            .dropdown-item {
                color: var(--text-primary) !important;
            }
            .dropdown-item:hover {
                background-color: var(--bg-body) !important;
            }
            .dropdown-item.text-danger { color: #ef4444 !important; }

            .form-control,
            .form-select {
                background-color: var(--input-bg) !important;
                border-color: var(--input-border) !important;
                color: var(--text-primary) !important;
            }

            h1, h2, h3, h4, h5, h6 {
                color: var(--text-primary) !important;
            }

            .border-bottom {
                border-color: var(--border-color) !important;
            }

            p, span, label, li, td, th, dt, dd, small, strong, b, em {
                color: var(--text-primary) !important;
            }

            .text-muted {
                color: var(--text-muted) !important;
            }

            .breadcrumb-item,
            .breadcrumb-item a,
            .breadcrumb-item.active {
                color: var(--text-muted) !important;
            }

            .badge.bg-secondary {
                background-color: var(--border-color) !important;
                color: var(--text-primary) !important;
            }

            .alert {
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) !important;
                color: var(--text-primary) !important;
            }

            .list-group-item {
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) !important;
                color: var(--text-primary) !important;
            }

            .modal-content {
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) !important;
                color: var(--text-primary) !important;
            }
            .modal-header,
            .modal-footer {
                border-color: var(--border-color) !important;
            }

            .nav-tabs .nav-link {
                color: var(--text-muted) !important;
            }
            .nav-tabs .nav-link.active {
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) !important;
                color: var(--text-primary) !important;
            }
            .tab-content {
                background-color: var(--card-bg) !important;
                color: var(--text-primary) !important;
            }

            .select2-container--default .select2-selection--single,
            .select2-container--default .select2-selection--multiple {
                background-color: var(--input-bg) !important;
                border-color: var(--input-border) !important;
                color: var(--text-primary) !important;
            }
            .select2-results__option {
                background-color: var(--card-bg) !important;
                color: var(--text-primary) !important;
            }

            .table-striped > tbody > tr:nth-of-type(odd) > * {
                background-color: rgba(128,128,128,0.06) !important;
                color: var(--text-primary) !important;
            }
            .table-hover > tbody > tr:hover > * {
                background-color: rgba(128,128,128,0.1) !important;
                color: var(--text-primary) !important;
            }

            ::placeholder {
                color: var(--text-muted) !important;
                opacity: 1;
            }

            html.dark-mode ::-webkit-scrollbar {
                width: 8px;
            }
            html.dark-mode ::-webkit-scrollbar-track {
                background: var(--bg-body);
            }
            html.dark-mode ::-webkit-scrollbar-thumb {
                background: var(--border-color);
                border-radius: 4px;
            }

            .sb-topnav .navbar-brand,
            .sb-topnav .nav-link,
            .sb-topnav .navbar-text,
            .sb-topnav button.btn-link,
            .sb-topnav button.btn-link i,
            .sb-topnav .fw-semibold,
            .sb-topnav span {
                color: #ffffff !important;
            }
            .sb-topnav .text-muted {
                color: rgba(255,255,255,0.6) !important;
            }

            html.dark-mode .table thead,
            html.dark-mode .table thead tr,
            html.dark-mode .table thead th,
            html.dark-mode .table > thead > tr > th {
                background-color: #2a2a3d !important;
                color: #e2e8f0 !important;
                border-color: #3d3d55 !important;
            }

            html:not(.dark-mode) .table thead th {
                color: #212529 !important;
            }

            html.dark-mode .pagination .page-link {
                background-color: #2a2a3d !important;
                border-color: #3d3d55 !important;
                color: #7dd3fc !important;
            }
            html.dark-mode .pagination .page-link:hover {
                background-color: #3d3d55 !important;
                color: #ffffff !important;
            }
            html.dark-mode .pagination .page-item.active .page-link {
                background-color: #0d6efd !important;
                border-color: #0d6efd !important;
                color: #ffffff !important;
            }
            html.dark-mode .pagination .page-item.disabled .page-link {
                background-color: #1e1e2e !important;
                border-color: #2d2d3f !important;
                color: #4a5568 !important;
            }

            .sb-topnav {
                background-color: var(--bg-navbar) !important;
                transition: background-color .3s ease;
            }

            .sb-topnav .dropdown-menu {
                background-color: var(--card-bg) !important;
                border-color: var(--border-color) !important;
            }

            .sb-topnav .dropdown-item {
                color: var(--text-primary) !important;
                transition: background .2s ease;
            }

            .sb-topnav .dropdown-item:hover {
                background-color: var(--bg-body) !important;
                color: var(--text-primary) !important;
            }

            .sb-topnav .dropdown-item.text-danger,
            .sb-topnav .dropdown-item.d-flex.text-danger {
                color: #ef4444 !important;
            }

            .sb-topnav .dropdown-item.text-danger:hover {
                background-color: rgba(239, 68, 68, 0.1) !important;
                color: #ef4444 !important;
            }

            #layoutSidenav_nav {
                width: 225px;
                min-width: 225px;
                transition: width 0.25s ease, min-width 0.25s ease;
            }

            .nav-icon-sm {
                font-size: 0.45rem;
                vertical-align: middle;
                opacity: 0.6;
            }

            @media (max-width: 991.98px) {
                #layoutSidenav_nav {
                    width: 100%;
                    min-width: unset;
                }

                .sb-sidenav-menu-heading {
                    font-size: 0.65rem;
                    padding: 0.75rem 1rem 0.25rem;
                    letter-spacing: 0.08em;
                }

                .sb-sidenav .nav-link {
                    padding: 0.65rem 1rem;
                    font-size: 0.875rem;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                .sb-nav-link-icon {
                    width: 1.5rem;
                    font-size: 0.85rem;
                    flex-shrink: 0;
                }

                .sb-sidenav-menu-nested .nav-link {
                    padding: 0.5rem 0.75rem 0.5rem 2.5rem;
                    font-size: 0.82rem;
                }

                .sb-sidenav-collapse-arrow {
                    margin-left: auto;
                    flex-shrink: 0;
                }
            }

            @media (max-width: 575.98px) {
                .sb-sidenav .nav-link {
                    font-size: 0.85rem;
                    padding: 0.6rem 0.85rem;
                }

                .sb-sidenav-menu-nested .nav-link {
                    font-size: 0.8rem;
                    padding: 0.45rem 0.65rem 0.45rem 2.25rem;
                }

                .sb-sidenav-menu-heading {
                    font-size: 0.6rem;
                }
            }

        </style>

        @include('layout.header')
    </head>

    <body class="sb-nav-fixed">

        <script>
            (function () {
                const STORAGE_KEY = 'theme-preference';
                const saved       = localStorage.getItem(STORAGE_KEY);
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (saved === 'dark' || (!saved && prefersDark)) {
                    document.getElementById('html-root').classList.add('dark-mode');
                }
            })();
        </script>

        @include('layout.navbar')

        <div id="layoutSidenav">
            @include('layout.sidebar')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">@yield("page_title")</h1>
                        @yield("content")
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        @include('layout.footer')

        <script>
            (function () {
                const html        = document.getElementById('html-root');
                const btn         = document.getElementById('darkModeToggle');
                const icon        = btn.querySelector('.toggle-icon');
                const label       = btn.querySelector('.toggle-label');
                const STORAGE_KEY = 'theme-preference';

                function applyTheme(dark) {
                    if (dark) {
                        html.classList.add('dark-mode');
                        icon.textContent     = '☀️';
                        label.textContent    = 'Mode Pagi';
                        icon.style.transform = 'rotate(20deg)';
                        btn.style.background = 'rgba(255,255,255,0.15)';
                    } else {
                        html.classList.remove('dark-mode');
                        icon.textContent     = '🌙';
                        label.textContent    = 'Mode Malam';
                        icon.style.transform = 'rotate(0deg)';
                        btn.style.background = 'rgba(255,255,255,0.08)';
                    }
                }

                const isDark = html.classList.contains('dark-mode');
                applyTheme(isDark);

                btn.addEventListener('click', function () {
                    const nowDark = html.classList.toggle('dark-mode');
                    localStorage.setItem(STORAGE_KEY, nowDark ? 'dark' : 'light');
                    applyTheme(nowDark);
                });
            })();
        </script>

    </body>
</html>