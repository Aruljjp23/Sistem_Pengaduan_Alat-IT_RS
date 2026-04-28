let semuaPerangkat   = [];
let perangkatDipilih = [];

function onScanSuccess(decodedText) {
    fetch("/api/perangkat/kode/" + encodeURIComponent(decodedText.trim()))
        .then(r => r.json())
        .then(data => {
            if (!data) { alert("Kode perangkat tidak ditemukan"); return; }

            document.getElementById("kode_scan").value              = data.kode_perangkat;
            document.getElementById("merek_scan").value             = data.merek;
            document.getElementById("kategori_perangkat_scan").value = data.kategori_perangkat;

            perangkatDipilih = [{ 
                id: data.id, kode_perangkat: data.kode_perangkat,              
                kategori_perangkat: data.kategori_perangkat, merek: data.merek 
            }];

            html5QrcodeScanner.clear();
            document.getElementById("card_scan").style.display = "none";
            tampilkanFormDenganPerangkat();
        });
}

let html5QrcodeScanner = new Html5QrcodeScanner("reader", {
    fps: 20,
    qrbox: { width: 250, height: 250 },
    rememberLastUsedCamera: true,
    formatsToSupport: [
        Html5QrcodeSupportedFormats.QR_CODE,
        Html5QrcodeSupportedFormats.CODE_128,
        Html5QrcodeSupportedFormats.CODE_39,
        Html5QrcodeSupportedFormats.EAN_13,
        Html5QrcodeSupportedFormats.EAN_8
    ]
});
html5QrcodeScanner.render(onScanSuccess);

function lewatiScan() {
    try { html5QrcodeScanner.clear(); } catch(e) {}
    document.getElementById("card_scan").style.display = "none";
    bukaModalPerangkat();
}

function bukaModalPerangkat() {
    muatDaftarPerangkat();
    new bootstrap.Modal(document.getElementById('modalPilihPerangkat')).show();
}

function muatDaftarPerangkat() {
    const tbody = document.getElementById('bodyPerangkat');
    tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4">
        <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
        <span class="ms-2 text-muted">Memuat data perangkat...</span>
    </td></tr>`;

    fetch("/api/perangkat/ruangan/" + id_ruangan)
        .then(r => r.json())
        .then(data => {
            semuaPerangkat = Array.isArray(data) ? data : [];
            renderTabelPerangkat(semuaPerangkat);
        })
        .catch(() => {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger py-3">
                <i class="fa-solid fa-circle-exclamation"></i> Gagal memuat data perangkat.
            </td></tr>`;
        });
}

function renderTabelPerangkat(list) {
    const tbody = document.getElementById('bodyPerangkat');
    if (!list.length) {
        tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-3">
            Tidak ada perangkat di ruangan ini.</td></tr>`;
        return;
    }
    tbody.innerHTML = list.map(p => {
        const checked = perangkatDipilih.some(d => d.id == p.id) ? 'checked' : '';
        return `<tr data-id="${p.id}" onclick="toggleBaris(this)" style="cursor:pointer;">
            <td onclick="event.stopPropagation()">
                <input type="checkbox" class="cb-perangkat" value="${p.id}" ${checked}
                    onchange="onCheckboxChange(this, ${JSON.stringify(p).replace(/"/g, '&quot;')})">
            </td>
            <td>${p.kode_perangkat}</td>
            <td>${p.kategori_perangkat}</td>
            <td>${p.merek}</td>
        </tr>`;
    }).join('');
    updateJumlahDipilih();
    syncCheckAll();
}

function toggleBaris(tr) {
    const cb = tr.querySelector('.cb-perangkat');
    cb.checked = !cb.checked;
    cb.dispatchEvent(new Event('change'));
}

function onCheckboxChange(cb, perangkat) {
    if (cb.checked) {
        if (!perangkatDipilih.some(p => p.id == perangkat.id)) perangkatDipilih.push(perangkat);
    } else {
        perangkatDipilih = perangkatDipilih.filter(p => p.id != perangkat.id);
    }
    updateJumlahDipilih();
    syncCheckAll();
}

function updateJumlahDipilih() {
    document.getElementById('jumlahDipilih').textContent = perangkatDipilih.length + ' perangkat dipilih';
}

function syncCheckAll() {
    const cbs = document.querySelectorAll('.cb-perangkat');
    const ca  = document.getElementById('checkAll');
    ca.checked       = cbs.length > 0 && [...cbs].every(c => c.checked);
    ca.indeterminate = !ca.checked && [...cbs].some(c => c.checked);
}

document.getElementById('checkAll').addEventListener('change', function() {
    document.querySelectorAll('.cb-perangkat').forEach(cb => {
        cb.checked = this.checked;
        const id = parseInt(cb.value);
        const p  = semuaPerangkat.find(x => x.id == id);
        if (this.checked && p && !perangkatDipilih.some(d => d.id == id)) {
            perangkatDipilih.push(p);
        } else if (!this.checked) {
            perangkatDipilih = perangkatDipilih.filter(d => d.id != id);
        }
    });
    updateJumlahDipilih();
});

document.getElementById('searchPerangkat').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    renderTabelPerangkat(semuaPerangkat.filter(p =>
        p.kode_perangkat.toLowerCase().includes(q) ||
        p.merek.toLowerCase().includes(q) ||
        p.kategori_perangkat.toLowerCase().includes(q)
    ));
});

function konfirmasiPilihPerangkat() {
    bootstrap.Modal.getInstance(document.getElementById('modalPilihPerangkat')).hide();
    tampilkanFormDenganPerangkat();
}

function lanjutTanpaPerangkat() {
    perangkatDipilih = [];
    bootstrap.Modal.getInstance(document.getElementById('modalPilihPerangkat')).hide();
    tampilkanFormTanpaPerangkat();
}

function tampilkanFormDenganPerangkat() {
    document.getElementById('form_pengaduan').style.display        = 'block';
    document.getElementById('section_perangkat_dipilih').style.display = 'block';
    document.getElementById('section_tanpa_perangkat').style.display   = 'none';

    document.getElementById('hidden_perangkat_ids').innerHTML =
        perangkatDipilih.map(p => `<input type="hidden" name="id_perangkat[]" value="${p.id}">`).join('');

    document.getElementById('body_perangkat_dipilih').innerHTML =
        perangkatDipilih.map((p, i) => `
        <tr>
            <td>${i + 1}</td>
            <td>${p.kode_perangkat}</td>
            <td>${p.kategori_perangkat}</td>
            <td>${p.merek}</td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="hapusDariDipilih(${p.id})">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </td>
        </tr>`).join('');
}

function tampilkanFormTanpaPerangkat() {
    document.getElementById('form_pengaduan').style.display            = 'block';
    document.getElementById('section_perangkat_dipilih').style.display = 'none';
    document.getElementById('section_tanpa_perangkat').style.display   = 'block';
    document.getElementById('hidden_perangkat_ids').innerHTML           = '';
}

function hapusDariDipilih(id) {
    perangkatDipilih = perangkatDipilih.filter(p => p.id != id);
    perangkatDipilih.length ? tampilkanFormDenganPerangkat() : tampilkanFormTanpaPerangkat();
}

function kembaliScan() {
    document.getElementById("card_scan").style.display    = "block";
    document.getElementById("form_pengaduan").style.display = "none";
    try { html5QrcodeScanner.clear(); } catch(e) {}
    html5QrcodeScanner = new Html5QrcodeScanner("reader", {
        fps: 20,
        qrbox: { width: 250, height: 250 },
        rememberLastUsedCamera: true
    });
    html5QrcodeScanner.render(onScanSuccess);
}