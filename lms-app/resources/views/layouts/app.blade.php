<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LMS App')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0f1117;
            --bg2:       #161b27;
            --bg3:       #1e253a;
            --border:    rgba(255,255,255,0.08);
            --text:      #e8eaf0;
            --text-muted:#8892a4;
            --accent:    #4f7eff;
            --accent2:   #7c3aed;
            --success:   #10b981;
            --warning:   #f59e0b;
            --danger:    #ef4444;
            --radius:    12px;
            --radius-lg: 20px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--bg2);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 28px 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-logo .icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }

        .sidebar-logo .brand {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .sidebar-logo .brand span { color: var(--accent); }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .nav-section {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: var(--text-muted);
            text-transform: uppercase;
            padding: 12px 12px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s;
        }

        .nav-item:hover {
            background: var(--bg3);
            color: var(--text);
        }

        .nav-item.active {
            background: rgba(79,126,255,0.12);
            color: var(--accent);
        }

        .nav-item .icon { font-size: 16px; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            background: var(--bg3);
        }

        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            font-weight: 700;
        }

        .user-info .name { font-size: 13px; font-weight: 600; }
        .user-info .role {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: capitalize;
        }

        .logout-btn {
            margin-left: auto;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
            border-radius: 6px;
            transition: color 0.15s;
        }
        .logout-btn:hover { color: var(--danger); }

        /* ── Main ── */
        .main-content {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            padding: 20px 32px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--bg);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .page-title {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.4px;
        }

        .page-title small {
            display: block;
            font-size: 13px;
            font-weight: 400;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .content-area {
            padding: 32px;
            flex: 1;
        }

        /* ── Cards ── */
        .card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
        }

        /* ── Stat Cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-icon.blue  { background: rgba(79,126,255,0.15); }
        .stat-icon.purple{ background: rgba(124,58,237,0.15); }
        .stat-icon.green { background: rgba(16,185,129,0.15); }
        .stat-icon.orange{ background: rgba(245,158,11,0.15); }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead th {
            text-align: left;
            padding: 10px 16px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: rgba(255,255,255,0.02); }

        /* ── Badges ── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-blue   { background: rgba(79,126,255,0.15); color: #7aa0ff; }
        .badge-purple { background: rgba(124,58,237,0.15); color: #a78bfa; }
        .badge-green  { background: rgba(16,185,129,0.15); color: #34d399; }
        .badge-orange { background: rgba(245,158,11,0.15); color: #fbbf24; }
        .badge-red    { background: rgba(239,68,68,0.15);  color: #f87171; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary:hover { background: #3a6aff; }

        .btn-secondary {
            background: var(--bg3);
            color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-secondary:hover { background: rgba(255,255,255,0.07); }

        .btn-danger {
            background: rgba(239,68,68,0.15);
            color: #f87171;
        }
        .btn-danger:hover { background: rgba(239,68,68,0.25); }

        .btn-success {
            background: var(--success);
            color: #fff;
        }
        .btn-success:hover { background: #059669; }

        .btn-sm { padding: 6px 12px; font-size: 12px; }

        /* ── Alert ── */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius);
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3); color: #34d399; }
        .alert-danger  { background: rgba(239,68,68,0.12);  border: 1px solid rgba(239,68,68,0.3);  color: #f87171; }
        .alert-info    { background: rgba(79,126,255,0.12); border: 1px solid rgba(79,126,255,0.3); color: #7aa0ff; }

        /* ── Form ── */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-muted);
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            font-family: inherit;
            font-size: 14px;
            transition: border-color 0.15s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--accent);
        }
        select.form-control option { background: var(--bg3); }
        .form-error { font-size: 12px; color: var(--danger); margin-top: 5px; }

        /* ── Progress Bar ── */
        .progress-bar-wrap {
            background: var(--bg3);
            border-radius: 100px;
            height: 8px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 100px;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            transition: width 0.5s ease;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="icon">🎓</div>
        <div class="brand">LMS<span>App</span></div>
    </div>

    <nav class="sidebar-nav">
        @yield('sidebar-nav')
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="user-info">
                <div class="name">{{ auth()->user()->name ?? 'User' }}</div>
                <div class="role">{{ auth()->user()->role ?? '' }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">⏻</button>
            </form>
        </div>
    </div>
</aside>

{{-- Main --}}
<div class="main-content">
    <div class="topbar">
        <div class="page-title">
            @yield('page-title', 'Dashboard')
            <small>@yield('page-subtitle', '')</small>
        </div>
        <div>@yield('topbar-actions')</div>
    </div>

    <div class="content-area">

        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">❌ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>