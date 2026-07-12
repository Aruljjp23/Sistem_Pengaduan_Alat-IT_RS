<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="{{ asset('/image/logo-sipitrs.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>

    <div id="loading-overlay">
        <div class="spinner"></div>
        <div class="loading-text">Sedang masuk...</div>
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

            <form method="POST" action="{{ route('login.proses') }}" id="login-form">
                @csrf
                <div class="field">
                    <span class="fa fa-user"></span>
                    <input type="text" name="name" id="input-name" value="{{ old('name') }}" placeholder="Username" autocomplete="off">
                </div>
                
                <div class="field space">
                    <span class="fa fa-lock"></span>
                    <input type="password" name="password" class="pass-key" required placeholder="Password" title="Password maksimal 10 karakter">
                    <span class="show">SHOW</span>
                </div>
                
                <div class="field space">
                    <input type="submit" value="LOGIN" id="login-btn">
                </div>
            </form>

            <div class="signup">
                Belum Punya Akun? <a href="{{ route('register') }}">Register</a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>