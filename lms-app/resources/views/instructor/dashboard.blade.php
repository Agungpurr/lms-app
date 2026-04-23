@extends('layouts.app')

@section('title', 'Dashboard Instruktur')
@section('page-title', 'Dashboard Instruktur')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('sidebar-nav')
    <span class="nav-section">Menu</span>
    <a href="{{ route('instructor.dashboard') }}" class="nav-item active">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('instructor.courses.index') }}" class="nav-item">
        <span class="icon">📚</span> Kursus Saya
    </a>
    <a href="{{ route('instructor.courses.create') }}" class="nav-item">
        <span class="icon">➕</span> Buat Kursus
    </a>
@endsection

@section('topbar-actions')
    <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">
        ➕ Buat Kursus Baru
    </a>
@endsection

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">📚</div>
        <div>
            <div class="stat-value">{{ $totalCourses ?? 0 }}</div>
            <div class="stat-label">Total Kursus</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">🎓</div>
        <div>
            <div class="stat-value">{{ $totalStudents ?? 0 }}</div>
            <div class="stat-label">Total Siswa</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">📝</div>
        <div>
            <div class="stat-value">{{ $totalLessons ?? 0 }}</div>
            <div class="stat-label">Total Materi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">✅</div>
        <div>
            <div class="stat-value">{{ $publishedCourses ?? 0 }}</div>
            <div class="stat-label">Kursus Aktif</div>
        </div>
    </div>
</div>

{{-- Courses --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">📚 Kursus Saya</div>
        <a href="{{ route('instructor.courses.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Modul</th>
                    <th>Siswa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses ?? [] as $course)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:14px;">{{ $course->title }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $course->created_at->format('d M Y') }}</div>
                    </td>
                    <td style="color:var(--text-muted);">{{ $course->modules_count ?? 0 }} modul</td>
                    <td style="color:var(--text-muted);">{{ $course->enrollments_count ?? 0 }} siswa</td>
                    <td>
                        @if($course->is_published ?? false)
                            <span class="badge badge-green">Aktif</span>
                        @else
                            <span class="badge badge-orange">Draft</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-secondary btn-sm">✏️ Edit</a>
                            <a href="{{ route('instructor.courses.modules.create', $course) }}" class="btn btn-secondary btn-sm">📦 Modul</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:var(--text-muted);padding:40px;">
                        Belum ada kursus. <a href="{{ route('instructor.courses.create') }}" style="color:var(--accent);">Buat kursus pertama →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
