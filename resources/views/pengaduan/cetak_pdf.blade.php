<!DOCTYPE html>
<html lang="id">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengaduan</title>

    <style>
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            background: #f4f6f9;
            color: #1f2937;
        }

        .web-action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
        }

        .btn-print {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        .paper {
            width: 297mm; 
            min-height: 210mm;
            margin: 20px auto;
            background: white;
            padding: 20mm;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .brand h1 { margin: 0; font-size: 20px; color: #2563eb; letter-spacing: 1px; }
        .brand p { margin: 0; font-size: 12px; color: #6b7280; }

        .title { font-weight: 700; font-size: 14px; text-transform: uppercase; color: #111827; }

        .info {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 10px 12px;
            font-size: 12px;
            margin-bottom: 18px;
            border-radius: 6px;
        }

        table { width: 100%; border-collapse: collapse; font-size: 11px; }

        th { background: #2563eb; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }

        tr:nth-child(even) { background: #f9fafb; }

        .badge { background: #eef2ff; color: #4338ca; padding: 3px 6px; border-radius: 4px; font-size: 10px; }

        .tabel-ttd { width: 100%; margin-top: 60px; border-collapse: collapse; text-align: center; font-size: 12px; color: #334155; }
        .tabel-ttd td { width: 33.33%; padding: 10px; vertical-align: top; border: none; font-weight: 500; }
        .spasi-ttd { height: 70px; }

        .table-wrapper { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }

        @media (max-width: 768px) {
            .paper { width: 100%; margin: 0; padding: 12px; box-shadow: none; }
            table, thead, tbody, th, td, tr { display: block; width: 100%; }
            thead { display: none; }
            tr { background: #ffffff; margin-bottom: 12px; border-radius: 10px; padding: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
            td { border: none; padding: 6px 0; font-size: 12px; display: flex; justify-content: space-between; }
            td::before { font-weight: 600; color: #6b7280; flex-shrink: 0; width: 30%; }
            td:nth-child(1)::before { content: "No"; }
            td:nth-child(2)::before { content: "Tanggal"; }
            td:nth-child(3)::before { content: "Pengadu"; }
            td:nth-child(4)::before { content: "Ruangan"; }
            td:nth-child(5)::before { content: "Kategori"; }
            td:nth-child(6)::before { content: "Deskripsi"; }
            td:nth-child(7)::before { content: "Teknisi"; }
            td:nth-child(8)::before { content: "Tindakan"; }
            .info { font-size: 11px; line-height: 1.4; }
            .header { flex-direction: column; gap: 6px; }
            .title { font-size: 12px; }
            .btn-print { width: 100%; }
            .tabel-ttd { display: flex; flex-direction: column; gap: 18px; margin-top: 30px; }
            .tabel-ttd tr { display: block; }
            .tabel-ttd td { width: 100%; display: block; text-align: center; margin-bottom: 10px; }
            .spasi-ttd { height: 40px; }
        }

        @media print {
            @page { size: landscape; margin: 10mm; }
            body { background: white; }
            .web-action-bar { display: none; }
            .paper { margin: 0; box-shadow: none; width: 100%; height: auto; }
        }
    </style>
    </head>

    <body onload="window.print()">

        <div class="web-action-bar">
            <div><b>Laporan Pengaduan</b></div>
            <button class="btn-print" onclick="window.print()">Cetak</button>
        </div>

        <div class="paper">
            <div class="header">
                <div class="brand">
                    <h1>SIPITRS</h1>
                    <p>Sistem Pengaduan RSU Darmayu Madiun</p>
                </div>
                <div class="title">Laporan Pengaduan</div>
            </div>

            <div class="info">
                Dicetak oleh <b>{{ Auth::user()->name }}</b> ({{ strtoupper(Auth::user()->role) }})  
                | {{ now('Asia/Jakarta')->format('d M Y H:i') }} WIB
                | Total Data : <b>{{ count($pengaduan) }}</b>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width:30px;">No</th>
                            <th style="width:80px;">Tanggal</th>
                            <th style="width:100px;">Pengadu</th>
                            <th style="width:100px;">Ruangan</th>
                            <th style="width:100px;">Kategori</th>
                            <th style="width:150px;">Masalah</th>
                            <th style="width:100px;">Teknisi</th>
                            <th>Tindakan / Solusi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($pengaduan as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y H:i') }}</td>
                            <td><b>{{ $item->nama_pengadu }}</b></td>
                            <td>{{ $item->nama_ruangan }}</td>
                            <td><span class="badge">{{ $item->kategori_perangkat }}</span></td>
                            <td>{{ $item->deskripsi_masalah }}</td>
                            <td>{{ $item->teknisi }}</td>
                            <td style="white-space: pre-wrap;">{{ $item->deskripsi_tindakan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:20px;">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <table class="tabel-ttd">
                    <tr>
                        <td>
                            DIREKTUR<br>
                            RSU DARMAYU MADIUN
                            <div class="spasi-ttd"></div>
                            (.................................................)
                        </td>
                        <td>
                            KEPALA BAGIAN<br>
                            ADMINISTRASI DAN UMUM
                            <div class="spasi-ttd"></div>
                            (.................................................)
                        </td>
                        <td>
                            KEPALA UNIT<br>
                            IT/PROGRAMMER
                            <div class="spasi-ttd"></div>
                            (.................................................)
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </body>
</html>