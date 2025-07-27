@extends('dashboard.layouts.main')

@section('container')
<div class="col-md-10 p-0">
  <h2 class="content-title text-center">Daftar {{ $title }}</h2>
  <div class="card-body text-end">
    @if(session()->has('roomSuccess'))
    <div class="col-md-16 mx-auto alert alert-success text-center alert-dismissible fade show" role="alert">
      {{ session('roomSuccess') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session()->has('deleteRoom'))
    <div class="col-md-16 mx-auto alert alert-success text-center alert-dismissible fade show" role="alert">
      {{ session('deleteRoom') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (auth()->user()->role_id <= 4)
      <p class="text-start mb-3">Pilih ruangan terlebih dahulu untuk melakukan reservasi.</p>
      @endif

      @if (auth()->user()->role_id <= 2)
        <button type="button" class="mb-3 btn button btn-primary" data-bs-toggle="modal" data-bs-target="#addRoom">
        Tambah Ruangan
        </button>
        @endif

        <div class="table-responsive">
          <table class="table table-hover table-stripped table-bordered text-center dt-head-center">
            <thead class="table-info">
              <tr>
                <th class="text-center" scope="row">No.</th>
                <th class="text-center" scope="row">Nama Ruangan</th>
                <th class="text-center" scope="row">Kode Ruangan</th>
                {{-- KOLOM BARU DITAMBAHKAN DI SINI --}}
                <th class="text-center" scope="row">Harga Sewa / Hari</th>
                @if(auth()->user()->role_id <= 2)
                  <th class="text-center" scope="row">Action</th>
                  @endif
              </tr>
            </thead>
            <tbody>
              @forelse ($rooms as $room)
                <tr>
                  <th>{{ $loop->iteration }}</th>
                  <td><a href="/dashboard/rooms/{{ $room->code }}" class="text-decoration-none" role="button">{{ $room->name }}</a></td>
                  <td>{{ $room->code }}</td>
                  <td class="text-nowrap">{{ 'Rp ' . number_format($room->price, 0, ',', '.') }}</td>
                  @if (auth()->user()->role_id <= 2)
                    <td style="font-size: 22px;">
                      <a href="#" class="bi bi-pencil-square text-warning border-0 edit-room-button" data-url="/dashboard/rooms/{{ $room->code }}/edit" data-bs-toggle="modal" data-bs-target="#editRoom"></a>
                      &nbsp;
                      <form action="/dashboard/rooms/{{ $room->code }}" method="post" class="d-inline">
                        @method('delete')
                        @csrf
                        <button type="submit" class="bi bi-trash-fill text-danger border-0" style="background: transparent;" onclick="return confirm('Hapus data ruangan?')"></button>
                      </form>
                    </td>
                  @endif
                </tr>
              @empty
                <tr>
                  <td colspan="5">Tidak ada data ruangan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-end">
          {{ $rooms->links() }}
        </div>
  </div>
</div>

{{-- Memastikan modal dipanggil dengan @include --}}
@include('dashboard.partials.rentModal')
@include('dashboard.partials.addRoomModal')
@include('dashboard.partials.editRoomModal')

@endsection

@section('scripts')
{{-- Tempat untuk JavaScript modal jika dipisah --}}
@endsection