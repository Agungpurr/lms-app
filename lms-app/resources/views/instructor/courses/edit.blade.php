@extends('layouts.app')

@section('title', isset($course) ? 'Edit Kursus' : 'Buat Kursus')
@section('page-title', isset($course) ? 'Edit Kursus' : 'Buat Kursus Baru')
@section('page-subtitle', isset($course) ? 'Perbarui informasi kursus' : 'Isi detail kursus baru')

@section('sidebar-nav')
    <span class="nav-section">Menu</span>
    <a href="{{ route('instructor.dashboard') }}" class="nav-item">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('instructor.courses.index') }}" class="nav-item">
        <span class="icon">📚</span> Kursus Saya
    </a>
    <a href="{{ route('instructor.courses.create') }}" class="nav-item active">
        <span class="icon">➕</span> Buat Kursus
    </a>
@endsection

@section('content')

<div style="max-width:640px;">
    <a href="{{ route('instructor.courses.index') }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px;">
        ← Kembali
    </a>

    <div class="card">
        <div class="card-title" style="margin-bottom:24px;">
            {{ isset($course) ? '✏️ Edit Kursus' : '➕ Buat Kursus Baru' }}
        </div>

        <form method="POST"
              action="{{ isset($course) ? route('instructor.courses.update', $course) : route('instructor.courses.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($course)) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label">Judul Kursus *</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $course->title ?? '') }}"
                       placeholder="Contoh: Belajar Laravel untuk Pemula"
                       required>
                @error('title') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi *</label>
                <textarea name="description" class="form-control" rows="4"
                          placeholder="Deskripsikan isi dan tujuan kursus ini...">{{ old('description', $course->description ?? '') }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" class="form-control"
                           value="{{ old('category', $course->category ?? '') }}"
                           placeholder="Contoh: Pemrograman">
                    @error('category') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Level</label>
                    <select name="level" class="form-control">
                        <option value="beginner"     {{ old('level', $course->level ?? '') === 'beginner'     ? 'selected' : '' }}>Pemula</option>
                        <option value="intermediate" {{ old('level', $course->level ?? '') === 'intermediate' ? 'selected' : '' }}>Menengah</option>
                        <option value="advanced"     {{ old('level', $course->level ?? '') === 'advanced'     ? 'selected' : '' }}>Mahir</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" name="is_published" value="1"
                           {{ old('is_published', $course->is_published ?? false) ? 'checked' : '' }}
                           style="width:16px;height:16px;accent-color:var(--accent);">
                    Publikasikan kursus (tampil ke siswa)
                </label>
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" class="btn btn-primary">
                    {{ isset($course) ? '💾 Simpan Perubahan' : '🚀 Buat Kursus' }}
                </button>
                <a href="{{ route('instructor.courses.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
