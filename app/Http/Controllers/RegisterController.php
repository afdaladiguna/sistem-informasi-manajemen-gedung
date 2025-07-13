<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Method untuk menampilkan halaman registrasi
    public function index()
    {
        return view('register.index', [
            'title' => 'Register'
        ]);
    }

    // Method untuk menyimpan data pengguna baru
    public function store(Request $request)
    {
        // Validasi data yang masuk
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'phone_number' => 'required|max:15',
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|min:6|confirmed' // 'confirmed' akan mencocokkan dengan password_confirmation
        ]);

        // Enkripsi password sebelum disimpan
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Atur role_id untuk pengguna baru secara default sebagai 'masyarakat' (ID 3)
        $validatedData['role_id'] = 3;

        // Simpan pengguna baru ke database
        User::create($validatedData);

        // Redirect ke halaman login dengan pesan sukses
        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
