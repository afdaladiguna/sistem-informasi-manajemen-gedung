<div class="modal fade" id="pinjamRuangan" tabindex="-1" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">Form Reservasi Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="text-align: left;">
                <form action="/dashboard/rents" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="room_id" class="form-label d-block">Pilih Ruangan</label>
                        <select class="form-select" aria-label="Default select example" name="room_id" id="room_id" required>
                            <option value="" data-price="0" selected disabled>-- Pilih Kode Ruangan --</option>
                            @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" data-price="{{ $room->price }}">{{ $room->name }} (Rp {{ number_format($room->price, 0, ',', '.') }}/hari)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="time_start_use" class="form-label">Mulai Sewa</label>
                        <input type="datetime-local" class="form-control" id="time_start_use" name="time_start_use" value="{{ old('time_start_use')}}" required>
                    </div>
                    <div class="mb-3">
                        <label for="time_end_use" class="form-label">Selesai Sewa</label>
                        <input type="datetime-local" class="form-control" id="time_end_use" name="time_end_use" value="{{ old('time_end_use')}}" required>
                    </div>
                    <div class="mb-3">
                        <label for="purpose" class="form-label">Tujuan</label>
                        <input type="text" class="form-control" id="purpose" name="purpose" value="{{ old('purpose')}}" required>
                    </div>

                    <div class="alert alert-success">
                        <h5 class="text-center mb-0" id="totalPriceDisplay">Pilih ruangan dan tanggal untuk melihat total biaya</h5>
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" name="payment_method" id="payment_method" required>
                            <option value="" selected disabled>-- Pilih Metode --</option>
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                    <div class="alert alert-info" id="rekeningInfoField" style="display: none;">
                        <strong>Silakan transfer ke rekening berikut:</strong><br>
                        Bank ABC <br>
                        No. Rek: 1234567890 <br>
                        A/N: PT. Pengelola Gedung
                    </div>
                    <div class="mb-3" id="buktiPembayaranField" style="display: none;">
                        <label for="payment_proof" class="form-label">Upload Bukti Pembayaran</label>
                        <input class="form-control @error('payment_proof') is-invalid @enderror" type="file" id="payment_proof" name="payment_proof">
                        @error('payment_proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ajukan Reservasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Menggunakan ID yang SAMA PERSIS dengan yang ada di HTML Anda
        const roomSelect = document.getElementById('room_id');
        const startTimeInput = document.getElementById('time_start_use');
        const endTimeInput = document.getElementById('time_end_use');
        const priceDisplay = document.getElementById('totalPriceDisplay');
        const paymentMethodSelect = document.getElementById('payment_method');
        const buktiPembayaranField = document.getElementById('buktiPembayaranField');
        const rekeningInfoField = document.getElementById('rekeningInfoField');

        // Fungsi untuk menghitung dan menampilkan total biaya sewa
        function calculateAndDisplayTotal() {
            if (!roomSelect) return;

            const selectedOption = roomSelect.options[roomSelect.selectedIndex];
            const pricePerDay = parseFloat(selectedOption.getAttribute('data-price'));
            const startTime = new Date(startTimeInput.value);
            const endTime = new Date(endTimeInput.value);

            if (pricePerDay > 0 && startTimeInput.value && endTimeInput.value && endTime > startTime) {
                const timeDifference = endTime.getTime() - startTime.getTime();
                const days = Math.ceil(timeDifference / (1000 * 3600 * 24));
                const totalCost = days * pricePerDay;

                priceDisplay.textContent = 'Total Biaya: ' + new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(totalCost);
            } else {
                priceDisplay.textContent = 'Pilih ruangan dan tanggal untuk melihat total biaya';
            }
        }

        // Fungsi untuk menampilkan atau menyembunyikan field terkait metode pembayaran
        function togglePaymentFields() {
            if (!paymentMethodSelect) return;

            if (paymentMethodSelect.value === 'Transfer') {
                buktiPembayaranField.style.display = 'block';
                rekeningInfoField.style.display = 'block';
            } else {
                buktiPembayaranField.style.display = 'none';
                rekeningInfoField.style.display = 'none';
            }
        }

        // Pasang event listener pada setiap input
        if (roomSelect && startTimeInput && endTimeInput) {
            roomSelect.addEventListener('change', calculateAndDisplayTotal);
            startTimeInput.addEventListener('change', calculateAndDisplayTotal);
            endTimeInput.addEventListener('change', calculateAndDisplayTotal);
        }
        if (paymentMethodSelect) {
            paymentMethodSelect.addEventListener('change', togglePaymentFields);
        }

        togglePaymentFields();
    });
</script>