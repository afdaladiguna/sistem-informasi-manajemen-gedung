<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use App\Models\Role;
use App\Models\Rent; // DITAMBAHKAN: use model Rent

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Menyesuaikan nama Role
        Role::create(['name' => 'admin']); // DIUBAH: ID 1
        Role::create(['name' => 'kepala_gedung']); // ID 2
        Role::create(['name' => 'masyarakat']); // ID 3

        // Data User (sudah benar)
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081234567890',
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'Kepala Gedung',
            'email' => 'kepala_gedung@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081234567891',
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'Luthfi (Masyarakat)',
            'email' => 'luthfi@gmail.com',
            'password' => bcrypt('password'),
            'phone_number' => '081234567892',
            'role_id' => 3,
        ]);

        // Membuat data Ruangan dengan tambahan 'price'
        Room::create([
            'code' => 'AULA-01',
            'name' => 'Aula Utama',
            'img' => 'room-image/roomdefault.jpg', // Dikosongkan agar bisa memakai gambar default
            'floor' => 1,
            'status' => false,
            'capacity' => 200,
            'price' => 2500000, // DITAMBAHKAN: Harga sewa
            'type' => 'Aula',
            'description' => 'Aula utama dengan kapasitas besar, cocok untuk seminar dan pernikahan.',
        ]);

        Room::create([
            'code' => 'MEET-01',
            'name' => 'Ruang Rapat VIP',
            'img' => 'room-image/roomdefault-2.jpg',
            'floor' => 2,
            'status' => false,
            'capacity' => 25,
            'price' => 750000, // DITAMBAHKAN: Harga sewa
            'type' => 'Ruang Rapat',
            'description' => 'Ruang rapat eksklusif dilengkapi dengan proyektor dan sound system.',
        ]);

        Room::create([
            'code' => 'HALL-02',
            'name' => 'Exhibition Hall',
            'img' => 'room-image/roomdefault-3.jpg',
            'floor' => 2,
            'status' => false,
            'capacity' => 75,
            'price' => 1200000, // DITAMBAHKAN: Harga sewa
            'type' => 'Hall',
            'description' => 'Ruang serbaguna untuk pameran atau acara gathering.',
        ]);

        // DITAMBAHKAN: Contoh data untuk tabel 'rents'
        // Rent::create([
        //     'room_id' => 1, // Aula Utama
        //     'user_id' => 3, // Luthfi
        //     'transaction_start' => now(),
        //     'transaction_end' => null,
        //     'time_start_use' => '2025-08-17 09:00:00',
        //     'time_end_use' => '2025-08-17 17:00:00',
        //     'purpose' => 'Acara Pernikahan',
        //     'payment_method' => 'Transfer',
        //     'payment_proof' => 'payment-proofs/contoh-bukti.jpg', // Contoh path bukti
        //     'status' => 'disetujui',
        // ]);

        // Rent::create([
        //     'room_id' => 2, // Ruang Rapat VIP
        //     'user_id' => 3,
        //     'transaction_start' => now(),
        //     'transaction_end' => null,
        //     'time_start_use' => '2025-07-20 10:00:00',
        //     'time_end_use' => '2025-07-20 12:00:00',
        //     'purpose' => 'Rapat Internal Perusahaan',
        //     'payment_method' => 'Cash',
        //     'payment_proof' => null,
        //     'status' => 'pending',
        // ]);

        // Rent::create([
        //     'room_id' => 1, // Aula Utama
        //     'user_id' => 3,
        //     'transaction_start' => '2025-06-01 11:00:00',
        //     'transaction_end' => '2025-06-02 12:00:00',
        //     'time_start_use' => '2025-06-01 13:00:00',
        //     'time_end_use' => '2025-06-02 11:00:00',
        //     'purpose' => 'Seminar Nasional',
        //     'payment_method' => 'Transfer',
        //     'payment_proof' => 'payment-proofs/contoh-bukti-2.jpg',
        //     'status' => 'selesai',
        // ]);
    }
}
