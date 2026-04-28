document.addEventListener('DOMContentLoaded', function () {

   const toast = document.getElementById('toast-login');

   if (toast) {
      setTimeout(() => {
         toast.style.transition = 'opacity 0.5s';
         toast.style.opacity = '0';
         setTimeout(() => toast.remove(), 500);
      }, 4000);
   }

});