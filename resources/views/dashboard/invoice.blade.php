@extends('dashboard.layouts.main')

@section('container')
<div class="col-md-10 p-0">
    <h2 class="content-title text-center">Invoice</h2>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>User:</strong> {{ $rent->user->name }}</p>
                <p><strong>Room:</strong> {{ $rent->room->name }}</p>
                <p><strong>Purpose:</strong> {{ $rent->purpose }}</p>
            </div>
            <div class="col-md-6 text-end">
                <p><strong>Rent Start:</strong> {{ $rent->time_start_use }}</p>
                <p><strong>Rent End:</strong> {{ $rent->time_end_use }}</p>
                <p><strong>Payment Method:</strong> {{ $rent->payment_method }}</p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Room Rent</td>
                            <td>Rp {{ number_format($rent->room->price, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                @if($rent->status == 'disetujui' || $rent->status == 'disewa' || $rent->status == 'selesai')
                    <span class="badge bg-success">Paid</span>
                @else
                    <span class="badge bg-warning">Pending</span>
                @endif
            </div>
            <div class="col-md-6 text-end">
                <p><strong>Total Price:</strong> Rp {{ number_format($rent->room->price, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="alert alert-secondary mt-4">
            <strong>Catatan:</strong> Jika terdapat pertanyaan mengenai invoice ini, silakan hubungi Kepala Gedung di nomor <strong>0812-3456-7890</strong>.
        </div>
    </div>
</div>
@endsection
