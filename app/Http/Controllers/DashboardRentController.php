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

        // Get all relevant bookings
        $bookings = Rent::whereIn('status', ['pending', 'disetujui'])->get();

        // Group bookings by room_id and date/session
        $schedule = [];
        foreach ($bookings as $booking) {
            $date = Carbon::parse($booking->time_start_use)->format('Y-m-d');
            $session = (Carbon::parse($booking->time_start_use)->hour < 17) ? 'siang' : 'malam';
            $schedule[$booking->room_id][$date][$session] = [
                'user' => $booking->user->name ?? '-',
                'purpose' => $booking->purpose ?? '-',
                'status' => $booking->status,
            ];
        }

        return view('dashboard.rents.index', [
            'title' => "Peminjaman",
            'adminRents' => Rent::latest()->get(),
            'userRents' => Rent::where('user_id', auth()->user()->id)->get(),
            'rooms' => Room::all(),
            // 3. PERBAIKAN UTAMA: Pastikan output selalu objek JSON, bahkan saat kosong
            'bookings' => json_encode((object) $formattedBookings),
            'schedule' => $schedule, // Nested array: [room_id][date][session]
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
            return redirect()->back()->with('rentError', 'Reservasi gagal: Jadwal pada tanggal tersebut sudah terisi. Mohon pilih tanggal atau sesi lain.')->withInput();
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

    public function generateInvoice($id)
    {
        $rent = Rent::with(['user', 'room'])->findOrFail($id);

        return view('dashboard.invoice', [
            'title' => 'Invoice',
            'rent' => $rent,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        $rentsQuery = Rent::with(['room', 'user']);

        if ($month && $year) {
            // Monthly report
            $rentsQuery->whereMonth('time_start_use', $month)
                       ->whereYear('time_start_use', $year);
            $reportType = 'monthly';
        } elseif ($year) {
            // Yearly report
            $rentsQuery->whereYear('time_start_use', $year);
            $reportType = 'yearly';
        } else {
            // All data report (if no month or year is specified, though forms should prevent this)
            $reportType = 'all';
        }

        $rents = $rentsQuery->get();

        $totalRevenue = 0;
        // Calculate total revenue only for 'selesai' (completed) rents
        foreach ($rents as $rent) {
            if ($rent->status === 'selesai' && $rent->room && isset($rent->room->price)) {
                $totalRevenue += $rent->room->price;
            }
        }

        $data = [
            'rents' => $rents,
            'month' => $month,
            'year' => $year,
            'totalRevenue' => $totalRevenue,
            'reportType' => $reportType,
        ];

        $pdf = \PDF::loadView('dashboard.report', $data);

        $filename = 'laporan-peminjaman';
        if ($reportType === 'monthly') {
            $filename .= '-' . $month . '-' . $year;
        } elseif ($reportType === 'yearly') {
            $filename .= '-' . $year;
        }
        $filename .= '.pdf';

        return $pdf->download($filename);
    }
}
