document.addEventListener('DOMContentLoaded', function () {

   const passField = document.querySelector('.pass-key');
   const showBtn = document.querySelector('.show');

   if (passField && showBtn) {
      showBtn.addEventListener('click', function () {
         if (passField.type === 'password') {
            passField.type = 'text';
            showBtn.textContent = 'HIDE';
            showBtn.style.color = '#3498db';
         } else {
            passField.type = 'password';
            showBtn.textContent = 'SHOW';
            showBtn.style.color = '#222';
         }
      });
   }

   const loginForm = document.getElementById('login-form');
   const loginBtn = document.getElementById('login-btn');
   const loadingOverlay = document.getElementById('loading-overlay');

   if (loginForm) {
      loginForm.addEventListener('submit', function () {
         if (loadingOverlay) loadingOverlay.classList.add('active');
         if (loginBtn) loginBtn.disabled = true;
      });
   }

   const notif = document.getElementById('notif-box');

   if (notif) {
      setTimeout(() => {
         notif.style.transition = 'opacity 0.5s';
         notif.style.opacity = '0';
         setTimeout(() => notif.remove(), 500);
      }, 4000);
   }

});