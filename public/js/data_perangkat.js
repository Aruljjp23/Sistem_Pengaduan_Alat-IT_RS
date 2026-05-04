let searchTimeout;

document.getElementById('search').addEventListener('input', function () {
    clearTimeout(searchTimeout);
    const value = this.value;

    searchTimeout = setTimeout(() => {
        if (value.length >= 2 || value.length === 0) {
            navigateWithParams();
        }
    }, 2000);
});

function navigateWithParams() {
    const search    = document.getElementById('search').value;
    const id_ruangan = document.getElementById('id_ruangan').value;

    const params = new URLSearchParams();

    if (id_ruangan) params.append('id_ruangan', id_ruangan);
    if (search) params.append('search', search);

    const query = params.toString();
    window.location.href = '/perangkat/data_perangkat/cari' + (query ? '?' + query : '');
}

document.getElementById('modalTambahperangkat').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.querySelector('#modalTambahperangkat button[type="submit"]').click();
    }
});

document.addEventListener('DOMContentLoaded', function () {
 
    document.querySelectorAll('.btn-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id                    = this.dataset.id;
            const kode_perangkat        = this.dataset.kode_perangkat;
            const ip_jaringan           = this.dataset.ip_jaringan;
            const merek                 = this.dataset.merek;
            const kategori_perangkat    = this.dataset.kategori_perangkat;
 
            document.getElementById('formEditperangkat').action = baseUrl + '/perangkat/data_perangkat/' + id + '/update';
 
            document.getElementById('edit_kode_perangkat').value       = kode_perangkat;
            document.getElementById('edit_perangkat').value            = ip_jaringan;
            document.getElementById('edit_merek').value                = merek;
            document.getElementById('edit_kategori_perangkat').value   = kategori_perangkat;
 
            var editModal = new bootstrap.Modal(document.getElementById('modalEditperangkat'));
            editModal.show();
        });
    });
 
    document.querySelectorAll('.btn-hapus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const kategoriPerangkat = this.dataset.kategori_perangkat;
            const url               = this.dataset.url;
 
            Swal.fire({
                title: 'Hapus Perangkat?',
                html: `Anda yakin ingin menghapus perangkat:<br>
                <strong class="text-danger">${kategoriPerangkat}</strong>?<br>
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