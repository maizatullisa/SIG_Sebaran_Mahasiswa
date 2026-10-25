@extends('layouts.admin')

@section('page-title', 'Peta Sebaran Mahasiswa')

@section('content')

<div class="px-6 py-5">

  {{-- TOPBAR --}}
  <div class="flex items-start justify-between mb-5">
    <div>
      <h1 class="text-xl font-semibold text-slate-800">Peta Sebaran Mahasiswa</h1>
      <p class="text-sm text-slate-400 mt-0.5">Analisis spasial distribusi mahasiswa Teknik Informatika</p>
    </div>
    <div class="flex items-center gap-2 text-xs font-medium text-blue-700
                bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-full">
      <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
      Sistem Informasi Geografis
    </div>
  </div>

  {{-- STAT CARDS --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">
    <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
      <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-1">Total Mahasiswa</p>
      <p class="text-2xl font-semibold text-slate-800" id="s-total">–</p>
      <p class="text-xs text-slate-400 mt-0.5">seluruh Indonesia</p>
    </div>
    <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
      <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-1">Provinsi Terpeta</p>
      <p class="text-2xl font-semibold text-slate-800" id="s-prov">–</p>
      <p class="text-xs text-slate-400 mt-0.5">dari 38 provinsi</p>
    </div>
    <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
      <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-1">Wilayah Terbanyak</p>
      <p class="text-base font-semibold text-slate-800 truncate leading-tight" id="s-top-name">–</p>
      <p class="text-xs text-blue-500 font-medium mt-0.5" id="s-top-val">–</p>
    </div>
    <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
      <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-1">Rata-rata / Wilayah</p>
      <p class="text-2xl font-semibold text-slate-800" id="s-avg">–</p>
      <p class="text-xs text-slate-400 mt-0.5">mahasiswa</p>
    </div>
  </div>

  {{-- MAIN GRID --}}
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

    {{-- MAP CARD --}}
    <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm flex flex-col">

      {{-- MAP TOOLBAR --}}
      <div class="flex items-center justify-between px-4 py-2.5 border-b border-slate-100 bg-white">
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2 text-sm font-medium text-slate-700">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            Viewport Peta Sebaran
          </div>

          {{-- BREADCRUMB --}}
          <div id="map-breadcrumb" class="hidden items-center gap-1 text-xs text-slate-400">
            <button id="bc-indonesia" class="hover:text-blue-500 transition">Indonesia</button>
            <span id="bc-sep-prov" class="hidden">›</span>
            <span id="bc-prov" class="hidden text-slate-600 font-medium"></span>
            <span id="bc-sep-kab" class="hidden">›</span>
            <span id="bc-kab" class="hidden text-blue-600 font-semibold"></span>
          </div>
        </div>

        <div class="flex items-center gap-2">
          {{-- LEGEND MINI --}}
          <div class="hidden lg:flex items-center gap-3 text-[10px] text-slate-400 mr-2">
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>&gt;20</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>5–20</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>1–5</span>
          </div>
          <button id="btn-reset-zoom"
            class="hidden items-center gap-1.5 text-xs text-slate-600 border border-slate-200
                   bg-white hover:bg-slate-50 px-3 py-1.5 rounded-lg transition font-medium shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Lihat Full Peta Indonesia
          </button>
        </div>
      </div>

      <div id="map" style="height:590px;width:100%;"></div>
    </div>

    {{-- SIDEBAR --}}
    <div class="flex flex-col gap-3 h-[590px]">

      {{-- Fokus Wilayah --}}
      <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
        <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-2">Fokus Wilayah</p>
        <div id="prov-sel"
          class="flex items-center gap-2 px-3 py-2.5 bg-slate-50 border border-slate-100
                 text-slate-500 text-sm rounded-xl">
          <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
          </svg>
          Pilih provinsi pada peta
        </div>
      </div>

      {{-- Legenda --}}
      <div class="bg-white rounded-2xl border border-slate-100 px-4 py-3 shadow-sm">
        <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-2">Legenda Kepadatan</p>
        <div class="space-y-1.5">
          <div class="flex items-center gap-2.5 text-xs text-slate-600">
            <span class="w-2.5 h-2.5 rounded-full bg-red-500 flex-shrink-0
                         shadow-[0_0_0_3px_rgba(239,68,68,.2)] animate-pulse"></span>
            <span>&gt; 20 mahasiswa — sangat padat</span>
          </div>
          <div class="flex items-center gap-2.5 text-xs text-slate-600">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 flex-shrink-0"></span>
            <span>5 – 20 mahasiswa — padat</span>
          </div>
          <div class="flex items-center gap-2.5 text-xs text-slate-600">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
            <span>1 – 4 mahasiswa — sedikit</span>
          </div>
          <div class="flex items-center gap-2.5 text-xs text-slate-600">
            <span class="w-2.5 h-2.5 rounded-full bg-slate-200 flex-shrink-0"></span>
            <span>0 — belum ada data</span>
          </div>
        </div>
      </div>

      {{-- Legenda Warna Prodi --}}
      <div class="bg-white rounded-2xl border border-slate-100 px-4 py-3 shadow-sm">
        <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-2">Legenda Warna Prodi</p>
        <div class="space-y-1.5">
          <div class="flex items-center gap-2.5 text-xs text-slate-600">
            <span class="w-2.5 h-2.5 rounded-full bg-red-700 flex-shrink-0"></span>
            <span>Teknik Informatika</span>
          </div>
          <div class="flex items-center gap-2.5 text-xs text-slate-600">
            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background: #1a6e9e;"></span>
            <span>Sistem Informasi</span>
          </div>
          <div class="flex items-center gap-2.5 text-xs text-slate-600">
            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background: #9a6012;"></span>
            <span>Teknik Kimia</span>
          </div>
          <div class="flex items-center gap-2.5 text-xs text-slate-600">
            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background: #6b3fa0;"></span>
            <span>Teknik Industri</span>
          </div>
          <div class="flex items-center gap-2.5 text-xs text-slate-600">
            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background: #1e7e34;"></span>
            <span>Teknik Elektro</span>
          </div>
        </div>
      </div>

      {{-- List Kabupaten --}}
      <div class="bg-white rounded-2xl border border-slate-100 px-4 py-3 shadow-sm flex flex-col flex-1 min-h-[200px]">
        <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-2" id="sidebar-list-title">
          Daftar Kabupaten
        </p>
        <div id="kec-list" class="space-y-1.5 overflow-y-auto pr-0.5 flex-1
                                   scrollbar-thin scrollbar-thumb-slate-200">
          <p class="text-xs text-slate-400 text-center py-10 leading-relaxed">
            Silakan pilih provinsi<br>untuk menampilkan data.
          </p>
        </div>
      </div>

    </div>
  </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
  #map { background: #f8fafc; z-index: 1; }

  .leaflet-zoom-animated { transition: transform .28s cubic-bezier(.25,.46,.45,.94); }
  .leaflet-fade-anim .leaflet-tile { transition: opacity .22s linear; }

  .leaflet-popup-content-wrapper {
    border-radius: 14px !important;
    border: 0.5px solid #e2e8f0 !important;
    box-shadow: 0 8px 30px rgba(0,0,0,.10) !important;
    padding: 0 !important;
    overflow: hidden;
  }
  .leaflet-popup-content { margin: 0 !important; font-family: inherit; }
  .leaflet-popup-close-button {
    color: #94a3b8 !important;
    font-size: 18px !important;
    top: 8px !important;
    right: 10px !important;
  }

  .leaflet-tooltip {
    border-radius: 8px !important;
    border: 0.5px solid #e2e8f0 !important;
    box-shadow: 0 4px 12px rgba(0,0,0,.08) !important;
    font-size: 12px !important;
    font-family: inherit !important;
    padding: 6px 10px !important;
    color: #1e293b !important;
  }

  @keyframes pinpulse {
    0%   { transform: rotate(-45deg) scale(1);   opacity: .45; }
    50%  { transform: rotate(-45deg) scale(2.1); opacity: 0;   }
    100% { transform: rotate(-45deg) scale(1);   opacity: .45; }
  }

  .pulse-dot-orange {
    width: 11px; height: 11px;
    background: #f97316;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 0 rgba(249,115,22,.6);
    animation: pulse-orange 1.8s infinite cubic-bezier(.66,0,0,1);
  }
  @keyframes pulse-orange {
    to { box-shadow: 0 0 0 13px rgba(249,115,22,0); }
  }

  .pulse-dot-ti {
    width: 11px; height: 11px;
    background: #c0392b;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 0 rgba(192,57,43,.6);
    animation: pulse-ti 1.8s infinite cubic-bezier(.66,0,0,1);
  }
  @keyframes pulse-ti {
    to { box-shadow: 0 0 0 13px rgba(192,57,43,0); }
  }

  .pulse-dot-si {
    width: 11px; height: 11px;
    background: #1a6e9e;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 0 rgba(26,110,158,.6);
    animation: pulse-si 1.8s infinite cubic-bezier(.66,0,0,1);
  }
  @keyframes pulse-si {
    to { box-shadow: 0 0 0 13px rgba(26,110,158,0); }
  }

  .pulse-dot-tk {
    width: 11px; height: 11px;
    background: #9a6012;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 0 rgba(154,96,18,.6);
    animation: pulse-tk 1.8s infinite cubic-bezier(.66,0,0,1);
  }
  @keyframes pulse-tk {
    to { box-shadow: 0 0 0 13px rgba(154,96,18,0); }
  }

  .pulse-dot-tin {
    width: 11px; height: 11px;
    background: #6b3fa0;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 0 rgba(107,63,160,.6);
    animation: pulse-tin 1.8s infinite cubic-bezier(.66,0,0,1);
  }
  @keyframes pulse-tin {
    to { box-shadow: 0 0 0 13px rgba(107,63,160,0); }
  }

  .pulse-dot-te {
    width: 11px; height: 11px;
    background: #1e7e34;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 0 rgba(30,126,52,.6);
    animation: pulse-te 1.8s infinite cubic-bezier(.66,0,0,1);
  }
  @keyframes pulse-te {
    to { box-shadow: 0 0 0 13px rgba(30,126,52,0); }
  }

  .pulse-dot-other {
    width: 11px; height: 11px;
    background: #4b5563;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 0 rgba(75,85,99,.6);
    animation: pulse-other 1.8s infinite cubic-bezier(.66,0,0,1);
  }
  @keyframes pulse-other {
    to { box-shadow: 0 0 0 13px rgba(75,85,99,0); }
  }

  .prodi-badge { display:inline-block; padding:2px 8px; font-size:10px; font-weight:600; border-radius:6px; margin-top:3px; }
  .prodi-ti    { background:#fdecea; color:#c0392b; }
  .prodi-si    { background:#e8f2f9; color:#1a6e9e; }
  .prodi-tk    { background:#fdf3e7; color:#9a6012; }
  .prodi-tin   { background:#f2eefa; color:#6b3fa0; }
  .prodi-te    { background:#eaf5ec; color:#1e7e34; }
  .prodi-other { background:#f3f4f6; color:#4b5563; }

  .pp-wrap  { padding: 12px 14px; min-width: 230px; }
  .pp-head  { border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 8px; }
  .pp-nim   { font-size: 10px; font-weight: 700; color: #2563eb; text-transform: uppercase; }
  .pp-name  { font-size: 13px; font-weight: 700; color: #1e293b; line-height: 1.2; margin-top: 1px; }
  .pp-row   { display:flex; gap:4px; font-size:11px; color:#334155; margin-bottom:3px; }
  .pp-lbl   { color:#94a3b8; flex-shrink:0; }
  .pp-addr  { background:#f8fafc; border:1px solid #e2e8f0; padding:6px 8px;
              border-radius:8px; font-size:10px; color:#475569; margin-top:6px; }
  .pp-btn   { display:block; width:100%; margin-top:8px; background:#2563eb; color:#fff;
              font-size:11px; font-weight:600; padding:7px 0; border-radius:8px;
              text-align:center; border:none; cursor:pointer; transition:background .15s; }
  .pp-btn:hover { background:#1d4ed8; }

  /* == CSS TAMBAHAN UNTUK GEOCODING BOX == */
  .pp-coord-box   { background:#f0f7ff; border:1px solid #bfdbfe; border-radius:8px; padding:7px 9px; margin-top:7px; }
  .pp-coord-label { font-size:9px; font-weight:700; color:#93c5fd; text-transform:uppercase; letter-spacing:.8px; margin-bottom:4px; }
  .pp-coord-row   { display:flex; align-items:center; justify-content:space-between; gap:6px; }
  .pp-coord-dec   { font-family:ui-monospace,'Cascadia Code',monospace; font-size:11px; font-weight:700; color:#1d4ed8; letter-spacing:.3px; }
  .pp-coord-dms   { font-family:ui-monospace,'Cascadia Code',monospace; font-size:10px; color:#64748b; margin-top:3px; letter-spacing:.2px; }
  .pp-copy-btn    { flex-shrink:0; background:#fff; border:1px solid #bfdbfe; border-radius:6px; padding:3px 8px; font-size:10px; font-weight:600; color:#3b82f6; cursor:pointer; transition:all .15s; white-space:nowrap; }
  .pp-copy-btn:hover  { background:#2563eb; color:#fff; border-color:#2563eb; }
  .pp-copy-btn.copied { background:#10b981; color:#fff; border-color:#10b981; }
</style>

<script>
(function () {
  'use strict';

  const INITIAL_VIEW = { center: [-2.5, 118], zoom: 5 };

  // ── MAP INIT ────────────────────────────────────────────────────────────────
  const map = L.map('map', {
    zoomControl:         true,
    zoomSnap:            0.25,
    zoomDelta:           0.5,
    wheelPxPerZoomLevel: 80,
    inertia:             true,
    inertiaDeceleration: 2000,
    inertiaMaxSpeed:     1200,
  }).setView(INITIAL_VIEW.center, INITIAL_VIEW.zoom);

  L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    maxZoom:     19,
    attribution: '&copy; OpenStreetMap &copy; CARTO',
    subdomains:  'abcd',
  }).addTo(map);

  const provinsiLayer  = L.layerGroup().addTo(map);
  const kabupatenLayer = L.layerGroup().addTo(map);
  const markerLayer    = L.layerGroup().addTo(map);

  // ── KONSTANTA ───────────────────────────────────────────────────────────────
  const PROVINSI_CENTER = {
    "ACEH":[4.7,96.7],"SUMATERA UTARA":[2.1,99.5],"SUMATERA BARAT":[-0.7,100.8],
    "RIAU":[0.3,101.7],"KEPULAUAN RIAU":[3.9,108.1],"JAMBI":[-1.6,103.6],
    "SUMATERA SELATAN":[-3.3,103.9],"KEPULAUAN BANGKA BELITUNG":[-2.7,106.4],"BENGKULU":[-3.8,102.3],
    "LAMPUNG":[-4.6,105.4],"DKI JAKARTA":[-6.2,106.8],"BANTEN":[-6.4,106.1],
    "JAWA BARAT":[-6.9,107.6],"JAWA TENGAH":[-7.2,110.1],"DI YOGYAKARTA":[-7.9,110.4],
    "JAWA TIMUR":[-7.5,112.2],"BALI":[-8.3,115.1],"NUSA TENGGARA BARAT":[-8.7,117.4],
    "NUSA TENGGARA TIMUR":[-8.7,121.1],"KALIMANTAN BARAT":[0.1,111.1],"KALIMANTAN TENGAH":[-1.7,113.4],
    "KALIMANTAN SELATAN":[-3.1,115.3],"KALIMANTAN TIMUR":[1.6,116.4],"KALIMANTAN UTARA":[3.1,116.0],
    "SULAWESI UTARA":[1.5,124.8],"GORONTALO":[0.5,123.1],"SULAWESI TENGAH":[-1.4,121.4],
    "SULAWESI BARAT":[-2.8,119.2],"SULAWESI SELATAN":[-3.7,119.9],"SULAWESI TENGGARA":[-4.1,122.2],
    "MALUKU":[-3.2,130.1],"MALUKU UTARA":[1.6,127.8],"PAPUA BARAT":[-1.3,133.2],"PAPUA":[-4.3,138.1],
  };

  // ── STATE ───────────────────────────────────────────────────────────────────
  let globalStats          = {};
  let geojsonKabupatenData = null;
  let currentMahasiswaData = [];
  let activeProvinsiCode   = null;
  let activeProvinsiNama   = '';
  let activeAbortController = null;

  // ── HELPERS ─────────────────────────────────────────────────────────────────
  function cleanName(str) {
    if (!str) return '';
    return str.toUpperCase()
      .replace(/^(KABUPATEN|KOTA|PROVINSI|PROPINSI)\s+/, '')
      .replace(/[^A-Z0-9\s]/g, '')
      .replace(/\s+/g, ' ')
      .trim();
  }

  function getColor(n) {
    if (n > 5) return '#ef4444';
    if (n > 2)  return '#f59e0b';
    if (n > 0)  return '#10b981';
    return '#e2e8f0';
  }

  // === FUNGSI BARU UNTUK MENDAPAT CLASS PRODI ===
  function getProdiDotClass(prodi) {
    const pc = cleanName(prodi || '');
    if (pc.includes('INFORMATIKA')) return 'pulse-dot-ti';
    else if (pc.includes('SISTEM'))   return 'pulse-dot-si';
    else if (pc.includes('KIMIA'))    return 'pulse-dot-tk';
    else if (pc.includes('INDUSTRI')) return 'pulse-dot-tin';
    else if (pc.includes('ELEKTRO'))  return 'pulse-dot-te';
    return 'pulse-dot-other';
  }
  // ============================================

  function pinIcon(jumlah, isKab = false) {
    const color = getColor(jumlah);
    const sz    = isKab ? (jumlah >= 10 ? 38 : 32) : (jumlah >= 20 ? 42 : jumlah >= 5 ? 36 : 30);
    const pulse = !isKab && jumlah >= 20;
    const fs    = sz <= 30 ? 9 : sz <= 36 ? 11 : 13;

    const html = `
      <div style="position:relative;width:${sz}px;height:${sz+8}px">
        ${pulse ? `<div style="position:absolute;top:0;left:0;width:${sz}px;height:${sz}px;
          border-radius:50% 50% 50% 0;transform:rotate(-45deg);
          background:rgba(239,68,68,.22);animation:pinpulse 1.7s ease-out infinite"></div>` : ''}
        <div style="position:absolute;top:0;left:0;width:${sz}px;height:${sz}px;
          background:${color};border-radius:50% 50% 50% 0;transform:rotate(-45deg);
          border:2.5px solid white;box-shadow:0 3px 12px rgba(0,0,0,.22)"></div>
        <div style="position:absolute;top:${Math.round(sz*.17)}px;left:0;width:${sz}px;
          text-align:center;font-size:${fs}px;font-weight:700;color:white;
          font-family:-apple-system,sans-serif;line-height:1">${jumlah}</div>
      </div>`;

    return L.divIcon({ className:'', html, iconSize:[sz,sz+8], iconAnchor:[sz/2,sz+8] });
  }

  // == JS TAMBAHAN UNTUK GEOCODING HELPER ==
  function toDMS(deg, isLat) {
    const abs = Math.abs(deg);
    const d   = Math.floor(abs);
    const mf  = (abs - d) * 60;
    const m   = Math.floor(mf);
    const s   = ((mf - m) * 60).toFixed(1);
    const dir = isLat ? (deg >= 0 ? 'U' : 'S') : (deg >= 0 ? 'T' : 'B');
    return d + '°' + m + "'" + s + '"' + dir;
  }

  function fallbackCopy(text, cb) {
    const el = document.createElement('textarea');
    el.value = text;
    el.style.cssText = 'position:fixed;opacity:0;top:0;left:0';
    document.body.appendChild(el);
    el.select();
    try { document.execCommand('copy'); } catch(e) {}
    document.body.removeChild(el);
    cb();
  }

  // ── BREADCRUMB ──────────────────────────────────────────────────────────────
  function setBreadcrumb(prov, kab) {
    const bc = document.getElementById('map-breadcrumb');
    bc.classList.remove('hidden');
    bc.classList.add('flex');
    document.getElementById('bc-sep-prov').classList.toggle('hidden', !prov);
    document.getElementById('bc-prov').classList.toggle('hidden', !prov);
    document.getElementById('bc-sep-kab').classList.toggle('hidden', !kab);
    document.getElementById('bc-kab').classList.toggle('hidden', !kab);
    if (prov) document.getElementById('bc-prov').textContent = prov;
    if (kab)  document.getElementById('bc-kab').textContent  = kab;
  }

  document.getElementById('bc-indonesia').addEventListener('click', resetToIndonesia);

  // ── SIDEBAR HELPERS ─────────────────────────────────────────────────────────
  function setProvSel(html) { document.getElementById('prov-sel').innerHTML = html; }
  function setKecList(html) { document.getElementById('kec-list').innerHTML = html; }
  function spinnerHtml() {
    return `<div class="flex justify-center py-8">
      <div class="w-5 h-5 border-2 border-slate-200 border-t-blue-400 rounded-full animate-spin"></div>
    </div>`;
  }

  // ── POPUP BUILDER ───────────────────────────────────────────────────────────
  function buildKabupatenPopup(kab) {
    const wrap = document.createElement('div');
    wrap.className = 'pp-wrap';

    const head = document.createElement('div');
    head.className = 'pp-head';

    const title = document.createElement('div');
    title.style.cssText = 'font-size:13px;font-weight:700;color:#1e293b';
    title.textContent = kab.name;

    const sub = document.createElement('div');
    sub.style.cssText = 'font-size:11px;color:#64748b;margin-top:2px';
    sub.textContent = activeProvinsiNama;

    head.appendChild(title);
    head.appendChild(sub);

    const row = document.createElement('div');
    row.className = 'pp-row';
    row.innerHTML = `<span class="pp-lbl">Mahasiswa:</span><b style="color:#2563eb">${kab.jumlah.toLocaleString('id-ID')}</b>`;

    const btn = document.createElement('button');
    btn.className = 'pp-btn';
    btn.textContent = 'Lihat Titik Sebaran Rumah →';
    btn.addEventListener('click', () => focusKeKabupaten(kab.name));

    wrap.appendChild(head);
    wrap.appendChild(row);
    wrap.appendChild(btn);
    return wrap;
  }

  function buildMahasiswaPopup(mhs) {
    let prodiClass = 'prodi-other';
    const pc = cleanName(mhs.prodi || '');
    if (pc.includes('INFORMATIKA')) prodiClass = 'prodi-ti';
    else if (pc.includes('SISTEM'))   prodiClass = 'prodi-si';
    else if (pc.includes('KIMIA'))    prodiClass = 'prodi-tk';
    else if (pc.includes('INDUSTRI')) prodiClass = 'prodi-tin';
    else if (pc.includes('ELEKTRO'))  prodiClass = 'prodi-te';

    const wrap = document.createElement('div');
    wrap.className = 'pp-wrap';
    wrap.innerHTML = `
      <div class="pp-head">
        <div class="pp-nim">${escHtml(mhs.nim || '–')}</div>
        <div class="pp-name">${escHtml(mhs.nama || '–')}</div>
        <span class="prodi-badge ${prodiClass}">${escHtml(mhs.prodi || 'Teknik Informatika')}</span>
      </div>
      <div class="pp-row"><span class="pp-lbl">Minat:</span><b>${escHtml(mhs.minat || '–')}</b></div>
      <div class="pp-row"><span class="pp-lbl">Sekolah:</span><span>${escHtml(mhs.nama_sekolah || '–')}</span></div>
      <div class="pp-row" style="font-size:10px;color:#94a3b8">
        Jur: ${escHtml(mhs.jurusan || '–')} · Kec: ${escHtml(mhs.kecamatan_sekolah || '–')}
      </div>

      <!-- 
      <div class="pp-addr">
        <b style="font-size:9px;color:#94a3b8;text-transform:uppercase;display:block;margin-bottom:2px">Alamat Tinggal</b>
        Desa ${escHtml(mhs.desa||'–')}, Kec. ${escHtml(mhs.kecamatan||'–')}, Kab. ${escHtml(mhs.kabupaten||'–')}.
        <span style="display:block;margin-top:3px;color:#1e293b;font-style:italic">"${escHtml(mhs.detail_alamat||'–')}"</span>
      </div>
      -->

      
      <div class="pp-addr">
  <b style="font-size:9px;color:#94a3b8;text-transform:uppercase;display:block;margin-bottom:2px">
    Alamat Tinggal
  </b>

  <span style="display:block;color:#1e293b;line-height:1.5">
    ${escHtml(mhs.detail_alamat || '-')}
  </span>
</div>`;

    // == LOGIKA GEOCODING BOX YANG DITAMBAHKAN ==
    const lat = parseFloat(mhs.latitude);
    const lng = parseFloat(mhs.longitude);

    if (!isNaN(lat) && !isNaN(lng)) {
      const dec = lat.toFixed(6) + ', ' + lng.toFixed(6);
      const dms = toDMS(lat, true) + '  ' + toDMS(lng, false);

      const coordBox = document.createElement('div');
      coordBox.className = 'pp-coord-box';
      coordBox.innerHTML = `
        <div class="pp-coord-label">Koordinat GPS</div>
        <div class="pp-coord-row">
          <div>
            <div class="pp-coord-dec">${dec}</div>
            <div class="pp-coord-dms">${dms}</div>
          </div>
        </div>
      `;

      const copyBtn = document.createElement('button');
      copyBtn.className = 'pp-copy-btn';
      copyBtn.textContent = 'Salin';
      
      // Event listener khusus agar lebih aman dari XSS
      copyBtn.addEventListener('click', () => {
        const doVisual = () => {
          copyBtn.textContent = 'Disalin';
          copyBtn.classList.add('copied');
          setTimeout(() => { copyBtn.textContent = 'Salin'; copyBtn.classList.remove('copied'); }, 1800);
        };

        if (navigator.clipboard) {
          navigator.clipboard.writeText(dec).then(doVisual).catch(() => fallbackCopy(dec, doVisual));
        } else {
          fallbackCopy(dec, doVisual);
        }
      });

      coordBox.querySelector('.pp-coord-row').appendChild(copyBtn);
      wrap.appendChild(coordBox);
    }
    // ============================================

    return wrap;
  }

  function escHtml(str) {
    return String(str)
      .replace(/&/g,'&amp;')
      .replace(/</g,'&lt;')
      .replace(/>/g,'&gt;')
      .replace(/"/g,'&quot;')
      .replace(/'/g,'&#039;');
  }

  // ── TOAST ───────────────────────────────────────────────────────────────────
  function showToast(msg) {
    const t = document.createElement('div');
    t.textContent = msg;
    t.style.cssText = `
      position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(8px);
      background:#1e293b;color:#fff;font-size:12px;font-weight:500;
      padding:10px 18px;border-radius:10px;z-index:9999;
      box-shadow:0 4px 20px rgba(0,0,0,.18);
      transition:opacity .25s,transform .25s;opacity:0;pointer-events:none;
    `;
    document.body.appendChild(t);
    requestAnimationFrame(() => {
      t.style.opacity = '1';
      t.style.transform = 'translateX(-50%) translateY(0)';
    });
    setTimeout(() => {
      t.style.opacity = '0';
      setTimeout(() => t.remove(), 300);
    }, 3000);
  }

  // ── 1. FETCH DATA PROVINSI ──────────────────────────────────────────────────
  fetch('/api/map/provinsi')
    .then(r => r.json())
    .then(res => {
      const arr = Array.isArray(res) ? res : Object.values(res);
      let total = 0, aktif = 0, maxVal = 0, maxName = '–';

      arr.forEach(item => {
        if (!item) return;
        const key = cleanName(item.name);
        globalStats[key] = { jumlah: item.jumlah, code: item.code, originalName: item.name };
        total += item.jumlah;
        if (item.jumlah > 0) aktif++;
        if (item.jumlah > maxVal) { maxVal = item.jumlah; maxName = item.name; }
      });

      document.getElementById('s-total').textContent    = total.toLocaleString('id-ID');
      document.getElementById('s-prov').textContent     = aktif;
      document.getElementById('s-top-name').textContent = maxName;
      document.getElementById('s-top-val').textContent  = maxVal.toLocaleString('id-ID') + ' mahasiswa';
      document.getElementById('s-avg').textContent      = aktif ? Math.round(total / aktif) : 0;

      loadProvinsiGeoJSON();
      preloadKabupatenGeoJSON();
    })
    .catch(() => showToast('Gagal memuat data provinsi.'));

  function preloadKabupatenGeoJSON() {
    fetch('/geojson/38 Provinsi Indonesia - Kabupaten.json')
      .then(r => r.json())
      .then(data => { geojsonKabupatenData = data; })
      .catch(e => console.error('GeoJSON kabupaten gagal:', e));
  }

  // ── 2. GEOJSON PROVINSI ─────────────────────────────────────────────────────
  function loadProvinsiGeoJSON() {
    fetch('/geojson/indonesia-provinsi.geojson')
      .then(r => r.json())
      .then(geoData => {
        L.geoJSON(geoData, {
          style: function (f) {
            const n  = f.properties.PROVINSI || f.properties.NAME_1 || f.properties.name || '';
            const st = globalStats[cleanName(n)];
            const c  = st ? st.jumlah : 0;
            return {
              fillColor:   getColor(c),
              weight:      1,
              opacity:     1,
              color:       '#94a3b8',
              fillOpacity: c > 0 ? 0.45 : 0.2,
            };
          },
          onEachFeature: function (f, layer) {
            const n  = f.properties.PROVINSI || f.properties.NAME_1 || f.properties.name || '';
            const st = globalStats[cleanName(n)];
            const c  = st ? st.jumlah : 0;

            layer.bindTooltip(
              `<b>${n}</b><br><span style="color:#3b82f6;font-weight:600">${c} mahasiswa</span>`,
              { sticky: true }
            );

            layer.on('mouseover', function () {
              if (!activeProvinsiCode) layer.setStyle({ fillOpacity: .65, weight: 1.5 });
            });
            layer.on('mouseout', function () {
              if (!activeProvinsiCode) layer.setStyle({ fillOpacity: c > 0 ? .45 : .2, weight: 1 });
            });
            layer.on('click', function () {
              if (st) handleProvinsiSelect(st.code, st.originalName, layer.getBounds());
            });
          },
        }).addTo(provinsiLayer);

        addProvinsiMarkers();
      })
      .catch(() => showToast('Gagal memuat data GeoJSON provinsi.'));
  }

  // ── MARKER PROVINSI OVERVIEW ─────────────────────────────────────────────
  function addProvinsiMarkers() {
    markerLayer.clearLayers();
    Object.values(globalStats).forEach(v => {
      if (v.jumlah <= 0) return;
      const c = PROVINSI_CENTER[v.originalName.toUpperCase()];
      if (!c) return;

      const popupEl = document.createElement('div');
      popupEl.className = 'pp-wrap';
      popupEl.innerHTML = `
        <div class="pp-head">
          <div style="font-size:13px;font-weight:700;color:#1e293b">${escHtml(v.originalName)}</div>
        </div>
        <div class="pp-row"><span class="pp-lbl">Mahasiswa:</span><b style="color:#2563eb">${v.jumlah.toLocaleString('id-ID')}</b></div>
        <div style="font-size:10px;color:#94a3b8;margin-top:4px">Klik wilayah pada peta untuk detail</div>`;

      L.marker(c, { icon: pinIcon(v.jumlah) })
        .addTo(markerLayer)
        .bindPopup(popupEl);
    });
  }

  // ── 3. KLIK PROVINSI ────────────────────────────────────────────────────────
  function handleProvinsiSelect(kodeProvinsi, namaProvinsi, bounds) {
    if (activeAbortController) {
      activeAbortController.abort();
    }
    activeAbortController = new AbortController();
    const signal = activeAbortController.signal;

    activeProvinsiCode = kodeProvinsi;
    activeProvinsiNama = namaProvinsi;

    document.getElementById('btn-reset-zoom').classList.remove('hidden');
    document.getElementById('btn-reset-zoom').classList.add('flex');

    setProvSel(`
      <svg class="w-4 h-4 flex-shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
      <span class="font-semibold text-blue-700">${escHtml(namaProvinsi)}</span>
    `);

    setBreadcrumb(namaProvinsi, null);
    setKecList(spinnerHtml());
    document.getElementById('sidebar-list-title').textContent = 'Daftar Kabupaten/Kota';

    map.flyToBounds(bounds, { padding:[32,32], duration:.7 });

    kabupatenLayer.clearLayers();
    markerLayer.clearLayers();

    Promise.all([
      fetch(`/api/map/mahasiswa/${kodeProvinsi}`, { signal }).then(r => r.json()),
      fetch(`/api/map/kabupaten/${kodeProvinsi}`, { signal }).then(r => r.json()),
    ])
    .then(([mhsData, kabData]) => {
      if (activeProvinsiCode !== kodeProvinsi) return;

      currentMahasiswaData = Array.isArray(mhsData) ? mhsData : Object.values(mhsData);
      const kabArr = Array.isArray(kabData) ? kabData : Object.values(kabData);
      renderKabupaten(kabArr, bounds);
    })
    .catch(err => {
      if (err.name === 'AbortError') return;
      showToast('Gagal memuat data kabupaten.');
      setKecList('<p class="text-xs text-slate-400 text-center py-10">Gagal memuat data.</p>');
    });
  }

  // ── 4. RENDER KABUPATEN LAYER + MARKER ──────────────────────────────────────
  function renderKabupaten(kabArr, provBounds) {
    const fragments = [];
    const markerBounds = L.latLngBounds();
    let   adaCoord     = false;

    kabArr.forEach((kab, idx) => {
      const tk     = cleanName(kab.name);
      let   center = null;

      if (geojsonKabupatenData) {
        const feat = geojsonKabupatenData.features.find(f => {
          const fn = f.properties.KABUPATEN || f.properties.NAME_2 || f.properties.name || '';
          return cleanName(fn) === tk;
        });
        if (feat) {
          const lyr = L.geoJSON(feat, {
            style: { fillColor:'#3b82f6', weight:1.2, color:'#1e40af', fillOpacity:.08, dashArray:'3' }
          }).addTo(kabupatenLayer);
          center = lyr.getBounds().getCenter();
          lyr.bindTooltip(`<b>${kab.name}</b> — ${kab.jumlah} mhs`, { sticky:true });
          lyr.on('click', () => focusKeKabupaten(kab.name));
        }
      }

      if (!center) {
        const mhsInKab = currentMahasiswaData.filter(m =>
          cleanName(m.kabupaten) === tk || cleanName(String(m.kabupaten)) === tk
        );
        let ls = 0, ln = 0, n = 0;
        mhsInKab.forEach(m => {
          const lat = parseFloat(m.latitude), lng = parseFloat(m.longitude);
          if (!isNaN(lat) && !isNaN(lng)) { ls += lat; ln += lng; n++; }
        });
        if (n > 0) center = L.latLng(ls / n, ln / n);
      }

      if (center && kab.jumlah > 0) {
        L.marker(center, { icon: pinIcon(kab.jumlah, true) })
          .addTo(markerLayer)
          .bindPopup(buildKabupatenPopup(kab));
        markerBounds.extend(center);
        adaCoord = true;
      }

      fragments.push(`
        <div data-kab="${escHtml(kab.name)}"
          class="kab-item flex items-center justify-between px-3 py-2.5 rounded-xl border border-slate-100
                 hover:bg-blue-50 hover:border-blue-200 transition cursor-pointer group">
          <div class="flex items-center gap-2.5">
            <span class="text-[10px] font-bold text-slate-300 w-4 group-hover:text-blue-300">${idx+1}</span>
            <span class="text-xs font-medium text-slate-700 group-hover:text-blue-700">${escHtml(kab.name)}</span>
          </div>
          <span class="text-[11px] font-semibold bg-slate-50 border border-slate-200
                       group-hover:bg-blue-100 group-hover:border-blue-200
                       text-slate-500 group-hover:text-blue-600 px-2 py-0.5 rounded-lg transition">
            ${kab.jumlah.toLocaleString('id-ID')}
          </span>
        </div>
      `);
    });

    const list = document.getElementById('kec-list');

    if (!kabArr.length) {
      list.innerHTML = '<p class="text-xs text-slate-400 text-center py-10">Tidak ada data mahasiswa di wilayah ini.</p>';
    } else {
      list.innerHTML = fragments.join('');
      list.querySelectorAll('.kab-item').forEach(el => {
        el.addEventListener('click', () => focusKeKabupaten(el.dataset.kab));
      });
    }

    if (adaCoord && markerBounds.isValid()) {
      setTimeout(() => {
        map.flyToBounds(markerBounds, { padding:[56,56], duration:.85, maxZoom:10 });
      }, 750);
    }
  }

  // ── 5. FOKUS KABUPATEN (TITIK RUMAH) ────────────────────────────────────────
  function focusKeKabupaten(namaKabupaten) {
    const tk       = cleanName(namaKabupaten);
    const terfilter = currentMahasiswaData.filter(m =>
      cleanName(m.kabupaten) === tk || cleanName(String(m.kabupaten)) === tk
    );

    if (!terfilter.length) {
      showToast('Belum ada data koordinat rumah mahasiswa di ' + namaKabupaten);
      return;
    }

    setBreadcrumb(activeProvinsiNama, namaKabupaten);
    markerLayer.clearLayers();

    const bounds = L.latLngBounds();
    let n = 0;

    terfilter.forEach(mhs => {
      const lat = parseFloat(mhs.latitude);
      const lng = parseFloat(mhs.longitude);
      if (isNaN(lat) || isNaN(lng)) return;

      // === UBAHAN DI SINI: MENGGUNAKAN FUNGSI GETPRIDIDOTCLASS ===
      const dotClass = getProdiDotClass(mhs.prodi);
      const dotIcon = L.divIcon({
        className: dotClass,
        iconSize:  [11, 11],
        iconAnchor:[5.5, 5.5],
      });
      // ============================================================

      L.marker([lat, lng], { icon: dotIcon })
        .addTo(markerLayer)
        .bindPopup(buildMahasiswaPopup(mhs));

      bounds.extend([lat, lng]);
      n++;
    });

    if (n > 0 && bounds.isValid()) {
      map.flyToBounds(bounds, { padding:[60,60], duration:.9, maxZoom:13 });
    }
  }

  // ── 6. RESET KE INDONESIA ───────────────────────────────────────────────────
  function resetToIndonesia() {
    if (activeAbortController) {
      activeAbortController.abort();
      activeAbortController = null;
    }

    activeProvinsiCode   = null;
    activeProvinsiNama   = '';
    currentMahasiswaData = [];

    kabupatenLayer.clearLayers();
    markerLayer.clearLayers();

    map.flyTo(INITIAL_VIEW.center, INITIAL_VIEW.zoom, { animate:true, duration:.9 });

    document.getElementById('btn-reset-zoom').classList.add('hidden');
    document.getElementById('btn-reset-zoom').classList.remove('flex');

    const bc = document.getElementById('map-breadcrumb');
    bc.classList.add('hidden');
    bc.classList.remove('flex');

    setProvSel(`
      <svg class="w-4 h-4 flex-shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
      </svg>
      Pilih provinsi pada peta
    `);
    setKecList('<p class="text-xs text-slate-400 text-center py-10 leading-relaxed">Silakan pilih provinsi<br>untuk menampilkan data.</p>');
    document.getElementById('sidebar-list-title').textContent = 'Daftar Kabupaten';

    addProvinsiMarkers();
  }

  document.getElementById('btn-reset-zoom').addEventListener('click', resetToIndonesia);

})();
</script>

@endsection