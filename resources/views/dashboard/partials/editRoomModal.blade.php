<div class="modal fade" id="editRoom" tabindex="-1" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModalLabel">Form Edit Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="text-align: left;">
                {{-- Pastikan action-nya dinamis jika modal ini dipakai untuk banyak room --}}
                <form action="/dashboard/rooms/{{ $room->code }}" method="post" enctype="multipart/form-data" id="editform">
                    @method('put')
                    @csrf
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Ruangan</label>
                        {{-- Nilai default diisi dengan data lama dari $room --}}
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" required value="{{ old('code', $room->code) }}">
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Ruangan</label>
                        <input type="text" class="form-control" id="name" name="name" required value="{{ old('name', $room->name) }}">
                    </div>
                    <div class='mb-3'>
                        <label for='img' class='form-label'>Foto Ruangan Baru (Opsional)</label>
                        <input class="form-control @error('img') is-invalid @enderror" type='file' id='img' name='img' />
                        <small>Kosongkan jika tidak ingin mengubah gambar.</small>
                        @error('img')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe Ruangan</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            {{-- Logika untuk memilih opsi yang sesuai dengan data lama --}}
                            <option value="Aula" {{ old('type', $room->type) == 'Aula' ? 'selected' : '' }}>Aula</option>
                            <option value="Ruang Rapat" {{ old('type', $room->type) == 'Ruang Rapat' ? 'selected' : '' }}>Ruang Rapat</option>
                            <option value="Hall" {{ old('type', $room->type) == 'Hall' ? 'selected' : '' }}>Hall</option>
                            <option value="Lainnya" {{ old('type', $room->type) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3 row">
                        <div class="col-6">
                            <label for="floor" class="form-label">Lantai</label>
                            <input type="number" class="form-control" id="floor" name="floor" required value="{{ old('floor', $room->floor) }}">
                        </div>
                        <div class="col-6">
                            <label for="capacity" class="form-label">Kapasitas</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" required value="{{ old('capacity', $room->capacity) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Biaya Sewa (per hari)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" required value="{{ old('price', $room->price) }}">
                        </div>
                        @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Ruangan</label>
                        <textarea name="description" id="description" cols="30" rows="5" class="form-control" required>{{ old('description', $room->description) }}</textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="editbtn" name="editbtn">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>