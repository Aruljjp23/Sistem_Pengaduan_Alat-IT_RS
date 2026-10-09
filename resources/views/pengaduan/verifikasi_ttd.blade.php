<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Verifikasi Tanda Tangan</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #eef4fb;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #1e293b;
        }

        .card {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(15, 23, 42, 0.15);
        }

        .header {
            background: linear-gradient(
                135deg,
                #2563eb,
                #1d4ed8
            );
            color: white;
            text-align: center;
            padding: 32px 20px 28px;
        }

        .icon-check {
            width: 72px;
            height: 72px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: white;
            color: #16a34a;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 42px;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .content {
            padding: 30px;
        }

        .document-title {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 28px;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 18px;
        }

        .info-box {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 14px;
            background: #f8fafc;
        }

        .label {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 5px;
        }

        .value {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.6;
        }

        .verified {
            margin-top: 25px;
            padding: 14px;
            text-align: center;
            background: #dcfce7;
            color: #15803d;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
        }

        .footer {
            padding: 0 30px 25px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>

</head>

<body>

    <div class="card">

        <div class="header">

            <div class="icon-check">
                ✓
            </div>

            <h1>
                TANDA TANGAN DIGITAL TERVERIFIKASI
            </h1>

        </div>

        <div class="content">

            <div class="document-title">

                {{ $dokumen }}

            </div>

            <div class="section-title">

                Informasi Penandatanganan

            </div>

            <div class="info-box">

                <div class="label">
                    Instansi
                </div>

                <div class="value">
                    {{ $instansi }}
                </div>

            </div>

            <div class="info-box">

                <div class="label">
                    Penandatangan
                </div>

                <div class="value">

                    {{ $jabatan }}

                    <br>

                    {{ $nama }}

                </div>

            </div>

            <div class="info-box">

                <div class="label">
                    Tanggal Penandatanganan
                </div>

                <div class="value">

                    {{ $tanggalTtd->translatedFormat('d F Y H:i') }} WIB

                </div>

            </div>

            <div class="info-box">

                <div class="label">
                    Tanggal Pindai
                </div>

                <div class="value">

                    {{ $tanggalPindai->translatedFormat('d F Y H:i:s') }} WIB

                </div>

            </div>

            <div class="verified">

                ✓ DOKUMEN VALID DAN TERVERIFIKASI

            </div>

        </div>

        <div class="footer">

            SIPITRS - Sistem Pengaduan RSU Darmayu Madiun

        </div>

    </div>

</body>

</html>