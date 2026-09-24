@php $route = request()->route()->getName(); @endphp
<a href="{{ route('admin.dashboard') }}" class="{{ str_starts_with($route,'admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-gauge"></i> Dashboard</a>
<a href="{{ route('admin.users.index') }}" class="{{ str_starts_with($route,'admin.users') ? 'active' : '' }}"><i class="fa-solid fa-users"></i> Kelola User</a>
<a href="{{ route('admin.pendaftaran.index') }}" class="{{ str_starts_with($route,'admin.pendaftaran') ? 'active' : '' }}"><i class="fa-solid fa-file-lines"></i> Data Pendaftaran</a>
<a href="{{ route('informasi-pmb') }}"><i class="fa-solid fa-circle-info"></i> Informasi PMB</a>
