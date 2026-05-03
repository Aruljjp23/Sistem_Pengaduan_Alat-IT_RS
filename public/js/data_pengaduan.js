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
        const icon = status === 'Selesai' ? 'bi-check-circle-fill' : status === 'Dalam Proses' ? 'bi-arrow-repeat' : 'bi-clock-history';

        document.getElementById('detail_nama_pengadu').textContent = this.dataset.nama_pengadu;
        document.getElementById('detail_tanggal').textContent = formatTanggal(this.dataset.tanggal);
        document.getElementById('detail_ruangan').textContent = this.dataset.ruangan;
        document.getElementById('detail_lokasi').textContent = this.dataset.lokasi;
        document.getElementById('detail_deskripsi').textContent = this.dataset.deskripsi;
        
        document.getElementById('detail_status_badge').innerHTML = 
            `<span class="badge-status ${statusClass}"><i class="bi ${icon}"></i> ${status}</span>`;

        const section = document.getElementById('section_tindakan');
        if (status !== 'Pending') {
            document.getElementById('detail_teknisi').textContent = this.dataset.teknisi || 'Menunggu teknisi...';
            document.getElementById('detail_kondisi').textContent = this.dataset.kondisi || 'Belum ada catatan.';
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }

        new bootstrap.Modal(document.getElementById('modalDetailPengaduan')).show();
    });
});

function formatTanggal(str) {
    if (!str) return '-';
    const d = new Date(str);
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + ' WIB';
}

document.querySelectorAll('.btn-edit').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const id      = this.dataset.id;
        const tanggal = this.dataset.tanggal;
 
        document.getElementById('formEditPengaduan').action = `/pengaduan/data_pengaduan/${id}/update`;
 
        document.getElementById('edit_nama_pengadu').value      = this.dataset.nama_pengadu;
        document.getElementById('edit_deskripsi_masalah').value = this.dataset.deskripsi_masalah;
 
        if (tanggal) {
            const d = new Date(tanggal);
            if (!isNaN(d)) {
                const pad = n => String(n).padStart(2, '0');
                document.getElementById('edit_tanggal').value =
                    `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
            }
        }
 
        new bootstrap.Modal(document.getElementById('modalEditPengaduan')).show();
    });
});
 
document.querySelectorAll('.btn-hapus').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const id   = this.dataset.id;
        const nama = this.dataset.nama_pengadu;
 
        document.getElementById('hapus_nama_pengadu').textContent = nama;
        document.getElementById('formHapusPengaduan').action =
            `/pengaduan/data_pengaduan/${id}/delete`;
 
        new bootstrap.Modal(document.getElementById('modalHapusPengaduan')).show();
    });
});