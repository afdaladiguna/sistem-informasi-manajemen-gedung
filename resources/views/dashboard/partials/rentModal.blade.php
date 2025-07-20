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

<?php if(session('rentError')): ?>
<script>
    alert("{{ session('rentError') }}");
</script>
<?php endif; ?>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rentModalEl = document.getElementById('sewaRuangan');
    if (!rentModalEl) return;

    const roomSelect = document.getElementById('room_id_rent');
    const eventDateInput = document.getElementById('event_date');
    const timeSlotSelect = document.getElementById('time_slot');
    const submitButton = document.getElementById('submit-rent-btn');
    const validationMessageDiv = document.getElementById('booking-validation-message');
    const rentForm = rentModalEl.querySelector('form[action="/dashboard/rents"]');
    const allBookings = JSON.parse(rentModalEl.getAttribute('data-bookings') || '{}');
    let roomBookings = [];

    function validateAvailability() {
        const selectedRoomId = roomSelect.value;
        const selectedDate = eventDateInput.value;
        const selectedSlot = timeSlotSelect.value;

        roomBookings = allBookings[selectedRoomId] || [];

        if (!selectedDate || !selectedSlot || !selectedRoomId) {
            submitButton.disabled = true;
            validationMessageDiv.style.display = 'none';
            return;
        }

        const sessionName = selectedSlot.charAt(0).toUpperCase() + selectedSlot.slice(1);
        const isAlreadyBooked = roomBookings.some(booking => {
            return booking.date === selectedDate && booking.session === sessionName;
        });

        if (isAlreadyBooked) {
            validationMessageDiv.innerHTML = 'Jadwal pada tanggal dan sesi ini <b>sudah terisi</b>. Silakan pilih jadwal lain.';
            validationMessageDiv.style.display = 'block';
            submitButton.disabled = true;
        } else {
            validationMessageDiv.style.display = 'none';
            submitButton.disabled = false;
        }
    }

    // Listen to all relevant events
    roomSelect.addEventListener('input', validateAvailability);
    eventDateInput.addEventListener('input', validateAvailability);
    timeSlotSelect.addEventListener('input', validateAvailability);

    // Also check on modal show (Bootstrap 5 event)
    rentModalEl.addEventListener('shown.bs.modal', function () {
        validateAvailability();
    });

    // Initial check
    validateAvailability();

    // Prevent form submit if slot is booked
    rentForm.addEventListener('submit', function(e) {
        const selectedRoomId = roomSelect.value;
        const selectedDate = eventDateInput.value;
        const selectedSlot = timeSlotSelect.value;
        const sessionName = selectedSlot.charAt(0).toUpperCase() + selectedSlot.slice(1);
        roomBookings = allBookings[selectedRoomId] || [];
        const isAlreadyBooked = roomBookings.some(booking => {
            return booking.date === selectedDate && booking.session === sessionName;
        });
        if (isAlreadyBooked) {
            validationMessageDiv.innerHTML = 'Jadwal pada tanggal dan sesi ini <b>sudah terisi</b>. Silakan pilih jadwal lain.';
            validationMessageDiv.style.display = 'block';
            submitButton.disabled = true;
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });
});
</script>
@endpush