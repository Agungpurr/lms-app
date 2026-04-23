@extends('layouts.app')

@section('title', 'Kelola Kursus')
@section('page-title', 'Kelola Kursus')
@section('page-subtitle', 'Pantau semua kursus di platform')

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

@section('content')

<div class="card">
    <div class="card-header">
        <div class="card-title">📚 Daftar Kursus</div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul Kursus</th>
                    <th>Instruktur</th>
                    <th>Modul</th>
                    <th>Siswa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses ?? [] as $course)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:600;font-size:14px;">{{ $course->title }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ Str::limit($course->description, 50) }}</div>
                    </td>
                    <td style="font-size:13px;">{{ $course->instructor->name ?? '-' }}</td>
                    <td style="font-size:13px;color:var(--text-muted);">{{ $course->modules_count ?? 0 }}</td>
                    <td style="font-size:13px;color:var(--text-muted);">{{ $course->enrollments_count ?? 0 }}</td>
                    <td>
                        @if($course->is_published ?? false)
                            <span class="badge badge-green">Aktif</span>
                        @else
                            <span class="badge badge-orange">Draft</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-secondary btn-sm">👁️</a>
                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-secondary btn-sm">✏️</a>
                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}"
                                  onsubmit="return confirm('Yakin hapus kursus ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px;">
                        Belum ada kursus
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($courses) && $courses->hasPages())
    <div style="padding:20px; display:flex; justify-content:flex-end;">
        {{ $courses->links() }}
    </div>
    @endif
</div>

@endsection
