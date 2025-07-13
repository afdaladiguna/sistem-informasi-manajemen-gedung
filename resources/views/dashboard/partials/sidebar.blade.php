<div class=" col-md-2 col-6 p-0 sidebar">
  <ul class="nav flex-column ">
    @if (auth()->user()->role_id <= 2)
      <li class="nav-item {{Request::is('dashboard/admin') ? 'sidebar-active' : ''}}">
      {{-- Tetap --}}
      <a class="nav-link" href="/dashboard/admin">Daftar Kepala Gedung</a>
      </li>
      <li class="nav-item {{Request::is('dashboard/users') ? 'sidebar-active' : ''}}">
        {{-- Diubah dari User menjadi Pengguna --}}
        <a class="nav-link " href="/dashboard/users">Daftar Pengguna</a>
      </li>
      <li class="nav-item {{Request::is('dashboard/temporaryRents') ? 'sidebar-active'  : ''}}">
        {{-- Diubah menjadi permintaan/request reservasi baru --}}
        <a class="nav-link " href="/dashboard/temporaryRents">Permintaan Reservasi</a>
      </li>
      @endif
      @if (auth()->user()->role_id <= 4)
        <li class="nav-item {{Request::is('dashboard/rents') ? 'sidebar-active' : ''}}">
        {{-- Diubah menjadi daftar semua reservasi --}}
        <a class="nav-link " href="/dashboard/rents">Daftar Reservasi</a>
        </li>
        @endif
        <li class="nav-item {{Request::is('dashboard/rooms') ? 'sidebar-active' : ''}}">
          {{-- Diubah agar lebih sesuai untuk dashboard --}}
          <a class="nav-link" href="/dashboard/rooms">Manajemen Ruangan</a>
        </li>
  </ul>
</div>