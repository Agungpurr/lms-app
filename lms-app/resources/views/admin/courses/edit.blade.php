@extends('layouts.app')

@section('title', 'Edit Kursus')
@section('page-title', 'Edit Kursus')
@section('page-subtitle', 'Ubah status atau informasi kursus')

@section('sidebar-nav')
    <span class="nav-section">Menu</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item">
        <span class="icon">👥</span> Kelola User
    </a>
    <a href="{{ route('admin.courses.index') }}" class="nav-item active">
        <span class="icon">📚</span> Kelola Kursus
    </a>
@endsection

@section('content')

<div style="max-width:560px;">
    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px;">
        ← Kembali
    </a>

    <div class="card">
        <div class="card-title" style="margin-bottom:24px;">✏️ Edit Kursus</div>

        <form method="POST" action="{{ route('admin.courses.update', $course) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Judul Kursus</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $course->title) }}" required>
                @error('title') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $course->description) }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <input type="text" name="category" class="form-control"
                       value="{{ old('category', $course->category) }}"
                       placeholder="Contoh: Pemrograman">
            </div>

            <div class="form-group">
                <label class="form-label" style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="is_published" value="1"
                           {{ old('is_published', $course->is_published) ? 'checked' : '' }}
                           style="width:16px;height:16px;accent-color:var(--accent);">
                    Publikasikan kursus (tampil ke siswa)
                </label>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
