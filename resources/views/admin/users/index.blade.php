@extends('layouts.app')
@section('title', 'Kelola User')
@section('sidebar') @include('components.admin-sidebar') @endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Kelola User</h4>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah User</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ $keyword }}" class="form-control form-control-sm" placeholder="Cari nama / email...">
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-outline-secondary w-100">Cari</button>
            </div>
        </form>

        <table class="table table-hover">
            <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th width="140">Aksi</th></tr></thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i></a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline form-delete">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data user.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</div>
@endsection
