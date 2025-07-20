<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardRentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // app/Http/Controllers/DashboardRentController.php

    public function index()
    {
        // Atur locale Carbon ke Bahasa Indonesia untuk format tanggal
        Carbon::setLocale('id');

        // 1. Ambil semua booking yang relevan (pending atau sudah disetujui)
        $bookings = Rent::whereIn('status', ['pending', 'disetujui'])->get();

        // 2. Kelompokkan booking berdasarkan room_id untuk kemudahan di JavaScript
        $formattedBookings = [];
        foreach ($bookings as $booking) {
            $date = Carbon::parse($booking->time_start_use)->format('Y-m-d');
            $startTime = Carbon::parse($booking->time_start_use)->hour;

            // Tentukan sesi Siang atau Malam
            $session = ($startTime < 17) ? 'Siang' : 'Malam';

            // 'isoFormat' akan menggunakan locale 'id' yang sudah di-set
            $formattedBookings[$booking->room_id][] = [
                'date' => $date,
                'session' => $session,
                'display' => Carbon::parse($date)->isoFormat('D MMMM YYYY') . ' (' . $session . ')'
            ];
        }

        return view('dashboard.rents.index', [
            'title' => "Peminjaman",
            'adminRents' => Rent::latest()->get(),
            'userRents' => Rent::where('user_id', auth()->user()->id)->get(),
            'rooms' => Room::all(),
            // 3. PERBAIKAN UTAMA: Pastikan output selalu objek JSON, bahkan saat kosong
            'bookings' => json_encode((object) $formattedBookings)
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
        // 1. Validasi input baru
        $validatedData = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'event_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|in:siang,malam',
            'purpose' => 'required|max:250',
            'payment_method' => 'required|string',
            'payment_proof' => 'required_if:payment_method,Transfer|image|file|max:2048',
        ]);

        // 2. Rekonstruksi waktu mulai dan selesai dari tanggal dan sesi
        $eventDate = Carbon::parse($request->event_date);
        if ($request->time_slot == 'siang') {
            $time_start_use = $eventDate->copy()->setTime(8, 0, 0);
            $time_end_use = $eventDate->copy()->setTime(16, 0, 0);
        } else { // malam
            $time_start_use = $eventDate->copy()->setTime(18, 0, 0);
            $time_end_use = $eventDate->copy()->setTime(23, 0, 0);
        }

        // 3. Validasi Double Booking di Backend (PALING PENTING)
        $isBooked = Rent::where('room_id', $request->room_id)
            ->whereIn('status', ['pending', 'disetujui'])
            ->where(function ($query) use ($time_start_use, $time_end_use) {
                $query->where('time_start_use', '<', $time_end_use)
                    ->where('time_end_use', '>', $time_start_use);
            })
            ->exists();

        if ($isBooked) {
            // Jika sudah ada, kembalikan dengan pesan error
            return redirect()->back()
                ->withErrors(['event_date' => 'Jadwal yang Anda pilih untuk ruangan ini sudah di-booking. Silakan pilih tanggal atau sesi lain.'])
                ->withInput(); // Bawa kembali input lama
        }

        // 4. Lanjutkan proses jika jadwal tersedia
        if ($request->hasFile('payment_proof')) {
            $validatedData['payment_proof'] = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['transaction_start'] = now();
        $validatedData['status'] = 'pending';
        $validatedData['time_start_use'] = $time_start_use;
        $validatedData['time_end_use'] = $time_end_use;

        Rent::create($validatedData);

        return redirect('/dashboard/rents')->with('rentSuccess', 'Reservasi diajukan. Harap tunggu konfirmasi admin.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function show(Rent $rent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function edit(Rent $rent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rent $rent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rent $rent)
    {
        Rent::destroy($rent->id);
        return redirect('/dashboard/rents')->with('deleteRent', 'Data peminjaman berhasil dihapus');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rent  $rent
     * @return \Illuminate\Http\Response
     */
    public function endTransaction($id)
    {
        $rent = Rent::findOrFail($id);
        $rent->update([
            'transaction_end' => now(),
            'status' => 'selesai',
        ]);
        return redirect('/dashboard/rents');
    }
}
