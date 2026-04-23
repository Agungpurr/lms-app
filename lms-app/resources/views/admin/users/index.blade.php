@extends('layouts.app')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')
@section('page-subtitle', 'Manajemen semua pengguna sistem')

@section('sidebar-nav')
    <span class="nav-section">Menu</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item active">
        <span class="icon">👥</span> Kelola User
    </a>
    <a href="{{ route('admin.courses.index') }}" class="nav-item">
        <span class="icon">📚</span> Kelola Kursus
    </a>
@endsection

@section('topbar-actions')
    {{-- Admin tidak buat user manual sesuai route --}}
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <div class="card-title">👥 Daftar User</div>
        {{-- Filter --}}
        <form method="GET" style="display:flex;gap:8px;">
            <select name="role" class="form-control" style="width:auto;padding:8px 12px;font-size:13px;" onchange="this.form.submit()">
                <option value="">Semua Role</option>
                <option value="admin"      {{ request('role') === 'admin'      ? 'selected' : '' }}>Admin</option>
                <option value="instructor" {{ request('role') === 'instructor' ? 'selected' : '' }}>Instruktur</option>
                <option value="student"    {{ request('role') === 'student'    ? 'selected' : '' }}>Siswa</option>
            </select>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users ?? [] as $user)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">{{ $loop->iteration }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:32px;height:32px;background:linear-gradient(135deg,#4f7eff,#7c3aed);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:600;font-size:14px;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="color:var(--text-muted);font-size:13px;">{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge badge-red">Admin</span>
                        @elseif($user->role === 'instructor')
                            <span class="badge badge-purple">Instruktur</span>
                        @else
                            <span class="badge badge-blue">Siswa</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:12px;">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary btn-sm">✏️ Edit</a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Yakin hapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:var(--text-muted);padding:40px;">
                        Belum ada user terdaftar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($users) && $users->hasPages())
    <div style="padding: 20px; display: flex; justify-content: flex-end;">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
