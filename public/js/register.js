document.addEventListener('DOMContentLoaded', () => {

   // 1. Toggle Show/Hide Password
   const passFields = document.querySelectorAll('.pass-key');
   const showBtns = document.querySelectorAll('.show');

   showBtns.forEach((btn, index) => {
       btn.addEventListener('click', () => {
           const field = passFields[index];
           if (field.type === 'password') {
               field.type = 'text';
               btn.textContent = 'HIDE';
               btn.style.color = '#3498db';
           } else {
               field.type = 'password';
               btn.textContent = 'SHOW';
               btn.style.color = 'rgba(255, 255, 255, 0.6)'; // Sesuai dengan warna UI baru
           }
       });
   });

   // 2. Loading Overlay Setup
   const forms = document.querySelectorAll('form');
   const loadingOverlay = document.getElementById('loading-overlay');

   forms.forEach(form => {
       form.addEventListener('submit', function() {
           const submitBtn = this.querySelector('input[type="submit"]');
           if (loadingOverlay) loadingOverlay.classList.add('active');
           if (submitBtn) {
               submitBtn.disabled = true;
               submitBtn.value = 'MEMPROSES...';
           }
       });
   });

   // 3. Auto-Hide Notifications
   const notif = document.getElementById('notif-box');
   if (notif) {
       setTimeout(() => {
           notif.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
           notif.style.opacity = '0';
           notif.style.transform = 'translateY(-10px)';
           setTimeout(() => notif.remove(), 600);
       }, 4000);
   }

   // 4. Modal Logic untuk halaman Register
   const linkLogin = document.getElementById('link-login');
   const modalBack = document.getElementById('modal-back');
   const btnCancel = document.getElementById('btn-cancel');
   const btnConfirm = document.getElementById('btn-confirm');

   const isFormDirty = () => {
       const name = document.getElementById('input-name');
       const password = document.getElementById('input-password');
       return (name && name.value.trim() !== '') || (password && password.value.trim() !== '');
   };

   if (linkLogin) {
       linkLogin.addEventListener('click', (e) => {
           e.preventDefault();
           if (isFormDirty() && modalBack) {
               modalBack.classList.add('active');
           } else {
               window.location.href = linkLogin.dataset.href;
           }
       });
   }

   if (btnConfirm) {
       btnConfirm.addEventListener('click', function() {
           window.location.href = this.dataset.href;
       });
   }

   if (btnCancel && modalBack) {
       btnCancel.addEventListener('click', () => {
           modalBack.classList.remove('active');
       });
       
       modalBack.addEventListener('click', (e) => {
           if (e.target === modalBack) modalBack.classList.remove('active');
       });
   }
});

document.getElementById('ruangan_search').addEventListener('input', function() {
    var input = this.value;
    var options = document.querySelectorAll('#list-ruangan option');
    var hiddenInput = document.getElementById('id_ruangan_hidden');
    
    hiddenInput.value = ""; 
    
    for (var i = 0; i < options.length; i++) {
        if (options[i].value === input) {
            hiddenInput.value = options[i].getAttribute('data-id');
            break;
        }
    }
});

document.getElementById('register-form').addEventListener('submit', function(e) {
    var hiddenInput = document.getElementById('id_ruangan_hidden');
    if (hiddenInput.value === "") {
        e.preventDefault();
        alert("Silakan pilih ruangan yang valid dari list yang tersedia!");
        document.getElementById('ruangan_search').focus();
    }
});