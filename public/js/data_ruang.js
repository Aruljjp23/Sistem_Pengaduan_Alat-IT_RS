document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('search');
    if (!input) return;

    input.addEventListener('keyup', function () {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll('#ruanganTable tr');

        rows.forEach(function(row) {

            if (row.id === 'noRoomData') return;

            let nama = row.children[1]?.innerText.toLowerCase() || '';
            let lokasi = row.children[2]?.innerText.toLowerCase() || '';

            if (nama.includes(keyword) || lokasi.includes(keyword)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }

        });

        document.getElementById('noRoomData').style.display = found ? 'none' : '';
    });

});

document.getElementById('modalTambahruangan').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.querySelector('#modalTambahruangan button[type="submit"]').click();
    }
});

document.addEventListener('DOMContentLoaded', function () {
 
    document.querySelectorAll('.btn-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id           = this.dataset.id;
            const namaRuangan  = this.dataset.nama_ruangan;
            const lantai       = this.dataset.lantai;
 
            document.getElementById('formEditruangan').action = baseUrl + '/ruang/data_ruang/' + id + '/update';
 
            document.getElementById('edit_ruangan').value = namaRuangan;
            document.getElementById('edit_lokasi').value  = lantai;
 
            var editModal = new bootstrap.Modal(document.getElementById('modalEditruangan'));
            editModal.show();
        });
    });
 
    document.querySelectorAll('.btn-hapus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const namaRuangan = this.dataset.nama_ruangan;
            const url         = this.dataset.url;
 
            Swal.fire({
                title: 'Hapus Ruangan?',
                html: `Anda yakin ingin menghapus ruangan:<br>
                       <strong class="text-danger">${namaRuangan}</strong>?<br>
                       <small class="text-muted">Data perangkat yang terkait mungkin ikut terhapus.</small>`,
                icon: 'warning',
                iconColor: '#dc3545',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-trash me-1"></i> Ya, Hapus!',
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