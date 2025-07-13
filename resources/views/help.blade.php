@extends('layouts.main')

@section('container')
{{-- ========== DIUBAH ========== --}}
<div class="hero hero-bantuan">
    <div class="hero__inner container">
        <div class="hero-description m-auto p-5">
            <h2>PETUNJUK PENGGUNAAN APLIKASI</h2>
        </div>
    </div>
</div>
<div class="how-use-simanuk my-4 container">
    <h2>TAHAPAN PENYEWAAN RUANGAN</h2>
    <div class="list-tahapan p-5">
        <ul>
            <li>Untuk melakukan penyewaan, Anda diharuskan melakukan <strong>registrasi akun</strong> terlebih dahulu, kemudian <strong>login</strong> ke dalam sistem.</li>
            <li>Jika ingin mengetahui ruangan yang tersedia, silakan menuju menu <strong>Daftar Ruangan</strong> untuk melihat pilihan dan detail setiap ruangan.</li>
            <li>Jika ruangan yang Anda inginkan tersedia pada jadwal yang dipilih, silakan klik tombol <strong>"Sewa"</strong> atau <strong>"Pesan"</strong> lalu isi formulir reservasi dengan lengkap.</li>
            <li>Pastikan data yang Anda masukkan sudah benar. Jika sudah, klik <strong>"Kirim"</strong> untuk mengajukan permohonan reservasi.</li>
            <li>Untuk mengecek status reservasi Anda (menunggu konfirmasi, disetujui, atau ditolak), silakan periksa menu <strong>"Daftar Reservasi"</strong> atau halaman profil Anda.</li>
        </ul>
    </div>
</div>
{{-- ============================ --}}
@endsection