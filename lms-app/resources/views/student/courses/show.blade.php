@extends('layouts.app')

@section('title', $course->title)
@section('page-title', $course->title)
@section('page-subtitle', 'Instruktur: ' . ($course->instructor->name ?? '-'))

@section('sidebar-nav')
    <span class="nav-section">Navigasi</span>
    <a href="{{ route('student.dashboard') }}" class="nav-item">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('student.courses.show', $course) }}" class="nav-item active">
        <span class="icon">📖</span> Kursus Ini
    </a>

    <span class="nav-section">Modul</span>
    @foreach($course->modules ?? [] as $module)
    <div style="padding:6px 12px;">
        <div style="font-size:12px;font-weight:700;color:var(--text-muted);margin-bottom:4px;">
            {{ $module->title }}
        </div>
        @foreach($module->lessons ?? [] as $lesson)
        <a href="{{ route('student.courses.lessons.show', [$course, $lesson]) }}"
           class="nav-item" style="padding:6px 8px;font-size:13px;">
            @if(in_array($lesson->id, $completedLessonIds ?? []))
                <span style="color:var(--success);">✅</span>
            @else
                <span class="icon">▶️</span>
            @endif
            {{ Str::limit($lesson->title, 20) }}
        </a>
        @endforeach
    </div>
    @endforeach
@endsection

@section('content')

{{-- Progress --}}
@php $progress = $enrollment->progress_percentage ?? 0; @endphp
<div class="card" style="margin-bottom:24px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
        <div style="font-size:14px; font-weight:600;">Progres Belajar</div>
        <div style="font-size:20px; font-weight:800; color:var(--accent);">{{ $progress }}%</div>
    </div>
    <div class="progress-bar-wrap" style="height:10px;">
        <div class="progress-bar-fill" style="width:{{ $progress }}%"></div>
    </div>
    @if($progress >= 100)
    <div style="margin-top:16px;">
        <a href="{{ route('student.certificate.download', $enrollment) }}" class="btn btn-success">
            📜 Download Sertifikat
        </a>
    </div>
    @endif
</div>

{{-- Modules --}}
@forelse($course->modules ?? [] as $module)
<div class="card" style="margin-bottom:16px;">
    <div style="font-size:16px; font-weight:700; margin-bottom:16px;">
        📦 {{ $module->title }}
    </div>

    @forelse($module->lessons ?? [] as $lesson)
    @php $isDone = in_array($lesson->id, $completedLessonIds ?? []); @endphp
    <div style="display:flex; align-items:center; gap:12px; padding:12px; border-radius:10px; background:var(--bg3); margin-bottom:8px;">
        <div style="font-size:20px;">
            @if($isDone) ✅
            @elseif($lesson->type === 'video') 🎬
            @elseif($lesson->type === 'quiz') 🧠
            @else 📄
            @endif
        </div>
        <div style="flex:1;">
            <div style="font-size:14px; font-weight:600; {{ $isDone ? 'text-decoration:line-through;color:var(--text-muted)' : '' }}">
                {{ $lesson->title }}
            </div>
            <div style="font-size:11px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px;">
                {{ $lesson->type ?? 'materi' }}
            </div>
        </div>
        <a href="{{ route('student.courses.lessons.show', [$course, $lesson]) }}"
           class="btn {{ $isDone ? 'btn-secondary' : 'btn-primary' }} btn-sm">
            {{ $isDone ? 'Ulangi' : 'Buka →' }}
        </a>
    </div>
    @empty
    <div style="color:var(--text-muted); font-size:14px; padding:12px;">Belum ada materi</div>
    @endforelse

    {{-- Quiz --}}
    @if($module->quizzes && $module->quizzes->isNotEmpty())
    @foreach($module->quizzes as $quiz)
    <div style="display:flex; align-items:center; gap:12px; padding:12px; border-radius:10px; background:rgba(124,58,237,0.08); border:1px solid rgba(124,58,237,0.2); margin-top:8px;">
        <div style="font-size:20px;">🧠</div>
        <div style="flex:1;">
            <div style="font-size:14px; font-weight:600;">{{ $quiz->title }}</div>
            <div style="font-size:11px; color:var(--text-muted);">{{ $quiz->questions_count ?? 0 }} soal · Passing: {{ $quiz->passing_score }}%</div>
        </div>
        <a href="{{ route('student.quiz.show', $quiz) }}" class="btn btn-secondary btn-sm">
            Kerjakan →
        </a>
    </div>
    @endforeach
    @endif
</div>
@empty
<div style="text-align:center; padding:48px; color:var(--text-muted);">
    Belum ada modul tersedia.
</div>
@endforelse

@endsection
