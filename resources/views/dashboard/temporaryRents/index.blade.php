@extends('dashboard.layouts.main')

@section('container')
<div class="col-md-10 p-0">
  <h2 class="content-title text-center">Daftar Permintaan Reservasi</h2>
  <div class="card-body text-end">
    <div class="table-responsive">
      <table class="table table-hover table-stripped table-bordered text-center dt-head-center" id="datatable">
        <thead class="table-info">
          <tr>
            <th scope="row">No.</th>
            <th scope="row">Nama Ruangan</th>
            <th scope="row">Nama Penyewa</th>
            <th scope="row">Mulai Sewa</th>
            <th scope="row">Selesai Sewa</th>
            <th scope="row">Tujuan</th>
            <th scope="row">Metode Pembayaran</th> {{-- <--- KOLOM BARU DITAMBAHKAN --}}
            <th scope="row">Status Sewa</th>
            <th scope="row">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($rents as $rent)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td><a href="/dashboard/rooms/{{ $rent->room->code }}" class="text-decoration-none">{{ $rent->room->code }}</a></td>
            <td>{{ $rent->user->name }}</td>
            <td>{{ \Carbon\Carbon::parse($rent->time_start_use)->format('d/m/Y H:i') }}</td>
            <td>{{ \Carbon\Carbon::parse($rent->time_end_use)->format('d/m/Y H:i') }}</td>
            <td>{{ $rent->purpose }}</td>

            {{-- ======================================================= --}}
            {{-- KODE UNTUK MENAMPILKAN BUKTI PEMBAYARAN        --}}
            {{-- ======================================================= --}}
            <td>
              {{-- Cek apakah ada isi di kolom payment_proof --}}
              @if ($rent->payment_proof)
              <span class="badge bg-secondary">{{ $rent->payment_method }}</span>
              <a href="{{ asset('storage/' . $rent->payment_proof) }}" target="_blank" class="badge bg-success">Lihat Bukti</a>
              @else
              <span class="badge bg-secondary">{{ $rent->payment_method }}</span>
              @endif
            </td>

            <td><span class="badge bg-warning">{{ $rent->status }}</span></td>
            <td>
              <a href="/dashboard/temporaryRents/{{ $rent->id }}/acceptRents" class="btn btn-success mb-2" style="padding: 2px 10px" title="Setujui"><i class="bi bi-check-lg"></i></a>
              <a href="/dashboard/temporaryRents/{{ $rent->id }}/declineRents" class="btn btn-danger mb-2" style="padding: 2px 10px" title="Tolak"><i class="bi bi-x-lg"></i></a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection