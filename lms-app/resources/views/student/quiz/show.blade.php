@extends('layouts.app')

@section('title', 'Quiz: ' . $quiz->title)
@section('page-title', $quiz->title)
@section('page-subtitle', 'Kerjakan semua soal dengan teliti')

@section('sidebar-nav')
    <span class="nav-section">Navigasi</span>
    <a href="{{ route('student.dashboard') }}" class="nav-item">
        <span class="icon">🏠</span> Dashboard
    </a>
@endsection

@section('content')

@if($lastResult)
<div class="alert {{ $lastResult->passed ? 'alert-success' : 'alert-danger' }}" style="margin-bottom:24px;">
    @if($lastResult->passed)
        🎉 Kamu sudah lulus quiz ini dengan skor {{ $lastResult->score }}%. Kamu bisa mengerjakan ulang.
    @else
        ❌ Percobaan terakhir: {{ $lastResult->score }}%. Skor minimum {{ $quiz->passing_score }}%. Coba lagi!
    @endif
</div>
@endif

<div style="display:grid; grid-template-columns:1fr 280px; gap:24px; align-items:start;">

    {{-- Quiz Form --}}
    <div>
        <form method="POST" action="{{ route('student.quiz.submit', $quiz) }}" id="quiz-form">
            @csrf

            @foreach($quiz->questions as $index => $question)
            <div class="card" style="margin-bottom:16px;" id="q-{{ $question->id }}">
                <div style="display:flex; gap:12px; margin-bottom:16px;">
                    <div style="width:28px;height:28px;background:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">
                        {{ $index + 1 }}
                    </div>
                    <div style="font-size:15px; font-weight:600; line-height:1.5;">
                        {{ $question->question }}
                    </div>
                </div>

                @if($question->type === 'multiple_choice')
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @foreach($question->options ?? [] as $key => $option)
                    <label style="display:flex; align-items:center; gap:12px; padding:12px 16px; background:var(--bg3); border-radius:10px; cursor:pointer; border:2px solid transparent; transition:border-color 0.15s;"
                           onmouseover="this.style.borderColor='rgba(79,126,255,0.3)'"
                           onmouseout="this.style.borderColor='transparent'">
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}"
                               required style="accent-color:var(--accent);width:16px;height:16px;">
                        <span style="font-size:14px;">{{ $option }}</span>
                    </label>
                    @endforeach
                </div>

                @elseif($question->type === 'true_false')
                <div style="display:flex; gap:10px;">
                    <label style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:var(--bg3); border-radius:10px; cursor:pointer; border:2px solid transparent; transition:border-color 0.15s;"
                           onmouseover="this.style.borderColor='rgba(16,185,129,0.4)'"
                           onmouseout="this.style.borderColor='transparent'">
                        <input type="radio" name="answers[{{ $question->id }}]" value="true"
                               style="accent-color:var(--success);width:16px;height:16px;">
                        <span style="font-size:15px; font-weight:600;">✅ Benar</span>
                    </label>
                    <label style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:var(--bg3); border-radius:10px; cursor:pointer; border:2px solid transparent; transition:border-color 0.15s;"
                           onmouseover="this.style.borderColor='rgba(239,68,68,0.4)'"
                           onmouseout="this.style.borderColor='transparent'">
                        <input type="radio" name="answers[{{ $question->id }}]" value="false"
                               style="accent-color:var(--danger);width:16px;height:16px;">
                        <span style="font-size:15px; font-weight:600;">❌ Salah</span>
                    </label>
                </div>

                @elseif($question->type === 'essay')
                <textarea name="answers[{{ $question->id }}]" class="form-control" rows="4"
                          placeholder="Tulis jawabanmu di sini..."
                          style="resize:vertical;"></textarea>
                @endif
            </div>
            @endforeach

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:8px;">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary" style="min-width:160px;">
                    🚀 Kumpulkan Jawaban
                </button>
            </div>
        </form>
    </div>

    {{-- Info Sidebar --}}
    <div>
        <div class="card" style="margin-bottom:16px;">
            <div style="font-size:14px; font-weight:700; margin-bottom:16px;">📋 Info Quiz</div>
            <div style="display:flex; flex-direction:column; gap:10px;">
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:var(--text-muted);">Total Soal</span>
                    <span style="font-weight:600;">{{ $quiz->questions->count() }} soal</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:var(--text-muted);">Nilai Minimum</span>
                    <span style="font-weight:600; color:var(--warning);">{{ $quiz->passing_score }}%</span>
                </div>
                @if($lastResult)
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:var(--text-muted);">Percobaan Terakhir</span>
                    <span style="font-weight:600; color:{{ $lastResult->passed ? 'var(--success)' : 'var(--danger)' }};">
                        {{ $lastResult->score }}%
                    </span>
                </div>
                @endif
            </div>
        </div>

        {{-- Progress indicator --}}
        <div class="card">
            <div style="font-size:14px; font-weight:700; margin-bottom:12px;">📊 Soal Terjawab</div>
            <div id="progress-counter" style="font-size:28px; font-weight:800; color:var(--accent);">
                0 / {{ $quiz->questions->count() }}
            </div>
            <div style="font-size:12px; color:var(--text-muted); margin-top:4px;">soal sudah dijawab</div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Track answered questions
    const total = {{ $quiz->questions->count() }};
    const counter = document.getElementById('progress-counter');

    function updateCounter() {
        const answered = new Set();
        document.querySelectorAll('input[type=radio]:checked, textarea').forEach(el => {
            const name = el.name;
            if (el.tagName === 'TEXTAREA' && el.value.trim()) answered.add(name);
            if (el.tagName === 'INPUT') answered.add(name);
        });
        counter.textContent = answered.size + ' / ' + total;
    }

    document.querySelectorAll('input, textarea').forEach(el => {
        el.addEventListener('change', updateCounter);
        el.addEventListener('input', updateCounter);
    });
</script>
@endpush

@endsection
