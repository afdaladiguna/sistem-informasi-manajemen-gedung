{{-- resources/views/register/index.blade.php --}}

@extends('layouts.main')

@section('container')
<div class="hero hero-login">
    <div class="hero__inner container d-flex flex-wrap align-items-center justify-content-center">
        <div class="left-login">
            <h4>Sistem Informasi Pengelolaan Gedung</h4>
            <p>Daftarkan akun Anda untuk memulai reservasi.</p>
        </div>
        <div class="right-login">
            <div class="form">
                <h3 class="title-form">Registrasi Akun</h3>
                <form action="/register" method="post" class="form-input">
                    @csrf

                    <input type="text" placeholder="Nama Lengkap" name="name" required class="input-form @error('name') is-invalid @enderror" value="{{ old('name') }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    <input type="email" placeholder="Email" name="email" required class="input-form @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    <input type="text" placeholder="Nomor Telepon" name="phone_number" required class="input-form @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}">
                    @error('phone_number')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    <input type="password" placeholder="Password" name="password" required class="input-form @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror

                    <input type="password" placeholder="Konfirmasi Password" name="password_confirmation" required class="input-form">

                    <button type="submit" class="button-submit">Register</button>
                </form>
                <p class="text-center mt-3">
                    Sudah punya akun? <a href="/login">Login di sini!</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection