@extends('layouts.app')

@section('title', 'Hasil Quiz')
@section('page-title', 'Hasil Quiz')
@section('page-subtitle', $result->quiz->title ?? 'Quiz')

@section('sidebar-nav')
    <span class="nav-section">Navigasi</span>
    <a href="{{ route('student.dashboard') }}" class="nav-item">
        <span class="icon">🏠</span> Dashboard
    </a>
@endsection

@section('content')

<div style="max-width:640px; margin:0 auto;">

    {{-- Result Card --}}
    <div class="card" style="text-align:center; padding:48px 40px; margin-bottom:24px; position:relative; overflow:hidden;">

        {{-- BG decoration --}}
        <div style="position:absolute; top:-60px; left:50%; transform:translateX(-50%); width:300px; height:300px;
             background:radial-gradient(circle, {{ $result->passed ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)' }} 0%, transparent 65%);
             pointer-events:none;"></div>

        {{-- Icon --}}
        <div style="font-size:80px; margin-bottom:16px; line-height:1;">
            {{ $result->passed ? '🎉' : '😔' }}
        </div>

        {{-- Score --}}
        <div style="font-size:72px; font-weight:900; letter-spacing:-3px;
             color:{{ $result->passed ? 'var(--success)' : 'var(--danger)' }};
             line-height:1; margin-bottom:8px;">
            {{ $result->score }}%
        </div>

        <div style="font-size:20px; font-weight:700; margin-bottom:8px;">
            {{ $result->passed ? '🏆 Selamat, Kamu Lulus!' : 'Belum Lulus, Semangat!' }}
        </div>

        <div style="font-size:14px; color:var(--text-muted); margin-bottom:28px; max-width:380px; margin-left:auto; margin-right:auto; line-height:1.6;">
            @if($result->passed)
                Kamu berhasil melewati nilai minimum {{ $result->quiz->passing_score ?? 70 }}%.
                Teruskan semangat belajarmu!
            @else
                Nilai minimum adalah {{ $result->quiz->passing_score ?? 70 }}%.
                Pelajari kembali materinya dan coba lagi!
            @endif
        </div>

        {{-- Stats --}}
        <div style="display:flex; justify-content:center; gap:32px; margin-bottom:32px;">
            @php
                $total = $result->quiz->questions->count();
                $correct = round($result->score / 100 * $total);
            @endphp
            <div>
                <div style="font-size:24px; font-weight:800; color:var(--success);">{{ $correct }}</div>
                <div style="font-size:12px; color:var(--text-muted);">Benar</div>
            </div>
            <div style="width:1px; background:var(--border);"></div>
            <div>
                <div style="font-size:24px; font-weight:800; color:var(--danger);">{{ $total - $correct }}</div>
                <div style="font-size:12px; color:var(--text-muted);">Salah</div>
            </div>
            <div style="width:1px; background:var(--border);"></div>
            <div>
                <div style="font-size:24px; font-weight:800;">{{ $total }}</div>
                <div style="font-size:12px; color:var(--text-muted);">Total Soal</div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex; justify-content:center; gap:12px; flex-wrap:wrap;">
            @if(!$result->passed)
            <a href="{{ route('student.quiz.show', $result->quiz) }}" class="btn btn-primary">
                🔄 Coba Lagi
            </a>
            @endif
            <a href="{{ url()->previous() }}" class="btn btn-secondary">← Kembali ke Kursus</a>
            <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">🏠 Dashboard</a>
        </div>
    </div>

    {{-- Answer Review --}}
    @if($result->quiz->questions->isNotEmpty())
    <div class="card">
        <div style="font-size:16px; font-weight:700; margin-bottom:20px;">📋 Review Jawaban</div>

        @foreach($result->quiz->questions as $index => $question)
        @php
            $userAnswer  = $result->answers[$question->id] ?? null;
            $correctAnswer = $question->correct_answer ?? null;
            $isCorrect   = $userAnswer == $correctAnswer;
        @endphp
        <div style="padding:16px; border-radius:12px; margin-bottom:10px;
             background:{{ $isCorrect ? 'rgba(16,185,129,0.06)' : 'rgba(239,68,68,0.06)' }};
             border:1px solid {{ $isCorrect ? 'rgba(16,185,129,0.2)' : 'rgba(239,68,68,0.2)' }};">

            <div style="display:flex; gap:10px; margin-bottom:8px;">
                <div style="font-size:16px;">{{ $isCorrect ? '✅' : '❌' }}</div>
                <div style="font-size:14px; font-weight:600;">{{ $question->question }}</div>
            </div>

            @if($userAnswer !== null)
            <div style="font-size:12px; color:var(--text-muted);">
                Jawabanmu: <strong style="color:{{ $isCorrect ? 'var(--success)' : 'var(--danger)' }};">
                    {{ is_array($question->options ?? null) ? ($question->options[$userAnswer] ?? $userAnswer) : $userAnswer }}
                </strong>
                @if(!$isCorrect && $correctAnswer !== null)
                 · Jawaban benar: <strong style="color:var(--success);">
                    {{ is_array($question->options ?? null) ? ($question->options[$correctAnswer] ?? $correctAnswer) : $correctAnswer }}
                </strong>
                @endif
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

</div>

@endsection
