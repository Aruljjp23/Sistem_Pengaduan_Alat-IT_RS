const baseUrl = "{{ url('') }}";


document.querySelectorAll('.btn-edit').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const id    = this.dataset.id;
        const name  = this.dataset.name;
        // const email = this.dataset.email;
        const role  = this.dataset.role;
        const id_ruangan  = this.dataset.id_ruangan;

        document.getElementById('edit_name').value       = name;
        document.getElementById('edit_role').value       = role;
        document.getElementById('edit_id_ruangan').value = id_ruangan ?? '';
        document.getElementById('edit_password').value   = '';

        const form = document.getElementById('formEditUser');
        const baseUrl = window.location.origin;
        form.action = baseUrl + '/user/data_user/' + id + '/update';

        const modal = new bootstrap.Modal(document.getElementById('modalEditUser'));
        modal.show();
    });
});

document.querySelectorAll('.btn-hapus').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const id   = this.dataset.id;
        const name = this.dataset.name;

        document.getElementById('hapus_name').textContent = name;
        const baseUrl = window.location.origin;
        document.getElementById('btnHapusKonfirmasi').href = baseUrl + '/user/data_user/' + id + '/delete';

        const modal = new bootstrap.Modal(document.getElementById('modalHapusUser'));
        modal.show();
    });
});

let searchTimeout;

document.getElementById('searchInput').addEventListener('input', function () {
    clearTimeout(searchTimeout);
    const value = this.value;

    searchTimeout = setTimeout(() => {
        if (value.length >= 2 || value.length === 0) {
            navigateWithParams();
        }
    }, 500);
});

function navigateWithParams() {
    const search = document.getElementById('searchInput').value;

    const params = new URLSearchParams();
    if (search) params.append('search', search);

    const query = params.toString();
    window.location.href = '/user/data_user' + (query ? '?' + query : '');
}