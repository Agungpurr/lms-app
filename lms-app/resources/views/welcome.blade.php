<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS App — Belajar Lebih Cerdas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Apply theme before CSS to prevent flash --}}
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.add('light');
        }
    </script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ── Dark mode (default) ── */
        :root {
            --bg:     #0b0e1a;
            --bg2:    #161b27;
            --accent: #4f7eff;
            --accent2:#7c3aed;
            --text:   #e8eaf0;
            --muted:  #8892a4;
            --border: rgba(255,255,255,0.08);
            --card-bg: rgba(255,255,255,0.03);
            --card-border: rgba(255,255,255,0.07);
            --nav-border: rgba(255,255,255,0.06);
            --btn-outline-border: rgba(255,255,255,0.15);
            --hero-secondary-border: rgba(255,255,255,0.12);
        }

        /* ── Light mode ── */
        html.light {
            --bg:     #f0f2f8;
            --bg2:    #ffffff;
            --accent: #4f7eff;
            --accent2:#7c3aed;
            --text:   #1a1d2e;
            --muted:  #6b7280;
            --border: rgba(0,0,0,0.08);
            --card-bg: rgba(0,0,0,0.02);
            --card-border: rgba(0,0,0,0.07);
            --nav-border: rgba(0,0,0,0.08);
            --btn-outline-border: rgba(0,0,0,0.2);
            --hero-secondary-border: rgba(0,0,0,0.15);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            transition: background 0.2s, color 0.2s;
        }

        /* ── Navbar ── */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 64px;
            border-bottom: 1px solid var(--nav-border);
        }
        .logo {
            display: flex; align-items: center; gap: 10px;
            font-size: 18px; font-weight: 800;
            text-decoration: none; color: var(--text);
        }
        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
        }
        .logo span { color: var(--accent); }
        .nav-links { display: flex; align-items: center; gap: 12px; }

        .btn-outline {
            padding: 9px 20px;
            border: 1px solid var(--btn-outline-border);
            border-radius: 10px;
            color: var(--text);
            text-decoration: none; font-size: 14px; font-weight: 600;
            transition: all 0.15s;
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }

        .btn-fill {
            padding: 9px 20px;
            background: var(--accent);
            border-radius: 10px; color: #fff;
            text-decoration: none; font-size: 14px; font-weight: 600;
            transition: background 0.15s;
        }
        .btn-fill:hover { background: #3a6aff; }

        .theme-toggle {
            background: var(--card-bg);
            border: 1px solid var(--btn-outline-border);
            border-radius: 10px; padding: 8px 12px;
            cursor: pointer; font-size: 15px; color: var(--text);
            transition: all 0.15s; line-height: 1;
        }
        .theme-toggle:hover { border-color: var(--accent); }

        /* ── Hero ── */
        .hero {
            text-align: center; padding: 100px 32px 80px;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; top: -100px; left: 50%;
            transform: translateX(-50%);
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(79,126,255,0.12) 0%, transparent 65%);
            pointer-events: none;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 16px;
            background: rgba(79,126,255,0.1);
            border: 1px solid rgba(79,126,255,0.25);
            border-radius: 100px; font-size: 13px; font-weight: 600;
            color: #7aa0ff; margin-bottom: 28px;
        }
        h1 {
            font-size: clamp(40px, 6vw, 68px); font-weight: 900;
            letter-spacing: -2px; line-height: 1.08;
            max-width: 780px; margin: 0 auto 24px;
        }
        h1 .gradient {
            background: linear-gradient(135deg, var(--accent) 0%, #a78bfa 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-sub {
            font-size: 18px; color: var(--muted);
            max-width: 520px; margin: 0 auto 40px; line-height: 1.65;
        }
        .hero-actions {
            display: flex; align-items: center; justify-content: center;
            gap: 12px; flex-wrap: wrap;
        }
        .btn-hero {
            padding: 14px 32px; border-radius: 12px;
            font-size: 15px; font-weight: 700;
            text-decoration: none; transition: all 0.15s;
        }
        .btn-hero-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #fff;
        }
        .btn-hero-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-hero-secondary {
            border: 1px solid var(--hero-secondary-border);
            color: var(--text);
        }
        .btn-hero-secondary:hover { background: rgba(128,128,128,0.08); }

        /* ── Features ── */
        .section { padding: 80px 64px; }
        .section-label {
            text-align: center; font-size: 12px; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
            color: var(--accent); margin-bottom: 12px;
        }
        .section-title {
            text-align: center; font-size: 36px; font-weight: 800;
            letter-spacing: -0.8px; margin-bottom: 48px;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px; max-width: 1100px; margin: 0 auto;
        }
        .feature-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px; padding: 28px;
            transition: border-color 0.2s, transform 0.2s;
        }
        .feature-card:hover { border-color: rgba(79,126,255,0.3); transform: translateY(-3px); }
        .feature-icon { font-size: 32px; margin-bottom: 16px; display: block; }
        .feature-title { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
        .feature-desc { font-size: 14px; color: var(--muted); line-height: 1.6; }

        /* ── Courses ── */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px; max-width: 1100px; margin: 0 auto;
        }
        .course-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px; overflow: hidden;
            text-decoration: none; color: var(--text);
            transition: all 0.2s; display: block;
        }
        .course-card:hover { border-color: rgba(79,126,255,0.3); transform: translateY(-3px); }
        .course-thumb {
            height: 140px;
            background: linear-gradient(135deg, rgba(79,126,255,0.2), rgba(124,58,237,0.2));
            display: flex; align-items: center; justify-content: center; font-size: 48px;
        }
        .course-body { padding: 20px; }
        .course-category {
            font-size: 11px; font-weight: 700; letter-spacing: 0.8px;
            text-transform: uppercase; color: #7aa0ff; margin-bottom: 8px;
        }
        .course-title { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
        .course-meta { display: flex; align-items: center; gap: 16px; font-size: 12px; color: var(--muted); }

        /* ── CTA ── */
        .cta-section {
            text-align: center; padding: 80px 32px;
            background: rgba(79,126,255,0.05);
            border-top: 1px solid rgba(79,126,255,0.1);
        }
        .cta-title { font-size: 40px; font-weight: 900; letter-spacing: -1px; margin-bottom: 16px; }
        .cta-sub { font-size: 16px; color: var(--muted); margin-bottom: 32px; }

        footer {
            text-align: center; padding: 24px; font-size: 13px;
            color: var(--muted); border-top: 1px solid var(--nav-border);
        }
    </style>
</head>
<body>

<nav>
    <a href="{{ route('home') }}" class="logo">
        <div class="logo-icon">🎓</div>
        LMS<span>App</span>
    </a>

    <div class="nav-links">
        <button id="theme-btn" class="theme-toggle" onclick="toggleTheme()">🌙</button>
        <a href="{{ route('courses.index') }}" class="btn-outline">Semua Kursus</a>
        @auth
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.dashboard') }}" class="btn-fill">Dashboard →</a>
            @elseif(auth()->user()->hasRole('instructor'))
                <a href="{{ route('instructor.dashboard') }}" class="btn-fill">Dashboard →</a>
            @else
                <a href="{{ route('student.dashboard') }}" class="btn-fill">Dashboard →</a>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn-outline">Masuk</a>
            <a href="{{ route('register') }}" class="btn-fill">Daftar Gratis</a>
        @endauth
    </div>
</nav>

<section class="hero">
    <div class="hero-badge">✨ Platform LMS Modern</div>
    <h1>Belajar Tanpa Batas, <span class="gradient">Raih Impianmu</span></h1>
    <p class="hero-sub">
        Platform pembelajaran online yang dirancang untuk memudahkan proses belajar mengajar
        dengan fitur modern dan pengalaman yang menyenangkan.
    </p>
    <div class="hero-actions">
        <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">Mulai Belajar Gratis →</a>
        <a href="{{ route('courses.index') }}" class="btn-hero btn-hero-secondary">Lihat Kursus</a>
    </div>
</section>

<section class="section">
    <p class="section-label">Kenapa LMSApp?</p>
    <h2 class="section-title">Semua yang kamu butuhkan</h2>
    <div class="features-grid">
        <div class="feature-card">
            <span class="feature-icon">📚</span>
            <div class="feature-title">Kursus Berkualitas</div>
            <p class="feature-desc">Ratusan kursus dari instruktur berpengalaman, siap membantumu berkembang.</p>
        </div>
        <div class="feature-card">
            <span class="feature-icon">🧠</span>
            <div class="feature-title">Quiz Interaktif</div>
            <p class="feature-desc">Uji pemahamanmu dengan quiz di setiap modul untuk memastikan progres belajar.</p>
        </div>
        <div class="feature-card">
            <span class="feature-icon">📜</span>
            <div class="feature-title">Sertifikat Resmi</div>
            <p class="feature-desc">Dapatkan sertifikat penyelesaian yang bisa kamu bagikan ke LinkedIn atau CV.</p>
        </div>
        <div class="feature-card">
            <span class="feature-icon">📊</span>
            <div class="feature-title">Pantau Progres</div>
            <p class="feature-desc">Lacak perkembangan belajarmu secara real-time dengan dashboard yang intuitif.</p>
        </div>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <p class="section-label">Populer</p>
    <h2 class="section-title">Kursus Unggulan</h2>
    <div class="courses-grid">
        @isset($featuredCourses)
            @foreach($featuredCourses as $course)
            <a href="{{ route('courses.show', $course) }}" class="course-card">
                <div class="course-thumb">📖</div>
                <div class="course-body">
                    <div class="course-category">{{ $course->category ?? 'Umum' }}</div>
                    <div class="course-title">{{ $course->title }}</div>
                    <div class="course-meta">
                        <span>👤 {{ $course->instructor->name ?? 'Instruktur' }}</span>
                        <span>📚 {{ $course->modules_count ?? 0 }} Modul</span>
                    </div>
                </div>
            </a>
            @endforeach
        @else
            <div class="course-card" style="pointer-events:none;">
                <div class="course-thumb">💻</div>
                <div class="course-body">
                    <div class="course-category">Teknologi</div>
                    <div class="course-title">Belajar Laravel dari Nol</div>
                    <div class="course-meta"><span>👤 Instruktur</span><span>📚 8 Modul</span></div>
                </div>
            </div>
            <div class="course-card" style="pointer-events:none;">
                <div class="course-thumb">🎨</div>
                <div class="course-body">
                    <div class="course-category">Desain</div>
                    <div class="course-title">UI/UX Design Fundamentals</div>
                    <div class="course-meta"><span>👤 Instruktur</span><span>📚 6 Modul</span></div>
                </div>
            </div>
            <div class="course-card" style="pointer-events:none;">
                <div class="course-thumb">📊</div>
                <div class="course-body">
                    <div class="course-category">Data</div>
                    <div class="course-title">Data Science dengan Python</div>
                    <div class="course-meta"><span>👤 Instruktur</span><span>📚 10 Modul</span></div>
                </div>
            </div>
        @endisset
    </div>
</section>

<section class="cta-section">
    <h2 class="cta-title">Siap mulai belajar?</h2>
    <p class="cta-sub">Bergabung dengan ribuan pelajar yang sudah berkembang bersama LMSApp.</p>
    <a href="{{ route('register') }}" class="btn-hero btn-hero-primary">Daftar Sekarang — Gratis</a>
</section>

<footer>© {{ date('Y') }} LMSApp. Dibuat dengan ❤️</footer>

<script>
    // Set icon sesuai tema saat ini
    const btn = document.getElementById('theme-btn');
    if (document.documentElement.classList.contains('light')) {
        btn.textContent = '☀️';
    }

    function toggleTheme() {
        const isLight = document.documentElement.classList.toggle('light');
        btn.textContent = isLight ? '☀️' : '🌙';
        localStorage.setItem('theme', isLight ? 'light' : 'dark');
    }
</script>

</body>
</html>