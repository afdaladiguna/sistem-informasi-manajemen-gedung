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
        // Set locale to Indonesian for date formatting
        Carbon::setLocale('id');

        // 1. Define the date range. We fetch a bit more for any potential JS datepickers in the modal.
        // The view itself will only display 7 days.
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(30);

        // 2. Fetch approved/pending bookings for all rooms within this date range.
        // Eager load the 'user' relationship to prevent N+1 query issues.
        $bookings = Rent::whereIn('status', ['pending', 'disewa','disetujui'])
                        ->whereBetween('time_start_use', [$startDate, $endDate])
                        ->with('user')
                        ->get();

        // 3. Process bookings into a schedule array for the view's availability table.
        $schedule = [];
        foreach ($bookings as $booking) {
            $date = Carbon::parse($booking->time_start_use)->format('Y-m-d');
            // Session is 'siang' if start time is before 5 PM (17:00), otherwise 'malam'.
            $session = (Carbon::parse($booking->time_start_use)->hour < 17) ? 'siang' : 'malam';

            $schedule[$booking->room_id][$date][$session] = [
                'user'    => $booking->user->name ?? 'Data tidak ditemukan',
                'purpose' => $booking->purpose,
                'status'  => $booking->status,
            ];
        }

        // 4. Send the required data to the view.
        return view('dashboard.rooms.show', [
            'title'    => "Detail Ruangan: " . $room->name,
            'room'     => $room,
            'rooms'    => Room::all(), // Needed for the 'Sewa Ruangan' modal dropdown
            'schedule' => $schedule,  // The new schedule array for the availability table
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
