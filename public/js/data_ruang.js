document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.btn-edit');
        const hapusBtn = e.target.closest('.btn-hapus');

        if (editBtn) {
            const id = editBtn.dataset.id;
            const namaRuangan = editBtn.dataset.nama_ruangan;
            const lantai = editBtn.dataset.lantai;

            document.getElementById('formEditruangan').action = baseUrl + '/ruang/data_ruang/' + id + '/update';
            document.getElementById('edit_ruangan').value = namaRuangan;
            document.getElementById('edit_lokasi').value = lantai;

            new bootstrap.Modal(document.getElementById('modalEditruangan')).show();
        }

        if (hapusBtn) {
            const namaRuangan = hapusBtn.dataset.nama_ruangan;
            const url = hapusBtn.dataset.url;

            Swal.fire({
                title: 'Hapus Ruangan?',
                html: `Hapus ruangan: <strong class="text-danger">${namaRuangan}</strong>?`,
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

    if (searchInput) {

        let timeout = null;

        searchInput.addEventListener('input', function () {

            clearTimeout(timeout);

            timeout = setTimeout(() => {

                const keyword = searchInput.value.trim();

                let url = baseUrl + '/ruang/data_ruang';

                if (keyword) {
                    url += '?search=' + encodeURIComponent(keyword);
                }

                if (window.location.href !== url) {
                    window.location.href = url;
                }

            }, 800);

        });

    }

    function cariRuangan() {

        const keyword = document.getElementById('search').value.trim();

        let url = baseUrl + '/ruang/data_ruang';

        if (keyword !== '') {
            url += '?search=' + encodeURIComponent(keyword);
        }

        window.location.href = url;
    }

    const modalTambah = document.getElementById('modalTambahruangan');
    if (modalTambah) {
        modalTambah.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && e.target.tagName !== 'SELECT' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                this.querySelector('button[type="submit"]').click();
            }
        });
    }
});