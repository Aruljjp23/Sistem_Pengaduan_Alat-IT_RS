@extends('layout.page')

@section('content')
<div class="container text-center py-5" style="max-width:480px;">

    <i class="fa-brands fa-whatsapp fa-3x text-success mb-3"></i>
    <h5>Pengaduan Berhasil Disimpan!</h5>
    <p class="text-muted mb-4">Tekan tombol di bawah untuk mengirim notifikasi ke Admin dan Teknisi sekaligus.</p>

    <button id="btnKirim"
        class="btn btn-success w-100 mb-3"
        onclick="kirimSekaligus()">
        <i class="fa-brands fa-whatsapp"></i> Kirim ke Admin & Teknisi
    </button>

    <a id="btnLanjut"
       href="{{ route('tindakan.tindakan_pengaduan') }}"
       class="btn btn-primary w-100 d-none">
        <i class="fa-solid fa-arrow-right"></i> Lihat Status Tindakan
    </a>

    <div id="infoStep" class="text-muted small mt-3">
        Klik tombol untuk mengirim notifikasi
    </div>

    <p class="text-muted small mt-2" id="infoBlokir" style="display:none;">
        Jika tab kedua tidak terbuka,
        <a id="linkTeknisi" href="#" target="_blank">klik di sini untuk kirim ke Teknisi</a>.
    </p>

</div>

<script>
    const admin   = "{{ $admin }}";
    const teknisi = "{{ $teknisi }}";
    const pesan   = "{!! $pesan !!}";  {{-- sudah urlencode dari controller --}}

    const urlAdmin   = "https://wa.me/" + admin   + "?text=" + pesan;
    const urlTeknisi = "https://wa.me/" + teknisi + "?text=" + pesan;

    document.getElementById('linkTeknisi').href = urlTeknisi;

    function kirimSekaligus() {
        window.open(urlAdmin, "_blank");

        setTimeout(() => {
            window.open(urlTeknisi, "_blank");
        }, 500);

        const btnKirim = document.getElementById('btnKirim');
        btnKirim.disabled = true;
        btnKirim.classList.remove('btn-success');
        btnKirim.classList.add('btn-outline-secondary');
        btnKirim.innerHTML = '<i class="fa-solid fa-check"></i> Terkirim ke Admin & Teknisi';

        document.getElementById('infoStep').textContent = '✓ Notifikasi berhasil dikirim';

        setTimeout(() => {
            document.getElementById('btnLanjut').classList.remove('d-none');
            document.getElementById('infoBlokir').style.display = 'block';
        }, 1000);
    }
</script>

@endsection