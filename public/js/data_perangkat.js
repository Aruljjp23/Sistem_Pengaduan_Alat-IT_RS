const searchInput = document.getElementById('search');
const formSearch  = document.getElementById('formSearch');

if (searchInput && formSearch) {
    let timeout;
    searchInput.addEventListener('input', function () {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            const keyword   = this.value.trim();
            const idRuangan = document.getElementById('id_ruangan').value;
            const kategori  = new URLSearchParams(window.location.search).get('kategori');

            let url = '/perangkat/data_perangkat?id_ruangan=' + idRuangan;

            if (keyword !== '') {
                url += '&search=' + encodeURIComponent(keyword);
            }
            if (kategori) {
                url += '&kategori=' + encodeURIComponent(kategori);
            }

            window.location.href = url;
        }, 500);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    if (!window.updateResultData) return;

    const result = window.updateResultData;
    const row    = document.querySelector(`tr[data-id="${result.id}"]`);
    if (!row) return;

    row.classList.add('row-updated');

    const fieldMap = {
        kode_inventaris   : 'kode_inventaris',
        alamat_ip         : 'alamat_ip',
        merek             : 'merek',
        kategori_perangkat: 'kategori_perangkat',
    };

    if (result.changes) {
        Object.entries(result.changes).forEach(([field, val]) => {
            const col = fieldMap[field];
            if (!col) return;

            const cell = row.querySelector(`td[data-col="${col}"]`);
            if (!cell) return;

            if (col === 'alamat_ip') {
                const el = cell.querySelector('code');
                if (el) el.textContent = val.baru;
            } else if (col === 'kode_inventaris') {
                let textNode = null;
                cell.childNodes.forEach(node => {
                    if (node.nodeType === Node.TEXT_NODE && node.textContent.trim() !== '') {
                        textNode = node;
                    }
                });
                if (textNode) {
                    textNode.textContent = val.baru;
                } else {
                    cell.textContent = val.baru;
                }
            } else if (col === 'kategori_perangkat') {
                const el = cell.querySelector('.badge');
                if (el) el.textContent = val.baru;
            } else {
                let textNode = null;
                cell.childNodes.forEach(node => {
                    if (node.nodeType === Node.TEXT_NODE && node.textContent.trim() !== '') {
                        textNode = node;
                    }
                });
                if (textNode) {
                    textNode.textContent = val.baru;
                } else {
                    cell.textContent = val.baru;
                }
            }

            const badge       = document.createElement('span');
            badge.className   = 'change-badge';
            badge.title       = `Diubah dari: ${val.lama}`;
            badge.textContent = `${val.lama} → ${val.baru}`;
            cell.appendChild(badge);
        });
    }

    row.scrollIntoView({ behavior: 'smooth', block: 'center' });
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.table tbody tr').forEach((row, i) => {
        row.style.animationDelay = `${i * 0.03}s`;
    });
});

let _modalEditInstance = null;

function getEditModal() {
    const modalEl = document.getElementById('modalEditperangkat');
    if (!modalEl) return null;
    if (!_modalEditInstance) {
        _modalEditInstance = new bootstrap.Modal(modalEl, {
            backdrop: true,
            keyboard: true,
            focus   : true,
        });
        modalEl.addEventListener('hidden.bs.modal', () => {
            if (_lastEditTrigger) {
                _lastEditTrigger.focus();
                _lastEditTrigger = null;
            }
        });
    }
    return _modalEditInstance;
}

let _lastEditTrigger = null;

document.addEventListener('click', function (e) {

    const editBtn = e.target.closest('.btn-edit');
    if (editBtn) {
        const d = editBtn.dataset;

        const formEdit   = document.getElementById('formEditperangkat');
        const elKode     = document.getElementById('edit_kode_inventaris');
        const elIp       = document.getElementById('edit_alamat_ip');
        const elMerek    = document.getElementById('edit_merek');
        const elKategori = document.getElementById('edit_id_kategori');

        if (!formEdit || !elKode || !elIp || !elMerek || !elKategori) {
            console.error('Modal edit: ada elemen yang tidak ditemukan di HTML!');
            return;
        }

        formEdit.action = BASE_URL + '/perangkat/data_perangkat/' + d.id_perangkat + '/update';

        elKode.value     = d.kode_inventaris;
        elIp.value       = d.alamat_ip;
        elMerek.value    = d.merek;
        elKategori.value = d.id_kategori;

        _lastEditTrigger = editBtn;

        const modal = getEditModal();
        if (modal) modal.show();

        return;
    }

    const hapusBtn = e.target.closest('.btn-hapus');
    if (hapusBtn) {
        const deleteUrl = hapusBtn.dataset.url;
        const kategori  = hapusBtn.dataset.kategori_perangkat;

        hapusBtn.blur();

        if (typeof Swal === 'undefined') {
            console.error('SweetAlert2 tidak ditemukan. Dialihkan langsung ke URL hapus.');
            window.location.href = deleteUrl;
            return;
        }

        Swal.fire({
            title             : 'Hapus Perangkat?',
            html              : `Perangkat <b>${kategori || 'ini'}</b> akan dihapus secara permanen!`,
            icon              : 'warning',
            showCancelButton  : true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor : '#6b7280',
            confirmButtonText : 'Ya, Hapus',
            cancelButtonText  : 'Batal',
            focusCancel       : true,
            customClass       : { popup: 'rounded-4' },
        }).then((res) => {
            if (res.isConfirmed) {
                window.location.href = deleteUrl;
            }
        });

        return;
    }
});