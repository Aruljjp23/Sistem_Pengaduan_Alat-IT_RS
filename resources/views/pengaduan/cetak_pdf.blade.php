<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan</title>
    <style>
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 30px;
        }
        h4 {
            text-align: center;
            margin-bottom: 4px;
        }
        p {
            text-align: center;
            margin: 0;
        }
        hr {
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            text-align: center;
        }
        thead th {
            background-color: darkorange !important;
            color: white !important;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9 !important;
        }
        tbody tr:hover {
            background-color: #fff3e0 !important;
        }
        .badge-selesai {
            background-color: #198754 !important;
            color: white !important;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            display: inline-block;
        }
    </style>
</head>
<body onload="window.print()">

    <h4>Laporan Pengaduan</h4>
    <p>Dicetak pada: {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }}
</p></p>
    @if(request('search'))
        <p>Filter Pencarian: {{ request('search') }}</p>
    @endif
    @if(request('tanggal'))
        <p>Filter Tanggal: {{ \Carbon\Carbon::parse(request('tanggal'))->locale('id')->translatedFormat('d F Y') }}</p>
    @endif
    <hr>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pengadu</th>
                <th>Ruangan</th>
                <th>Lokasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengaduan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}</td>
                <td>{{ $item->nama_pengadu }}</td>
                <td>{{ $item->nama_ruangan }}</td>
                <td>{{ $item->lokasi }}</td>
                <td><span class="badge-selesai">Selesai</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:#999; padding:16px;">
                    Tidak ada data laporan pengaduan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>