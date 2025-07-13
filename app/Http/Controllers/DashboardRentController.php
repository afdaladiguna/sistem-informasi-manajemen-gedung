<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use App\Models\Room;
use Illuminate\Http\Request;

class DashboardRentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.rents.index', [
            'adminRents' => Rent::latest()->get(),
            'userRents' => Rent::where('user_id', auth()->user()->id)->get(),
            'title' => "Peminjaman",
            'rooms' => Room::all(),
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
        $validatedData = $request->validate([
            'room_id' => 'required',
            'time_start_use' => 'required|date',
            'time_end_use' => 'required|date|after:time_start_use',
            'purpose' => 'required|max:250',
            'payment_method' => 'required|string',
            // Validasi bukti pembayaran: wajib jika metode transfer, harus gambar, maks 2MB
            'payment_proof' => 'required_if:payment_method,Transfer|image|file|max:2048',
        ]);

        // Proses upload file jika ada
        if ($request->hasFile('payment_proof')) {
            // Simpan file di folder 'public/payment-proofs' dan simpan path-nya
            $validatedData['payment_proof'] = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['transaction_start'] = now();
        $validatedData['status'] = 'pending';
        $validatedData['transaction_end'] = null;

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
        $transaction = [
            'transaction_end' => now(),
            'status' => 'selesai',
        ];

        Rent::where('id', $id)->update($transaction);

        return redirect('/dashboard/rents');
    }
}
