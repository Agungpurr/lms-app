@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name . '!')

@section('sidebar-nav')
    <span class="nav-section">Menu</span>
    <a href="{{ route('student.dashboard') }}" class="nav-item active">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('courses.index') }}" class="nav-item">
        <span class="icon">🔍</span> Jelajahi Kursus
    </a>
    <span class="nav-section">Belajar</span>
    @foreach($enrollments ?? [] as $enrollment)
    <a href="{{ route('student.courses.show', $enrollment->course) }}" class="nav-item" style="padding-left:16px;">
        <span class="icon">📖</span> {{ Str::limit($enrollment->course->title, 22) }}
    </a>
    @endforeach
@endsection

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">📚</div>
        <div>
            <div class="stat-value">{{ $totalEnrollments ?? 0 }}</div>
            <div class="stat-label">Kursus Diikuti</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div>
            <div class="stat-value">{{ $completedCourses ?? 0 }}</div>
            <div class="stat-label">Kursus Selesai</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">🧠</div>
        <div>
            <div class="stat-value">{{ $quizPassed ?? 0 }}</div>
            <div class="stat-label">Quiz Lulus</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">📜</div>
        <div>
            <div class="stat-value">{{ $certificates ?? 0 }}</div>
            <div class="stat-label">Sertifikat</div>
        </div>
    </div>
</div>

{{-- My Courses --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">📖 Kursus yang Diikuti</div>
        <a href="{{ route('courses.index') }}" class="btn btn-secondary btn-sm">+ Tambah Kursus</a>
    </div>

    @forelse($enrollments ?? [] as $enrollment)
    @php
        $progress = $enrollment->progress_percentage ?? 0;
    @endphp
    <div style="padding:16px 0; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:16px;">
        <div style="width:48px;height:48px;background:linear-gradient(135deg,rgba(79,126,255,0.2),rgba(124,58,237,0.2));border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">
            📚
        </div>
        <div style="flex:1; min-width:0;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
                <div>
                    <div style="font-size:15px;font-weight:700;">{{ $enrollment->course->title }}</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $enrollment->course->instructor->name ?? 'Instruktur' }}</div>
                </div>
                <span style="font-size:13px;font-weight:700;color:var(--accent);flex-shrink:0;margin-left:12px;">{{ $progress }}%</span>
            </div>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" style="width:{{ $progress }}%"></div>
            </div>
        </div>
        <div style="flex-shrink:0;">
            @if($progress >= 100)
                <a href="{{ route('student.certificate.download', $enrollment) }}" class="btn btn-success btn-sm">📜 Sertifikat</a>
            @else
                <a href="{{ route('student.courses.show', $enrollment->course) }}" class="btn btn-primary btn-sm">Lanjutkan →</a>
            @endif
        </div>
    </div>
    @empty
    <div style="text-align:center; padding:48px 32px; color:var(--text-muted);">
        <div style="font-size:48px; margin-bottom:12px;">🎒</div>
        <div style="font-size:16px; font-weight:600; margin-bottom:8px; color:var(--text);">Belum ada kursus</div>
        <div style="margin-bottom:20px;">Mulai belajar sekarang dengan mendaftar ke kursus pertamamu.</div>
        <a href="{{ route('courses.index') }}" class="btn btn-primary">🔍 Jelajahi Kursus</a>
    </div>
    @endforelse
</div>

@endsection
