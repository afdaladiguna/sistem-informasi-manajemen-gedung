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
    <h2>Laporan Peminjaman Ruangan</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode Ruangan</th>
                <th>Nama Peminjam</th>
                <th>Mulai Sewa</th>
                <th>Selesai Sewa</th>
                <th>Tujuan</th>
                <th>Status Sewa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rents as $rent)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rent->room->code }}</td>
                <td>{{ $rent->user->name }}</td>
                <td>{{ $rent->time_start_use }}</td>
                <td>{{ $rent->time_end_use }}</td>
                <td>{{ $rent->purpose }}</td>
                <td>{{ $rent->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
