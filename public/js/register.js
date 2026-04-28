document.addEventListener('DOMContentLoaded', function () {

   const passField = document.querySelector('.pass-key');
   const showBtn   = document.querySelector('.show');

   if (passField && showBtn) {
      showBtn.addEventListener('click', function () {
         if (passField.type === 'password') {
            passField.type      = 'text';
            showBtn.textContent = 'HIDE';
            showBtn.style.color = '#3498db';
         } else {
            passField.type      = 'password';
            showBtn.textContent = 'SHOW';
            showBtn.style.color = '#222';
         }
      });
   }

   const registerForm   = document.getElementById('register-form');
   const registerBtn    = document.getElementById('register-btn');
   const loadingOverlay = document.getElementById('loading-overlay');

   if (registerForm) {
      registerForm.addEventListener('submit', function () {
         if (loadingOverlay) loadingOverlay.classList.add('active');
         if (registerBtn)    registerBtn.disabled = true;
      });
   }

   const notif = document.getElementById('notif-box');
   if (notif) {
      setTimeout(() => {
         notif.style.transition = 'opacity 0.5s';
         notif.style.opacity    = '0';
         setTimeout(() => notif.remove(), 500);
      }, 4000);
   }

   const linkLogin  = document.getElementById('link-login');
   const modalBack  = document.getElementById('modal-back');
   const btnCancel  = document.getElementById('btn-cancel');
   const btnConfirm = document.getElementById('btn-confirm');

   function isFormDirty() {
      const name     = document.getElementById('input-name');
      const email    = document.getElementById('input-email');
      const password = document.getElementById('input-password');

      return (
         (name     && name.value.trim()     !== '') ||
         (email    && email.value.trim()    !== '') ||
         (password && password.value.trim() !== '')
      );
   }

   if (linkLogin) {
      linkLogin.addEventListener('click', function (e) {
         e.preventDefault();

         if (isFormDirty()) {
            modalBack.classList.add('active');
         } else {
            window.location.href = linkLogin.dataset.href;
         }
      });
   }

   if (btnConfirm) {
      btnConfirm.addEventListener('click', function () {
         window.location.href = this.dataset.href;
      });
   }

   if (btnCancel) {
      btnCancel.addEventListener('click', function () {
         modalBack.classList.remove('active');
      });
   }

   if (modalBack) {
      modalBack.addEventListener('click', function (e) {
         if (e.target === modalBack) {
            modalBack.classList.remove('active');
         }
      });
   }

});