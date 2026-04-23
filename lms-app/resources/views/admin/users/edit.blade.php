@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Ubah data pengguna')

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

@section('content')

<div style="max-width: 560px;">
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px;">
        ← Kembali
    </a>

    <div class="card">
        <div class="card-title" style="margin-bottom:24px;">✏️ Edit Data User</div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-control">
                    <option value="student"    {{ $user->role === 'student'    ? 'selected' : '' }}>Siswa</option>
                    <option value="instructor" {{ $user->role === 'instructor' ? 'selected' : '' }}>Instruktur</option>
                    <option value="admin"      {{ $user->role === 'admin'      ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password Baru <span style="color:var(--text-muted);font-weight:400;">(kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" class="form-control">
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
