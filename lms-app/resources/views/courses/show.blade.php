<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} — LMSApp</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #0b0e1a; --bg2: #161b27; --bg3: #1e253a;
            --border: rgba(255,255,255,0.08);
            --text: #e8eaf0; --muted: #8892a4;
            --accent: #4f7eff; --accent2: #7c3aed; --success: #10b981;
            --radius: 12px; --radius-lg: 20px;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); }
        nav {
            display:flex; align-items:center; justify-content:space-between;
            padding:18px 64px; border-bottom:1px solid var(--border);
        }
        .logo { display:flex;align-items:center;gap:10px;font-size:17px;font-weight:800;text-decoration:none;color:var(--text); }
        .logo-icon { width:34px;height:34px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px; }
        .logo span { color:var(--accent); }
        .btn { display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:10px;font-size:14px;font-weight:600;font-family:inherit;border:none;cursor:pointer;text-decoration:none;transition:all 0.15s; }
        .btn-primary { background:var(--accent);color:#fff; }
        .btn-primary:hover { background:#3a6aff; }
        .btn-success { background:var(--success);color:#fff; }
        .btn-outline { border:1px solid var(--border);color:var(--text); }
        .btn-outline:hover { border-color:var(--accent);color:var(--accent); }

        .layout { display:grid; grid-template-columns:1fr 360px; gap:0; min-height:calc(100vh - 65px); }

        /* Hero */
        .course-hero {
            background:linear-gradient(135deg, rgba(79,126,255,0.08), rgba(124,58,237,0.08));
            padding:48px 64px;
            border-bottom:1px solid var(--border);
        }
        .breadcrumb { font-size:13px; color:var(--muted); margin-bottom:16px; }
        .breadcrumb a { color:var(--accent); text-decoration:none; }
        h1 { font-size:36px; font-weight:800; letter-spacing:-0.8px; margin-bottom:12px; line-height:1.2; }
        .course-meta-bar { display:flex; flex-wrap:wrap; gap:20px; font-size:13px; color:var(--muted); margin-top:16px; }
        .course-meta-bar span { display:flex;align-items:center;gap:6px; }

        /* Main */
        .course-main { padding:40px 64px; }
        .section-title { font-size:18px; font-weight:700; margin-bottom:16px; }
        .about-text { font-size:15px; line-height:1.75; color:var(--muted); margin-bottom:32px; }

        /* Curriculum */
        .module-item {
            background:var(--bg2); border:1px solid var(--border);
            border-radius:var(--radius-lg); margin-bottom:12px; overflow:hidden;
        }
        .module-header {
            padding:16px 20px; display:flex; align-items:center; justify-content:space-between;
            cursor:pointer; user-select:none;
        }
        .module-header:hover { background:rgba(255,255,255,0.02); }
        .module-title { font-size:15px; font-weight:700; display:flex;align-items:center;gap:10px; }
        .module-count { font-size:12px; color:var(--muted); }
        .lesson-list { border-top:1px solid var(--border); }
        .lesson-item {
            display:flex; align-items:center; gap:12px;
            padding:12px 20px; border-bottom:1px solid var(--border);
            font-size:13px;
        }
        .lesson-item:last-child { border-bottom:none; }
        .lesson-type { font-size:16px; width:24px; text-align:center; }

        /* Sidebar */
        .course-sidebar {
            border-left:1px solid var(--border);
            padding:32px 32px;
            position:sticky; top:0; height:fit-content;
        }
        .enroll-card {
            background:var(--bg2); border:1px solid var(--border);
            border-radius:var(--radius-lg); padding:24px; margin-bottom:20px;
        }
        .enroll-title { font-size:20px; font-weight:800; margin-bottom:6px; }
        .enroll-sub { font-size:13px; color:var(--muted); margin-bottom:20px; }
        .enroll-features { display:flex;flex-direction:column;gap:8px;margin-bottom:20px; }
        .enroll-features li { display:flex;align-items:center;gap:8px;font-size:13px;list-style:none; }
        .enroll-features li span:first-child { font-size:16px; }

        footer { text-align:center; padding:24px; font-size:13px; color:var(--muted); border-top:1px solid var(--border); }
    </style>
</head>
<body>

<nav>
    <a href="{{ route('home') }}" class="logo">
        <div class="logo-icon">🎓</div>
        LMS<span>App</span>
    </a>
    <div style="display:flex;gap:10px;">
        @auth
            <a href="#" class="btn btn-outline">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
        @endauth
    </div>
</nav>

{{-- Hero --}}
<div class="course-hero">
    <div class="breadcrumb">
        <a href="{{ route('courses.index') }}">Kursus</a> / {{ $course->title }}
    </div>
    <h1>{{ $course->title }}</h1>
    <p style="font-size:16px;color:var(--muted);max-width:680px;line-height:1.6;">
        {{ Str::limit($course->description, 200) }}
    </p>
    <div class="course-meta-bar">
        <span>👤 {{ $course->instructor->name ?? 'Instruktur' }}</span>
        <span>📦 {{ $course->modules->count() }} Modul</span>
        <span>👥 {{ $course->enrollments_count ?? 0 }} Siswa</span>
        @if($course->category)
        <span>🏷️ {{ $course->category }}</span>
        @endif
    </div>
</div>

<div class="layout">
    {{-- Main Content --}}
    <div class="course-main">

        {{-- About --}}
        <div class="section-title">📖 Tentang Kursus</div>
        <div class="about-text">{{ $course->description }}</div>

        {{-- Curriculum --}}
        <div class="section-title">📦 Kurikulum</div>
        @forelse($course->modules as $module)
        <div class="module-item">
            <div class="module-header" onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'block':'none'">
                <div class="module-title">
                    📦 {{ $module->title }}
                </div>
                <div class="module-count">{{ $module->lessons->count() }} materi</div>
            </div>
            <div class="lesson-list">
                @foreach($module->lessons as $lesson)
                <div class="lesson-item">
                    <span class="lesson-type">
                        @if($lesson->type === 'video') 🎬
                        @elseif($lesson->type === 'quiz') 🧠
                        @else 📄
                        @endif
                    </span>
                    <span>{{ $lesson->title }}</span>
                    <span style="margin-left:auto;font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">
                        {{ $lesson->type ?? 'materi' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div style="color:var(--muted);font-size:14px;padding:24px;text-align:center;">
            Belum ada modul tersedia.
        </div>
        @endforelse
    </div>

    {{-- Sidebar --}}
    <div class="course-sidebar">
        <div class="enroll-card">
            <div class="enroll-title">Mulai Belajar</div>
            <div class="enroll-sub">Akses semua materi kursus secara gratis</div>

            <ul class="enroll-features">
                <li><span>📦</span> {{ $course->modules->count() }} Modul pembelajaran</li>
                <li><span>📄</span> {{ $course->modules->sum(fn($m) => $m->lessons->count()) }} Materi lengkap</li>
                <li><span>🧠</span> Quiz di setiap modul</li>
                <li><span>📜</span> Sertifikat kelulusan</li>
                <li><span>♾️</span> Akses selamanya</li>
            </ul>

            @auth
                @php
                    $enrolled = $course->enrollments->where('user_id', auth()->id())->isNotEmpty();
                @endphp
                @if($enrolled)
                <a href="{{ route('student.courses.show', $course) }}" class="btn btn-success" style="width:100%;justify-content:center;">
                    ▶️ Lanjutkan Belajar
                </a>
                @else
                <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                        🚀 Daftar Kursus Gratis
                    </button>
                </form>
                @endif
            @else
            <a href="{{ route('login') }}" class="btn btn-primary" style="width:100%;justify-content:center;">
                🔑 Masuk untuk Daftar
            </a>
            @endauth
        </div>

        {{-- Instructor info --}}
        @if($course->instructor)
        <div style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;">
            <div style="font-size:13px;font-weight:700;margin-bottom:12px;color:var(--muted);">INSTRUKTUR</div>
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;">
                    {{ strtoupper(substr($course->instructor->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:15px;font-weight:700;">{{ $course->instructor->name }}</div>
                    <div style="font-size:12px;color:var(--muted);">Instruktur</div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<footer>© {{ date('Y') }} LMSApp 🎓</footer>

</body>
</html>
