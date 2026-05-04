const baseUrl = "{{ url('') }}";

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id         = this.dataset.id;
            const name       = this.dataset.name;
            const role       = this.dataset.role;
            const idRuangan  = this.dataset.id_ruangan;

            document.getElementById('formEditUser').action = '/user/data_user/' + id + '/update';

            document.getElementById('edit_name').value     = name;
            document.getElementById('edit_role').value     = role;
            document.getElementById('edit_id_ruangan').value = idRuangan ?? '';

            document.getElementById('edit_password').value = '';

            var editModal = new bootstrap.Modal(document.getElementById('modalEditUser'));
            editModal.show();
        });
    });

    document.querySelectorAll('.btn-hapus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const name = this.dataset.name;
            const url  = this.dataset.url;

            Swal.fire({
                title: 'Hapus User?',
                html: `Anda yakin ingin menghapus user:<br><strong class="text-danger">${name}</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
                icon: 'warning',
                iconColor: '#dc3545',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-trash me-1"></i> Ya, Hapus!<br>',
                cancelButtonText: '<i class="fa fa-times me-1"></i> Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'shadow',
                    confirmButton: 'btn btn-danger px-4',
                    cancelButton: 'btn btn-secondary px-4',
                    actions: 'd-flex gap-3 justify-content-center'
                },
                buttonsStyling: false,
            }).then(function (result) {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
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