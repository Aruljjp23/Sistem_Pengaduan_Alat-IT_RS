"use strict";

let perangkatDipilih = [];
let isProcessingScan = false;
let activeStream     = null;
let currentDeviceId  = null;
let cameras          = [];
let decodeRAF        = null;
let isMobileDevice   = false;
let ID_RUANGAN_AKTIF = null;
let useImageMode = false;

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

    initScanner();
});

function detectMobile() {
    return /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function canUseLiveCamera() {

    const host = location.hostname;

    const isLocal =
        host === "localhost" ||
        host === "127.0.0.1" ||
        host === "::1";

    return location.protocol === "https:" || isLocal;
}

async function initScanner() {

    hideKameraError();

    isMobileDevice = detectMobile();

    const secure = canUseLiveCamera();

    if (isMobileDevice && !secure) {

        useImageMode = true;

        document.querySelector(".scanner-container").style.display = "none";

        document.getElementById("mobile_qr_upload").style.display = "block";

        initImageScanner();

        return;
    }

    useImageMode = false;

    document.getElementById("mobile_qr_upload").style.display = "none";

    if (!navigator.mediaDevices?.getUserMedia) {

        showKameraError(
            "Browser tidak mendukung kamera."
        );

        return;
    }

    try {

        const tempStream =
            await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: false
            });

        tempStream.getTracks().forEach(t => t.stop());

        const devices =
            await navigator.mediaDevices.enumerateDevices();

        cameras =
            devices.filter(
                d => d.kind === "videoinput"
            );

        if (!cameras.length) {

            showKameraError(
                "Tidak ada kamera ditemukan."
            );

            return;
        }

        currentDeviceId = getDefaultCamera();

        setupSwitchKameraBtn();

        await startScanner();

    } catch (err) {

        handleCameraError(err);
    }
}

function isSecureContext() {
    const host = location.hostname;
    const isLocal = host === "localhost" || host === "127.0.0.1" || host === "::1";
    return location.protocol === "https:" || isLocal;
}

function showHttpsWarning() {
    const box  = document.getElementById("https_warning_box");
    const link = document.getElementById("https_redirect_link");
    if (!box) return;
    if (link) {
        const httpsUrl = location.href.replace(/^http:/, "https:");
        link.href = httpsUrl;
        link.textContent = httpsUrl;
    }
    box.style.display = "flex";
}

async function initScanner() {
    hideKameraError();

    if (!isSecureContext()) {
        showHttpsWarning();
        showKameraError(
            "<strong>Kamera diblokir browser.</strong><br>" +
            "Chrome tidak mengizinkan akses kamera pada HTTP selain localhost.<br>" +
            "Silakan akses via <strong>HTTPS</strong> atau hubungi admin."
        );
        return;
    }

    if (!navigator.mediaDevices?.getUserMedia) {
        showKameraError(
            "Browser ini tidak mendukung akses kamera.<br>" +
            "Gunakan Chrome, Firefox, Edge, atau Safari versi terbaru."
        );
        return;
    }

    if (typeof jsQR === "undefined") {
        showKameraError("Library jsQR gagal dimuat. Periksa koneksi internet dan reload halaman.");
        return;
    }

    isMobileDevice = detectMobile();

    try {
        const tempStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
        tempStream.getTracks().forEach(t => t.stop());

        const devices = await navigator.mediaDevices.enumerateDevices();
        cameras = devices.filter(d => d.kind === "videoinput");

        if (cameras.length === 0) {
            showKameraError("Tidak ada kamera yang ditemukan pada perangkat ini.");
            return;
        }

        currentDeviceId = getDefaultCamera();
        setupSwitchKameraBtn();
        await startScanner();

    } catch (err) {
        console.error("[initScanner]", err);
        handleCameraError(err);
    }
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

function setupSwitchKameraBtn() {
    const btn = document.getElementById("btn_switch_camera");
    if (!btn) return;

    if (!isMobileDevice || cameras.length <= 1) {
        btn.style.display = "none";
        return;
    }

    btn.style.display = "flex";
    btn.innerHTML = `<i class="fa-solid fa-camera-rotate"></i> Ganti Kamera`;
    btn.onclick = async () => {
        const idx = cameras.findIndex(c => c.deviceId === currentDeviceId);
        currentDeviceId = cameras[(idx + 1) % cameras.length].deviceId;
        await startScanner();
    };
}

async function startScanner() {
    await stopScanner();
    hideKameraError();

    const video  = document.getElementById("reader-video");
    const canvas = document.getElementById("decode-canvas");
    if (!video || !canvas) return;

    try {
        activeStream = await navigator.mediaDevices.getUserMedia(buildConstraints());
        video.srcObject = activeStream;

        await new Promise((resolve, reject) => {
            video.onloadedmetadata = () => video.play().then(resolve).catch(reject);
            video.onerror = reject;
            setTimeout(() => reject(new Error("Video timeout")), 8000);
        });

        startDecodeLoop(video, canvas);

    } catch (err) {
        console.error("[startScanner]", err);
        handleCameraError(err);
    }
}

function buildConstraints() {
    const videoSize = isMobileDevice
        ? { width: { ideal: 640 }, height: { ideal: 480 } }
        : { width: { ideal: 1280 }, height: { ideal: 720 } };

    if (currentDeviceId) {
        return { audio: false, video: { ...videoSize, deviceId: { exact: currentDeviceId } } };
    }
    return { audio: false, video: { ...videoSize, facingMode: isMobileDevice ? "environment" : "user" } };
}

async function stopScanner() {
    if (decodeRAF) {
        cancelAnimationFrame(decodeRAF);
        decodeRAF = null;
    }
    if (activeStream) {
        activeStream.getTracks().forEach(t => t.stop());
        activeStream = null;
    }
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
                canvas.width  = w;
                canvas.height = h;
                ctx.drawImage(video, 0, 0, w, h);

                const code = jsQR(ctx.getImageData(0, 0, w, h).data, w, h, {
                    inversionAttempts: "dontInvert"
                });

                if (code?.data && !isProcessingScan) {
                    stopScanner();
                    onScanSuccess(code.data);
                }
            }
        }

        decodeRAF = requestAnimationFrame(tick);
    }

    decodeRAF = requestAnimationFrame(tick);
}

function initImageScanner() {

    const input =
        document.getElementById("qr_image_input");

    if (!input) return;

    input.addEventListener("change", e => {

        const file = e.target.files[0];

        if (!file) return;

        const img = new Image();

        img.onload = function () {

            const canvas =
                document.createElement("canvas");

            const ctx =
                canvas.getContext("2d");

            canvas.width = img.width;

            canvas.height = img.height;

            ctx.drawImage(
                img,
                0,
                0
            );

            const imageData =
                ctx.getImageData(
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );

            const code = jsQR(
                imageData.data,
                canvas.width,
                canvas.height
            );

            if (code?.data) {

                onScanSuccess(code.data);

            } else {

                showToast(
                    "QR Code tidak terbaca.",
                    "warning"
                );
            }
        };

        img.src =
            URL.createObjectURL(file);
    });
}

function handleCameraError(err) {
    const name = err.name || "";

    if (name === "NotAllowedError" || name === "PermissionDeniedError") {
        showKameraError(
            "<strong>Izin kamera ditolak.</strong><br>" +
            "Klik ikon kamera di address bar dan pilih <em>Izinkan</em>, lalu reload."
        );
    } else if (name === "NotFoundError" || name === "DevicesNotFoundError") {
        showKameraError(
            "<strong>Kamera tidak ditemukan.</strong><br>" +
            "Pastikan kamera terhubung dan driver terinstall."
        );
    } else if (name === "NotReadableError" || name === "TrackStartError") {
        showKameraError(
            "<strong>Kamera sedang dipakai aplikasi lain.</strong><br>" +
            "Tutup Zoom/Teams/dll, lalu reload halaman."
        );
    } else if (name === "OverconstrainedError") {
        console.warn("[handleCameraError] OverconstrainedError, retry tanpa deviceId");
        currentDeviceId = null;
        startScanner();
    } else if (!isSecureContext()) {
        showHttpsWarning();
        showKameraError("<strong>Kamera diblokir (HTTP bukan HTTPS).</strong>");
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
    const box  = document.getElementById("camera_error_box");
    const warn = document.getElementById("https_warning_box");
    if (box)  box.style.display  = "none";
    if (warn) warn.style.display = "none";
}

function showToast(pesan, tipe = "success") {
    document.getElementById("toast_scan_global")?.remove();

    const icons  = { success: "fa-circle-check", warning: "fa-triangle-exclamation", danger: "fa-circle-xmark", info: "fa-circle-info" };
    const colors = { success: "#16a34a", warning: "#d97706", danger: "#dc2626", info: "#2563eb" };
    const bgs    = { success: "#f0fdf4", warning: "#fffbeb", danger: "#fef2f2", info: "#eff6ff" };

    const toast = document.createElement("div");
    toast.id = "toast_scan_global";
    Object.assign(toast.style, {
        position: "fixed",
        top: "24px",
        left: "50%",
        transform: "translateX(-50%) translateY(-20px)",
        zIndex: "99999",
        background: bgs[tipe] || "#fff",
        border: `2px solid ${colors[tipe] || "#2563eb"}`,
        borderRadius: "14px",
        padding: "14px 22px",
        display: "flex",
        alignItems: "center",
        gap: "12px",
        boxShadow: "0 8px 32px rgba(0,0,0,0.15)",
        fontSize: "0.95rem",
        fontWeight: "600",
        color: colors[tipe] || "#2563eb",
        minWidth: "280px",
        maxWidth: "90vw",
        opacity: "0",
        transition: "all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1)",
        pointerEvents: "none",
    });

    toast.innerHTML = `
        <i class="fa-solid ${icons[tipe] || "fa-circle-info"}" style="font-size:1.3rem;flex-shrink:0;"></i>
        <span>${pesan}</span>
    `;
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
        setTimeout(() => { isProcessingScan = false; startScanner(); }, 2000);
        return;
    }

    const apiUrl = `/api/perangkat/kode/${encodeURIComponent(kodeInventaris)}`
        + (ID_RUANGAN_AKTIF ? `?id_ruangan=${ID_RUANGAN_AKTIF}` : "");

    fetch(apiUrl)
        .then(res => {
            if (res.status === 403) throw new Error("wrong_room");
            if (res.status === 404) throw new Error("not_found");
            if (!res.ok)            throw new Error("HTTP " + res.status);
            return res.json();
        })
        .then(data => {
            if (!data?.kode_inventaris) {
                showAlert(`⚠️ Kode tidak ditemukan: ${kodeInventaris}`, "warning");
                isProcessingScan = false;
                startScanner();
                return;
            }

            setVal("kode_scan",               data.kode_inventaris    || "-");
            setVal("merek_scan",              data.merek               || "-");
            setVal("kategori_perangkat_scan", data.kategori_perangkat  || "-");

            if (perangkatDipilih.some(p => p.id == data.id)) {
                showToast(`⚠️ <strong>${esc(data.kode_inventaris)}</strong> sudah ada dalam daftar scan!`, "warning");
                isProcessingScan = false;
                startScanner();
                return;
            }

            perangkatDipilih.push({
                id:                 data.id,
                kode_inventaris:    data.kode_inventaris,
                kategori_perangkat: data.kategori_perangkat,
                merek:              data.merek,
                alamat_ip:          data.alamat_ip || null,
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
            setTimeout(() => { isProcessingScan = false; startScanner(); }, 2000);
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

    document.getElementById("card_scan").style.display    = "none";
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
        inp.name  = "perangkat_ids[]";
        inp.value = p.id;
        container.appendChild(inp);
    });
}

function kembaliScan() {
    document.getElementById("card_scan").style.display      = "block";
    document.getElementById("form_pengaduan").style.display = "none";

    updateScanTable();
    renderAlreadyScannedBanner();

    if (cameras.length > 0 && currentDeviceId) {
        startScanner();
    } else {
        initScanner();
    }
}

function renderAlreadyScannedBanner() {
    document.getElementById("banner_sudah_scan")?.remove();

    if (perangkatDipilih.length === 0) return;

    const section = document.getElementById("section_scan_list");
    if (!section) return;

    const badges = perangkatDipilih
        .map(p => `<span class="badge me-1 mb-1" style="font-size:0.78rem;background:#4f46e5;">${esc(p.kode_inventaris)}</span>`)
        .join("");

    const banner = document.createElement("div");
    banner.id = "banner_sudah_scan";
    Object.assign(banner.style, {
        display: "flex",
        alignItems: "flex-start",
        gap: "10px",
        background: "#eff6ff",
        border: "1.5px solid #bfdbfe",
        borderRadius: "10px",
        padding: "12px 14px",
        marginTop: "14px",
        fontSize: "0.85rem",
        color: "#1e40af",
    });
    banner.innerHTML = `
        <i class="fa-solid fa-circle-info" style="color:#3b82f6;margin-top:2px;flex-shrink:0;"></i>
        <div>
            <strong>Perangkat berikut sudah di-scan dan tidak bisa di-scan ulang:</strong>
            <div class="mt-1">${badges}</div>
        </div>`;

    section.insertAdjacentElement("afterend", banner);
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