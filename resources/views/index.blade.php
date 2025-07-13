@extends('layouts.main')

@section('container')
<!-- Start Hero -->
<div class="hero">
  <div class="hero__inner container">
    <div class="hero-description">
      <h2>Asrama Haji Embarkasi Sudiang</h2>
      <p>Sistem Informasi Penyewaan Ruangan Gedung</p>
    </div>
  </div>
</div>
<!-- End Hero -->
<!-- Start Daftar Ruangan -->
<div class="list-ruangan d-flex flex-wrap justify-content-center">
  @foreach ($rooms as $room)
  <div class="card m-3" style="width: 18rem;">

    {{-- ================= PERUBAHAN DI SINI ================= --}}
    @if ($room->img)
    {{-- Jika ruangan punya gambar, tampilkan gambar itu --}}
    <img src="{{ asset('storage/' . $room->img) }}" style="height: 250px; object-fit: cover;" class="card-img-top" alt="{{ $room->name }}">
    @else
    {{-- Jika tidak, tampilkan gambar default --}}
    <img src="{{ asset('img/ruang-kelas.jpeg') }}" style="height: 250px; object-fit: cover;" class="card-img-top" alt="Gambar Ruangan Default">
    @endif
    {{-- ======================================================= --}}

    <div class="card-body d-flex flex-column">
      <h5 class="card-title text-center">{{ $room->name }}</h5>

      {{-- Menampilkan deskripsi dan harga --}}
      <p class="card-text">
        {{ $room->description }}
      </p>
      <h6 class="text-success">Rp {{ number_format($room->price, 0, ',', '.') }} / hari</h6>

      <a href="/dashboard/rooms/{{ $room->code }}" class="btn btn-primary mt-auto">Lihat Detail & Sewa</a>
    </div>
  </div>
  @endforeach
</div>
@endsection