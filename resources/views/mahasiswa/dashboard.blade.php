@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css"/>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --blue:      #1a6e9e;
        --blue-dark: #0f4d80;
        --blue-mid:  #2d7fa8;
        --blue-light:#e8f2f9;
        --text:      #1a1a1a;
        --muted:     #6b7280;
        --border:    #ebebeb;
    }

    .db-wrap * { font-family: 'Inter', sans-serif; }
    .db-wrap .serif { font-family: 'EB Garamond', serif; }

    /* ── HERO BANNER ── */
    .db-hero {
        background: var(--blue);
        border-radius: 6px;
        padding: 36px 40px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .db-hero::before {
        content: '';
        position: absolute; top: -50px; right: -50px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,0.05); pointer-events: none;
    }
    .db-hero::after {
        content: '';
        position: absolute; bottom: -70px; left: -30px;
        width: 260px; height: 260px; border-radius: 50%;
        background: rgba(255,255,255,0.04); pointer-events: none;
    }
    .db-hero-tag {
        font-size: 10px; letter-spacing: 0.16em;
        text-transform: uppercase; color: rgba(255,255,255,0.55);
        font-weight: 600; margin-bottom: 10px;
        display: flex; align-items: center; gap: 8px;
    }
    .db-hero-tag::before {
        content: ''; width: 16px; height: 1.5px;
        background: rgba(255,255,255,0.4); display: inline-block;
    }
    .db-hero h2 {
        font-family: 'EB Garamond', serif;
        font-size: clamp(22px, 3vw, 32px);
        font-weight: 500; color: #fff; line-height: 1.2;
        margin-bottom: 6px; position: relative; z-index: 1;
    }
    .db-hero p {
        font-size: 13px; color: rgba(255,255,255,0.65);
        font-weight: 300; position: relative; z-index: 1;
    }

    /* ── SECTION LABEL ── */
    .section-label {
        font-size: 10px; letter-spacing: 0.14em;
        text-transform: uppercase; color: var(--muted);
        font-weight: 600;
    }

    /* ── CARDS ── */
    .db-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 24px 28px;
        position: relative;
        transition: box-shadow .25s, border-color .25s;
    }
    .db-card:hover {
        box-shadow: 0 4px 24px rgba(26,110,158,0.08);
        border-color: #d8e8f4;
    }

    /* ── PROFILE CARD ── */
    .profile-row {
        display: flex; flex-direction: column;
        gap: 0;
    }
    .profile-field {
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
    }
    .profile-field:first-child { padding-top: 4px; }
    .profile-field:last-child { border-bottom: none; padding-bottom: 0; }
    .profile-field-label {
        font-size: 10px; text-transform: uppercase;
        letter-spacing: 0.1em; color: var(--blue);
        font-weight: 600; margin-bottom: 3px;
    }
    .profile-field-val {
        font-size: 14px; color: var(--text); font-weight: 500;
    }

    /* ── MAP ── */
    #map {
        height: 300px; width: 100%; display: block;
        border-radius: 4px; overflow: hidden;
    }
    .map-empty {
        height: 300px;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center; gap: 8px;
        border: 1.5px dashed var(--border);
        border-radius: 4px; color: #ccc;
        font-size: 13px; font-weight: 300;
    }
    .map-empty svg { opacity: 0.3; }

    /* Status badge */
    .db-badge {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 11px; font-weight: 600;
        padding: 3px 10px; border-radius: 20px;
    }
    .db-badge.filled      { background: #e8f5ee; color: #1a7a4a; }
    .db-badge.empty       { background: #fdf2f2; color: #b94040; }

    /* Prodi badge variants */
    .db-badge.prodi-ti    { background: #fdecea; color: #c0392b; } /* Teknik Informatika — merah */
    .db-badge.prodi-si    { background: #e8f2f9; color: #1a6e9e; } /* Sistem Informasi — biru */
    .db-badge.prodi-tk    { background: #fdf3e7; color: #9a6012; } /* Teknik Kimia — coklat */
    .db-badge.prodi-tin   { background: #f2eefa; color: #6b3fa0; } /* Teknik Industri — ungu */
    .db-badge.prodi-te    { background: #eaf5ec; color: #1e7e34; } /* Teknik Elektro — hijau */
    .db-badge.prodi-other { background: #f3f4f6; color: #4b5563; } /* Prodi lain — abu */

    .db-badge-dot {
        width: 5px; height: 5px; border-radius: 50%;
        background: currentColor;
    }

    /* ── STATUS CARDS ── */
    .status-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 22px 24px;
        transition: box-shadow .25s, border-color .25s;
        cursor: default;
    }
    .status-card:hover {
        box-shadow: 0 4px 24px rgba(26,110,158,0.08);
        border-color: #d8e8f4;
    }
    .status-card-title {
        font-size: 10px; letter-spacing: 0.14em;
        text-transform: uppercase; color: var(--muted);
        font-weight: 600; margin-bottom: 12px;
    }
    .status-card-val {
        font-family: 'EB Garamond', serif;
        font-size: 22px; font-weight: 400;
        color: var(--text); margin-bottom: 14px;
        line-height: 1;
    }
    .status-bar-track {
        height: 2px; background: var(--border);
        border-radius: 2px; overflow: hidden;
    }
    .status-bar-fill {
        height: 100%; border-radius: 2px;
        background: var(--blue);
        transition: width .6s cubic-bezier(0.215,0.61,0.355,1);
    }

    /* ── PROGRESS SUMMARY ── */
    .progress-ring-wrap {
        display: flex; align-items: center; gap: 20px;
        padding: 20px 28px;
        background: var(--blue-light);
        border-radius: 6px;
        border: 1px solid #c8dff0;
        margin-bottom: 24px;
    }
    .progress-ring-wrap p {
        font-size: 13px; color: var(--text); font-weight: 400; line-height: 1.6;
    }
    .progress-ring-wrap strong {
        color: var(--blue); font-weight: 600;
    }

    /* Leaflet popup overrides — match landing */
    .leaflet-popup-content-wrapper {
        background: rgba(10,30,55,0.92) !important;
        border: 1px solid rgba(79,195,247,0.25) !important;
        border-radius: 4px !important;
        box-shadow: 0 4px 24px rgba(0,0,0,0.3) !important;
        color: #fff !important;
    }
    .leaflet-popup-tip { background: rgba(10,30,55,0.92) !important; }
    .leaflet-popup-content { margin: 10px 14px !important; }
    .leaflet-popup-close-button { color: rgba(255,255,255,0.5) !important; }
    .leaflet-popup-close-button:hover { color: #fff !important; }
    .leaflet-control-zoom a {
        background: rgba(10,30,55,0.85) !important;
        border-color: rgba(255,255,255,0.12) !important;
        color: rgba(255,255,255,0.8) !important;
    }
    .leaflet-control-zoom a:hover {
        background: rgba(26,110,158,0.9) !important;
        color: #fff !important;
    }

    /* Pulse dot */
    @keyframes mapPulse {
        0% { transform: scale(0.5); opacity: 1; }
        100% { transform: scale(2.5); opacity: 0; }
    }
    .map-pulse-dot {
        position: relative; width: 14px; height: 14px;
        background: #4fc3f7; border-radius: 50%;
        border: 2px solid #fff; box-shadow: 0 0 10px rgba(79,195,247,0.8);
    }
    .map-pulse-dot::after {
        content: ''; position: absolute; top: -2px; left: -2px;
        width: 14px; height: 14px; border-radius: 50%;
        border: 2px solid #4fc3f7;
        animation: mapPulse 1.8s infinite ease-out;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
        .db-hero { padding: 24px 20px; }
        .db-card { padding: 18px 16px; }
        .progress-ring-wrap { flex-direction: column; align-items: flex-start; gap: 8px; }
    }
</style>
@endpush

@section('content')

@php
    $m      = $mahasiswa ?? null;
    $alamat = $alamat ?? null;

    // Hitung kelengkapan data (0–3)
    $filled = collect([$alamat, $sekolah ?? null, $minat ?? null])->filter()->count();
    $pct    = round($filled / 3 * 100);
@endphp

<div class="db-wrap">

    {{-- ── HERO ── --}}
    <div class="db-hero">
        <div class="db-hero-tag">Dashboard Mahasiswa</div>
        <h2>Halo, {{ optional($m)->nama ?? 'Mahasiswa' }} 👋</h2>
        <p>Selamat datang di Sistem Informasi Geografis Sebaran Mahasiswa Fakultas Teknik.</p>
    </div>

    {{-- ── PROGRESS SUMMARY ── --}}
    <div class="progress-ring-wrap">
        <div style="flex-shrink:0; width:48px; height:48px; border-radius:50%;
            background:var(--blue); display:flex; align-items:center; justify-content:center;">
            <span style="font-family:'EB Garamond',serif; font-size:18px; font-weight:600; color:#fff;">
                {{ $pct }}%
            </span>
        </div>
        <p>
            Profil kamu sudah <strong>{{ $pct }}% lengkap</strong> —
            @if($pct === 100)
                semua data telah terisi. Terima kasih sudah berkontribusi!
            @else
                lengkapi sisa data agar titik sebaranmu terdaftar di peta.
            @endif
        </p>
    </div>

    {{-- ── MAIN GRID: PROFIL + PETA ── --}}
    <div class="grid lg:grid-cols-3 gap-5 mb-5">

        {{-- PROFIL --}}
        <div class="db-card">
            <p class="section-label" style="margin-bottom:16px;">Data Mahasiswa</p>
            <div class="profile-row">
                <div class="profile-field">
                    <p class="profile-field-label">NIM</p>
                    <p class="profile-field-val">{{ optional($m)->nim ?? '—' }}</p>
                </div>
                <div class="profile-field">
                    <p class="profile-field-label">Nama</p>
                    <p class="profile-field-val">{{ optional($m)->nama ?? '—' }}</p>
                </div>
                <div class="profile-field">
                    <p class="profile-field-label">E-Mail</p>
                    <p class="profile-field-val" style="word-break:break-all;">{{ $user->email ?? '—' }}</p>
                </div>
                <div class="profile-field">
                    <p class="profile-field-label">Program Studi</p>
                    @php
                        $prodi     = optional($m)->prodi ?? null;
                        $prodiLow  = strtolower($prodi ?? '');
                        $prodiClass = match(true) {
                            str_contains($prodiLow, 'informatika')  => 'prodi-ti',
                            str_contains($prodiLow, 'sistem informasi') => 'prodi-si',
                            str_contains($prodiLow, 'kimia')        => 'prodi-tk',
                            str_contains($prodiLow, 'industri')     => 'prodi-tin',
                            str_contains($prodiLow, 'elektro')      => 'prodi-te',
                            default                                  => 'prodi-other',
                        };
                    @endphp
                    <span class="db-badge {{ $prodi ? $prodiClass : 'prodi-other' }}">
                        <span class="db-badge-dot"></span>{{ $prodi ?? '—' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- PETA --}}
        <div class="db-card lg:col-span-2">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
                <p class="section-label">Lokasi Saya</p>
                @if($alamat && $alamat->latitude)
                    <span class="db-badge filled"><span class="db-badge-dot"></span> Tersimpan</span>
                @else
                    <span class="db-badge empty"><span class="db-badge-dot"></span> Belum diisi</span>
                @endif
            </div>

            @if($alamat && $alamat->latitude && $alamat->longitude)
                <div style="border:1px solid var(--border); border-radius:4px; overflow:hidden;">
                    <div id="map"></div>
                </div>
            @else
                <div class="map-empty">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="1.5">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                        <circle cx="12" cy="9" r="2.5"/>
                    </svg>
                    <span>Lokasi belum diisi</span>
                </div>
            @endif
        </div>

    </div>

    {{-- ── STATUS CARDS ── --}}
    <div class="grid md:grid-cols-3 gap-4">

        <div class="status-card">
            <p class="status-card-title">Alamat</p>
            <p class="status-card-val serif">{{ $alamat ? 'Lengkap' : 'Belum terisi' }}</p>
            <div class="status-bar-track">
                <div class="status-bar-fill" style="width:{{ $alamat ? '100' : '0' }}%"></div>
            </div>
        </div>

        <div class="status-card">
            <p class="status-card-title">Sekolah Asal</p>
            <p class="status-card-val serif">{{ ($sekolah ?? null) ? 'Lengkap' : 'Belum terisi' }}</p>
            <div class="status-bar-track">
                <div class="status-bar-fill" style="width:{{ ($sekolah ?? null) ? '100' : '0' }}%"></div>
            </div>
        </div>

        <div class="status-card">
            <p class="status-card-title">Minat &amp; Bakat</p>
            <p class="status-card-val serif">{{ ($minat ?? null) ? 'Lengkap' : 'Belum terisi' }}</p>
            <div class="status-bar-track">
                <div class="status-bar-fill" style="width:{{ ($minat ?? null) ? '100' : '0' }}%"></div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>

@if($alamat && $alamat->latitude && $alamat->longitude)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lat = {{ $alamat->latitude }};
    const lng = {{ $alamat->longitude }};

    const map = L.map('map', { zoomControl: true, scrollWheelZoom: false })
        .setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 20
    }).addTo(map);

    const icon = L.divIcon({
        className: '',
        html: '<div class="map-pulse-dot"></div>',
        iconSize: [14, 14], iconAnchor: [7, 7]
    });

    L.marker([lat, lng], { icon })
        .addTo(map)
        .bindPopup('<span style="font-size:12px;font-family:Inter,sans-serif;font-weight:600;color:#fff;">📍 Lokasi Saya</span>')
        .openPopup();

    setTimeout(() => map.invalidateSize(), 300);
});
</script>
@endif
@endpush