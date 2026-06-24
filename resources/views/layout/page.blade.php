<!DOCTYPE html>
<html lang="id" id="html-root">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIPITRS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --brand-primary: #0d934a;
            --brand-dark: #096e37;
            --brand-soft: rgba(13, 147, 74, 0.1);
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
            --radius-xl: 16px;
            --radius-lg: 12px;
            --shadow-subtle: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        html.dark-mode {
            --bg-body: #0f172a;
            --bg-card: #1e293b;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --border-light: #334155;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            transition: all 0.3s ease;
        }

        .sb-topnav.navbar {
            background-color: var(--brand-primary) !important;
            height: 70px;
            padding: 0;
            border-bottom: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .brand-text {
            font-weight: 800;
            letter-spacing: -0.5px;
            font-size: 1.25rem;
            color: #fff !important;
        }

        .btn-theme-toggle {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            color: #fff;
            padding: 6px 14px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-theme-toggle:hover {
            background: rgba(255,255,255,0.25);
            color: #fff;
        }

        .profile-name { font-weight: 700; font-size: 0.85rem; line-height: 1.2; color: #fff; }
        .profile-role { font-size: 0.65rem; color: rgba(255,255,255,0.8); font-weight: 600; letter-spacing: 0.5px; }

        .sb-sidenav-custom {
            background-color: var(--brand-primary) !important;
            border-right: none;
        }

        .menu-label {
            color: rgba(255,255,255,0.4);
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 1.5rem 1rem 0.5rem;
        }

        .modern-link {
            border-radius: 12px !important;
            margin: 2px 8px;
            padding: 10px 15px !important;
            color: rgba(255,255,255,0.8) !important;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .modern-link:hover, .modern-link.active {
            background-color: rgba(255,255,255,0.15) !important;
            color: #fff !important;
            transform: translateX(4px);
        }

        .sb-nav-link-icon {
            width: 30px;
            font-size: 1.1rem;
            color: #fff;
            opacity: 0.7;
        }

        .custom-sub-menu {
            background: rgba(0,0,0,0.1) !important;
            margin: 4px 15px !important;
            border-radius: 10px;
        }

        .sub-link {
            font-size: 0.8rem !important;
            padding: 8px 15px !important;
            color: rgba(255,255,255,0.7) !important;
        }

        .sub-link:hover { color: #fff !important; }

        @media (max-width: 991.98px) {
            .sb-topnav.navbar { height: 65px; }
            .sb-sidenav-toggled #layoutSidenav_nav { width: 260px; box-shadow: 20px 0 50px rgba(0,0,0,0.2); }
            .modern-link { padding: 12px 15px !important; }
        }

        .card {
            border: 1px solid var(--border-light);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-subtle);
        }

        #layoutSidenav_content { background-color: var(--bg-body); }

        #refresh-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: rgba(15, 23, 42, 0.85);
            color: #fff;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }
        #refresh-toast.show { opacity: 1; }
        #refresh-toast .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            animation: pulse-dot 1s infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.7); }
        }
    </style>
    @include('layout.header')
</head>

<body class="sb-nav-fixed">
    <script>
        (function () {
            const saved = localStorage.getItem("theme");
            if (saved === 'dark') document.getElementById('html-root').classList.add('dark-mode');
        })();
    </script>

    @include('layout.navbar')

    <div id="layoutSidenav">
        @include('layout.sidebar')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between mb-2 mt-4">
                        <h1 class="h3 fw-bold text-dark">@yield("page_title")</h1>
                    </div>
                    @yield("content")
                </div>
            </main>
            <footer class="py-4 mt-auto border-top">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small text-muted">
                        <div>&copy; 2026 SIPITRS RSUD. All Rights Reserved.</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <div id="refresh-toast">
        <span class="dot"></span>
        <span id="refresh-label">Refresh otomatis dalam <b id="refresh-countdown">30:00</b></span>
    </div>

    @include('layout.footer')

    <script>
        const themeBtn   = document.getElementById("darkModeToggle");
        const rootHtml   = document.documentElement;
        const themeIcon  = themeBtn.querySelector(".toggle-icon");
        const themeLabel = themeBtn.querySelector(".toggle-label");

        themeBtn.addEventListener("click", function() {
            rootHtml.classList.toggle("dark-mode");
            const isDark = rootHtml.classList.contains("dark-mode");
            localStorage.setItem("theme", isDark ? "dark" : "light");
            themeIcon.textContent  = isDark ? "☀️" : "🌙";
            themeLabel.textContent = isDark ? "Mode Terang" : "Mode Malam";
        });

        if (localStorage.getItem("theme") === "dark") {
            themeIcon.textContent  = "☀️";
            themeLabel.textContent = "Mode Terang";
        }

        (function () {
            const TOTAL_SECONDS  = 30 * 60;     
            const WARN_SECONDS   = 60;           
            const countdownEl    = document.getElementById('refresh-countdown');
            const toast          = document.getElementById('refresh-toast');

            let remaining = TOTAL_SECONDS;

            function fmt(s) {
                const m = String(Math.floor(s / 60)).padStart(2, '0');
                const sec = String(s % 60).padStart(2, '0');
                return `${m}:${sec}`;
            }

            const timer = setInterval(function () {
                remaining--;

                if (remaining <= 0) {
                    clearInterval(timer);
                    window.location.reload();
                    return;
                }

                if (remaining <= WARN_SECONDS) {
                    countdownEl.textContent = fmt(remaining);
                    toast.classList.add('show');
                }
            }, 1000);
        })();
    </script>
</body>
</html>