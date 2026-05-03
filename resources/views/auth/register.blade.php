<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Register</title>
      <link rel="stylesheet" href="{{ asset('css/register.css') }}">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
      <meta http-equiv="refresh" content="60">
   </head>
   <body>

      <div id="loading-overlay">
         <div class="spinner"></div>
         <div class="loading-text">Sedang mendaftar...</div>
      </div>

      <div id="modal-back">
         <div class="modal-box">
            <h3><i class="fas fa-exclamation-triangle" style="color:#e67e22;"></i> Yakin ingin kembali?</h3>
            <p>Data yang sudah kamu isi akan hilang jika kembali ke halaman login.</p>
            <div class="modal-actions">
               <button class="btn-cancel" id="btn-cancel">Tetap di sini</button>
               <button class="btn-confirm" id="btn-confirm" data-href="{{ route('login') }}">Ya, Kembali</button>
            </div>
         </div>
      </div>

      <div class="bg-img">
         <div class="content">
            <header>Register</header>

            @if(session('error'))
            <div class="notif notif-error" id="notif-box">
               <i class="fas fa-times-circle"></i> {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="notif notif-error" id="notif-box">
               <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
            @endif

            @if(session('success'))
            <div class="notif notif-success" id="notif-box">
               <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            <form method="post" action="{{ url('/register/save') }}" id="register-form">
               @csrf
               <div class="field">
                  <span class="fa fa-user"></span>
                  <input type="text" name="name" id="input-name" value="{{ old('name') }}" placeholder="Username">
               </div>
               <br>
               {{-- <div class="field">
                  <span class="fas fa-envelope"></span>
                  <input type="text" name="email" id="input-email"
                     value="{{ old('email') }}" required placeholder="Email">
               </div> --}}
               <div class="field space">
                  <span class="fa fa-lock"></span>
                  <input type="password" name="password" class="pass-key" required placeholder="Password" title="Password maksimal 10 karakter">
                  <span class="show">SHOW</span>
               </div>
               <br>
               <div class="field">
                  <input type="submit" value="Register" id="register-btn">
               </div>
            </form>
            <br>
            <div class="signup">
               Sudah Punya Akun ?
               <a href="#" id="link-login" data-href="{{ route('login') }}">Login</a>
            </div>
         </div>
      </div>

      <script src="{{ asset('js/register.js') }}"></script>
   </body>
</html>