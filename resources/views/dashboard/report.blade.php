<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman Ruangan
        @if ($reportType === 'monthly')
            Bulan {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}
        @elseif ($reportType === 'yearly')
            Tahun {{ $year }}
        @else
            Keseluruhan
        @endif
    </h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode Ruangan</th>
                <th>Nama Peminjam</th>
                <th>Mulai Sewa</th>
                <th>Selesai Sewa</th>
                <th>Tujuan</th>
                <th>Metode Pembayaran</th>
                <th>Status Sewa</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rents as $rent)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rent->room->code }}</td>
                <td>{{ $rent->user->name }}</td>
                <td>{{ \Carbon\Carbon::parse($rent->time_start_use)->translatedFormat('d M Y H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($rent->time_end_use)->translatedFormat('d M Y H:i') }}</td>
                <td>{{ $rent->purpose }}</td>
                <td>{{ $rent->payment_method }}</td>
                <td>{{ $rent->status }}</td>
                <td>Rp{{ number_format($rent->room->price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="8" style="text-align:right;"><strong>Total Pendapatan:</strong></td>
                <td><strong>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
