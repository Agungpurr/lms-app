@extends('layouts.app')

@section('title', $lesson->title)
@section('page-title', $lesson->title)
@section('page-subtitle', $course->title)

@section('sidebar-nav')
    <span class="nav-section">Navigasi</span>
    <a href="{{ route('student.dashboard') }}" class="nav-item">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('student.courses.show', $course) }}" class="nav-item">
        <span class="icon">←</span> Kembali ke Kursus
    </a>
    <span class="nav-section">Modul</span>
    @foreach($course->modules ?? [] as $module)
    <div style="padding:4px 12px;">
        <div style="font-size:11px;font-weight:700;color:var(--text-muted);margin-bottom:4px;text-transform:uppercase;letter-spacing:0.8px;">
            {{ $module->title }}
        </div>
        @foreach($module->lessons ?? [] as $item)
        <a href="{{ route('student.courses.lessons.show', [$course, $item]) }}"
           class="nav-item {{ $item->id === $lesson->id ? 'active' : '' }}"
           style="padding:6px 8px;font-size:13px;">
            @if(in_array($item->id, $completedLessonIds ?? []))
                <span style="color:var(--success);">✅</span>
            @else
                <span>▶️</span>
            @endif
            {{ Str::limit($item->title, 22) }}
        </a>
        @endforeach
    </div>
    @endforeach
@endsection

@section('content')

<div style="display:grid; grid-template-columns:1fr 320px; gap:24px; align-items:start;">

    {{-- Content --}}
    <div>
        <div class="card" style="margin-bottom:16px;">

            {{-- Video --}}
            @if($lesson->type === 'video' && $lesson->video_url)
            <div style="border-radius:12px; overflow:hidden; margin-bottom:20px; background:#000; aspect-ratio:16/9;">
                @if(Str::contains($lesson->video_url, ['youtube.com', 'youtu.be']))
                @php
                    preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $lesson->video_url, $matches);
                    $videoId = $matches[1] ?? '';
                @endphp
                <iframe width="100%" height="100%"
                    src="https://www.youtube.com/embed/{{ $videoId }}"
                    frameborder="0" allowfullscreen
                    style="display:block;"></iframe>
                @else
                <video controls style="width:100%;height:100%;">
                    <source src="{{ $lesson->video_url }}">
                </video>
                @endif
            </div>
            @endif

            {{-- Title --}}
            <h2 style="font-size:22px; font-weight:800; letter-spacing:-0.4px; margin-bottom:8px;">
                {{ $lesson->title }}
            </h2>
            <div style="font-size:13px; color:var(--text-muted); margin-bottom:20px;">
                📦 {{ $lesson->module->title ?? '' }} · {{ ucfirst($lesson->type ?? 'materi') }}
            </div>

            {{-- Body --}}
            @if($lesson->content)
            <div style="font-size:15px; line-height:1.75; color:var(--text);">
                {!! nl2br(e($lesson->content)) !!}
            </div>
            @endif

            {{-- File --}}
            @if($lesson->file_path)
            <div style="margin-top:20px; padding:14px 16px; background:var(--bg3); border-radius:10px; display:flex; align-items:center; gap:12px;">
                <span style="font-size:24px;">📎</span>
                <div>
                    <div style="font-size:13px; font-weight:600;">Materi Pendukung</div>
                    <a href="{{ Storage::url($lesson->file_path) }}" target="_blank"
                       style="font-size:12px; color:var(--accent);">Download File →</a>
                </div>
            </div>
            @endif
        </div>

        {{-- Navigation --}}
        <div style="display:flex; justify-content:space-between; gap:12px;">
            @if($prevLesson ?? null)
            <a href="{{ route('student.courses.lessons.show', [$course, $prevLesson]) }}"
               class="btn btn-secondary">← Materi Sebelumnya</a>
            @else
            <div></div>
            @endif

            @if($nextLesson ?? null)
            <a href="{{ route('student.courses.lessons.show', [$course, $nextLesson]) }}"
               class="btn btn-primary">Materi Berikutnya →</a>
            @else
            <a href="{{ route('student.courses.show', $course) }}"
               class="btn btn-success">✅ Selesai</a>
            @endif
        </div>
    </div>

    {{-- Sidebar --}}
    <div>
        {{-- Mark Complete --}}
        @php $isDone = in_array($lesson->id, $completedLessonIds ?? []); @endphp
        <div class="card" style="margin-bottom:16px;">
            <div style="font-size:14px; font-weight:600; margin-bottom:12px;">Status Materi</div>
            @if($isDone)
            <div style="display:flex; align-items:center; gap:8px; color:var(--success); margin-bottom:12px;">
                <span style="font-size:20px;">✅</span>
                <span style="font-size:14px; font-weight:600;">Sudah Selesai</span>
            </div>
            <form method="POST" action="{{ route('student.lessons.incomplete', $lesson) }}">
                @csrf
                <button type="submit" class="btn btn-secondary" style="width:100%;">
                    Tandai Belum Selesai
                </button>
            </form>
            @else
            <form method="POST" action="{{ route('student.lessons.complete', $lesson) }}">
                @csrf
                <button type="submit" class="btn btn-success" style="width:100%;">
                    ✅ Tandai Selesai
                </button>
            </form>
            @endif
        </div>

        {{-- Quiz in this module --}}
        @if(isset($moduleQuiz))
        <div class="card">
            <div style="font-size:14px; font-weight:600; margin-bottom:12px;">🧠 Quiz Modul Ini</div>
            <div style="font-size:13px; margin-bottom:4px; font-weight:600;">{{ $moduleQuiz->title }}</div>
            <div style="font-size:12px; color:var(--text-muted); margin-bottom:16px;">
                {{ $moduleQuiz->questions_count ?? 0 }} soal · Passing: {{ $moduleQuiz->passing_score }}%
            </div>
            <a href="{{ route('student.quiz.show', $moduleQuiz) }}" class="btn btn-primary" style="width:100%;">
                Kerjakan Quiz →
            </a>
        </div>
        @endif
    </div>

</div>

@endsection
