<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Kursus — LMSApp</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #0b0e1a; --bg2: #161b27; --bg3: #1e253a;
            --border: rgba(255,255,255,0.08);
            --text: #e8eaf0; --muted: #8892a4;
            --accent: #4f7eff; --accent2: #7c3aed;
            --radius: 12px; --radius-lg: 20px;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }
        nav {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 64px; border-bottom: 1px solid var(--border);
        }
        .logo { display:flex; align-items:center; gap:10px; font-size:17px; font-weight:800; text-decoration:none; color:var(--text); }
        .logo-icon { width:34px;height:34px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px; }
        .logo span { color: var(--accent); }
        .nav-links { display:flex; gap:10px; align-items:center; }
        .btn { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; border-radius:10px; font-size:13px; font-weight:600; font-family:inherit; border:none; cursor:pointer; text-decoration:none; transition:all 0.15s; }
        .btn-outline { border:1px solid var(--border); color:var(--text); }
        .btn-outline:hover { border-color:var(--accent); color:var(--accent); }
        .btn-fill { background:var(--accent); color:#fff; }
        .btn-fill:hover { background:#3a6aff; }

        .hero-small {
            padding: 56px 64px 40px;
            text-align: center;
        }
        h1 { font-size:36px; font-weight:800; letter-spacing:-0.8px; margin-bottom:8px; }
        .sub { color:var(--muted); font-size:16px; }

        .filter-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0 64px 32px;
            flex-wrap: wrap;
        }
        .filter-bar input {
            background: var(--bg2); border: 1px solid var(--border); border-radius: 10px;
            padding: 10px 16px; color: var(--text); font-family:inherit; font-size:14px;
            min-width: 280px;
        }
        .filter-bar input:focus { outline:none; border-color:var(--accent); }
        .filter-bar select {
            background: var(--bg2); border: 1px solid var(--border); border-radius: 10px;
            padding: 10px 14px; color: var(--text); font-family:inherit; font-size:14px;
        }
        .filter-bar select option { background: var(--bg2); }

        .courses-section { padding: 0 64px 80px; }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .course-card {
            background: var(--bg2); border: 1px solid var(--border);
            border-radius: var(--radius-lg); overflow:hidden;
            text-decoration:none; color:var(--text);
            transition:all 0.2s; display:flex; flex-direction:column;
        }
        .course-card:hover { border-color:rgba(79,126,255,0.35); transform:translateY(-3px); }
        .course-thumb {
            height:150px;
            background:linear-gradient(135deg, rgba(79,126,255,0.15), rgba(124,58,237,0.15));
            display:flex; align-items:center; justify-content:center; font-size:52px;
        }
        .course-body { padding:20px; flex:1; display:flex; flex-direction:column; }
        .course-cat { font-size:11px; font-weight:700; letter-spacing:0.8px; text-transform:uppercase; color:#7aa0ff; margin-bottom:8px; }
        .course-title { font-size:16px; font-weight:700; margin-bottom:8px; line-height:1.4; }
        .course-desc { font-size:13px; color:var(--muted); line-height:1.6; flex:1; margin-bottom:16px; }
        .course-footer {
            display:flex; justify-content:space-between; align-items:center;
            padding-top:12px; border-top:1px solid var(--border);
            font-size:12px; color:var(--muted);
        }
        .btn-dashboard {
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    color: #fff;
    padding: 9px 20px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 13px;
    text-decoration: none;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-dashboard:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 126, 255, 0.3);
    color: #fff;
}
        .enroll-btn {
            display:inline-flex; align-items:center; gap:6px;
            padding:8px 16px; background:var(--accent); border-radius:8px;
            color:#fff; font-size:13px; font-weight:600; text-decoration:none;
            transition:background 0.15s;
        }
        .enroll-btn:hover { background:#3a6aff; }

        .empty { text-align:center; padding:80px; color:var(--muted); }
        .empty div:first-child { font-size:56px; margin-bottom:12px; }

        footer { text-align:center; padding:24px; font-size:13px; color:var(--muted); border-top:1px solid var(--border); }
    </style>
</head>
<body>

<nav>
    <a href="{{ route('home') }}" class="logo">
        <div class="logo-icon">🎓</div>
        LMS<span>App</span>
    </a>
    <div class="nav-links">
        @auth
            @if(auth()->user()->hasRole('admin'))
    <a href="{{ route('admin.dashboard') }}" class="btn-dashboard">Dashboard →</a>
        @elseif(auth()->user()->hasRole('instructor'))
    <a href="{{ route('instructor.dashboard') }}" class="btn-dashboard">Dashboard →</a>
        @else
    <a href="{{ route('student.dashboard') }}" class="btn-dashboard">Dashboard →</a>
        @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-fill">Daftar</a>
        @endauth
    </div>
</nav>

<div class="hero-small">
    <h1>📚 Semua Kursus</h1>
    <p class="sub">Temukan kursus yang tepat untuk perjalanan belajarmu</p>
</div>

<div class="filter-bar">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;justify-content:center;">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="🔍 Cari kursus...">
        <select name="category" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories ?? [] as $cat)
            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-fill">Cari</button>
    </form>
</div>

<div class="courses-section">
    @if($courses->isEmpty())
    <div class="empty">
        <div>🔍</div>
        <div style="font-size:18px;font-weight:700;color:var(--text);margin-bottom:8px;">Kursus tidak ditemukan</div>
        <div>Coba kata kunci lain atau <a href="{{ route('courses.index') }}" style="color:var(--accent);">lihat semua kursus</a></div>
    </div>
    @else
    <div class="courses-grid">
        @foreach($courses as $course)
        <div class="course-card">
            <div class="course-thumb">
                @php
                    $emojis = ['💻','🎨','📊','🧠','🔬','📐','🌍','🎵','📷','✍️'];
                    echo $emojis[$course->id % count($emojis)];
                @endphp
            </div>
            <div class="course-body">
                <div class="course-cat">{{ $course->category ?? 'Umum' }}</div>
                <div class="course-title">{{ $course->title }}</div>
                <div class="course-desc">{{ Str::limit($course->description, 100) }}</div>
                <div class="course-footer">
                    <div>
                        <div>👤 {{ $course->instructor->name ?? 'Instruktur' }}</div>
                        <div>📦 {{ $course->modules_count ?? 0 }} Modul · 👥 {{ $course->enrollments_count ?? 0 }} Siswa</div>
                    </div>
                    @auth
                        @if($course->enrollments->where('user_id', auth()->id())->isNotEmpty())
                            <a href="{{ route('student.courses.show', $course) }}" class="enroll-btn" style="background:var(--success);">
                                Lanjut →
                            </a>
                        @else
                            <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                                @csrf
                                <button type="submit" class="enroll-btn">Daftar</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="enroll-btn">Masuk dulu</a>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($courses->hasPages())
    <div style="display:flex; justify-content:center; margin-top:40px;">
        {{ $courses->links() }}
    </div>
    @endif
    @endif
</div>

<footer>© {{ date('Y') }} LMSApp · Semangat Belajar! 🚀</footer>

</body>
</html>
