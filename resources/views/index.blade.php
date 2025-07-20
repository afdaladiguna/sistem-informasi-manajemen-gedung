@extends('layouts.main')

@section('container')
<div class="hero">
  <div class="hero__inner container">
    <div class="hero-description">
      <h2>Asrama Haji Embarkasi Sudiang</h2>
      <p>Sistem Informasi Penyewaan Ruangan Gedung</p>
    </div>
  </div>
</div>
<div class="list-ruangan d-flex flex-wrap justify-content-center">
  <div class="col-12 text-center mb-4">
    <div class="alert alert-info">
      <strong>Ada pertanyaan?</strong> Hubungi Kepala Gedung di nomor <strong>0812-3456-7890</strong> untuk informasi lebih lanjut.
    </div>
  </div>
  @foreach ($rooms as $room)
  {{-- Card untuk setiap ruangan --}}
  <div class="card m-3" style="width: 18rem;">

    {{-- 1. TAMPILKAN GAMBAR PERTAMA ATAU GAMBAR DEFAULT --}}
    @if ($room->images->isNotEmpty())
    {{-- Jika ruangan punya gambar, tampilkan gambar pertama --}}
    <img src="{{ asset('storage/' . $room->images->first()->path) }}" style="height: 200px; object-fit: cover;" class="card-img-top" alt="{{ $room->name }}">
    @else
    {{-- Jika tidak, tampilkan gambar default --}}
    <img src="{{ asset('img/ruang-kelas.jpeg') }}" style="height: 200px; object-fit: cover;" class="card-img-top" alt="Gambar Default">
    @endif

    <div class="card-body d-flex flex-column">
      <h5 class="card-title text-center">{{ $room->name }}</h5>
      <p class="card-text mb-2 text-muted" style="font-size: 0.9rem;">
        <i class="bi bi-people-fill"></i> Kapasitas: {{ $room->capacity }} orang
      </p>
      <h6 class="text-success mt-auto">Rp {{ number_format($room->price, 0, ',', '.') }} / hari</h6>

      {{-- 2. TOMBOL UNTUK MEMBUKA MODAL --}}
      <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#roomDetailModal-{{ $room->id }}">
        Lihat Detail
      </button>
    </div>
  </div>

  {{-- 3. STRUKTUR MODAL UNTUK SETIAP RUANGAN --}}
  <div class="modal fade" id="roomDetailModal-{{ $room->id }}" tabindex="-1" aria-labelledby="roomDetailModalLabel-{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="roomDetailModalLabel-{{ $room->id }}">{{ $room->name }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-7">
              {{-- Carousel untuk menampilkan semua gambar ruangan --}}
              @if ($room->images->isNotEmpty())
              <div id="carousel-{{ $room->id }}" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                  @foreach ($room->images as $key => $image)
                  <button type="button" data-bs-target="#carousel-{{ $room->id }}" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-current="true" aria-label="Slide {{ $key + 1 }}"></button>
                  @endforeach
                </div>
                <div class="carousel-inner rounded">
                  @foreach ($room->images as $key => $image)
                  <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $image->path) }}" class="d-block w-100" style="height: 300px; object-fit: cover;" alt="Gambar {{ $key + 1 }} dari {{ $room->name }}">
                  </div>
                  @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $room->id }}" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $room->id }}" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </button>
              </div>
              @else
              {{-- Gambar default jika tidak ada gambar sama sekali --}}
              <img src="{{ asset('img/ruang-kelas.jpeg') }}" class="d-block w-100 rounded" style="height: 300px; object-fit: cover;" alt="Gambar Default">
              @endif
            </div>
            <div class="col-md-5">
              {{-- Detail informasi ruangan --}}
              <p class="mt-3 mt-md-0">{{ $room->description }}</p>
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><strong>Tipe:</strong> <span>{{ $room->type }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><strong>Lantai:</strong> <span>{{ $room->floor }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><strong>Kapasitas:</strong> <span>{{ $room->capacity }} orang</span></li>
                <li class="list-group-item d-flex justify-content-between"><strong>Harga:</strong> <span class="text-success fw-bold">Rp {{ number_format($room->price, 0, ',', '.') }}/hari</span></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          {{-- Arahkan ke halaman login atau halaman sewa jika sudah login --}}
          <a href="{{ url('/dashboard/rooms/' . $room->code) }}?open_modal=true" class="btn btn-primary">Sewa Sekarang</a>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection