<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Modern UI</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>

    <div id="loading-overlay">
        <div class="spinner"></div>
        <div class="loading-text">Sedang mendaftar...</div>
    </div>

    <div id="modal-back">
        <div class="modal-box">
            <h3><i class="fas fa-exclamation-triangle" style="color:#f1c40f;"></i> Yakin ingin kembali?</h3>
            <p>Data yang sudah kamu isi akan hilang jika kembali ke halaman login.</p>
            <div class="modal-actions">
                <button class="btn-cancel" id="btn-cancel">Tetap di sini</button>
                <button class="btn-confirm" id="btn-confirm" data-href="{{ route('login') }}">Ya, Kembali</button>
            </div>
        </div>
    </div>

    <div class="bg-img">
        <div class="content">
            <div class="brand-header">
                <img src="{{ asset('/image/logo-sipitrs.png') }}" alt="Logo RSU Darmayu" class="logo">
                <h1>SIPITRS</h1>
                <p>Sistem Pengaduan Masalah Perangkat IT<br>RSU Darmayu Madiun</p>
            </div>

            @if(session('error'))
            <div class="notif notif-error" id="notif-box">
                <i class="fas fa-times-circle"></i> <span>{{ session('error') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="notif notif-error" id="notif-box">
                <i class="fas fa-exclamation-circle"></i> <span>{{ $errors->first() }}</span>
            </div>
            @endif

            @if(session('success'))
            <div class="notif notif-success" id="notif-box">
                <i class="fas fa-check-circle"></i> <span>{{ session('success') }}</span>
            </div>
            @endif

            <form method="post" action="{{ url('/register/save') }}" id="register-form">
                @csrf
                <div class="field">
                    <span class="fa fa-user"></span>
                    <input type="text" name="name" id="input-name" value="{{ old('name') }}" placeholder="Username" autocomplete="off">
                </div>
                
                <div class="field space">
                    <span class="fa fa-lock"></span>
                    <input type="password" name="password" id="input-password" class="pass-key" required placeholder="Password" title="Password maksimal 10 karakter">
                    <span class="show">SHOW</span>
                </div>
                
                <div class="field space">
                    <input type="submit" value="REGISTER" id="register-btn">
                </div>
            </form>

            <div class="signup">
                Sudah Punya Akun? <a href="#" id="link-login" data-href="{{ route('login') }}">Masuk</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/register.js') }}"></script>
</body>
</html>