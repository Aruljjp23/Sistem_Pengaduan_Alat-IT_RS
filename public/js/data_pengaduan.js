document.addEventListener('DOMContentLoaded', function () {

    const baseUrl = "{{ url('/') }}";

    let searchTimeout;

    document.getElementById('inputSearch').addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => navigateWithParams(), 1000);
    });

    document.querySelector('input[name="tanggal"]').addEventListener('change', function () {
        navigateWithParams();
    });

    function navigateWithParams() {
        const search = document.getElementById('inputSearch').value;
        const tanggal = document.querySelector('input[name="tanggal"]').value;
        const params = new URLSearchParams();

        if (search) params.append('search', search);
        if (tanggal) params.append('tanggal', tanggal);

        window.location.href = '/pengaduan/data_pengaduan' + (params.toString() ? '?' + params.toString() : '');
    }

    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', function () {
            const status = this.dataset.status;
            const statusClass = status === 'Selesai' ? 'selesai' : status === 'Dalam Proses' ? 'dalam-proses' : 'pending';

            document.getElementById('detail_nama_pengadu').textContent = this.dataset.nama_pengadu;
            document.getElementById('detail_tanggal').textContent = formatTanggal(this.dataset.tanggal);
            document.getElementById('detail_ruangan').textContent = this.dataset.ruangan;
            document.getElementById('detail_lokasi').textContent = this.dataset.lokasi;
            document.getElementById('detail_deskripsi').textContent = this.dataset.deskripsi;

            document.getElementById('detail_status_badge').innerHTML =
                `<span class="badge-status ${statusClass}">${status}</span>`;

            new bootstrap.Modal(document.getElementById('modalDetailPengaduan')).show();
        });
    });

    function formatTanggal(str) {
        if (!str) return '-';
        const d = new Date(str);
        return d.toLocaleDateString('id-ID');
    }

    document.getElementById('formEditPengaduan').addEventListener('submit', function(e){

        e.preventDefault();

        Swal.fire({
            title: 'Simpan perubahan?',
            text: 'Data akan diperbarui',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0ea5e9',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });

    });

    document.querySelectorAll('.btn-edit-action').forEach(button => {
        button.addEventListener('click', function () {

            let id = this.dataset.id;

            document.getElementById('edit_nama').value = this.dataset.nama_pengadu;
            document.getElementById('edit_tanggal').value = this.dataset.tanggal;
            document.getElementById('edit_deskripsi').value = this.dataset.deskripsi_masalah;

            document.getElementById('formEditPengaduan').action = `/pengaduan/data_pengaduan/${id}/update`;

            new bootstrap.Modal(document.getElementById('modalEditPengaduan')).show();
        });
    });

    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function () {

            let id = this.dataset.id;
            let nama = this.dataset.nama_pengadu;

            Swal.fire({
                title: 'Yakin hapus?',
                text: `Data ${nama} akan dihapus`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {

                    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/pengaduan/data_pengaduan/${id}/delete`;

                    form.innerHTML = `<input type="hidden" name="_token" value="${csrf}">`;

                    document.body.appendChild(form);
                    form.submit();
                }
            });

        });
    });

});