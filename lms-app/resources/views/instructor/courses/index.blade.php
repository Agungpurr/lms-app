@extends('layouts.app')

@section('title', 'Kursus Saya')
@section('page-title', 'Kursus Saya')
@section('page-subtitle', 'Kelola semua kursus yang kamu buat')

@section('sidebar-nav')
    <span class="nav-section">Menu</span>
    <a href="{{ route('instructor.dashboard') }}" class="nav-item">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('instructor.courses.index') }}" class="nav-item active">
        <span class="icon">📚</span> Kursus Saya
    </a>
    <a href="{{ route('instructor.courses.create') }}" class="nav-item">
        <span class="icon">➕</span> Buat Kursus
    </a>
@endsection

@section('topbar-actions')
    <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">➕ Buat Kursus</a>
@endsection

@section('content')

@if($courses->isEmpty())
<div style="text-align:center; padding: 80px 32px;">
    <div style="font-size:64px; margin-bottom:16px;">📚</div>
    <div style="font-size:20px; font-weight:700; margin-bottom:8px;">Belum ada kursus</div>
    <div style="color:var(--text-muted); margin-bottom:24px;">Mulai buat kursus pertamamu dan bagikan ilmu ke dunia.</div>
    <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">➕ Buat Kursus Pertama</a>
</div>
@else
<div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap:20px;">
    @foreach($courses as $course)
    <div class="card" style="padding:0;overflow:hidden;">
        <div style="height:10px; background:linear-gradient(90deg, var(--accent), var(--accent2));"></div>
        <div style="padding:24px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <div style="flex:1;">
                    <div style="font-size:16px; font-weight:700; margin-bottom:4px;">{{ $course->title }}</div>
                    <div style="font-size:12px; color:var(--text-muted);">{{ Str::limit($course->description, 80) }}</div>
                </div>
                @if($course->is_published)
                    <span class="badge badge-green" style="margin-left:12px;">Aktif</span>
                @else
                    <span class="badge badge-orange" style="margin-left:12px;">Draft</span>
                @endif
            </div>

            <div style="display:flex; gap:16px; margin-bottom:20px;">
                <div style="font-size:12px; color:var(--text-muted);">
                    📦 {{ $course->modules_count ?? 0 }} Modul
                </div>
                <div style="font-size:12px; color:var(--text-muted);">
                    👥 {{ $course->enrollments_count ?? 0 }} Siswa
                </div>
                <div style="font-size:12px; color:var(--text-muted);">
                    📅 {{ $course->created_at->format('d M Y') }}
                </div>
            </div>

            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-secondary btn-sm">✏️ Edit</a>
                <a href="{{ route('instructor.courses.modules.create', $course) }}" class="btn btn-secondary btn-sm">📦 Tambah Modul</a>
                <form method="POST" action="{{ route('instructor.courses.destroy', $course) }}"
                      onsubmit="return confirm('Yakin hapus kursus ini?')" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
