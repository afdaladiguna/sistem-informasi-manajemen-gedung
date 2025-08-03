@extends('dashboard.layouts.main')

@section('container')
<div class="col-md-10 p-0">
  <h2 class="content-title text-center">Daftar {{$title}}</h2>
  <div class="card-body text-end">
    @if(session()->has('rentSuccess'))
    <div class="col-md-16 mx-auto alert alert-success text-center  alert-success alert-dismissible fade show" style="margin-top: 50px" role="alert">
      {{session('rentSuccess')}}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session()->has('deleteRent'))
    <div class="col-md-16 mx-auto alert alert-success text-center  alert-dismissible fade show" style="margin-top: 50px" role="alert">
      {{session('deleteRent')}}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (auth()->user()->role_id <= 4)
      @endif
      <a href="/dashboard/rents/export-pdf" class="btn btn-primary mb-3">Export to PDF</a>
      <div class="table-responsive">
        <table class="table table-hover table-stripped table-bordered text-center dt-head-center" id="datatable">
          <thead class="table-info">
            <tr>
              <th scope="row">No.</th>
              <th scope="row">Kode Ruangan</th>
              @if (auth()->user()->role_id <= 2)
                <th scope="row">Nama Peminjam</th>
                @endif
                <th scope="row">Mulai Sewa</th>
                <th scope="row">Selesai Sewa</th>
                <th scope="row">Tujuan</th>
                <th scope="row">Waktu Transaksi</th>
                <th scope="row">Selesai Reservasi</th>
                <th scope="row">Status Sewa</th>
                <th scope="row">Action</th>
            </tr>
          </thead>
          <tbody>
            @if (auth()->user()->role_id <= 2)
              @foreach ($adminRents as $rent)
              <tr>
              <th scope="row">{{ $loop->iteration }}</th scope="row">
              <td><a href="/dashboard/rooms/{{ $rent->room->code }}" class="text-decoration-none" role="button">{{ $rent->room->code }}</a></td>
              <td>{{ $rent->user->name }}</td>
              <td>{{ $rent->time_start_use }}</td>
              <td>{{ $rent->time_end_use }}</td>
              <td>{{ $rent->purpose }}</td>
              <td>{{ $rent->transaction_start }}</td>
              @if ($rent->status == "disewa")
                                <td>Otomatis Selesai Pukul {{ date('H:i', strtotime($rent->time_end_use)) }}</td>
              @else
              @if(!is_null($rent->transaction_end))
              <td>{{ $rent->transaction_end }}</td>
              @else
              <td>-</td>
              @endif
              @endif
              <td>{{ $rent->status }}</td>
              <td>
                <form action="/dashboard/rents/{{ $rent->id }}" method="post" class="d-inline">
                  @method('delete')
                  @csrf
                  <a href="/dashboard/rents/{{ $rent->id }}/invoice" class="bi bi-receipt text-success border-0"></a>
                  <button type="submit" class="bi bi-trash-fill text-danger border-0" onclick="return confirm('Hapus data peminjaman?')"></button>
                </form>
              </td>
              </tr>
              @endforeach
              @else
              @foreach ($userRents as $rent)
              <tr>
                <th scope="row">{{ $loop->iteration }}</th scope="row">
                <td><a href="/dashboard/rooms/{{ $rent->room->code }}" class="text-decoration-none" role="button">{{ $rent->room->code }}</a></td>
                @if (auth()->user()->role_id <= 2)
                  <td>{{ $rent->user->name }}</td>
                  @endif
                  <td>{{ $rent->time_start_use }}</td>
                  <td>{{ $rent->time_end_use }}</td>
                  <td>{{ $rent->purpose }}</td>
                  <td>{{ $rent->transaction_start }}</td>
                  @if ($rent->status == "disewa")
                                    <td>Otomatis Selesai Pukul {{ date('H:i', strtotime($rent->time_end_use)) }}</td>
                  @else
                  @if(!is_null($rent->transaction_end))
                  <td>{{ $rent->transaction_end }}</td>
                  @else
                  <td>-</td>
                  @endif
                  @endif
                  <td>{{ $rent->status }}</td>
                  <td>
                    @if($rent->status == 'disetujui' || $rent->status == 'disewa' || $rent->status == 'selesai')
                    <a href="/dashboard/rents/{{ $rent->id }}/invoice" class="bi bi-receipt text-success border-0"></a>
                    @else
                    -
                    @endif
                  </td>
              </tr>
              @endforeach
              @endif
          </tbody>
        </table>
      </div>
  </div>
</div>

@extends('dashboard.partials.rentModal')
@endsection