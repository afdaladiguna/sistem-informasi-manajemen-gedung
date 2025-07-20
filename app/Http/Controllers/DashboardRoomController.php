<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Rent;
use App\Models\RoomImage;
use Illuminate\Support\Facades\Storage; // Jika akan menghapus gambar di method update/destroy nanti
use Illuminate\Http\Request;
use Carbon\Carbon;
// use App\Models\Rent; /

class DashboardRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.rooms.index', [
            'title' => "Ruangan",
            'rooms' => Room::latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validasi data utama dan array gambar
        $validatedData = $request->validate([
            'code' => 'required|max:10|unique:rooms',
            'name' => 'required',
            'floor' => 'required|numeric',
            'capacity' => 'required|numeric',
            'price' => 'required|numeric|min:0',
            'type' => 'required',
            'description' => 'required|max:1000', // Sesuaikan jika perlu
            'images' => 'nullable|array', // Validasi bahwa 'images' adalah array jika ada
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048' // Validasi setiap file di dalam array
        ]);

        // 2. Buat data ruangan terlebih dahulu (tanpa gambar)
        $room = Room::create([
            'code' => $validatedData['code'],
            'name' => $validatedData['name'],
            'floor' => $validatedData['floor'],
            'status' => false,
            'capacity' => $validatedData['capacity'],
            'price' => $validatedData['price'],
            'type' => $validatedData['type'],
            'description' => $validatedData['description'],
        ]);

        // 3. Proses upload dan simpan path gambar jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('room-images', 'public');
                RoomImage::create([
                    'room_id' => $room->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect('/dashboard/rooms')->with('roomSuccess', 'Data ruangan baru berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        // ===================================================================
        // SALIN LOGIKA PERSIAPAN BOOKING DARI CONTROLLER SEBELUMNYA KE SINI
        // ===================================================================

        // Atur locale Carbon ke Bahasa Indonesia
        Carbon::setLocale('id');

        // 1. Ambil semua booking yang relevan
        $allBookings = Rent::whereIn('status', ['pending', 'disetujui'])->get();

        // 2. Kelompokkan booking berdasarkan room_id
        $formattedBookings = [];
        foreach ($allBookings as $booking) {
            $date = Carbon::parse($booking->time_start_use)->format('Y-m-d');
            $startTime = Carbon::parse($booking->time_start_use)->hour;
            $session = ($startTime < 17) ? 'Siang' : 'Malam';

            $formattedBookings[$booking->room_id][] = [
                'date' => $date,
                'session' => $session,
                'display' => Carbon::parse($date)->isoFormat('D MMMM YYYY') . ' (' . $session . ')'
            ];
        }
        // ===================================================================
        // AKHIR DARI LOGIKA YANG DISALIN
        // ===================================================================

        // Sekarang kirim semua data yang diperlukan ke view
        return view('dashboard.rooms.show', [
            'title' => $room->name,
            'room' => $room,
            'rooms' => Room::all(), // rooms diperlukan untuk modal
            'rents' => Rent::where('room_id', $room->id)->get(),
            'bookings' => json_encode((object) $formattedBookings) // <-- TAMBAHKAN INI
            
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        return json_encode($room);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        $rules = [
            'name' => 'required',
            'img' => 'image',
            'floor' => 'required',
            'capacity' => 'required',
            // 'building_id' => 'required', // <--- DIHAPUS
            'type' => 'required',
            'description' => 'required|max:255',
        ];

        if ($request->code != $room->code) {
            $rules['code'] = 'required|max:10|unique:rooms';
        }

        $validatedData = $request->validate($rules);

        if ($request->file('img')) {
            $validatedData['img'] = $request->file('img')->store('room-image', 'public');
        }

        // Tidak perlu set status di sini agar status existing tidak berubah saat update
        // $validatedData['status'] = false;

        Room::where('id', $room->id)
            ->update($validatedData);

        return redirect('/dashboard/rooms')->with('roomSuccess', 'Data ruangan berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        Room::destroy($room->id);
        return redirect('/dashboard/rooms')->with('deleteRoom', 'Data ruangan berhasil dihapus');
    }
}
