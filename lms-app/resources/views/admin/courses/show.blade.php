@extends('layouts.app')

@section('title', 'Detail Kursus')
@section('page-title', $course->title)
@section('page-subtitle', 'Detail kursus — Admin View')

@section('sidebar-nav')
    <span class="nav-section">Menu</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item">
        <span class="icon">👥</span> Kelola User
    </a>
    <a href="{{ route('admin.courses.index') }}" class="nav-item active">
        <span class="icon">📚</span> Kelola Kursus
    </a>
@endsection

@section('topbar-actions')
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-secondary">✏️ Edit</a>
        <form method="POST" action="{{ route('admin.courses.destroy', $course) }}"
              onsubmit="return confirm('Hapus kursus ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger">🗑️ Hapus</button>
        </form>
    </div>
@endsection

@section('content')

<div style="display:grid;grid-template-columns:1fr 280px;gap:24px;align-items:start;">
    <div>
        <div class="card" style="margin-bottom:20px;">
            <div style="font-size:16px;font-weight:700;margin-bottom:16px;">📋 Informasi Kursus</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:14px;">
                <div>
                    <div style="color:var(--text-muted);font-size:12px;margin-bottom:4px;">JUDUL</div>
                    <div style="font-weight:600;">{{ $course->title }}</div>
                </div>
                <div>
                    <div style="color:var(--text-muted);font-size:12px;margin-bottom:4px;">INSTRUKTUR</div>
                    <div style="font-weight:600;">{{ $course->instructor->name ?? '-' }}</div>
                </div>
                <div>
                    <div style="color:var(--text-muted);font-size:12px;margin-bottom:4px;">KATEGORI</div>
                    <div>{{ $course->category ?? '-' }}</div>
                </div>
                <div>
                    <div style="color:var(--text-muted);font-size:12px;margin-bottom:4px;">STATUS</div>
                    <div>
                        @if($course->is_published)
                            <span class="badge badge-green">Aktif</span>
                        @else
                            <span class="badge badge-orange">Draft</span>
                        @endif
                    </div>
                </div>
            </div>
            @if($course->description)
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                <div style="color:var(--text-muted);font-size:12px;margin-bottom:6px;">DESKRIPSI</div>
                <div style="font-size:14px;line-height:1.65;color:var(--text-muted);">{{ $course->description }}</div>
            </div>
            @endif
        </div>

        {{-- Enrolled students --}}
        <div class="card">
            <div style="font-size:16px;font-weight:700;margin-bottom:16px;">👥 Siswa Terdaftar</div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Terdaftar</th>
                            <th>Progres</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($course->enrollments ?? [] as $enrollment)
                        <tr>
                            <td style="font-weight:600;font-size:13px;">{{ $enrollment->user->name ?? '-' }}</td>
                            <td style="font-size:12px;color:var(--text-muted);">{{ $enrollment->user->email ?? '-' }}</td>
                            <td style="font-size:12px;color:var(--text-muted);">{{ $enrollment->created_at->format('d M Y') }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div style="flex:1;background:var(--bg3);border-radius:100px;height:6px;overflow:hidden;">
                                        <div style="width:{{ $enrollment->progress_percentage ?? 0 }}%;height:100%;background:linear-gradient(90deg,var(--accent),var(--accent2));border-radius:100px;"></div>
                                    </div>
                                    <span style="font-size:12px;font-weight:600;color:var(--accent);">{{ $enrollment->progress_percentage ?? 0 }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--text-muted);padding:32px;">Belum ada siswa terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div>
        <div class="card" style="margin-bottom:16px;">
            <div style="font-size:14px;font-weight:700;margin-bottom:16px;">📊 Statistik</div>
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:var(--text-muted);">Total Modul</span>
                    <span style="font-weight:700;">{{ $course->modules->count() }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:var(--text-muted);">Total Materi</span>
                    <span style="font-weight:700;">{{ $course->modules->sum(fn($m) => $m->lessons->count()) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:var(--text-muted);">Siswa Terdaftar</span>
                    <span style="font-weight:700;">{{ $course->enrollments->count() }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;">
                    <span style="color:var(--text-muted);">Dibuat</span>
                    <span style="font-weight:600;font-size:12px;">{{ $course->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
