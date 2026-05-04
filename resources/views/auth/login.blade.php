<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Login</title>
      <link rel="stylesheet" href="{{ asset('css/login.css') }}">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
      {{-- <meta http-equiv="refresh" content="60"> --}}
   </head>
   <body>

      <div id="loading-overlay">
         <div class="spinner"></div>
         <div class="loading-text">Sedang masuk...</div>
      </div>

      <div class="bg-img">
         <div class="content">
            <header>Login</header>

            @if(session('error'))
            <div class="notif notif-error" id="notif-box">
               <i class="fas fa-times-circle"></i> {{ session('error') }}
            </div>
            @endif

            {{-- Validasi Error --}}
            @if($errors->any())
            <div class="notif notif-error" id="notif-box">
               <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
            @endif

            <form method="post" action="{{ url('/login/proses') }}" id="login-form">
               @csrf
               <div class="field">
                  <span class="fa fa-user"></span>
                  <input type="text" name="name" id="input-name" value="{{ old('name') }}" placeholder="Username">
               </div>
               <div class="field space">
                  <span class="fa fa-lock"></span>
                  <input type="password" name="password" class="pass-key" required placeholder="Password" title="Password maksimal 10 karakter">
                  <span class="show">SHOW</span>
               </div>
               <br>
               <div class="field">
                  <input type="submit" value="LOGIN" id="login-btn">
               </div>
            </form>
            <br>
            <div class="signup">
               Tidak Punya Akun ?
               <a href="{{ route('register') }}">Register</a>
            </div>
         </div>
      </div>

      <script src="{{ asset('js/login.js') }}"></script>
   </body>
</html>