const searchInput = document.getElementById('search');
const formSearch = document.getElementById('formSearch');

if (searchInput && formSearch) {

    let timeout;

    searchInput.addEventListener('input', function () {

        clearTimeout(timeout);

        timeout = setTimeout(() => {

            const keyword = this.value.trim();

            if (keyword === '') {
                window.location.href = formSearch.action;
                return;
            }

            formSearch.submit();

        }, 800);

    });

}

document.addEventListener('click', function (e) {

    const editBtn = e.target.closest('.btn-edit');

    if (editBtn) {

        const id   = editBtn.dataset.id;
        const nama = editBtn.dataset.nama;

        document.getElementById('edit_nama_kategori').value = nama;

        document.getElementById('formEditKategori').action =
            window.baseUrl +
            '/kategori/data_kategori/' +
            id +
            '/update';

        new bootstrap.Modal(
            document.getElementById('modalEditKategori')
        ).show();
    }

});

document.addEventListener('click', function (e) {

    const hapusBtn = e.target.closest('.btn-hapus');

    if (!hapusBtn) return;

    const nama = hapusBtn.dataset.nama;
    const url  = hapusBtn.dataset.url;

    Swal.fire({
        title: 'Hapus Kategori?',
        html: 'Kategori <b>' + nama + '</b> akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(function(result){

        if(result.isConfirmed){

            const form = document.createElement('form');

            form.method = 'POST';
            form.action = url;

            form.innerHTML =
                '<input type="hidden" name="_token" value="' +
                document.querySelector('meta[name=csrf-token]').content +
                '">';

            document.body.appendChild(form);
            form.submit();
        }
    });
});