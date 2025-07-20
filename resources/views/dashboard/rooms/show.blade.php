@extends('dashboard.layouts.main')

@section('container')
<div class="col-md-10 p-0">
    <h2 class="content-title text-center mb-3">{{ $room->name }}</h2>
    <article class='explore-detail d-flex flex-wrap' style="margin-left: 20px;" tabindex='0'>
        <div class='img-container' style="width: 450px;">
            @if ($room->images->isNotEmpty())
            <div id="carouselDetail" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded">
                    @foreach ($room->images as $key => $image)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $image->path) }}" class="d-block w-100" style="height: 300px; object-fit: cover;" alt="Gambar {{ $key + 1 }}">
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselDetail" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselDetail" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            @else
            <img src="{{ asset('img/ruang-kelas.jpeg') }}" class="d-block w-100 rounded" style="height: 300px; object-fit: cover;" alt="Gambar Default">
            @endif
        </div>

        <ul class='detail-explore__info'>
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <th scope="col">Nama</th>
                        <td>: {{ $room->name }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Kode Ruangan</th>
                        <td>: {{ $room->code }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Lantai</th>
                        <td>: {{ $room->floor }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Kapasitas</th>
                        <td>: {{ $room->capacity }} orang</td>
                    </tr>

                    {{-- =============================================== --}}
                    {{-- HARGA SEWA DITAMPILKAN DI SINI          --}}
                    {{-- =============================================== --}}
                    <tr>
                        <th scope="col">Harga Sewa</th>
                        <td>: {{ 'Rp ' . number_format($room->price, 0, ',', '.') }} / hari</td>
                    </tr>

                    <tr>
                        <th scope="col">Tipe Ruangan</th>
                        <td>: {{ $room->type }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Deskripsi</th>
                        <td>: {{ $room->description }}</td>
                    </tr>
                </tbody>
            </table>
        </ul>
    </article>

    {{-- Judul diubah agar konsisten --}}
    <h2 class="content-title text-center" style="margin-top: 20px;">Jadwal Reservasi Ruangan</h2>

    <div class="card-body text-end me-3">
        <div class="d-flex justify-content-between align-items-center">
            {{-- Bagian filter tanggal tetap sama --}}
            <div class="input-group mb-3 filter-tgl-wrap">
                ...
            </div>
            @if (auth()->user()->role_id <= 4)
                {{-- Tombol disesuaikan untuk memanggil modal yang benar --}}
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#sewaRuangan">Sewa</button>
                @endif
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-stripped table-bordered text-center dt-head-center" id="datatable">
                <thead class="table-info">
                    <tr>
                        <th scope="row">No.</th>
                        <th scope="row">Nama Penyewa</th>
                        <th scope="row">Mulai Sewa</th>
                        <th scope="row">Selesai Sewa</th>
                        <th scope="row">Tujuan</th>
                        <th scope="row">Waktu Transaksi</th>
                        <th scope="row">Status</th>
                    </tr>
                </thead>
                <tbody class="rent-details">
                    @foreach ($rents as $rent)
                    <tr class="rent-detail">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $rent->user->name }}</td>
                        <td class="detail-rent-room_start-time">{{ \Carbon\Carbon::parse($rent->time_start_use)->format('d/m/y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($rent->time_end_use)->format('d/m/y H:i') }}</td>
                        <td>{{ $rent->purpose }}</td>
                        <td>{{ $rent->created_at->format('d/m/y H:i') }}</td>
                        <td><span class="badge bg-success">{{ $rent->status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Memanggil modal --}}
@include('dashboard.partials.rentModal')

@endsection