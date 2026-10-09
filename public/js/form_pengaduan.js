"use strict";

let perangkatDipilih = [];
let isProcessingScan = false;
let activeStream     = null;
let currentDeviceId  = null;
let cameras          = [];
let decodeRAF        = null;
let isMobileDevice   = false;
let ID_RUANGAN_AKTIF = null;

document.addEventListener("DOMContentLoaded", () => {
    const ruanganInput = document.querySelector('input[name="id_ruangan"]');
    if (ruanganInput) {
        ID_RUANGAN_AKTIF = parseInt(ruanganInput.value, 10);
    }

    const mainForm = document.getElementById("mainForm");
    if (mainForm) {
        mainForm.addEventListener("submit", () => {
            const overlay = document.getElementById("submitLoading");
            if (overlay) overlay.classList.add("show");
        });
    }

    const manualInput = document.getElementById("manual_search_input");
    if (manualInput) {
        manualInput.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                doManualSearch();
            }
        });
        manualInput.addEventListener("input", debounce(() => {
            const q = manualInput.value.trim();
            if (q.length >= 2) {
                doManualSearch();
            } else if (q.length === 0) {
                tutupHasilCari();
            }
        }, 400));
    }

    initScanner();
});

function debounce(fn, delay) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), delay);
    };
}

async function doManualSearch() {
    const input = document.getElementById("manual_search_input");
    const keyword = input ? input.value.trim() : "";

    if (!keyword) {
        showManualEmpty("Masukkan kata kunci terlebih dahulu.");
        return;
    }

    if (keyword.length < 2) {
        showManualEmpty("Kata kunci minimal 2 karakter.");
        return;
    }

    setManualLoading(true);
    hideManualEmpty();
    hideManualResults();

    try {
        const params = new URLSearchParams({ keyword });

        if (ID_RUANGAN_AKTIF) {
            params.append("id_ruangan", ID_RUANGAN_AKTIF);
        }

        const url = `/api/perangkat/cari?${params.toString()}`;

        console.log("URL Request:", url);

        const res = await fetch(url, {
            method: "GET",
            headers: {
                "Accept": "application/json"
            }
        });

        console.log("Status Response:", res.status);

        if (!res.ok) {
            const text = await res.text();
            console.error("Response Server:", text);

            throw new Error(`HTTP ${res.status}`);
        }

        const data = await res.json();

        console.log("Data Perangkat:", data);

        const list = Array.isArray(data)
            ? data
            : (data.data ?? []);

        setManualLoading(false);

        if (list.length === 0) {
            showManualEmpty(
                `Tidak ada perangkat yang cocok dengan "<strong>${esc(keyword)}</strong>".`
            );
            return;
        }

        renderManualResults(list);

    } catch (err) {
        setManualLoading(false);

        console.error("[doManualSearch ERROR]", err);

        showManualEmpty(
            `Gagal mengambil data perangkat. Error: ${esc(err.message)}`
        );
    }
}

function renderManualResults(list) {
    const tbody = document.getElementById("manual_result_tbody");
    const count = document.getElementById("manual_result_count");
    if (!tbody) return;

    if (count) count.textContent = list.length;

    tbody.innerHTML = "";

    list.forEach((p, i) => {
        const sudahAda = perangkatDipilih.some(x => x.id == p.id);

        const tr = document.createElement("tr");

        if (sudahAda) {
            tr.style.background = "#f0fdf4";
        }

        tr.innerHTML = `
            <td class="ps-3 text-muted">${i + 1}</td>
            <td class="fw-bold">
                <code style="color:#4f46e5;">${esc(p.kode_inventaris)}</code>
            </td>
            <td>
                <code style="color:#e83e8c;font-size:0.8rem;">${esc(p.alamat_ip || "-")}</code>
            </td>
            <td>${esc(p.merek || "-")}</td>
            <td>
                <span class="badge bg-secondary-subtle text-secondary border" style="font-size:0.75rem;">
                    ${esc(p.kategori_perangkat || "-")}
                </span>
            </td>
            <td class="text-center">
                ${sudahAda
                    ? `<span class="badge bg-success-subtle text-success border px-3 py-2">
                           <i class="fa-solid fa-check me-1"></i>Sudah Ditambahkan
                       </span>`
                    : `<button type="button"
                               class="btn btn-sm btn-primary px-3"
                               onclick="tambahDariManual(${JSON.stringify(p).replace(/"/g, '&quot;')})">
                           <i class="fa-solid fa-plus me-1"></i>Tambahkan
                       </button>`
                }
            </td>`;

        tbody.appendChild(tr);
    });

    showManualResults();
}

function tambahDariManual(p) {
    if (perangkatDipilih.some(x => x.id == p.id)) {
        showToast(`⚠️ <strong>${esc(p.kode_inventaris)}</strong> sudah ada dalam daftar!`, "warning");
        return;
    }

    perangkatDipilih.push({
        id                : p.id,
        kode_inventaris   : p.kode_inventaris,
        kategori_perangkat: p.kategori_perangkat,
        merek             : p.merek,
        alamat_ip         : p.alamat_ip || null,
    });

    setVal("kode_scan",               p.kode_inventaris    || "-");
    setVal("merek_scan",              p.merek               || "-");
    setVal("kategori_perangkat_scan", p.kategori_perangkat  || "-");

    updateScanTable();

    showToast(
        `✅ <strong>${esc(p.kode_inventaris)}</strong> berhasil ditambahkan!`,
        "success"
    );

    const keyword = document.getElementById("manual_search_input")?.value.trim();
    if (keyword) doManualSearch();

    setTimeout(() => { goToForm(); }, 1200);
}

function tutupHasilCari() {
    hideManualResults();
    hideManualEmpty();
    const input = document.getElementById("manual_search_input");
    if (input) input.value = "";
}

function setManualLoading(show) {
    const el = document.getElementById("manual_search_loading");
    if (!el) return;
    el.classList.toggle("d-none", !show);
}

function showManualEmpty(msg) {
    const el  = document.getElementById("manual_search_empty");
    const txt = document.getElementById("manual_search_empty_msg");
    if (!el) return;
    if (txt) txt.innerHTML = msg;
    el.classList.remove("d-none");
}

function hideManualEmpty() {
    document.getElementById("manual_search_empty")?.classList.add("d-none");
}

function showManualResults() {
    document.getElementById("manual_search_results")?.classList.remove("d-none");
}

function hideManualResults() {
    document.getElementById("manual_search_results")?.classList.add("d-none");
}

function detectMobile() {
    return /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function canUseLiveCamera() {
    const host = location.hostname;

    const isLocal =
        host === "localhost" ||
        host === "127.0.0.1" ||
        host === "::1";

    const isLanIp =
        /^192\.168\./.test(host) ||
        /^10\./.test(host) ||
        /^172\.(1[6-9]|2\d|3[0-1])\./.test(host);

    const isTrustedDomain =
        host === "sistem_pengaduan_rs.com" ||
        host.endsWith(".miooo.my.id") ||
        host === "miooo.my.id";

    return isLocal || isLanIp || isTrustedDomain;
}

async function initScanner() {
    isMobileDevice = detectMobile();

    if (isMobileDevice) {
        startMobilePhotoScanner();
        return;
    }

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        hideKameraError();
        return;
    }

    try {
        const tempStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
        tempStream.getTracks().forEach(t => t.stop());

        const devices = await navigator.mediaDevices.enumerateDevices();
        cameras = devices.filter(d => d.kind === "videoinput");
        currentDeviceId = getDefaultCamera();

        await startDesktopScanner();
    } catch (err) {
        handleCameraError(err);
    }
}

function startMobilePhotoScanner() {
    document.getElementById("camera_error_box")?.remove();

    let input = document.getElementById("qr_image_input");
    if (!input) {
        input = document.createElement("input");
        input.type = "file";
        input.id = "qr_image_input";
        input.accept = "image/*";
        input.hidden = true;
        document.body.appendChild(input);
    }

    setTimeout(() => { input.click(); }, 300);

    input.onchange = async function () {
        const file = this.files[0];
        this.value = "";
        if (!file) return;

        showToast("Sedang membaca QR Code...", "info");

        try {
            const bitmap = await createImageBitmap(file);
            const hasil  = await decodeQrFromBitmap(bitmap);

            if (hasil) {
                onScanSuccess(hasil);
            } else {
                showToast("QR Code tidak terbaca. Coba foto lebih dekat, fokus, & hindari pantulan cahaya.", "warning");
                setTimeout(() => { input.click(); }, 800);
            }
        } catch (err) {
            console.error("[startMobilePhotoScanner]", err);
            showToast("Gagal membaca foto. Coba lagi.", "danger");
            setTimeout(() => { input.click(); }, 800);
        }
    };
}

async function decodeQrFromBitmap(bitmap) {
    const ukuranDicoba = [1000, Math.max(bitmap.width, bitmap.height)];

    for (const maxSize of ukuranDicoba) {
        const { canvas, ctx } = drawBitmapScaled(bitmap, maxSize);
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

        const qr = jsQR(imageData.data, canvas.width, canvas.height, {
            inversionAttempts: "attemptBoth"
        });

        if (qr?.data) return qr.data;
    }

    return null;
}

function drawBitmapScaled(bitmap, maxSize) {
    let width = bitmap.width, height = bitmap.height;

    if (width > height) {
        if (width > maxSize) { height = Math.round(height * (maxSize / width)); width = maxSize; }
    } else {
        if (height > maxSize) { width = Math.round(width * (maxSize / height)); height = maxSize; }
    }

    const canvas = document.createElement("canvas");
    canvas.width  = width;
    canvas.height = height;
    const ctx = canvas.getContext("2d", { willReadFrequently: true });
    ctx.drawImage(bitmap, 0, 0, width, height);

    return { canvas, ctx };
}

function getDefaultCamera() {
    if (isMobileDevice) {
        const back = cameras.find(c => {
            const lbl = c.label.toLowerCase();
            return lbl.includes("back") || lbl.includes("rear") ||
                   lbl.includes("environment") || lbl.includes("belakang");
        });
        return back ? back.deviceId : cameras[cameras.length - 1].deviceId;
    }
    return cameras[0].deviceId;
}

async function startDesktopScanner() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        return;
    }

    await stopScanner();
    hideKameraError();

    const video  = document.getElementById("reader-video");
    const canvas = document.getElementById("decode-canvas");
    if (!video || !canvas) return;

    try {
        activeStream = await navigator.mediaDevices.getUserMedia(buildConstraints());
        video.srcObject = activeStream;

        const track = activeStream.getVideoTracks()[0];
        if (track && track.getCapabilities) {
            try {
                const caps = track.getCapabilities();
                if (caps.focusMode) {
                    await track.applyConstraints({
                        advanced: [{ focusMode: "continuous" }]
                    });
                }
            } catch (e) {}
        }

        await new Promise(resolve => {
            video.onloadedmetadata = () => resolve();
        });

        await video.play().catch(() => {});

        await new Promise(r => setTimeout(r, 800));
        startDecodeLoop(video, canvas);
    } catch (err) {
        console.error("[startDesktopScanner]", err);
        handleCameraError(err);
    }
}

function buildConstraints() {
    return {
        audio: false,
        video: {
            facingMode: { ideal: "environment" },
            width: { ideal: 1920 },
            height: { ideal: 1080 }
        }
    };
}

async function stopScanner() {
    if (decodeRAF) { cancelAnimationFrame(decodeRAF); decodeRAF = null; }
    if (activeStream) { activeStream.getTracks().forEach(t => t.stop()); activeStream = null; }
    const video = document.getElementById("reader-video");
    if (video) video.srcObject = null;
}

function startDecodeLoop(video, canvas) {
    const ctx = canvas.getContext("2d", { willReadFrequently: true });

    function tick() {
        if (!activeStream) return;

        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            const w = video.videoWidth;
            const h = video.videoHeight;

            if (w > 0 && h > 0) {

                canvas.width = w;
                canvas.height = h;

                ctx.drawImage(video, 0, 0, w, h);

                const imageData = ctx.getImageData(0, 0, w, h);

                const code = jsQR(imageData.data, w, h, {
                    inversionAttempts: "attemptBoth"
                });

                if (code?.data && !isProcessingScan) {
                    stopScanner();
                    onScanSuccess(code.data);
                    return;
                }
            }
        }

        decodeRAF = requestAnimationFrame(tick);
    }

    decodeRAF = requestAnimationFrame(tick);
}

function handleCameraError(err) {
    const name = err.name || "";
    if (name === "NotAllowedError" || name === "PermissionDeniedError") {
        showKameraError("<strong>Izin kamera ditolak.</strong><br>Klik ikon kamera di address bar dan pilih <em>Izinkan</em>, lalu reload.");
    } else if (name === "NotFoundError" || name === "DevicesNotFoundError") {
        showKameraError("<strong>Kamera tidak ditemukan.</strong><br>Pastikan kamera terhubung dan driver terinstall.");
    } else if (name === "NotReadableError" || name === "TrackStartError") {
        showKameraError("<strong>Kamera sedang dipakai aplikasi lain.</strong><br>Tutup Zoom/Teams/dll, lalu reload halaman.");
    } else if (name === "OverconstrainedError") {
        currentDeviceId = null;
        startDesktopScanner();
    } else if (!canUseLiveCamera()) {
        return;
    } else {
        showKameraError(
            "<strong>Kamera gagal diakses.</strong><br>" +
            "1. Pastikan izin kamera diizinkan<br>" +
            "2. Tidak dipakai aplikasi lain<br>" +
            "3. Reload halaman<br>" +
            `<small class='text-muted'>Error: ${name || err.message}</small>`
        );
    }
}

function showKameraError(pesan) {
    const box = document.getElementById("camera_error_box");
    const msg = document.getElementById("camera_error_msg");
    if (!box || !msg) return;
    msg.innerHTML = pesan;
    box.style.display = "flex";
}

function hideKameraError() {
    const box = document.getElementById("camera_error_box");
    if (box) box.style.display = "none";
}

function showToast(pesan, tipe = "success") {
    document.getElementById("toast_scan_global")?.remove();
    const icons  = { success: "fa-circle-check", warning: "fa-triangle-exclamation", danger: "fa-circle-xmark", info: "fa-circle-info" };
    const colors = { success: "#16a34a", warning: "#d97706", danger: "#dc2626", info: "#2563eb" };
    const bgs    = { success: "#f0fdf4", warning: "#fffbeb", danger: "#fef2f2", info: "#eff6ff" };
    const toast  = document.createElement("div");
    toast.id = "toast_scan_global";
    Object.assign(toast.style, {
        position: "fixed", top: "24px", left: "50%",
        transform: "translateX(-50%) translateY(-20px)", zIndex: "99999",
        background: bgs[tipe] || "#fff", border: `2px solid ${colors[tipe] || "#2563eb"}`,
        borderRadius: "14px", padding: "14px 22px", display: "flex",
        alignItems: "center", gap: "12px", boxShadow: "0 8px 32px rgba(0,0,0,0.15)",
        fontSize: "0.95rem", fontWeight: "600", color: colors[tipe] || "#2563eb",
        minWidth: "280px", maxWidth: "90vw", opacity: "0",
        transition: "all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1)", pointerEvents: "none",
    });
    toast.innerHTML = `<i class="fa-solid ${icons[tipe] || "fa-circle-info"}" style="font-size:1.3rem;flex-shrink:0;"></i><span>${pesan}</span>`;
    document.body.appendChild(toast);
    requestAnimationFrame(() => requestAnimationFrame(() => {
        toast.style.opacity = "1";
        toast.style.transform = "translateX(-50%) translateY(0)";
    }));
    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateX(-50%) translateY(-20px)";
        setTimeout(() => toast.remove(), 400);
    }, 2600);
}

function showAlert(pesan, tipe = "info") {
    const el = document.getElementById("scan_alert_success");
    if (el) {
        el.className = `alert mt-2 py-2 alert-${tipe}`;
        el.textContent = pesan;
        el.classList.remove("d-none");
        setTimeout(() => el.classList.add("d-none"), 2800);
    }
    showToast(pesan, tipe);
}

function restartScanner() {
    isProcessingScan = false;
    if (isMobileDevice) {
        startMobilePhotoScanner();
    } else {
        startDesktopScanner();
    }
}

function onScanSuccess(decodedText) {
    if (isProcessingScan) return;
    isProcessingScan = true;
    navigator.vibrate?.(50);

    let kodeInventaris  = decodedText.trim();
    let idRuanganDariQR = null;

    if (kodeInventaris.includes(" - ")) {
        kodeInventaris = kodeInventaris.split(" - ")[0].trim();
    }

    try {
        const parsed = JSON.parse(decodedText);
        if (parsed?.kode_inventaris) {
            kodeInventaris  = parsed.kode_inventaris.trim();
            idRuanganDariQR = parsed.id_ruangan != null ? parseInt(parsed.id_ruangan, 10) : null;
        }
    } catch (e) {}

    if (idRuanganDariQR !== null && ID_RUANGAN_AKTIF !== null && idRuanganDariQR !== ID_RUANGAN_AKTIF) {
        showAlert("❌ Perangkat ini bukan milik ruangan ini! (QR dari ruangan lain)", "danger");
        setTimeout(restartScanner, 2000);
        return;
    }

    const apiUrl = `/api/perangkat/kode/${encodeURIComponent(kodeInventaris)}`
        + (ID_RUANGAN_AKTIF ? `?id_ruangan=${ID_RUANGAN_AKTIF}` : "");

    fetch(apiUrl)
        .then(res => {
            if (res.status === 403) throw new Error("wrong_room");
            if (res.status === 404) throw new Error("not_found");
            if (!res.ok) throw new Error("HTTP " + res.status);
            return res.json();
        })
        .then(data => {
            if (!data?.kode_inventaris) {
                showAlert(`⚠️ Kode tidak ditemukan: ${kodeInventaris}`, "warning");
                restartScanner();
                return;
            }

            setVal("kode_scan",               data.kode_inventaris    || "-");
            setVal("merek_scan",              data.merek               || "-");
            setVal("kategori_perangkat_scan", data.kategori_perangkat  || "-");

            if (perangkatDipilih.some(p => p.id == data.id)) {
                showToast(`⚠️ <strong>${esc(data.kode_inventaris)}</strong> sudah ada dalam daftar scan!`, "warning");
                restartScanner();
                return;
            }

            perangkatDipilih.push({
                id                : data.id,
                kode_inventaris   : data.kode_inventaris,
                kategori_perangkat: data.kategori_perangkat,
                merek             : data.merek,
                alamat_ip         : data.alamat_ip || null,
            });

            updateScanTable();
            showToast(`✅ <strong>${esc(data.kode_inventaris)}</strong> berhasil ditambahkan!`, "success");

            setTimeout(() => {
                isProcessingScan = false;
                goToForm();
            }, 1500);
        })
        .catch(err => {
            if (err.message === "wrong_room") {
                showAlert("❌ Perangkat ini bukan milik ruangan ini!", "danger");
            } else if (err.message === "not_found") {
                showAlert("⚠️ Perangkat tidak terdaftar di sistem.", "warning");
            } else {
                console.error("[onScanSuccess]", err);
                showAlert("⚠️ Gagal mengambil data. Coba scan ulang.", "warning");
            }
            setTimeout(restartScanner, 2000);
        });
}

function updateScanTable() {
    const tbody   = document.getElementById("body_scan_list");
    const section = document.getElementById("section_scan_list");
    if (!tbody) return;
    tbody.innerHTML = "";
    perangkatDipilih.forEach((p, i) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${i + 1}</td>
            <td><code>${esc(p.kode_inventaris)}</code></td>
            <td>${esc(p.kategori_perangkat || "-")}</td>
            <td>${esc(p.merek || "-")}</td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusDariList(${i})">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>`;
        tbody.appendChild(tr);
    });
    if (section) section.style.display = perangkatDipilih.length > 0 ? "block" : "none";
}

function hapusDariList(index) {
    perangkatDipilih.splice(index, 1);
    updateScanTable();
}

function goToForm() {
    if (perangkatDipilih.length === 0) {
        showAlert("⚠️ Belum ada perangkat yang di-scan!", "warning");
        return;
    }
    stopScanner();
    document.getElementById("banner_sudah_scan")?.remove();
    document.getElementById("card_scan").style.display     = "none";
    document.getElementById("form_pengaduan").style.display = "block";
    syncHiddenInputs();
    renderFormCards();
    document.getElementById("form_pengaduan").scrollIntoView({ behavior: "smooth", block: "start" });
}

function renderFormCards() {
    const container      = document.getElementById("badge_perangkat_container");
    const formCount      = document.getElementById("form_count");
    const sectionDipilih = document.getElementById("section_perangkat_dipilih");

    if (formCount) formCount.textContent = perangkatDipilih.length;
    if (!container) return;

    container.innerHTML = "";

    if (perangkatDipilih.length > 1) {
        const row = document.createElement("div");
        row.className = "w-100 d-flex justify-content-end mb-2";
        row.innerHTML = `
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusSemuaPerangkat()">
                <i class="fa-solid fa-trash-can me-1"></i> Hapus Semua Perangkat
            </button>`;
        container.appendChild(row);
    }

    perangkatDipilih.forEach((p, i) => {
        const card = document.createElement("div");
        card.className = "border rounded p-3 bg-white shadow-sm mb-2 w-100";
        card.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div class="fw-bold text-primary mb-2">
                    <strong>Kode Inventaris :</strong> ${esc(p.kode_inventaris)}
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger ms-2"
                        title="Hapus perangkat ini" onclick="hapusPerangkatDiForm(${i})">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
            <div class="small">
                <div><strong>Merek :</strong> ${esc(p.merek || "-")}</div>
                <div><strong>IP Address :</strong> ${esc(p.alamat_ip || "-")}</div>
                <div><strong>Kategori :</strong> ${esc(p.kategori_perangkat || "-")}</div>
            </div>`;
        container.appendChild(card);
    });

    if (sectionDipilih) sectionDipilih.style.display = "block";
    syncHiddenInputs();
}

function hapusPerangkatDiForm(index) {
    const kode = perangkatDipilih[index]?.kode_inventaris || "";
    perangkatDipilih.splice(index, 1);
    if (perangkatDipilih.length === 0) {
        showToast("ℹ️ Semua perangkat dihapus. Silakan scan ulang.", "info");
        kembaliScan();
        return;
    }
    showToast(`🗑️ ${esc(kode)} dihapus dari daftar.`, "warning");
    renderFormCards();
}

function hapusSemuaPerangkat() {
    if (!confirm("Yakin ingin menghapus semua perangkat yang dipilih?")) return;
    perangkatDipilih = [];
    showToast("ℹ️ Semua perangkat dihapus. Silakan scan ulang.", "info");
    kembaliScan();
}

function syncHiddenInputs() {
    const container = document.getElementById("hidden_perangkat_ids");
    if (!container) return;
    container.innerHTML = "";
    perangkatDipilih.forEach(p => {
        const inp = document.createElement("input");
        inp.type  = "hidden";
        inp.name = "id_perangkat[]";
        inp.value = p.id;
        container.appendChild(inp);
    });
}

function kembaliScan() {
    document.getElementById("card_scan").style.display     = "block";
    document.getElementById("form_pengaduan").style.display = "none";

    if (isMobileDevice) {
        startMobilePhotoScanner();
    } else {
        startDesktopScanner();
    }
}

function setVal(id, val) {
    const el = document.getElementById(id);
    if (el) el.value = val;
}

function esc(str) {
    const d = document.createElement("div");
    d.appendChild(document.createTextNode(str || ""));
    return d.innerHTML;
}