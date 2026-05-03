<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Pengaduan_{{ now()->format('dmy') }}</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            font-size: 11px; 
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }

        .header h2 {
            text-transform: uppercase;
            margin: 0;
            font-size: 18px;
            color: #000;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
            font-style: italic;
        }

        .meta-info {
            width: 100%;
            margin-bottom: 20px;
        }

        .meta-info td {
            border: none;
            text-align: left;
            padding: 2px 0;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; 
        }

        th {
            background-color: #f2f2f2 !important;
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            border: 1px solid #999;
            padding: 10px 5px;
        }

        td {
            border: 1px solid #ccc;
            padding: 8px 6px;
            word-wrap: break-word;
            vertical-align: middle;
        }

        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }

        tbody tr:nth-child(even) {
            background-color: #fafafa !important;
        }

        .status-pill {
            background-color: #e6f4ea !important;
            color: #1e7e34 !important;
            border: 1px solid #1e7e34;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
        }
        /* .footer-sign {
            margin-top: 40px;
            width: 100%;
        }
        
        .sign-box {
            float: right;
            width: 200px;
            text-align: center;
        } */

        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>Laporan Data Pengaduan</h2>
        <p>Sistem Pengaduan Masalah Perangkat IT</p>
    </div>

    <table class="meta-info">
        <tr>
            <td width="15%">Dicetak Oleh</td>
            <td width="2%">:</td>
            <td>{{Auth::user()->name}} ({{Auth::user()->role}})</td>
            <td width="15%" style="text-align: right;">Waktu Cetak</td>
            <td width="2%" style="text-align: center;">:</td>
            <td width="20%">{{ now('Asia/Jakarta')->translatedFormat('d/m/Y H:i') }} WIB</td>
        </tr>
        @if(request('search') || request('tanggal'))
        <tr>
            <td>Filter Aktif</td>
            <td>:</td>
            <td colspan="4">
                @if(request('search')) <strong>Pencarian:</strong> "{{ request('search') }}" @endif
                @if(request('tanggal')) | <strong>Periode:</strong> {{ \Carbon\Carbon::parse(request('tanggal'))->translatedFormat('d F Y') }} @endif
            </td>
        </tr>
        @endif
    </table>

    <table>
        <thead class="thead">
            <tr>
                <th width="30">No</th>
                <th width="100">Tanggal</th>
                <th>Nama Pengadu</th>
                <th>Unit/Ruangan</th>
                <th>Lokasi Spesifik</th>
                <th width="80">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengaduan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m/Y') }}</td>
                <td class="text-bold">{{ $item->nama_pengadu }}</td>
                <td>{{ $item->nama_ruangan }}</td>
                <td>{{ $item->lokasi }}</td>
                <td class="text-center">
                    <span class="status-pill">Selesai</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 30px; color: #999;">
                    --- Tidak ada data untuk periode ini ---
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- <div class="footer-sign">
        <div class="sign-box">
            <p>Madiun, {{ now()->translatedFormat('d F Y') }}</p>
            <p style="margin-bottom: 60px;">Petugas Operasional,</p>
            <p class="text-bold">( ____________________ )</p>
        </div>
    </div> --}}

</body>
</html>