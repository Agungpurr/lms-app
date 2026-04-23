@extends('layouts.app')

@section('title', 'Tambah Modul')
@section('page-title', 'Tambah Modul')
@section('page-subtitle', $course->title)

@section('sidebar-nav')
    <span class="nav-section">Menu</span>
    <a href="{{ route('instructor.dashboard') }}" class="nav-item">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('instructor.courses.index') }}" class="nav-item">
        <span class="icon">📚</span> Kursus Saya
    </a>
    <a href="{{ route('instructor.courses.edit', $course) }}" class="nav-item">
        <span class="icon">✏️</span> Edit Kursus
    </a>
@endsection

@section('content')

<div style="max-width:560px;">
    <a href="{{ route('instructor.courses.show', $course) }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px;">
        ← Kembali ke Kursus
    </a>

    <div class="card">
        <div class="card-title" style="margin-bottom:24px;">📦 Tambah Modul Baru</div>

        <form method="POST" action="{{ route('instructor.courses.modules.store', $course) }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Judul Modul *</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title') }}"
                       placeholder="Contoh: Pengenalan Laravel"
                       required autofocus>
                @error('title') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Modul</label>
                <textarea name="description" class="form-control" rows="3"
                          placeholder="Apa yang akan dipelajari di modul ini?">{{ old('description') }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Urutan</label>
                <input type="number" name="order" class="form-control"
                       value="{{ old('order', ($course->modules->count() + 1)) }}"
                       min="1">
                @error('order') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" class="btn btn-primary">📦 Simpan Modul</button>
                <a href="{{ route('instructor.courses.show', $course) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
