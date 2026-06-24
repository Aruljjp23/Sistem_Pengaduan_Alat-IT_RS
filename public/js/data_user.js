document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.btn-edit');
        const hapusBtn = e.target.closest('.btn-hapus');

        if (editBtn) {
            const id = editBtn.dataset.id;
            const name = editBtn.dataset.name;
            const role = editBtn.dataset.role;
            const idRuangan = editBtn.dataset.id_ruangan;

            document.getElementById('formEditUser').action = '/user/data_user/' + id + '/update';
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_role').value = role;

            const inputRuangan = document.getElementById('edit_id_ruangan');
            if (inputRuangan) inputRuangan.value = idRuangan || '';

            const passField = document.getElementById('edit_password');
            if (passField) passField.value = '';

            new bootstrap.Modal(document.getElementById('modalEditUser')).show();
        }

        if (hapusBtn) {
            const name = hapusBtn.dataset.name;
            const url = hapusBtn.dataset.url;

            Swal.fire({
                title: 'Hapus User?',
                html: `Anda yakin ingin menghapus user:<br><strong class="text-danger">${name}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: { confirmButton: 'btn btn-danger px-4 mx-2', cancelButton: 'btn btn-secondary px-4 mx-2' }
            }).then((result) => { if (result.isConfirmed) window.location.href = url; });
        }
    });

    const searchInput = document.getElementById('search');
    const formSearch = document.getElementById('formSearch');

    if (!searchInput || !formSearch) return;

    let timeout;

    searchInput.addEventListener('input', function () {

        clearTimeout(timeout);

        timeout = setTimeout(() => {

            const keyword = this.value.trim();

            if (keyword === '') {
                window.location.href = window.location.pathname;
                return;
            }

            formSearch.submit();

        }, 800);

    });
});