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

document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchInput');

    if (!searchInput) return;

    searchInput.addEventListener('keyup', function () {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll('#userTable tr');

        rows.forEach(function(row) {
            if (row.id === 'noUserData') return; 
            let name = row.children[1]?.innerText.toLowerCase() || '';
            let role = row.children[2]?.innerText.toLowerCase() || '';

            if (name.includes(keyword) || role.includes(keyword)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('noUserData').style.display = found ? 'none' : '';
    });

});