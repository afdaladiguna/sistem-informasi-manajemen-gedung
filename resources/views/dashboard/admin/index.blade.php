@extends('dashboard.layouts.main')

@section('container')
<div class="col-md-10 p-0">
  <h2 class="content-title text-center">Daftar Kepala Gedung</h2>
  <div class="card-body text-end">
    <button type="button" class="mb-3 btn button btn-primary" data-bs-toggle="modal" data-bs-target="#addAdmin">
      Tambah Kepala Gedung
    </button>
    <div class="table-responsive">
      <table class="table table-hover table-stripped table-bordered text-center dt-head-center" id="datatable">
        <thead class="table-info">
          <tr>
            <th scope="row">No.</th>
            <th scope="row">Username</th>
            <th scope="row">Nomor Telepon</th>
            <th scope="row">Email</th>
            <th scope="row">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($admins as $admin)
          <tr>
            <th scope="row">{{$loop->iteration}} </th>
            <td>{{$admin->name}} </td>
            <td>{{$admin->phone_number}} </td>
            <td>{{$admin->email}} </td>
            <td style="font-size: 22px;">
              <a href="/dashboard/admin/{{ $admin->id }}/removeAdmin" class="bi bi-trash-fill text-danger border-0" onclick="return confirm('Ubah admin menjadi mahasiswa?')"></a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@extends('dashboard.partials.addAdminModal')
@extends('dashboard.partials.editUserModal')
{{-- @extends('dashboard.partials.chooseAdminModal') --}}
@endsection