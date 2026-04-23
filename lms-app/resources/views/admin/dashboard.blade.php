@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('sidebar-nav')
    <span class="nav-section">Menu</span>
    <a href="{{ route('admin.dashboard') }}" class="nav-item active">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" class="nav-item">
        <span class="icon">👥</span> Kelola User
    </a>
    <a href="{{ route('admin.courses.index') }}" class="nav-item">
        <span class="icon">📚</span> Kelola Kursus
    </a>
@endsection

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">👥</div>
        <div>
            <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
            <div class="stat-label">Total User</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple">📚</div>
        <div>
            <div class="stat-value">{{ $totalCourses ?? 0 }}</div>
            <div class="stat-label">Total Kursus</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">🎓</div>
        <div>
            <div class="stat-value">{{ $totalStudents ?? 0 }}</div>
            <div class="stat-label">Siswa Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange">📝</div>
        <div>
            <div class="stat-value">{{ $totalEnrollments ?? 0 }}</div>
            <div class="stat-label">Pendaftaran</div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

    {{-- Recent Users --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">👥 User Terbaru</div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers ?? [] as $user)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:30px;height:30px;background:linear-gradient(135deg,#4f7eff,#7c3aed);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-size:13px;font-weight:600;">{{ $user->name }}</div>
                                    <div style="font-size:11px;color:var(--text-muted);">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-red">Admin</span>
                            @elseif($user->role === 'instructor')
                                <span class="badge badge-purple">Instruktur</span>
                            @else
                                <span class="badge badge-blue">Siswa</span>
                            @endif
                        </td>
                        <td style="color:var(--text-muted);font-size:12px;">
                            {{ $user->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center;color:var(--text-muted);padding:32px;">
                            Belum ada user
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Courses --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">📚 Kursus Terbaru</div>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Instruktur</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCourses ?? [] as $course)
                    <tr>
                        <td style="font-size:13px;font-weight:600;">{{ $course->title }}</td>
                        <td style="font-size:12px;color:var(--text-muted);">
                            {{ $course->instructor->name ?? '-' }}
                        </td>
                        <td>
                            @if($course->is_published ?? false)
                                <span class="badge badge-green">Aktif</span>
                            @else
                                <span class="badge badge-orange">Draft</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center;color:var(--text-muted);padding:32px;">
                            Belum ada kursus
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection