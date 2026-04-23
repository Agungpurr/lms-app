@extends('layouts.app')

@section('title', $course->title)
@section('page-title', $course->title)
@section('page-subtitle', 'Kelola modul dan materi kursus')

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
    <div style="display:flex;gap:8px;">
        <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-secondary">✏️ Edit Kursus</a>
        <a href="{{ route('instructor.courses.modules.create', $course) }}" class="btn btn-primary">📦 Tambah Modul</a>
    </div>
@endsection

@section('content')

{{-- Info Bar --}}
<div class="card" style="margin-bottom:24px; display:flex; gap:32px; flex-wrap:wrap;">
    <div>
        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">STATUS</div>
        @if($course->is_published)
            <span class="badge badge-green">✅ Aktif</span>
        @else
            <span class="badge badge-orange">📝 Draft</span>
        @endif
    </div>
    <div>
        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">MODUL</div>
        <div style="font-size:18px;font-weight:700;">{{ $course->modules->count() }}</div>
    </div>
    <div>
        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">TOTAL MATERI</div>
        <div style="font-size:18px;font-weight:700;">{{ $course->modules->sum(fn($m) => $m->lessons->count()) }}</div>
    </div>
    <div>
        <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">SISWA</div>
        <div style="font-size:18px;font-weight:700;">{{ $course->enrollments_count ?? 0 }}</div>
    </div>
</div>

{{-- Modules --}}
@forelse($course->modules as $module)
<div class="card" style="margin-bottom:16px; padding:0; overflow:hidden;">
    {{-- Module Header --}}
    <div style="padding:18px 24px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid var(--border);">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:32px;height:32px;background:rgba(79,126,255,0.15);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;">📦</div>
            <div>
                <div style="font-size:15px;font-weight:700;">{{ $module->title }}</div>
                <div style="font-size:12px;color:var(--text-muted);">{{ $module->lessons->count() }} materi</div>
            </div>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('instructor.courses.modules.lessons.create', [$course, $module]) }}"
               class="btn btn-primary btn-sm">➕ Materi</a>
            <form method="POST" action="{{ route('instructor.courses.modules.destroy', [$course, $module]) }}"
                  onsubmit="return confirm('Hapus modul beserta semua materinya?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
            </form>
        </div>
    </div>

    {{-- Lessons --}}
    @forelse($module->lessons->sortBy('order') as $lesson)
    <div style="display:flex;align-items:center;gap:14px;padding:14px 24px;border-bottom:1px solid var(--border);">
        <div style="font-size:18px;width:24px;text-align:center;flex-shrink:0;">
            @if($lesson->type === 'video') 🎬
            @elseif($lesson->type === 'file') 📎
            @else 📄
            @endif
        </div>
        <div style="flex:1;">
            <div style="font-size:14px;font-weight:600;">{{ $lesson->title }}</div>
            <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;">{{ $lesson->type ?? 'teks' }}</div>
        </div>
        <div style="display:flex;gap:6px;flex-shrink:0;">
            <a href="{{ route('instructor.courses.modules.lessons.edit', [$course, $module, $lesson]) }}"
               class="btn btn-secondary btn-sm">✏️</a>
            <form method="POST" action="{{ route('instructor.courses.modules.lessons.destroy', [$course, $module, $lesson]) }}"
                  onsubmit="return confirm('Hapus materi ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
            </form>
        </div>
    </div>
    @empty
    <div style="padding:20px 24px;color:var(--text-muted);font-size:14px;">
        Belum ada materi. <a href="{{ route('instructor.courses.modules.lessons.create', [$course, $module]) }}" style="color:var(--accent);">Tambah sekarang →</a>
    </div>
    @endforelse
</div>
@empty
<div style="text-align:center;padding:64px;color:var(--text-muted);">
    <div style="font-size:56px;margin-bottom:16px;">📦</div>
    <div style="font-size:18px;font-weight:700;color:var(--text);margin-bottom:8px;">Belum ada modul</div>
    <div style="margin-bottom:24px;">Tambah modul pertama untuk kursus ini.</div>
    <a href="{{ route('instructor.courses.modules.create', $course) }}" class="btn btn-primary">📦 Tambah Modul</a>
</div>
@endforelse

@endsection
