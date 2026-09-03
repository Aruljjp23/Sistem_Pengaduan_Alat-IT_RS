<!DOCTYPE html>
<html lang="id" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIPITRS</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/page.css') }}">

    @include('layout.header')

    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');

            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark-mode');
            }
        })();
    </script>
</head>

<body class="sb-nav-fixed">

    @include('layout.navbar')

    <div id="layoutSidenav">
        @include('layout.sidebar')

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between mb-2 mt-4">
                        <h1 class="h3 fw-bold page-title">
                            @yield('page_title')
                        </h1>
                    </div>

                    @yield('content')
                </div>
            </main>

            <footer class="py-4 mt-5 border-top">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small text-muted">
                        <div>
                            &copy; 2026 SIPITRS RSUD. All Rights Reserved.
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <div id="refresh-toast">
        <span class="dot"></span>
        <span id="refresh-label">
            Refresh otomatis dalam
            <b id="refresh-countdown">30:00</b>
        </span>
    </div>

    @include('layout.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const themeBtn = document.getElementById('darkModeToggle');

            if (themeBtn) {
                const themeIcon = themeBtn.querySelector('.toggle-icon');
                const themeLabel = themeBtn.querySelector('.toggle-label');

                function updateThemeButton(isDark) {
                    if (themeIcon) {
                        themeIcon.textContent = isDark ? '☀️' : '🌙';
                    }

                    if (themeLabel) {
                        themeLabel.textContent = isDark
                            ? 'Mode Terang'
                            : 'Mode Malam';
                    }
                }

                const isDark = document.documentElement.classList.contains('dark-mode');

                updateThemeButton(isDark);

                themeBtn.addEventListener('click', function () {
                    document.documentElement.classList.toggle('dark-mode');

                    const darkMode = document.documentElement.classList.contains('dark-mode');

                    localStorage.setItem(
                        'theme',
                        darkMode ? 'dark' : 'light'
                    );

                    updateThemeButton(darkMode);
                });
            }

            const TOTAL_SECONDS = 30 * 60;
            const WARN_SECONDS = 60;

            const countdownEl = document.getElementById('refresh-countdown');
            const toast = document.getElementById('refresh-toast');

            if (!countdownEl || !toast) {
                return;
            }

            let remaining = TOTAL_SECONDS;

            function formatTime(seconds) {
                const minutes = String(
                    Math.floor(seconds / 60)
                ).padStart(2, '0');

                const secondsValue = String(
                    seconds % 60
                ).padStart(2, '0');

                return `${minutes}:${secondsValue}`;
            }

            const timer = setInterval(function () {
                remaining--;

                if (remaining <= 0) {
                    clearInterval(timer);
                    window.location.reload();
                    return;
                }

                if (remaining <= WARN_SECONDS) {
                    countdownEl.textContent = formatTime(remaining);
                    toast.classList.add('show');
                }
            }, 1000);
        });
    </script>

</body>
</html>