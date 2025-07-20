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
    <h2 class="content-title text-center" style="margin-top: 20px;">Jadwal Ketersediaan Ruangan</h2>

    <div class="card-body text-end me-3">
        @if (auth()->user()->role_id <= 4)
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#sewaRuangan">Sewa Ruangan</button>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-info">
                    <tr>
                        <th>Tanggal</th>
                        <th>Sesi</th>
                        <th>Status</th>
                        <th>Peminjam</th>
                        <th>Tujuan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Show next 7 days from today
                        $today = \Carbon\Carbon::today();
                    @endphp
                    @for ($i = 0; $i < 7; $i++)
                        @php
                            $date = $today->copy()->addDays($i)->format('Y-m-d');
                        @endphp
                        @foreach (['Siang', 'Malam'] as $session)
                            <tr>
                                @if ($loop->first)
                                    <td rowspan="2" style="vertical-align: middle;">
                                        {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') }}
                                    </td>
                                @endif
                                <td>{{ $session }}</td>
                                @php
                                    // Use strtolower for checking the array key
                                    $sessionKey = strtolower($session);
                                @endphp
                                @if (isset($schedule[$room->id][$date][$sessionKey]))
                                    <td class="text-danger">Terisi</td>
                                    <td>{{ $schedule[$room->id][$date][$sessionKey]['user'] }}</td>
                                    <td>{{ $schedule[$room->id][$date][$sessionKey]['purpose'] }}</td>
                                @else
                                    <td class="text-success">Tersedia</td>
                                    <td>-</td>
                                    <td>-</td>
                                @endif
                            </tr>
                        @endforeach
                    @endfor
                </tbody>
            </table>
            <div class="alert alert-info mt-3">
                <strong>Catatan:</strong> Jadwal hanya menampilkan 7 hari ke depan. Untuk pertanyaan mengenai ketersediaan di luar jadwal yang tertera atau untuk permintaan khusus, silakan hubungi Kepala Gedung di nomor <strong>0812-3456-7890</strong>.
            </div>
        </div>
    </div>
</div>

{{-- Memanggil modal --}}
@include('dashboard.partials.rentModal')

@endsection