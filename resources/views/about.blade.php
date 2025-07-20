@extends('layouts.main')

@section('container')

<div class="hero hero-about">
  <div class="hero__inner container">
    {{-- ========== KONTEN DIUBAH ========== --}}
    <div class="hero-description text-center">
      <h2>TENTANG APLIKASI RESERVASI</h2>
      <p>
        Aplikasi <span><b>Sistem Informasi Reservasi Gedung Asrama Haji Sudiang</b></span> merupakan aplikasi real-time berbasis website.
        Aplikasi ini menampilkan ketersediaan gedung serta informasi mendetail untuk penyewaan fasilitas seperti penginapan, seminar, dan pernikahan.
        Aplikasi ini ditujukan bagi pihak pengelola dan masyarakat umum yang ingin melakukan reservasi.
      </p>
    </div>
  </div>
</div>
<div class="description-about container">
  <div class="upper-content d-flex flex-wrap my-4 justify-content-center">
    {{-- ========== KONTEN DIUBAH ========== --}}
    <div class="section section-1 mx-3" style="width: 30vw;">
      <h1 class="title-section text-center">LATAR BELAKANG</h1>
      <div class="section-description">
        Pengelolaan reservasi gedung di Asrama Haji Sudiang masih dilakukan secara manual, sehingga menimbulkan kendala seperti kurangnya transparansi ketersediaan jadwal secara real-time.
        Proses manual juga berisiko tinggi menyebabkan kesalahan pencatatan, seperti jadwal yang tumpang tindih. Untuk mengatasi masalah tersebut, dikembangkanlah sistem informasi reservasi berbasis web ini agar lebih efisien, transparan, dan akurat.
      </div>
    </div>
    {{-- ========== KONTEN DIUBAH ========== --}}
    <div class="section mx-3" style="width: 40vw;">
      <h1 class="title-section text-center">TUJUAN</h1>
      <div class="section-description">
        Tujuan utama aplikasi ini adalah untuk menghasilkan sebuah sistem informasi yang dapat memfasilitasi manajemen pengelolaan Gedung Asrama Haji Sudiang.
        Dengan sistem yang terkomputerisasi, proses pengecekan ketersediaan, reservasi, hingga pencatatan pembayaran menjadi lebih mudah, cepat, dan akurat.
        Sistem ini diharapkan dapat meningkatkan kualitas layanan bagi masyarakat serta efisiensi kerja bagi pihak pengelola gedung.
      </div>
    </div>
  </div>
  <div class="lower-content">
    <h1 class="text-center">LOKASI</h1>
    {{-- ========== PETA DIUBAH ========== --}}
    <div class="mapouter m-auto my-4 mb-5">
      <div class="gmap_canvas">
        <iframe width="600" height="500" id="gmap_canvas" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3974.1036319571067!2d119.53533697570225!3d-5.086939894889933!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbefbbc473fbc9d%3A0xd20c3e21785712f0!2sAsrama%20Haji%20Embarkasi%20Makassar!5e0!3m2!1sen!2sid!4v1752069668933!5m2!1sen!2sid" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
        <style>
          .mapouter {
            position: relative;
            text-align: right;
            height: 500px;
            width: 600px;
          }

          .gmap_canvas {
            overflow: hidden;
            background: none !important;
            height: 500px;
            width: 600px;
          }
        </style>
      </div>
    </div>
  </div>
  <div class="contact-us mt-4">
    <h1 class="text-center">HUBUNGI KAMI</h1>
    <div class="section-description text-center">
        <p>Jika Anda memiliki pertanyaan lebih lanjut atau memerlukan bantuan, jangan ragu untuk menghubungi kami:</p>
        <p><strong>Kepala Gedung:</strong> 0812-3456-7890</p>
        <p><strong>Email:</strong> support@gedungsejahtera.com</p>
    </div>
  </div>
</div>
@endsection