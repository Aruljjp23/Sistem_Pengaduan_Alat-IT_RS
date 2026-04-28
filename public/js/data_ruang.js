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
    const search = document.getElementById('search').value;

    const params = new URLSearchParams();
    if (search) params.append('search', search);

    const query = params.toString();
    window.location.href = '/ruang/data_ruang/cari' + (query ? '?' + query : '');
}

document.getElementById('modalTambahruangan').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.querySelector('#modalTambahruangan button[type="submit"]').click();
    }
});

function bindButtons() {
    document.querySelectorAll('.btn-edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id           = this.dataset.id;
            const nama_ruangan = this.dataset.nama_ruangan;
            const lokasi       = this.dataset.lokasi;

            document.getElementById('edit_ruangan').value = nama_ruangan;
            document.getElementById('edit_lokasi').value  = lokasi;

            document.getElementById('formEditruangan').action = baseUrl + '/ruang/data_ruang/' + id + '/update';

            const modal = new bootstrap.Modal(document.getElementById('modalEditruangan'));
            modal.show();
        });
    });

    document.querySelectorAll('.btn-hapus').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id           = this.dataset.id;
            const nama_ruangan = this.dataset.nama_ruangan;

            document.getElementById('hapus_nama_ruangan').textContent = nama_ruangan;
            document.getElementById('formHapus').action = baseUrl + '/ruang/data_ruang/' + id + '/delete';

            const modal = new bootstrap.Modal(document.getElementById('modalHapusruangan'));
            modal.show();
        });
    });
}

bindButtons();