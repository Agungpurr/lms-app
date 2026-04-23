@extends('layouts.app')

@section('title', isset($lesson) ? 'Edit Materi' : 'Tambah Materi')
@section('page-title', isset($lesson) ? 'Edit Materi' : 'Tambah Materi')
@section('page-subtitle', $module->title . ' · ' . $course->title)

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

<div style="max-width:680px;">
    <a href="{{ route('instructor.courses.show', $course) }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px;">
        ← Kembali ke Kursus
    </a>

    <div class="card">
        <div class="card-title" style="margin-bottom:24px;">
            {{ isset($lesson) ? '✏️ Edit Materi' : '➕ Tambah Materi Baru' }}
        </div>

        <form method="POST"
              action="{{ isset($lesson)
                ? route('instructor.courses.modules.lessons.update', [$course, $module, $lesson])
                : route('instructor.courses.modules.lessons.store', [$course, $module]) }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($lesson)) @method('PUT') @endif

            <div class="form-group">
                <label class="form-label">Judul Materi *</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $lesson->title ?? '') }}"
                       placeholder="Contoh: Instalasi Laravel"
                       required autofocus>
                @error('title') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tipe Materi *</label>
                <select name="type" class="form-control" id="lesson-type" onchange="toggleFields()">
                    <option value="text"  {{ old('type', $lesson->type ?? '') === 'text'  ? 'selected' : '' }}>📄 Teks / Artikel</option>
                    <option value="video" {{ old('type', $lesson->type ?? '') === 'video' ? 'selected' : '' }}>🎬 Video</option>
                    <option value="file"  {{ old('type', $lesson->type ?? '') === 'file'  ? 'selected' : '' }}>📎 File / PDF</option>
                </select>
                @error('type') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Text content --}}
            <div class="form-group" id="field-content">
                <label class="form-label">Konten Materi</label>
                <textarea name="content" class="form-control" rows="8"
                          placeholder="Tulis isi materi di sini...">{{ old('content', $lesson->content ?? '') }}</textarea>
                @error('content') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- Video URL --}}
            <div class="form-group" id="field-video" style="display:none;">
                <label class="form-label">URL Video</label>
                <input type="url" name="video_url" class="form-control"
                       value="{{ old('video_url', $lesson->video_url ?? '') }}"
                       placeholder="https://youtube.com/watch?v=...">
                <div style="font-size:12px;color:var(--text-muted);margin-top:5px;">
                    Mendukung YouTube dan URL video langsung
                </div>
                @error('video_url') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            {{-- File upload --}}
            <div class="form-group" id="field-file" style="display:none;">
                <label class="form-label">Upload File</label>
                @if(isset($lesson) && $lesson->file_path)
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:8px;">
                    File saat ini: <a href="{{ Storage::url($lesson->file_path) }}" target="_blank" style="color:var(--accent);">Lihat File</a>
                </div>
                @endif
                <input type="file" name="file" class="form-control"
                       accept=".pdf,.doc,.docx,.ppt,.pptx,.zip">
                <div style="font-size:12px;color:var(--text-muted);margin-top:5px;">
                    Format: PDF, DOC, PPT, ZIP · Maks: 50MB
                </div>
                @error('file') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Urutan</label>
                <input type="number" name="order" class="form-control"
                       value="{{ old('order', $lesson->order ?? ($module->lessons->count() + 1)) }}"
                       min="1">
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" class="btn btn-primary">
                    {{ isset($lesson) ? '💾 Simpan Perubahan' : '➕ Tambah Materi' }}
                </button>
                <a href="{{ route('instructor.courses.show', $course) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleFields() {
    const type = document.getElementById('lesson-type').value;
    document.getElementById('field-content').style.display = type === 'text'  ? 'block' : 'none';
    document.getElementById('field-video').style.display   = type === 'video' ? 'block' : 'none';
    document.getElementById('field-file').style.display    = type === 'file'  ? 'block' : 'none';
}
// Init on load
toggleFields();
</script>
@endpush

@endsection
