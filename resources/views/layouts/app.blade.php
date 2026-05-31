<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — SIG Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        :root {
            --blue:       #1a6e9e;
            --blue-dark:  #0f4d80;
            --blue-mid:   #2d7fa8;
            --blue-light: #e8f2f9;
            --text:       #1a1a1a;
            --muted:      #6b7280;
            --border:     #ebebeb;
        }

        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f6f8;
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        /* ── TOPBAR ── */
        .topbar {
            position: sticky; top: 0; z-index: 50;
            height: 60px;
            background: rgba(255,255,255,0.97);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: box-shadow .3s ease;
        }
        .topbar.scrolled { box-shadow: 0 1px 16px rgba(0,0,0,0.06); }
        .topbar-inner {
            max-width: 1200px; margin: 0 auto;
            padding: 0 32px; height: 100%;
            display: flex; align-items: center; justify-content: space-between; gap: 24px;
        }

        /* ── LOGO ── */
        .topbar-logo {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; flex-shrink: 0;
        }
        .topbar-logo-mark {
            width: 32px; height: 32px;
            background: var(--blue);
            border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; transition: background .2s;
        }
        .topbar-logo:hover .topbar-logo-mark { background: var(--blue-dark); }
        .topbar-logo-mark span {
            font-family: 'Inter', sans-serif;
            font-size: 10px; font-weight: 700;
            color: #fff; letter-spacing: 0.05em;
        }
        .topbar-logo-text { display: flex; flex-direction: column; gap: 1px; }
        .topbar-logo-text strong {
            font-family: 'EB Garamond', serif;
            font-size: 16px; font-weight: 600;
            color: var(--text); line-height: 1;
            letter-spacing: 0.01em;
        }
        .topbar-logo-text small {
            font-size: 10px; color: var(--muted);
            font-weight: 400; letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* ── DIVIDER ── */
        .topbar-divider {
            width: 1px; height: 20px;
            background: var(--border); flex-shrink: 0;
        }

        /* ── NAV LINKS ── */
        .topbar-nav { display: flex; align-items: center; gap: 2px; }
        .topbar-nav a {
            display: flex; align-items: center; gap: 6px;
            padding: 7px 14px; border-radius: 4px;
            font-size: 13px; font-weight: 500;
            color: var(--muted); text-decoration: none;
            transition: color .15s, background .15s;
            position: relative; white-space: nowrap;
        }
        .topbar-nav a:hover { color: var(--blue); background: var(--blue-light); }
        .topbar-nav a.active {
            color: var(--blue); background: var(--blue-light);
            font-weight: 600;
        }
        .topbar-nav a.active::after {
            content: '';
            position: absolute; bottom: 4px;
            left: 50%; transform: translateX(-50%);
            width: 4px; height: 4px; border-radius: 50%;
            background: var(--blue);
        }
        .topbar-nav .nav-icon { width: 15px; height: 15px; flex-shrink: 0; }

        /* ── RIGHT SIDE ── */
        .topbar-right { display: flex; align-items: center; gap: 12px; }

        /* ── PROFILE BUTTON ── */
        .profile-btn {
            display: flex; align-items: center; gap: 9px;
            background: #fff; border: 1px solid var(--border);
            border-radius: 4px; padding: 6px 12px 6px 6px;
            cursor: pointer; transition: border-color .2s, box-shadow .2s;
            text-decoration: none; position: relative;
        }
        .profile-btn:hover {
            border-color: #c8dff0;
            box-shadow: 0 2px 8px rgba(26,110,158,0.08);
        }
        .profile-avatar {
            width: 28px; height: 28px; border-radius: 3px;
            background: var(--blue);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: #fff;
            flex-shrink: 0; overflow: hidden;
        }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .profile-info { display: flex; flex-direction: column; gap: 1px; }
        .profile-info strong {
            font-size: 12px; font-weight: 600; color: var(--text);
            line-height: 1;
        }
        .profile-info small {
            font-size: 10px; color: var(--muted);
            font-weight: 400; letter-spacing: 0.03em; text-transform: uppercase;
        }
        .profile-chevron {
            width: 14px; height: 14px; color: #bbb;
            transition: transform .2s;
        }

        /* ── DROPDOWN ── */
        .profile-dropdown-wrap { position: relative; }
        .profile-dropdown {
            position: absolute; right: 0; top: calc(100% + 8px);
            width: 220px;
            background: #fff; border: 1px solid var(--border);
            border-radius: 6px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            opacity: 0; visibility: hidden; transform: translateY(6px);
            transition: opacity .2s, visibility .2s, transform .2s;
            overflow: hidden; z-index: 200;
        }
        .profile-dropdown-wrap:hover .profile-dropdown,
        .profile-dropdown-wrap:focus-within .profile-dropdown {
            opacity: 1; visibility: visible; transform: translateY(0);
        }
        .dropdown-head {
            padding: 14px 16px;
            background: var(--blue-light);
            border-bottom: 1px solid #d8eaf5;
        }
        .dropdown-head p {
            font-size: 13px; font-weight: 600; color: var(--text);
            line-height: 1; margin-bottom: 3px;
        }
        .dropdown-head small {
            font-size: 11px; color: var(--muted); font-weight: 400;
        }
        .dropdown-body { padding: 6px; }
        .dropdown-item {
            display: flex; align-items: center; gap: 9px;
            padding: 9px 10px; border-radius: 4px;
            font-size: 13px; color: #444; text-decoration: none;
            font-weight: 500; transition: background .15s, color .15s;
        }
        .dropdown-item:hover { background: var(--blue-light); color: var(--blue); }
        .dropdown-item.danger { color: #b94040; }
        .dropdown-item.danger:hover { background: #fdecea; color: #c0392b; }
        .dropdown-sep { height: 1px; background: var(--border); margin: 4px 6px; }
        .dropdown-item span.di-icon { font-size: 14px; }
        .dropdown-item svg.di-icon { width: 14px; height: 14px; flex-shrink: 0; }

        /* ── HAMBURGER ── */
        .hamburger {
            display: none; flex-direction: column; gap: 5px;
            cursor: pointer; background: none; border: none; padding: 4px;
        }
        .hamburger span {
            width: 20px; height: 1.5px;
            background: var(--text); display: block; transition: background .2s;
        }
        .hamburger:hover span { background: var(--blue); }

        /* ── MOBILE MENU ── */
        .mobile-menu {
            display: none;
            position: fixed; top: 60px; left: 0; right: 0;
            background: #fff; z-index: 49;
            border-bottom: 1px solid var(--border);
            padding: 8px 16px 16px; flex-direction: column; gap: 2px;
            max-height: calc(100vh - 60px); overflow-y: auto;
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 12px; border-radius: 4px;
            font-size: 14px; font-weight: 500; color: #444;
            text-decoration: none; transition: color .15s, background .15s;
        }
        .mobile-menu a:hover { color: var(--blue); background: var(--blue-light); }
        .mobile-menu a.active { color: var(--blue); background: var(--blue-light); font-weight: 600; }
        .mobile-menu-sep { height: 1px; background: var(--border); margin: 6px 0; }

        /* ── MAIN CONTENT ── */
        .layout-main {
            max-width: 1200px; margin: 0 auto;
            padding: 28px 32px;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .topbar-inner { padding: 0 20px; }
            .layout-main { padding: 20px; }
            .topbar-divider { display: none; }
        }
        @media (max-width: 768px) {
            .topbar-nav { display: none; }
            .hamburger { display: flex; }
            .topbar-logo-text small { display: none; }
            .profile-info { display: none; }
            .profile-chevron { display: none; }
            .profile-btn { padding: 5px; }
            .layout-main { padding: 16px; }
        }
        @media (max-width: 480px) {
            .topbar-inner { padding: 0 16px; }
            .topbar-logo-text { display: none; }
        }
    </style>
</head>

<body>

    {{-- ── TOPBAR ── --}}
    <header class="topbar" id="mainTopbar">
        <div class="topbar-inner">

            {{-- LEFT --}}
            <div style="display:flex; align-items:center; gap:20px;">

                {{-- LOGO --}}
                <a href="/dashboard" class="topbar-logo">
                    <div class="topbar-logo-mark logo-unipma">
                        <img src="{{ asset('images/unipma-logo.png') }}" alt="Logo UNIPMA">
                    </div>

                    <div class="topbar-logo-text">
                        <strong>SIG Mahasiswa</strong>
                        <small>Fakultas Teknik · UNIPMA</small>
                    </div>
                </a>

                <div class="topbar-divider"></div>

                {{-- NAV DESKTOP --}}
                @php $path = request()->path(); @endphp
                <nav class="topbar-nav">

                    {{-- Dashboard: grid/chart --}}
                    @php $active = $path === 'dashboard' || str_starts_with($path, 'dashboard'); @endphp
                    <a href="/dashboard" class="{{ $active ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="7" height="7" rx="1.5"/>
                            <rect x="11" y="2" width="7" height="7" rx="1.5"/>
                            <rect x="2" y="11" width="7" height="7" rx="1.5"/>
                            <rect x="11" y="11" width="7" height="7" rx="1.5"/>
                        </svg>
                        Dashboard
                    </a>

                    {{-- Alamat: location pin --}}
                    @php $active = $path === 'alamat' || str_starts_with($path, 'alamat'); @endphp
                    <a href="/alamat" class="{{ $active ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 2a6 6 0 0 1 6 6c0 4.5-6 10-6 10S4 12.5 4 8a6 6 0 0 1 6-6z"/>
                            <circle cx="10" cy="8" r="2"/>
                        </svg>
                        Alamat
                    </a>

                    {{-- Sekolah: graduation cap --}}
                    @php $active = $path === 'sekolah' || str_starts_with($path, 'sekolah'); @endphp
                    <a href="/sekolah" class="{{ $active ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 8l8-4 8 4-8 4-8-4z"/>
                            <path d="M5 9.5V14c0 1.5 2.2 2.5 5 2.5s5-1 5-2.5V9.5"/>
                            <path d="M18 8v4"/>
                        </svg>
                        Sekolah
                    </a>

                    {{-- Minat: lightbulb / spark of interest --}}
                    @php $active = $path === 'minat' || str_starts_with($path, 'minat'); @endphp
                    <a href="/minat" class="{{ $active ? 'active' : '' }}">
                        <svg class="nav-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 2a5 5 0 0 1 3.5 8.5c-.6.6-1 1.4-1 2.1V13H7.5v-.4c0-.7-.4-1.5-1-2.1A5 5 0 0 1 10 2z"/>
                            <path d="M7.5 15.5h5M8.5 17.5h3"/>
                        </svg>
                        Minat
                    </a>

                </nav>

            </div>

            {{-- RIGHT --}}
            <div class="topbar-right">
                @php $user = auth()->user(); @endphp

                {{-- PROFILE DROPDOWN --}}
<div class="profile-dropdown-wrap">
    <div class="profile-btn" tabindex="0">
        {{-- Ukuran avatar disesuaikan (w-9 h-9 = 36px) dan dibuat bulat total --}}
        <div class="profile-avatar" style="width: 36px; height: 36px; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center; background: var(--blue); flex-shrink: 0;">
            @if($user->foto && file_exists(public_path('foto-profile/' . $user->foto)))
                <img src="{{ asset('foto-profile/' . $user->foto) }}" alt="Foto Profil" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($mahasiswa->nama ?? $user->name ?? 'M') }}&background=1a6e9e&color=fff&bold=true&size=72" alt="Inisial" style="width: 100%; height: 100%; object-fit: cover;">
            @endif
        </div>
        
        <div class="profile-info" style="display: flex; flex-direction: column; gap: 2px;">
            {{-- Mengambil nama panggilan/kata pertama saja (contoh: "Maizatul Lisa" menjadi "Maizatul") --}}
            <strong style="font-size: 13px; font-weight: 600; color: var(--text); line-height: 1.2;">
                {{ implode(' ', array_slice(explode(' ', $mahasiswa->nama ?? $user->name ?? 'Mahasiswa'), 0, 1)) }}
            </strong>
            <small style="font-size: 10px; color: var(--muted); font-weight: 500; letter-spacing: 0.03em; text-transform: uppercase; line-height: 1;">
                Mahasiswa
            </small>
        </div>
        
        <svg class="profile-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px; color: #bbb; flex-shrink: 0;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>

    <div class="profile-dropdown">
        <div class="dropdown-head">
            <p>{{ $mahasiswa->nama ?? $user->name ?? 'Mahasiswa' }}</p>
            <small>{{ $user->email ?? '-' }}</small>
        </div>
                        <div class="dropdown-body">
                            <a href="/profile" class="dropdown-item">
                                <svg class="di-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="10" cy="7" r="3.5"/>
                                    <path d="M3 17c0-3.3 3.1-6 7-6s7 2.7 7 6"/>
                                </svg>
                                Profil Saya
                            </a>
                            <div class="dropdown-sep"></div>
                            <form action="/logout" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item danger" style="width:100%; background:none; border:none; cursor:pointer; text-align:left;">
                                    <svg class="di-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M13 3h3a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-3"/>
                                        <path d="M9 14l4-4-4-4"/>
                                        <path d="M13 10H3"/>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- HAMBURGER --}}
                <button class="hamburger" onclick="toggleMobileMenu()" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>

            </div>
        </div>
    </header>

    {{-- ── MOBILE MENU ── --}}
    <div class="mobile-menu" id="mobileMenu">
        @php $path = request()->path(); @endphp

        @php $active = $path === 'dashboard' || str_starts_with($path, 'dashboard'); @endphp
        <a href="/dashboard" class="{{ $active ? 'active' : '' }}" onclick="toggleMobileMenu()">
            <svg style="width:16px;height:16px;flex-shrink:0;" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="7" height="7" rx="1.5"/><rect x="11" y="2" width="7" height="7" rx="1.5"/>
                <rect x="2" y="11" width="7" height="7" rx="1.5"/><rect x="11" y="11" width="7" height="7" rx="1.5"/>
            </svg>
            Dashboard
        </a>

        @php $active = $path === 'alamat' || str_starts_with($path, 'alamat'); @endphp
        <a href="/alamat" class="{{ $active ? 'active' : '' }}" onclick="toggleMobileMenu()">
            <svg style="width:16px;height:16px;flex-shrink:0;" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 2a6 6 0 0 1 6 6c0 4.5-6 10-6 10S4 12.5 4 8a6 6 0 0 1 6-6z"/>
                <circle cx="10" cy="8" r="2"/>
            </svg>
            Alamat
        </a>

        @php $active = $path === 'sekolah' || str_starts_with($path, 'sekolah'); @endphp
        <a href="/sekolah" class="{{ $active ? 'active' : '' }}" onclick="toggleMobileMenu()">
            <svg style="width:16px;height:16px;flex-shrink:0;" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 8l8-4 8 4-8 4-8-4z"/>
                <path d="M5 9.5V14c0 1.5 2.2 2.5 5 2.5s5-1 5-2.5V9.5"/>
                <path d="M18 8v4"/>
            </svg>
            Sekolah
        </a>

        @php $active = $path === 'minat' || str_starts_with($path, 'minat'); @endphp
        <a href="/minat" class="{{ $active ? 'active' : '' }}" onclick="toggleMobileMenu()">
            <svg style="width:16px;height:16px;flex-shrink:0;" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 2a5 5 0 0 1 3.5 8.5c-.6.6-1 1.4-1 2.1V13H7.5v-.4c0-.7-.4-1.5-1-2.1A5 5 0 0 1 10 2z"/>
                <path d="M7.5 15.5h5M8.5 17.5h3"/>
            </svg>
            Minat
        </a>

        <div class="mobile-menu-sep"></div>
        <a href="/profile" onclick="toggleMobileMenu()">
            <svg style="width:16px;height:16px;flex-shrink:0;" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="10" cy="7" r="3.5"/>
                <path d="M3 17c0-3.3 3.1-6 7-6s7 2.7 7 6"/>
            </svg>
            Profil Saya
        </a>
        <form action="/logout" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="dropdown-item danger" style="width:100%; background:none; border:none; cursor:pointer; padding:11px 12px; font-size:14px; font-weight:500; border-radius:4px; text-align:left; display:flex; align-items:center; gap:10px;">
                <svg style="width:16px;height:16px;flex-shrink:0;" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 3h3a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-3"/>
                    <path d="M9 14l4-4-4-4"/><path d="M13 10H3"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>

    {{-- ── MAIN CONTENT ── --}}
    <main class="layout-main">
        @yield('content')
    </main>

    @stack('scripts')
    <script>
        // Topbar scroll shadow
        window.addEventListener('scroll', () => {
            document.getElementById('mainTopbar').classList.toggle('scrolled', window.scrollY > 4);
        }, { passive: true });

        // Mobile menu
        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('open');
        }
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('mobileMenu');
            const btn = document.querySelector('.hamburger');
            if (menu.classList.contains('open') && !menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.remove('open');
            }
        });
    </script>

</body>
</html>