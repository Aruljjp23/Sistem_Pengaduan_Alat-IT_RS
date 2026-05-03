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