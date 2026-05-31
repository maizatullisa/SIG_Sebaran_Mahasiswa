<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIG Mahasiswa — Fakultas Teknik UNIPMA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css"/>
    <style>
        :root {
            --blue: #1a6e9e;
            --blue-dark: #0f4d80;
            --blue-light: #e8f2f9;
            --blue-mid: #2d7fa8;
            --text: #1a1a1a;
            --muted: #6b7280;
            --border: #ebebeb;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; }
        body {
            font-family:'Inter',sans-serif;
            background:#fff;
            color:var(--text);
            -webkit-font-smoothing:antialiased;
            overflow-x:hidden;
        }

        /* ─── CUSTOM CURSOR ─── */
        .cursor {
            position:fixed; pointer-events:none; z-index:9999;
            width:8px; height:8px; background:var(--blue);
            border-radius:50%; transform:translate(-50%,-50%);
            transition:transform .1s ease, opacity .2s;
            mix-blend-mode:multiply;
        }
        .cursor-ring {
            position:fixed; pointer-events:none; z-index:9998;
            width:32px; height:32px;
            border:1px solid rgba(26,110,158,0.4);
            border-radius:50%; transform:translate(-50%,-50%);
            transition:all .18s ease;
        }
        .cursor-ring.hover { width:56px; height:56px; background:rgba(26,110,158,0.06); border-color:rgba(26,110,158,0.3); }
        @media (hover:none) { .cursor, .cursor-ring { display:none; } }

        /* ─── LOADING SCREEN ─── */
        #loader {
            position:fixed; inset:0; z-index:9990;
            background:#fff; display:flex; align-items:center; justify-content:center;
            flex-direction:column; gap:16px;
            transition:opacity .6s ease, visibility .6s ease;
        }
        #loader.hide { opacity:0; visibility:hidden; }
        .loader-wordmark {
            font-family:'EB Garamond',serif;
            font-size:clamp(28px,6vw,60px); font-weight:500; color:var(--blue);
            letter-spacing:-0.02em;
            overflow:hidden;
        }
        .loader-wordmark span { display:inline-block; animation:loaderReveal .8s cubic-bezier(0.16,1,0.3,1) both; }
        .loader-wordmark span:nth-child(1) { animation-delay:.0s; }
        .loader-wordmark span:nth-child(2) { animation-delay:.05s; }
        .loader-wordmark span:nth-child(3) { animation-delay:.1s; }
        .loader-wordmark span:nth-child(4) { animation-delay:.15s; }
        .loader-wordmark span:nth-child(5) { animation-delay:.2s; }
        @keyframes loaderReveal {
            from { transform:translateY(100%) rotateX(-60deg); opacity:0; }
            to   { transform:translateY(0) rotateX(0); opacity:1; }
        }
        .loader-bar-wrap {
            width:120px; height:1px; background:#eee; overflow:hidden; border-radius:1px;
        }
        .loader-bar {
            height:100%; background:var(--blue);
            animation:loaderBarAnim 1.2s cubic-bezier(0.65,0,0.35,1) forwards;
        }
        @keyframes loaderBarAnim {
            from { width:0; } to { width:100%; }
        }
        .loader-sub {
            font-size:11px; letter-spacing:0.14em; text-transform:uppercase;
            color:#bbb; font-weight:400;
            animation:fadeIn .8s .4s both ease;
        }

        /* ─── NAV ─── */
        .nav {
            position:fixed; top:0; width:100%; z-index:100;
            display:flex; align-items:center; justify-content:space-between;
            padding:0 48px; height:60px;
            background:rgba(255,255,255,0.97);
            border-bottom:1px solid var(--border);
            backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px);
            transition:box-shadow .3s ease, transform .4s cubic-bezier(0.16,1,0.3,1);
        }
        .nav.scrolled { box-shadow:0 1px 16px rgba(0,0,0,0.06); }
        .nav.hidden { transform:translateY(-100%); }
        .nav-logo {
            font-family:'EB Garamond',serif; font-size:19px; font-weight:600;
            color:var(--text); text-decoration:none;
            display:flex; align-items:center; gap:10px; flex-shrink:0;
        }
        .nav-logo-mark {
            width:30px; height:30px; background:var(--blue);
            display:flex; align-items:center; justify-content:center;
            border-radius:3px; flex-shrink:0;
            transition:background .2s, transform .3s cubic-bezier(0.34,1.56,0.64,1);
        }
        .nav-logo:hover .nav-logo-mark { background:var(--blue-dark); transform:rotate(90deg); }
        .nav-logo-mark span { color:#fff; font-size:10px; font-weight:700; letter-spacing:0.05em; font-family:'Inter',sans-serif; }
        .nav-links { display:flex; align-items:center; gap:28px; }
        .nav-links a {
            font-size:13px; font-weight:500; color:#555; text-decoration:none;
            transition:color .2s; position:relative; white-space:nowrap;
        }
        .nav-links a:not(.btn-nav):not(.btn-nav-outline)::after {
            content:''; position:absolute; bottom:-2px; left:0; right:0;
            height:1px; background:var(--blue);
            transform:scaleX(0); transform-origin:left; transition:transform .25s cubic-bezier(0.16,1,0.3,1);
        }
        .nav-links a:not(.btn-nav):not(.btn-nav-outline):hover::after { transform:scaleX(1); }
        .nav-links a:hover { color:var(--blue); }
        .btn-nav {
            position:relative; overflow:hidden;
            background:var(--blue); color:#fff !important;
            padding:8px 20px; border-radius:3px;
            font-size:13px !important; font-weight:600 !important;
            transition:background .2s, transform .15s !important; letter-spacing:0.01em;
        }
        .btn-nav:hover { background:var(--blue-dark) !important; transform:translateY(-2px) !important; }
        .btn-nav-outline {
            border:1.5px solid var(--blue); color:var(--blue) !important;
            padding:7px 18px; border-radius:3px;
            font-size:13px !important; font-weight:600 !important;
            transition:all .2s !important;
        }
        .btn-nav-outline:hover { background:var(--blue) !important; color:#fff !important; transform:translateY(-2px) !important; }

        /* shimmer */
        .btn-nav::before, .btn-hero-primary::before, .btn-about::before, .btn-cta::before {
            content:''; position:absolute; top:0; left:-100%; width:50%; height:100%;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,0.25),transparent);
            transform:skewX(-25deg);
        }
        .btn-nav:hover::before, .btn-hero-primary:hover::before,
        .btn-about:hover::before, .btn-cta:hover::before {
            left:150%; transition:left .6s ease-in-out;
        }

        /* ─── HAMBURGER ─── */
        .hamburger { display:none; flex-direction:column; gap:5px; cursor:pointer; padding:4px; background:none; border:none; }
        .hamburger span { width:22px; height:1.5px; background:var(--text); display:block; transition:all .3s cubic-bezier(0.16,1,0.3,1); }
        .hamburger.open span:nth-child(1) { transform:translateY(6.5px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity:0; transform:scaleX(0); }
        .hamburger.open span:nth-child(3) { transform:translateY(-6.5px) rotate(-45deg); }

        /* ─── MOBILE MENU ─── */
        .mobile-menu {
            display:none; position:fixed; top:60px; left:0; right:0;
            background:#fff; z-index:99; border-bottom:1px solid var(--border);
            padding:12px 24px; flex-direction:column; gap:0;
            max-height:calc(100vh - 60px); overflow-y:auto;
            transform:translateY(-10px); opacity:0;
            transition:transform .3s cubic-bezier(0.16,1,0.3,1), opacity .3s ease;
        }
        .mobile-menu.open { display:flex; transform:translateY(0); opacity:1; }
        .mobile-menu a { font-size:14px; font-weight:500; color:#444; text-decoration:none; padding:13px 0; border-bottom:1px solid var(--border); transition:color .15s, padding-left .2s; }
        .mobile-menu a:last-child { border-bottom:none; }
        .mobile-menu a:hover { color:var(--blue); padding-left:6px; }
        .mobile-menu .btn-mobile { background:var(--blue); color:#fff !important; padding:13px 0; border-radius:3px; text-align:center; margin-top:12px; font-weight:600; border:none !important; }

        /* ─── HERO ─── */
        .hero {
            position:relative; width:100%; height:100vh; min-height:560px;
            overflow:hidden; margin-top:60px;
        }
        .hero-photo {
            position:absolute; inset:0;
            background: url('/kampus.png') center center / cover no-repeat,
                linear-gradient(135deg, #0f4d80 0%, #1a6e9e 50%, #2d7fa8 100%);
            will-change:transform; transform:scale(1.08);
            transition:transform 12s ease-out;
        }
        .hero-photo.loaded { transform:scale(1.0); }
        .hero-overlay {
            position:absolute; inset:0;
            background:linear-gradient(to bottom, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.55) 40%, rgba(0,0,0,0.88) 100%);
        }

        /* PARTICLES */
        #hero-particles { position:absolute; inset:0; z-index:1; pointer-events:none; }
        .particle {
            position:absolute; border-radius:50%;
            background:rgba(255,255,255,0.15);
            animation:particleFloat linear infinite;
        }
        @keyframes particleFloat {
            0%   { transform:translateY(0) scale(1); opacity:.15; }
            50%  { opacity:.35; transform:translateY(-30px) scale(1.1); }
            100% { transform:translateY(0) scale(1); opacity:.15; }
        }

        /* HERO SCAN LINE */
        .hero-scanline {
            position:absolute; inset:0; z-index:2; pointer-events:none;
            background:repeating-linear-gradient(to bottom, transparent 0px, transparent 3px, rgba(255,255,255,0.012) 3px, rgba(255,255,255,0.012) 4px);
            animation:scanMove 8s linear infinite;
        }
        @keyframes scanMove {
            from { background-position-y: 0; }
            to   { background-position-y: 100px; }
        }

        .hero-wordmark {
            position:absolute; top:50%; left:50%; z-index:3;
            transform:translate(-50%,-50%);
            font-family:'EB Garamond',serif;
            font-size:clamp(64px,13vw,180px); font-weight:500;
            color:rgba(255,255,255,0.18); white-space:nowrap; letter-spacing:-0.02em;
            pointer-events:none; user-select:none; line-height:1;
            opacity:0; animation:heroWordmark 1.4s .3s cubic-bezier(0.16,1,0.3,1) forwards;
            text-shadow:0 0 80px rgba(79,195,247,0.2);
        }
        @keyframes heroWordmark {
            from { opacity:0; letter-spacing:0.2em; filter:blur(8px); }
            to   { opacity:1; letter-spacing:-0.02em; filter:blur(0); }
        }

        .hero-line {
            position:absolute; left:48px; right:48px; bottom:96px; height:1px;
            background:rgba(255,255,255,0.12); z-index:3;
            opacity:0; animation:lineExpand 1s 1s cubic-bezier(0.16,1,0.3,1) forwards;
        }
        @keyframes lineExpand {
            from { transform:scaleX(0); opacity:0; }
            to   { transform:scaleX(1); opacity:1; }
        }

        .hero-bottom {
            position:absolute; bottom:0; left:0; right:0; z-index:3;
            display:flex; align-items:flex-end; justify-content:space-between;
            padding:28px 48px;
            opacity:0; animation:fadeIn 1s .9s ease forwards;
        }
        .hero-year { font-size:10px; color:rgba(255,255,255,0.35); letter-spacing:0.18em; font-weight:400; text-transform:uppercase; flex-shrink:0; }
        .hero-caption { max-width:340px; text-align:right; }
        .hero-caption p { font-size:12px; color:rgba(255,255,255,0.55); line-height:1.8; font-weight:300; letter-spacing:0.01em; }

        .hero-topleft {
            position:absolute; top:28px; left:48px; z-index:3;
            display:flex; align-items:center; gap:12px;
            opacity:0; animation:slideInLeft .8s .5s cubic-bezier(0.16,1,0.3,1) forwards;
        }
        @keyframes slideInLeft {
            from { opacity:0; transform:translateX(-20px); }
            to   { opacity:1; transform:translateX(0); }
        }
        .hero-topleft-dot { width:5px; height:5px; border-radius:50%; background:rgba(255,255,255,0.45); animation:pulse 2s ease infinite; }
        @keyframes pulse { 0%,100% { transform:scale(1); opacity:.45; } 50% { transform:scale(1.5); opacity:.8; } }
        .hero-topleft span { font-size:10px; color:rgba(255,255,255,0.45); letter-spacing:0.14em; text-transform:uppercase; font-weight:400; }

        .hero-topright {
            position:absolute; top:28px; right:48px; z-index:3;
            font-family:'EB Garamond',serif; font-size:11px; color:rgba(255,255,255,0.25); letter-spacing:0.08em;
            opacity:0; animation:slideInRight .8s .5s cubic-bezier(0.16,1,0.3,1) forwards;
        }
        @keyframes slideInRight {
            from { opacity:0; transform:translateX(20px); }
            to   { opacity:1; transform:translateX(0); }
        }

        /* SCROLL INDICATOR */
        .scroll-indicator {
            position:absolute; bottom:32px; left:50%; transform:translateX(-50%); z-index:3;
            display:flex; flex-direction:column; align-items:center; gap:6px;
            opacity:0; animation:fadeIn 1s 1.5s ease forwards;
        }
        .scroll-indicator span { font-size:9px; letter-spacing:0.16em; text-transform:uppercase; color:rgba(255,255,255,0.35); }
        .scroll-mouse {
            width:18px; height:28px; border:1px solid rgba(255,255,255,0.25); border-radius:10px;
            position:relative; overflow:hidden;
        }
        .scroll-mouse::after {
            content:''; position:absolute; top:4px; left:50%; transform:translateX(-50%);
            width:2px; height:6px; background:rgba(255,255,255,0.5); border-radius:1px;
            animation:scrollDot 1.6s ease infinite;
        }
        @keyframes scrollDot {
            0%   { top:4px; opacity:1; }
            100% { top:14px; opacity:0; }
        }

        /* TYPEWRITER */
        .typewriter-wrap { display:inline; }
        .typewriter-cursor {
            display:inline-block; width:2px; height:1em; background:rgba(255,255,255,0.7);
            vertical-align:middle; margin-left:2px;
            animation:blink .9s step-end infinite;
        }
        @keyframes blink { 0%,100% { opacity:1; } 50% { opacity:0; } }

        /* ─── SCROLL REVEAL ─── */
        .reveal {
            opacity:0;
            transition:opacity .9s cubic-bezier(0.215,0.610,0.355,1), transform .9s cubic-bezier(0.215,0.610,0.355,1);
        }
        .reveal.from-bottom { transform:translateY(40px); }
        .reveal.from-left   { transform:translateX(-40px); }
        .reveal.from-right  { transform:translateX(40px); }
        .reveal.scale-up    { transform:scale(0.94); }
        .reveal.visible     { opacity:1; transform:translate(0) scale(1); }

        /* ─── STATS ─── */
        .stats { background:#fff; border-bottom:1px solid var(--border); }
        .stats-inner {
            max-width:1080px; margin:0 auto;
            display:grid; grid-template-columns:repeat(4,1fr);
        }
        .stat-item {
            padding:44px 36px; border-right:1px solid var(--border);
            transition:background .3s; cursor:default; position:relative; overflow:hidden;
        }
        .stat-item:last-child { border-right:none; }
        .stat-item::after {
            content:''; position:absolute; bottom:0; left:0; right:0;
            height:2px; background:var(--blue);
            transform:scaleX(0); transform-origin:left;
            transition:transform .4s cubic-bezier(0.16,1,0.3,1);
        }
        .stat-item:hover::after { transform:scaleX(1); }
        .stat-item:hover { background:#fafcff; }
        .stat-num {
            font-family:'EB Garamond',serif; font-size:52px; font-weight:400;
            color:var(--blue); line-height:1; margin-bottom:6px; transition:color .2s;
        }
        .stat-item:hover .stat-num { color:var(--blue-dark); }
        .stat-label { font-size:12px; color:var(--muted); font-weight:400; line-height:1.5; letter-spacing:0.02em; }

        /* ─── ABOUT ─── */
        .about {
            background:#fff; padding:0; overflow:hidden;
            border-top:1px solid var(--border); border-bottom:1px solid var(--border);
        }
        .about-inner { display:grid; grid-template-columns:1fr 1fr; min-height:520px; }
        .about-left {
            background:var(--blue); padding:88px 72px;
            display:flex; flex-direction:column; justify-content:center;
            border-right:1px solid rgba(255,255,255,0.15);
            position:relative; overflow:hidden;
        }
        /* animated bg grid on about-left */
        .about-left::before {
            content:''; position:absolute; inset:-20px;
            background-image: linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size:40px 40px;
            animation:gridDrift 20s linear infinite;
        }
        @keyframes gridDrift {
            from { transform:translate(0,0); }
            to   { transform:translate(40px,40px); }
        }
        .about-left > * { position:relative; z-index:1; }

        .about-tag {
            font-size:10px; letter-spacing:0.16em; text-transform:uppercase;
            color:rgba(255,255,255,0.6); font-weight:600; margin-bottom:18px;
            display:flex; align-items:center; gap:10px;
        }
        .about-tag::before { content:''; width:20px; height:1.5px; background:rgba(255,255,255,0.5); display:inline-block; }
        .about h2 {
            font-family:'EB Garamond',serif; font-size:clamp(24px,3vw,40px);
            font-weight:500; line-height:1.2; color:#fff; margin-bottom:18px;
        }
        .about h2 em { font-style:italic; color:rgba(255,255,255,0.55); }
        .about-desc { font-size:14px; color:rgba(255,255,255,0.72); line-height:1.85; font-weight:300; margin-bottom:32px; max-width:440px; }
        .btn-about {
            display:inline-block; background:#fff; color:var(--blue);
            padding:11px 26px; border-radius:3px; font-size:12px; font-weight:700;
            text-decoration:none; letter-spacing:0.06em; text-transform:uppercase;
            transition:opacity .2s, transform .2s cubic-bezier(0.34,1.56,0.64,1);
            width:fit-content; position:relative; overflow:hidden;
        }
        .btn-about:hover { opacity:0.9; transform:translateY(-3px) scale(1.02); }

        .about-map-wrap { position:relative; min-height:520px; overflow:hidden; }
        .about-map-wrap #about-map { position:absolute; inset:0; width:100%; height:100%; filter:saturate(0.85) contrast(1.05); }
        .map-vignette { position:absolute; inset:0; z-index:2; pointer-events:none; background:radial-gradient(ellipse at center, transparent 40%, rgba(10,30,55,0.45) 100%); }
        .map-stats-overlay { position:absolute; top:20px; right:20px; z-index:999; display:flex; flex-direction:column; gap:8px; pointer-events:none; }
        .map-stat-pill {
            background:rgba(10,30,55,0.82); border:1px solid rgba(255,255,255,0.12);
            backdrop-filter:blur(8px); padding:8px 14px; border-radius:4px;
            display:flex; align-items:center; gap:10px;
            animation:pillSlideIn .6s cubic-bezier(0.16,1,0.3,1) both;
        }
        .map-stat-pill:nth-child(2) { animation-delay:.1s; }
        @keyframes pillSlideIn {
            from { transform:translateX(20px); opacity:0; }
            to   { transform:translateX(0); opacity:1; }
        }
        .map-stat-dot { width:7px; height:7px; border-radius:50%; background:#4fc3f7; flex-shrink:0; box-shadow:0 0 6px rgba(79,195,247,0.7); animation:dotGlow 2s ease infinite; }
        .map-stat-dot.orange { background:#ffb347; box-shadow:0 0 6px rgba(255,179,71,0.7); }
        @keyframes dotGlow { 0%,100% { box-shadow:0 0 6px rgba(79,195,247,0.7); } 50% { box-shadow:0 0 12px rgba(79,195,247,1); } }
        .map-stat-text { font-size:11px; font-weight:500; color:rgba(255,255,255,0.88); letter-spacing:0.03em; font-family:'Inter',sans-serif; }
        .map-stat-text em { font-style:normal; font-weight:700; color:#fff; }
        .about-map-label {
            position:absolute; bottom:20px; left:20px; z-index:999;
            background:rgba(10,30,55,0.82); border:1px solid rgba(255,255,255,0.12);
            border-left:3px solid #4fc3f7; backdrop-filter:blur(8px);
            padding:10px 16px 10px 14px; border-radius:0 4px 4px 0; pointer-events:none;
            max-width:calc(100% - 40px);
            animation:pillSlideIn .6s .2s cubic-bezier(0.16,1,0.3,1) both;
        }
        .about-map-label span { display:block; }
        .about-map-label .label-title { font-size:12px; font-weight:600; color:#fff; font-family:'Inter',sans-serif; letter-spacing:0.02em; }
        .about-map-label .sublabel { font-size:10px; color:rgba(255,255,255,0.55); margin-top:2px; font-family:'Inter',sans-serif; letter-spacing:0.04em; text-transform:uppercase; }

        /* Leaflet overrides */
        .leaflet-popup-content-wrapper { background:rgba(10,30,55,0.92) !important; border:1px solid rgba(79,195,247,0.25) !important; border-radius:4px !important; box-shadow:0 4px 24px rgba(0,0,0,0.35) !important; color:#fff !important; }
        .leaflet-popup-tip { background:rgba(10,30,55,0.92) !important; }
        .leaflet-popup-content { margin:10px 14px !important; }
        .leaflet-popup-close-button { color:rgba(255,255,255,0.5) !important; }
        .leaflet-popup-close-button:hover { color:#fff !important; }
        .leaflet-control-zoom a { background:rgba(10,30,55,0.85) !important; border-color:rgba(255,255,255,0.12) !important; color:rgba(255,255,255,0.8) !important; }
        .leaflet-control-zoom a:hover { background:rgba(26,110,158,0.9) !important; color:#fff !important; }
        .leaflet-control-attribution { background:rgba(10,30,55,0.65) !important; color:rgba(255,255,255,0.4) !important; font-size:9px !important; }
        .leaflet-control-attribution a { color:rgba(255,255,255,0.55) !important; }
        @keyframes mapPulse { 0% { transform:scale(0.5); opacity:1; } 100% { transform:scale(2.5); opacity:0; } }
        .map-pulse-dot { position:relative; width:18px; height:18px; background:#4fc3f7; border-radius:50%; border:2px solid #fff; box-shadow:0 0 12px rgba(79,195,247,0.8); }
        .map-pulse-dot::after { content:''; position:absolute; top:-2px; left:-2px; width:18px; height:18px; border-radius:50%; border:2px solid #4fc3f7; animation:mapPulse 1.8s infinite ease-out; }

        /* ─── WORKFLOW ─── */
        .workflow { padding:88px 48px; background:#fafafa; border-bottom:1px solid var(--border); }
        .workflow-inner { max-width:1080px; margin:0 auto; }
        .workflow-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:40px; margin-top:48px; position:relative; }
        .workflow-connector { position:absolute; top:24px; left:48px; right:-20px; height:1px; border-top:1.5px dashed rgba(26,110,158,0.3); z-index:0; }
        .workflow-item:last-child .workflow-connector { display:none; }
        .workflow-circle {
            width:48px; height:48px; background:#fff;
            border:1.5px solid var(--blue); border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-family:'EB Garamond',serif; font-size:20px; font-weight:600; color:var(--blue);
            margin-bottom:20px; position:relative; z-index:2;
            box-shadow:0 2px 8px rgba(0,0,0,0.02);
            transition:background .3s cubic-bezier(0.34,1.56,0.64,1), color .3s, transform .3s cubic-bezier(0.34,1.56,0.64,1), box-shadow .3s;
        }
        .workflow-item:hover .workflow-circle {
            background:var(--blue); color:#fff;
            transform:scale(1.15) rotate(10deg);
            box-shadow:0 8px 24px rgba(26,110,158,0.3);
        }
        .workflow-item { position:relative; transition:transform .3s ease; }
        .workflow-item:hover { transform:translateY(-4px); }
        .workflow-item h3 { font-family:'EB Garamond',serif; font-size:20px; font-weight:500; margin-bottom:8px; transition:color .2s; }
        .workflow-item:hover h3 { color:var(--blue); }
        .workflow-item p { font-size:13px; color:var(--muted); line-height:1.7; font-weight:300; }

        /* ─── DIVIDER QUOTE ─── */
        .divider-quote { border-top:1px solid var(--border); padding:56px 48px; text-align:center; position:relative; overflow:hidden; }
        .divider-quote::before {
            content:'"'; font-family:'EB Garamond',serif; font-size:200px;
            color:rgba(26,110,158,0.04); position:absolute;
            top:-20px; left:50%; transform:translateX(-50%);
            pointer-events:none; line-height:1;
        }
        .divider-quote p {
            font-family:'EB Garamond',serif; font-size:clamp(17px,2.5vw,26px);
            font-weight:400; font-style:italic; color:#aaa; line-height:1.6;
            max-width:640px; margin:0 auto; position:relative;
        }

        /* ─── FEATURES ─── */
        .features { padding:88px 48px; border-top:1px solid var(--border); }
        .features-inner { max-width:1080px; margin:0 auto; }
        .features-header { margin-bottom:52px; }
        .features-tag { font-size:10px; letter-spacing:0.16em; text-transform:uppercase; color:var(--blue); font-weight:600; margin-bottom:12px; }
        .features-header h2 { font-family:'EB Garamond',serif; font-size:clamp(24px,3vw,40px); font-weight:500; color:var(--text); line-height:1.2; }
        .features-grid {
            display:grid; grid-template-columns:repeat(3,1fr);
            border-top:1px solid var(--border); border-left:1px solid var(--border);
        }
        .feature-card {
            background:#fff; padding:44px 36px;
            border-right:1px solid var(--border); border-bottom:1px solid var(--border);
            transition:background .2s, box-shadow .4s cubic-bezier(0.16,1,0.3,1), transform .3s cubic-bezier(0.34,1.56,0.64,1);
            position:relative; overflow:hidden; will-change:transform; transform-style:preserve-3d;
        }
        .feature-card::before {
            content:''; position:absolute; left:0; top:0; bottom:0; width:3px;
            background:var(--blue); transform:scaleY(0); transform-origin:bottom;
            transition:transform .4s cubic-bezier(0.16,1,0.3,1);
        }
        .feature-card:hover::before { transform:scaleY(1); }
        .feature-card:hover { background:#fafcff; box-shadow:0 12px 40px rgba(26,110,158,0.12); z-index:1; }
        .feature-card .card-shine {
            position:absolute; inset:0;
            background:radial-gradient(circle at var(--mx,50%) var(--my,50%), rgba(255,255,255,0.18) 0%, transparent 60%);
            opacity:0; transition:opacity .3s; pointer-events:none;
        }
        .feature-card:hover .card-shine { opacity:1; }
        /* ripple */
        .feature-card .ripple {
            position:absolute; border-radius:50%; background:rgba(26,110,158,0.12);
            transform:scale(0); animation:rippleAnim .6s ease-out forwards; pointer-events:none;
        }
        @keyframes rippleAnim {
            to { transform:scale(4); opacity:0; }
        }
        .feature-num { font-size:10px; letter-spacing:0.14em; text-transform:uppercase; color:#c8c8c8; font-weight:500; margin-bottom:20px; display:block; transition:color .2s; }
        .feature-card:hover .feature-num { color:var(--blue); }
        .feature-card h3 { font-family:'EB Garamond',serif; font-size:20px; font-weight:500; color:var(--text); margin-bottom:10px; transition:transform .3s; }
        .feature-card:hover h3 { transform:translateX(4px); }
        .feature-card p { font-size:13px; color:var(--muted); line-height:1.8; font-weight:300; }

        /* ─── CONTACT ─── */
        .contact { padding:88px 48px; border-top:1px solid var(--border); background:#fff; }
        .contact-inner { max-width:1080px; margin:0 auto; display:grid; grid-template-columns:4fr 6fr; gap:64px; }
        .contact-info h2 { font-family:'EB Garamond',serif; font-size:32px; font-weight:500; margin-bottom:16px; }
        .contact-info p { font-size:14px; color:var(--muted); line-height:1.7; font-weight:300; margin-bottom:32px; }
        .contact-details { display:flex; flex-direction:column; gap:20px; }
        .contact-meta-item { display:flex; flex-direction:column; gap:2px; transition:transform .2s; }
        .contact-meta-item:hover { transform:translateX(6px); }
        .contact-meta-item span.label { font-size:11px; text-transform:uppercase; letter-spacing:0.05em; color:var(--blue); font-weight:600; }
        .contact-meta-item span.val { font-size:14px; color:var(--text); font-weight:400; }
        .faq-accordion { display:flex; flex-direction:column; border-top:1px solid var(--border); }
        .faq-item { border-bottom:1px solid var(--border); padding:20px 0; }
        .faq-trigger { width:100%; display:flex; justify-content:space-between; align-items:center; background:none; border:none; text-align:left; cursor:pointer; padding:0; }
        .faq-trigger h4 { font-size:15px; font-weight:500; color:var(--text); transition:color .2s; }
        .faq-trigger:hover h4 { color:var(--blue); }
        .faq-icon { font-size:18px; color:var(--muted); transition:transform .35s cubic-bezier(0.34,1.56,0.64,1), color .2s; font-weight:300; flex-shrink:0; margin-left:12px; }
        .faq-item.active .faq-icon { transform:rotate(45deg) scale(1.1); color:var(--blue); }
        .faq-content { max-height:0; overflow:hidden; transition:max-height .5s cubic-bezier(0.16,1,0.3,1); }
        .faq-content p { font-size:13px; color:var(--muted); line-height:1.7; padding-top:12px; font-weight:300; }
        .faq-item.active .faq-content { max-height:200px; }

        /* ─── CTA ─── */
        .cta { background:var(--blue); padding:88px 48px; text-align:center; position:relative; overflow:hidden; }
        /* animated orbs */
        .cta-orb {
            position:absolute; border-radius:50%; pointer-events:none;
            background:rgba(255,255,255,0.05); animation:orbFloat ease-in-out infinite;
        }
        @keyframes orbFloat {
            0%,100% { transform:translateY(0) scale(1); }
            50%      { transform:translateY(-20px) scale(1.05); }
        }
        .cta h2 { font-family:'EB Garamond',serif; font-size:clamp(26px,4.5vw,50px); font-weight:500; color:#fff; line-height:1.2; margin-bottom:12px; position:relative; z-index:1; }
        .cta h2 em { font-style:italic; color:rgba(255,255,255,0.52); }
        .cta p { font-size:14px; color:rgba(255,255,255,0.72); margin-bottom:36px; font-weight:300; position:relative; z-index:1; }
        .btn-cta {
            display:inline-block; background:#fff; color:var(--blue); padding:14px 38px; border-radius:3px;
            font-size:13px; font-weight:700; text-decoration:none;
            transition:transform .3s cubic-bezier(0.34,1.56,0.64,1), opacity .2s, box-shadow .3s;
            letter-spacing:0.03em; position:relative; z-index:1; overflow:hidden;
        }
        .btn-cta:hover { opacity:0.95; transform:translateY(-4px) scale(1.03); box-shadow:0 16px 40px rgba(0,0,0,0.2); }

        /* ─── FOOTER ─── */
        footer { background:#fafafa; border-top:1px solid var(--border); padding:28px 48px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
        footer p { font-size:11px; color:#bbb; letter-spacing:0.06em; text-transform:uppercase; }
        footer nav { display:flex; gap:20px; }
        footer nav a { font-size:11px; color:#bbb; text-decoration:none; letter-spacing:0.06em; text-transform:uppercase; transition:color .15s; }
        footer nav a:hover { color:var(--blue); }

        /* ─── PROGRESS BAR ─── */
        #progress-bar {
            position:fixed; top:60px; left:0; height:2px; z-index:101;
            background:var(--blue); width:0%;
            transition:width .1s linear;
            box-shadow:0 0 8px rgba(26,110,158,0.5);
        }

        /* ─── BACK TO TOP ─── */
        #back-to-top {
            position:fixed; bottom:32px; right:32px; z-index:200;
            width:44px; height:44px; background:var(--blue); color:#fff;
            border:none; border-radius:50%; cursor:pointer; font-size:18px;
            display:flex; align-items:center; justify-content:center;
            opacity:0; transform:translateY(20px);
            transition:opacity .3s ease, transform .3s cubic-bezier(0.34,1.56,0.64,1), background .2s;
            box-shadow:0 4px 16px rgba(26,110,158,0.35);
            pointer-events:none;
        }
        #back-to-top.visible { opacity:1; transform:translateY(0); pointer-events:all; }
        #back-to-top:hover { background:var(--blue-dark); transform:translateY(-4px); }
        #back-to-top svg { width:16px; height:16px; stroke:#fff; stroke-width:2; fill:none; }

        @keyframes fadeIn { to { opacity:1; } }

        /* ════ RESPONSIVE ════ */
        @media (max-width:1024px) {
            .nav { padding:0 28px; }
            .about-left { padding:64px 48px; }
            .workflow { padding:64px 28px; }
            .features { padding:64px 28px; }
            .contact { padding:64px 28px; }
            .cta { padding:64px 28px; }
            footer { padding:24px 28px; }
        }
        @media (max-width:900px) {
            .about-inner { grid-template-columns:1fr; }
            .about-left { padding:52px 40px; border-right:none; border-bottom:1px solid rgba(255,255,255,0.15); }
            .about-map-wrap { min-height:360px; }
            .features-grid { grid-template-columns:repeat(2,1fr); }
            .contact-inner { grid-template-columns:1fr; gap:48px; }
        }
        @media (max-width:768px) {
            .nav { padding:0 20px; }
            .nav-links { display:none; }
            .hamburger { display:flex; }
            .hero { height:55vh; min-height:340px; }
            .hero-topleft { left:24px; top:20px; }
            .hero-topleft span { font-size:9px; }
            .hero-topright { right:24px; top:20px; }
            .hero-line { left:24px; right:24px; bottom:80px; }
            .hero-bottom { padding:20px 24px; flex-direction:column; align-items:flex-start; gap:8px; }
            .hero-caption { text-align:left; max-width:100%; }
            .stats-inner { grid-template-columns:repeat(2,1fr); }
            .stat-item { padding:28px 20px; border-bottom:1px solid var(--border); }
            .stat-item:nth-child(2) { border-right:none; }
            .stat-item:nth-child(3) { border-bottom:none; }
            .stat-item:nth-child(4) { border-right:none; border-bottom:none; }
            .stat-num { font-size:42px; }
            .about-left { padding:44px 24px; }
            .about-map-wrap { min-height:300px; }
            .workflow { padding:52px 20px; }
            .workflow-grid { grid-template-columns:1fr; gap:32px; }
            .workflow-connector { display:none; }
            .features { padding:52px 20px; }
            .features-grid { grid-template-columns:1fr; }
            .feature-card { padding:32px 24px; }
            .contact { padding:52px 20px; }
            .contact-info h2 { font-size:26px; }
            .divider-quote { padding:40px 20px; }
            .cta { padding:52px 20px; }
            .btn-cta { display:block; text-align:center; }
            footer { padding:20px; flex-direction:column; align-items:flex-start; }
            #back-to-top { bottom:20px; right:20px; width:38px; height:38px; }
            .scroll-indicator { display:none; }
        }
        @media (max-width:480px) {
            .hero-wordmark { font-size:20vw; }
            .stat-num { font-size:36px; }
            .stat-item { padding:22px 16px; }
            .about-left { padding:36px 20px; }
            .about h2 { font-size:22px; }
            .nav-logo span:not(.nav-logo-mark span) { display:none; }
            .nav-logo { gap:0; }
            .cta h2 { font-size:24px; }
        }
        @media (max-width:360px) {
            .stats-inner { grid-template-columns:1fr; }
            .stat-item { border-right:none !important; border-bottom:1px solid var(--border) !important; }
            .stat-item:last-child { border-bottom:none !important; }
        }
        @media (hover:none) {
            .feature-card { transform:none !important; }
        }
    </style>
</head>
<body>

    <!-- CURSOR -->
    <div class="cursor" id="cursor"></div>
    <div class="cursor-ring" id="cursorRing"></div>

    <!-- LOADER -->
    <div id="loader">
        <div class="loader-wordmark">
            <span>U</span><span>N</span><span>I</span><span>P</span><span>M</span><span>A</span>
        </div>
        <div class="loader-bar-wrap"><div class="loader-bar"></div></div>
        <div class="loader-sub">Memuat Sistem Informasi Geospasial</div>
    </div>

    <!-- PROGRESS BAR -->
    <div id="progress-bar"></div>

    <!-- BACK TO TOP -->
    <button id="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Kembali ke atas">
        <svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
    </button>

    <header class="nav" id="mainNav">
        <a href="/" class="nav-logo">
            <div class="nav-logo-mark">
                <img src="{{ asset('images/unipma-logo.png') }}" alt="Logo UNIPMA" style="width:100%;height:100%;object-fit:contain;">
            </div>
            SIG Mahasiswa
        </a>
        <nav class="nav-links">
            <a href="#tentang">Tentang</a>
            <a href="#alur">Alur</a>
            <a href="#fitur">Fitur</a>
            <a href="#bantuan">Bantuan</a>
            <a href="/login" class="btn-nav-outline">Masuk</a>
            <a href="/register" class="btn-nav">Daftar</a>
        </nav>
        <button class="hamburger" id="hamburgerBtn" onclick="toggleMenu()" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </header>

    <div class="mobile-menu" id="mobileMenu">
        <a href="#tentang" onclick="closeMenu()">Tentang</a>
        <a href="#alur" onclick="closeMenu()">Alur</a>
        <a href="#fitur" onclick="closeMenu()">Fitur</a>
        <a href="#bantuan" onclick="closeMenu()">Bantuan</a>
        <a href="/login" onclick="closeMenu()">Masuk</a>
        <a href="/register" class="btn-mobile" onclick="closeMenu()">Daftar Sekarang</a>
    </div>

    <section class="hero" id="heroSection">
        <div class="hero-photo" id="heroPhoto"></div>
        <div class="hero-overlay"></div>
        <canvas id="hero-particles"></canvas>
        <div class="hero-scanline"></div>
        <div class="hero-wordmark">UNIPMA</div>
        <div class="hero-topleft">
            <div class="hero-topleft-dot"></div>
            <span>Fakultas Teknik · Universitas PGRI Madiun</span>
        </div>
        <div class="hero-line"></div>
        <div class="hero-bottom">
            <div class="hero-year">Sistem Informasi Geospasial</div>
            <div class="hero-caption">
                <p id="hero-typewriter"></p>
            </div>
        </div>
        <div class="scroll-indicator">
            <div class="scroll-mouse"></div>
            <span>Scroll</span>
        </div>
    </section>

    <section class="stats">
        <div class="stats-inner">
            <div class="stat-item reveal from-bottom">
                <div class="stat-num" data-target="324">0</div>
                <div class="stat-label">Mahasiswa Terdaftar</div>
            </div>
            <div class="stat-item reveal from-bottom" style="transition-delay:.08s">
                <div class="stat-num" data-target="47">0</div>
                <div class="stat-label">Kota Asal</div>
            </div>
            <div class="stat-item reveal from-bottom" style="transition-delay:.16s">
                <div class="stat-num" data-target="12">0</div>
                <div class="stat-label">Angkatan Aktif</div>
            </div>
            <div class="stat-item reveal from-bottom" style="transition-delay:.24s">
                <div class="stat-num" data-target="96" data-suffix="%">0%</div>
                <div class="stat-label">Data Terverifikasi</div>
            </div>
        </div>
    </section>

    <section class="about" id="tentang">
        <div class="about-inner">
            <div class="about-left">
                <div class="about-tag reveal from-left">Tentang Platform</div>
                <h2 class="reveal from-left" style="transition-delay:.08s">Data mahasiswa terpetakan untuk <em>mendukung penelitian</em> Fakultas.</h2>
                <p class="about-desc reveal from-left" style="transition-delay:.16s">Platform ini memungkinkan mahasiswa memasukkan data lokasi tempat tinggal, asal sekolah, jurusan SMA/SMK, hingga minat dan bakat semasa sekolah — semua dalam satu sistem yang terintegrasi.</p>
                <a href="/register" class="btn-about reveal from-left" style="transition-delay:.24s">Mulai Sekarang</a>
            </div>
            <div class="about-map-wrap reveal from-right">
                <div id="about-map"></div>
                <div class="map-vignette"></div>
                <div class="map-stats-overlay">
                    <div class="map-stat-pill">
                        <div class="map-stat-dot"></div>
                        <span class="map-stat-text"><em>5</em> Klaster Aktif</span>
                    </div>
                    <div class="map-stat-pill">
                        <div class="map-stat-dot orange"></div>
                        <span class="map-stat-text"><em>195</em> Titik Sebaran</span>
                    </div>
                </div>
                <div class="about-map-label">
                    <span class="label-title">Gedung Fakultas Teknik</span>
                    <span class="sublabel">Universitas PGRI Madiun</span>
                </div>
            </div>
        </div>
    </section>

    <section class="workflow" id="alur">
        <div class="workflow-inner">
            <div class="features-header reveal from-bottom">
                <div class="features-tag">Alur Penggunaan</div>
                <h2>3 Langkah Mudah Terintegrasi</h2>
            </div>
            <div class="workflow-grid">
                <div class="workflow-item reveal from-bottom" style="transition-delay:0s">
                    <div class="workflow-connector"></div>
                    <div class="workflow-circle">1</div>
                    <h3>Buat &amp; Masuk Akun</h3>
                    <p>Daftarkan diri Anda menggunakan NIM aktif. Pastikan data kredensial awal sesuai dengan profil mahasiswa resmi Anda.</p>
                </div>
                <div class="workflow-item reveal from-bottom" style="transition-delay:0.12s">
                    <div class="workflow-connector"></div>
                    <div class="workflow-circle">2</div>
                    <h3>Isi Informasi Spasial</h3>
                    <p>Gunakan peta interaktif di dasbor untuk menandai koordinat tempat tinggal, mengisi data asal sekolah, serta preferensi minat.</p>
                </div>
                <div class="workflow-item reveal from-bottom" style="transition-delay:0.24s">
                    <div class="workflow-circle">3</div>
                    <h3>Verifikasi &amp; Selesai</h3>
                    <p>Tim admin fakultas akan memvalidasi data Anda. Setelah disetujui, titik profil Anda akan langsung berkontribusi pada peta sebaran.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="features" id="fitur">
        <div class="features-inner">
            <div class="features-header reveal from-bottom">
                <div class="features-tag">Fitur Utama</div>
                <h2>Apa yang bisa kamu lakukan?</h2>
            </div>
            <div class="features-grid">
                <div class="feature-card reveal scale-up" style="transition-delay:.0s">
                    <div class="card-shine"></div>
                    <span class="feature-num">01</span>
                    <h3>Lokasi Tinggal</h3>
                    <p>Tandai lokasi tempat tinggalmu di peta interaktif berdasarkan alamat yang terdaftar.</p>
                </div>
                <div class="feature-card reveal scale-up" style="transition-delay:.12s">
                    <div class="card-shine"></div>
                    <span class="feature-num">02</span>
                    <h3>Asal Sekolah</h3>
                    <p>Simpan data sekolah dan jurusan saat SMA/SMK sebagai rekam jejak akademik.</p>
                </div>
                <div class="feature-card reveal scale-up" style="transition-delay:.24s">
                    <div class="card-shine"></div>
                    <span class="feature-num">03</span>
                    <h3>Minat &amp; Bakat</h3>
                    <p>Rekam minat dan bakat semasa SMA untuk melengkapi data profil akademikmu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="contact" id="bantuan">
        <div class="contact-inner">
            <div class="contact-info reveal from-left">
                <div class="features-tag">Bantuan</div>
                <h2>Hubungi Kami</h2>
                <p>Memiliki pertanyaan mengenai validasi data geospasial atau mengalami kendala teknis saat memetakan lokasi? Hubungi unit administrasi kami.</p>
                <div class="contact-details">
                    <div class="contact-meta-item">
                        <span class="label">Sekretariat</span>
                        <span class="val">Gedung Kampus 1, Lantai 2 Kampus UNIPMA</span>
                    </div>
                    <div class="contact-meta-item">
                        <span class="label">E-Mail Resmi</span>
                        <span class="val">ft@unipma.ac.id</span>
                    </div>
                </div>
            </div>
            <div class="faq-accordion reveal from-right">
                <div class="faq-item">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        <h4>Bagaimana cara menandai lokasi jika tidak akurat?</h4>
                        <span class="faq-icon">+</span>
                    </button>
                    <div class="faq-content">
                        <p>Anda dapat memperbesar (zoom-in) peta dasbor semaksimal mungkin, lalu geser pin (drag marker) tepat di atas atap lokasi rumah atau kos Anda untuk mendapatkan akurasi koordinat terbaik.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-trigger" onclick="toggleFaq(this)">
                        <h4>Apakah data lokasi spesifik saya aman?</h4>
                        <span class="faq-icon">+</span>
                    </button>
                    <div class="faq-content">
                        <p>Ya, sistem publik hanya menampilkan agregasi wilayah dalam bentuk peta klaster (clustering) atau peta panas (heatmap). Detail alamat privat Anda dilindungi secara ketat.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="divider-quote reveal from-bottom">
        <p>"Transformasi data geospasial yang akurat demi pengembangan mutu akademik Fakultas Teknik yang lebih responsif."</p>
    </div>

    <section class="cta">
        <div class="cta-orb" style="width:300px;height:300px;top:-80px;right:-60px;animation-duration:8s;animation-delay:0s;"></div>
        <div class="cta-orb" style="width:200px;height:200px;bottom:-60px;left:-40px;animation-duration:11s;animation-delay:2s;"></div>
        <div class="cta-orb" style="width:120px;height:120px;top:40%;left:15%;animation-duration:14s;animation-delay:1s;"></div>
        <h2 class="reveal from-bottom">Siap bergabung?<br><em>Daftarkan akunmu sekarang.</em></h2>
        <p class="reveal from-bottom" style="transition-delay:.1s">Lengkapi data profilmu sebagai bagian dari sistem sebaran mahasiswa.</p>
        <a href="/register" class="btn-cta reveal from-bottom" style="transition-delay:.2s">Buat Akun Sekarang</a>
    </section>

    <footer>
        <p>© 2026 Fakultas Teknik · Universitas PGRI Madiun</p>
        <nav>
            <a href="#tentang">Tentang</a>
            <a href="#bantuan">Bantuan</a>
        </nav>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    const kecamatanData = @json($kecamatan);
    </script>
    <script>
    // ── LOADER ──
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.getElementById('loader').classList.add('hide');
        }, 1400);
    });

    document.addEventListener('DOMContentLoaded', function () {

        // ── CURSOR ──
        const cursor = document.getElementById('cursor');
        const cursorRing = document.getElementById('cursorRing');
        let mx = 0, my = 0, rx = 0, ry = 0;
        document.addEventListener('mousemove', e => {
            mx = e.clientX; my = e.clientY;
            cursor.style.left = mx + 'px'; cursor.style.top = my + 'px';
        });
        function animRing() {
            rx += (mx - rx) * 0.12;
            ry += (my - ry) * 0.12;
            cursorRing.style.left = rx + 'px'; cursorRing.style.top = ry + 'px';
            requestAnimationFrame(animRing);
        }
        animRing();
        document.querySelectorAll('a,button,.stat-item,.feature-card,.workflow-item').forEach(el => {
            el.addEventListener('mouseenter', () => cursorRing.classList.add('hover'));
            el.addEventListener('mouseleave', () => cursorRing.classList.remove('hover'));
        });

        // ── NAVBAR HIDE ON SCROLL DOWN ──
        const nav = document.getElementById('mainNav');
        let lastScroll = 0;
        window.addEventListener('scroll', () => {
            const s = window.scrollY;
            nav.classList.toggle('scrolled', s > 10);
            nav.classList.toggle('hidden', s > lastScroll + 5 && s > 120);
            if (s < lastScroll) nav.classList.remove('hidden');
            lastScroll = Math.max(0, s);
        }, { passive: true });

        // ── PROGRESS BAR ──
        const progressBar = document.getElementById('progress-bar');
        window.addEventListener('scroll', () => {
            const docH = document.documentElement.scrollHeight - window.innerHeight;
            progressBar.style.width = (window.scrollY / docH * 100) + '%';
        }, { passive: true });

        // ── BACK TO TOP ──
        const btt = document.getElementById('back-to-top');
        window.addEventListener('scroll', () => {
            btt.classList.toggle('visible', window.scrollY > 400);
        }, { passive: true });

        // ── HERO PHOTO ZOOM ──
        const heroPhoto = document.getElementById('heroPhoto');
        if (heroPhoto) setTimeout(() => heroPhoto.classList.add('loaded'), 100);

        // ── HERO PARALLAX ──
        window.addEventListener('scroll', () => {
            const s = window.scrollY;
            if (heroPhoto) heroPhoto.style.transform = `scale(1) translateY(${s * 0.3}px)`;
        }, { passive: true });

        // ── PARTICLE CANVAS ──
        const canvas = document.getElementById('hero-particles');
        const ctx = canvas.getContext('2d');
        let particles = [];
        function resizeCanvas() {
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas, { passive: true });
        for (let i = 0; i < 50; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                r: Math.random() * 2 + .5,
                vx: (Math.random() - .5) * .4,
                vy: (Math.random() - .5) * .4,
                alpha: Math.random() * .3 + .05
            });
        }
        function drawParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255,255,255,${p.alpha})`;
                ctx.fill();
                p.x += p.vx; p.y += p.vy;
                if (p.x < 0) p.x = canvas.width;
                if (p.x > canvas.width) p.x = 0;
                if (p.y < 0) p.y = canvas.height;
                if (p.y > canvas.height) p.y = 0;
            });
            // draw connecting lines
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 100) {
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.strokeStyle = `rgba(255,255,255,${.08 * (1 - dist / 100)})`;
                        ctx.lineWidth = .5;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(drawParticles);
        }
        drawParticles();

        // ── TYPEWRITER ──
        const texts = [
            'Petakan data mahasiswamu secara akurat.',
            'Sistem sebaran yang terintegrasi penuh.',
            'Mendukung riset & analitik Fakultas Teknik.'
        ];
        let ti = 0, ci = 0, deleting = false;
        const el = document.getElementById('hero-typewriter');
        function typeWriter() {
            const full = texts[ti];
            if (!deleting) {
                el.textContent = full.slice(0, ci + 1);
                ci++;
                if (ci === full.length) { deleting = true; setTimeout(typeWriter, 2200); return; }
                setTimeout(typeWriter, 55);
            } else {
                el.textContent = full.slice(0, ci - 1);
                ci--;
                if (ci === 0) { deleting = false; ti = (ti + 1) % texts.length; setTimeout(typeWriter, 400); return; }
                setTimeout(typeWriter, 28);
            }
        }
        setTimeout(typeWriter, 2000);

        // ── SCROLL REVEAL ──
        const revealEls = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    revealObserver.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        revealEls.forEach(el => revealObserver.observe(el));

        // ── COUNTER ANIMATION ──
        const statNums = document.querySelectorAll('.stat-num[data-target]');
        let statsTriggered = false;
        const statsSection = document.querySelector('.stats');
        const statsObserver = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting && !statsTriggered) {
                statsTriggered = true;
                statNums.forEach(el => {
                    const target = parseInt(el.dataset.target);
                    const suffix = el.dataset.suffix || '';
                    const duration = 2200;
                    const start = performance.now();
                    function tick(now) {
                        const progress = Math.min((now - start) / duration, 1);
                        const eased = 1 - Math.pow(1 - progress, 4);
                        el.textContent = Math.round(eased * target) + suffix;
                        if (progress < 1) requestAnimationFrame(tick);
                    }
                    requestAnimationFrame(tick);
                });
            }
        }, { threshold: 0.3 });
        if (statsSection) statsObserver.observe(statsSection);

        // ── 3D CARD TILT ──
        if (window.matchMedia('(hover: hover)').matches) {
            document.querySelectorAll('.feature-card').forEach(card => {
                card.addEventListener('mousemove', e => {
                    const rect = card.getBoundingClientRect();
                    const dx = (e.clientX - rect.left - rect.width / 2) / (rect.width / 2);
                    const dy = (e.clientY - rect.top - rect.height / 2) / (rect.height / 2);
                    card.style.transform = `perspective(700px) rotateX(${dy * -8}deg) rotateY(${dx * 8}deg) translateZ(8px)`;
                    card.style.setProperty('--mx', ((e.clientX - rect.left) / rect.width * 100).toFixed(1) + '%');
                    card.style.setProperty('--my', ((e.clientY - rect.top) / rect.height * 100).toFixed(1) + '%');
                });
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'perspective(700px) rotateX(0) rotateY(0) translateZ(0)';
                });
                // ripple on click
                card.addEventListener('click', e => {
                    const rect = card.getBoundingClientRect();
                    const ripple = document.createElement('span');
                    ripple.className = 'ripple';
                    const size = Math.max(rect.width, rect.height);
                    ripple.style.cssText = `width:${size}px;height:${size}px;left:${e.clientX-rect.left-size/2}px;top:${e.clientY-rect.top-size/2}px`;
                    card.appendChild(ripple);
                    setTimeout(() => ripple.remove(), 600);
                });
            });
        }

        // ── LEAFLET MAP ──
        const map = L.map('about-map', { zoomControl: true, scrollWheelZoom: false })
            .setView([-7.634394, 111.542409], 12);
        L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {
            attribution: '© <a href="https://stadiamaps.com/">Stadia Maps</a> © <a href="https://openmaptiles.org/">OpenMapTiles</a> © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 20
        }).addTo(map);

        // Campus marker - BESAR (paling menonjol)
        const campusIcon = L.divIcon({
            className: '', html: '<div class="map-pulse-dot" style="width:22px;height:22px;"></div>',
            iconSize: [22,22], iconAnchor: [11,11],
        });
        L.marker([-7.634394, 111.542409], { icon: campusIcon }).addTo(map)
            .bindPopup('<span style="font-size:13px;font-family:Inter,sans-serif;font-weight:600;color:#fff;">🏛️ Pusat: Fakultas Teknik UNIPMA</span>')
            .openPopup();

        // Kecamatan markers - KECIL tapi hidup
        kecamatanData.forEach(kec => {
            if (kec.latitude && kec.longitude) {
                const lat = parseFloat(kec.latitude);
                const lng = parseFloat(kec.longitude);
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    // Marker KECIL (12px) tapi tetap berdenyut
                    const animatedIcon = L.divIcon({
                        className: 'custom-marker-live',
                        html: `<div style="
                            width: 10px;
                            height: 10px;
                            background: radial-gradient(circle, #4fc3f7 0%, #1a6e9e 100%);
                            border: 1.5px solid white;
                            border-radius: 50%;
                            box-shadow: 0 0 8px rgba(79,195,247,0.8);
                            animation: markerPulseSmall 1.2s ease-in-out infinite;
                            cursor: pointer;
                            transition: transform 0.2s;
                        "></div>`,
                        iconSize: [10, 10],
                        iconAnchor: [5, 5],
                        popupAnchor: [0, -8]
                    });
                    
                    const marker = L.marker([lat, lng], { icon: animatedIcon })
                        .addTo(map)
                        .bindPopup(`
                            <div style="text-align:center; font-family:Inter,sans-serif;">
                                <strong style="color:#4fc3f7; font-size:11px;">📍 ${kec.kecamatan}</strong>
                                <div style="font-size:9px; color:#aaa; margin-top:2px;">Kecamatan</div>
                            </div>
                        `);
                    
                    marker.on('mouseover', function() {
                        const el = this._icon;
                        if (el) {
                            el.style.transform = 'scale(1.5)';
                            el.style.transition = 'transform 0.2s';
                        }
                    });
                    marker.on('mouseout', function() {
                        const el = this._icon;
                        if (el) {
                            el.style.transform = 'scale(1)';
                        }
                    });
                }
            }
        });

        // CSS animation untuk marker kecil
        const style = document.createElement('style');
        style.textContent = `
            @keyframes markerPulseSmall {
                0% {
                    transform: scale(1);
                    box-shadow: 0 0 0 0 rgba(79,195,247,0.6);
                }
                50% {
                    transform: scale(1.2);
                    box-shadow: 0 0 0 6px rgba(79,195,247,0);
                }
                100% {
                    transform: scale(1);
                    box-shadow: 0 0 0 0 rgba(79,195,247,0);
                }
            }
            .custom-marker-live {
                background: transparent !important;
                border: none !important;
            }
        `;
        document.head.appendChild(style);

        setTimeout(() => map.invalidateSize(), 300);
        window.addEventListener('resize', () => map.invalidateSize(), { passive: true });

        // ── SMOOTH ANCHOR ──
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

    }); // end DOMContentLoaded

    // ── MOBILE MENU TOGGLE ──
    function toggleMenu() {
        const menu = document.getElementById('mobileMenu');
        const btn = document.getElementById('hamburgerBtn');
        const open = menu.classList.contains('open');
        menu.classList.toggle('open', !open);
        btn.classList.toggle('open', !open);
    }
    function closeMenu() {
        document.getElementById('mobileMenu').classList.remove('open');
        document.getElementById('hamburgerBtn').classList.remove('open');
    }

    // ── FAQ TOGGLE ──
    function toggleFaq(button) {
        const item = button.parentElement;
        const wasActive = item.classList.contains('active');
        document.querySelectorAll('.faq-item.active').forEach(i => i.classList.remove('active'));
        if (!wasActive) item.classList.add('active');
    }

    // ── OUTSIDE CLICK CLOSES MENU ──
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('mobileMenu');
        const hamburger = document.getElementById('hamburgerBtn');
        if (menu && menu.classList.contains('open') && !menu.contains(e.target) && !hamburger.contains(e.target)) {
            closeMenu();
        }
    });
    </script>
</body>
</html>