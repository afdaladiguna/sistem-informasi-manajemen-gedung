{{-- Letakkan ini di file partials modal Anda, misal: dashboard/partials/rentModal.blade.php --}}

<div class="modal fade" id="sewaRuangan" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true" data-bookings="{!! $bookings ?? '{}' !!}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">Form Reservasi Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="text-align: left;">
                <form action="/dashboard/rents" method="post" enctype="multipart/form-data">
                    @csrf
                    {{-- Pilih Ruangan --}}
                    <div class="mb-3">
                        <label for="room_id_rent" class="form-label d-block">Pilih Ruangan</label>
                        <select class="form-select" name="room_id" id="room_id_rent" required>
                            <option value="" data-price="0" selected disabled>-- Pilih Ruangan --</option>
                            @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" data-price="{{ $room->price }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Info Jadwal Terisi --}}
                    <div class="alert alert-warning" id="booked-dates-info" style="display: none;">
                        <strong>Jadwal Terisi untuk Ruangan ini:</strong>
                        <ul id="booked-list" class="mb-0 mt-2"></ul>
                    </div>

                    {{-- Pilih Tanggal Acara --}}
                    <div class="mb-3">
                        <label for="event_date" class="form-label">Tanggal Acara</label>
                        <input type="date" class="form-control @error('event_date') is-invalid @enderror" id="event_date" name="event_date" value="{{ old('event_date')}}" required>
                        @error('event_date')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pilih Sesi Acara --}}
                    <div class="mb-3">
                        <label for="time_slot" class="form-label">Sesi Acara</label>
                        <select class="form-select" name="time_slot" id="time_slot" required>
                            <option value="" selected disabled>-- Pilih Sesi --</option>
                            <option value="siang" {{ old('time_slot') == 'siang' ? 'selected' : '' }}>Siang (08:00 - 16:00)</option>
                            <option value="malam" {{ old('time_slot') == 'malam' ? 'selected' : '' }}>Malam (18:00 - 23:00)</option>
                        </select>
                    </div>

                    {{-- Tujuan --}}
                    <div class="mb-3">
                        <label for="purpose" class="form-label">Tujuan</label>
                        <input type="text" class="form-control" id="purpose" name="purpose" value="{{ old('purpose')}}" required>
                    </div>

                    {{-- Total Biaya --}}
                    <div class="alert alert-success">
                        <h5 class="text-center mb-0" id="totalPriceDisplayRent">Pilih ruangan untuk melihat biaya</h5>
                    </div>

                    {{-- Opsi Pembayaran (tetap sama) --}}
                    <div class="mb-3">
                        <label for="payment_method_rent" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" name="payment_method" id="payment_method_rent" required>
                            <option value="" selected disabled>-- Pilih Metode --</option>
                            <option value="Cash">Cash</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                    <div class="alert alert-info" id="rekeningInfoFieldRent" style="display: none;">
                        <strong>Silakan transfer ke rekening berikut:</strong><br>
                        Bank ABC, No. Rek: 1234567890, A/N: PT. Pengelola Gedung
                    </div>
                    <div class="mb-3" id="buktiPembayaranFieldRent" style="display: none;">
                        <label for="payment_proof" class="form-label">Upload Bukti Pembayaran</label>
                        <input class="form-control @error('payment_proof') is-invalid @enderror" type="file" id="payment_proof" name="payment_proof">
                        @error('payment_proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div id="booking-validation-message" class="alert alert-danger mt-3" style="display: none;"></div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="submit-rent-btn" class="btn btn-primary">Ajukan Reservasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rentModalEl = document.getElementById('sewaRuangan');
        if (!rentModalEl) return;

        // --- Ambil semua elemen form ---
        const roomSelect = document.getElementById('room_id_rent');
        const eventDateInput = document.getElementById('event_date');
        const timeSlotSelect = document.getElementById('time_slot');
        const submitButton = document.getElementById('submit-rent-btn');

        // --- Ambil semua elemen untuk display info ---
        const priceDisplay = document.getElementById('totalPriceDisplayRent');
        const bookedInfoDiv = document.getElementById('booked-dates-info');
        const bookedListUl = document.getElementById('booked-list');
        const validationMessageDiv = document.getElementById('booking-validation-message');

        // --- Ambil & proses data booking ---
        const allBookings = JSON.parse(rentModalEl.getAttribute('data-bookings') || '{}');

        // DEBUG: Cek console browser (F12) untuk memastikan data ini ada
        console.log("Data Booking dari Controller:", allBookings);

        let roomBookings = []; // Simpan booking untuk ruangan yang dipilih

        // --- FUNGSI-FUNGSI UTAMA ---

        function displayBookedDates() {
            const selectedRoomId = roomSelect.value;
            roomBookings = allBookings[selectedRoomId] || [];
            bookedListUl.innerHTML = '';

            if (roomBookings.length > 0) {
                roomBookings.forEach(booking => {
                    const li = document.createElement('li');
                    li.textContent = `${booking.display}`;
                    bookedListUl.appendChild(li);
                });
                bookedInfoDiv.style.display = 'block';
            } else {
                bookedInfoDiv.style.display = 'none';
            }
        }

        function displayPrice() {
            const selectedOption = roomSelect.options[roomSelect.selectedIndex];
            const pricePerDay = parseFloat(selectedOption.getAttribute('data-price'));
            priceDisplay.textContent = pricePerDay > 0 ?
                'Biaya Sewa: ' + new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(pricePerDay) :
                'Pilih ruangan untuk melihat biaya';
        }

        function validateAvailability() {
            const selectedDate = eventDateInput.value;
            const selectedSlot = timeSlotSelect.value;

            if (!selectedDate || !selectedSlot || !roomSelect.value) {
                submitButton.disabled = false;
                validationMessageDiv.style.display = 'none';
                return;
            }

            const isAlreadyBooked = roomBookings.some(booking => {
                const sessionName = selectedSlot.charAt(0).toUpperCase() + selectedSlot.slice(1);
                return booking.date === selectedDate && booking.session === sessionName;
            });

            if (isAlreadyBooked) {
                validationMessageDiv.textContent = 'Jadwal pada tanggal dan sesi ini tidak tersedia.';
                validationMessageDiv.style.display = 'block';
                submitButton.disabled = true;
            } else {
                validationMessageDiv.style.display = 'none';
                submitButton.disabled = false;
            }
        }

        // --- EVENT LISTENERS ---

        roomSelect.addEventListener('change', function() {
            displayBookedDates();
            displayPrice();
            validateAvailability();
        });

        eventDateInput.addEventListener('change', validateAvailability);
        timeSlotSelect.addEventListener('change', validateAvailability);

        // --- Logika untuk metode pembayaran ---
        const paymentMethodSelect = document.getElementById('payment_method_rent');
        const buktiPembayaranField = document.getElementById('buktiPembayaranFieldRent');
        const rekeningInfoField = document.getElementById('rekeningInfoFieldRent');

        paymentMethodSelect.addEventListener('change', function() {
            const isTransfer = this.value === 'Transfer';
            buktiPembayaranField.style.display = isTransfer ? 'block' : 'none';
            rekeningInfoField.style.display = isTransfer ? 'block' : 'none';
        });
    });

    // SCRIPT UNTUK MEMBUKA KEMBALI MODAL JIKA ADA ERROR DARI BACKEND
    // INI BAGIAN YANG DIPERBAIKI (spasi dihapus)
    // prettier-ignore
    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        var errorModal = new bootstrap.Modal(document.getElementById('sewaRuangan'));
        errorModal.show();
    });
    @endif
</script>
@endpush