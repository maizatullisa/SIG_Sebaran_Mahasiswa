@extends('layouts.admin')

@section('content')

<div class="db-page">

    {{-- ── TOP BAR ── --}}
    <div class="db-topbar">
        <div>
            <p class="db-topbar__eyebrow">Panel Administrator</p>
            <h1 class="db-topbar__title">
                Selamat datang, <span class="db-topbar__name">{{ auth()->user()->name }}</span>
            </h1>
        </div>
        <div class="db-topbar__right">
            <div class="db-date-chip">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
            </div>
        </div>
    </div>

    <div class="db-body">

        {{-- ── ROW 1: KPI CARDS ── --}}
        <div class="db-kpi-grid">

            {{-- Mahasiswa --}}
            <div class="db-card db-card--blue">
                <div class="db-card__top">
                    <div class="db-card__icon db-card__icon--blue">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="db-badge db-badge--green">
                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        +2.4%
                    </span>
                </div>
                <p class="db-card__value">{{ number_format($totalMahasiswa) }}</p>
                <p class="db-card__label">Total Mahasiswa</p>
            </div>

            {{-- Admin --}}
            <div class="db-card db-card--violet">
                <div class="db-card__top">
                    <div class="db-card__icon db-card__icon--violet">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="db-badge db-badge--green">
                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        Aktif
                    </span>
                </div>
                <p class="db-card__value">{{ number_format($totalAdmin) }}</p>
                <p class="db-card__label">Total Admin</p>
            </div>

            {{-- Kaprodi --}}
            <div class="db-card db-card--cyan">
                <div class="db-card__top">
                    <div class="db-card__icon db-card__icon--cyan">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <span class="db-badge db-badge--cyan">12 prodi</span>
                </div>
                <p class="db-card__value">{{ number_format($totalKaprodi) }}</p>
                <p class="db-card__label">Total Kaprodi</p>
            </div>

            {{-- Rasio --}}
            <div class="db-card db-card--amber">
                <div class="db-card__top">
                    <div class="db-card__icon db-card__icon--amber">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="db-badge db-badge--amber">Rasio</span>
                </div>
                @php $rasio = $totalKaprodi > 0 ? round($totalMahasiswa / $totalKaprodi) : 0; @endphp
                <p class="db-card__value">{{ $rasio }}:1</p>
                <p class="db-card__label">Mhs / Kaprodi</p>
            </div>

        </div>

        {{-- ── ROW 2: CHART + MENU ── --}}
        <div class="db-row2">

            {{-- Chart Provinsi --}}
            <div class="db-chart-panel">
                <div class="db-chart-panel__header">
                    <div>
                        <div class="db-chart-panel__title-row">
                            <div class="db-chart-panel__accent"></div>
                            <h2 class="db-chart-panel__title">Sebaran Mahasiswa Fakultas</h2>
                        </div>
                        <p class="db-chart-panel__sub">Top 3 Provinsi Terbanyak</p>
                    </div>
                    <!-- <div class="db-chart-panel__total">
                        <span class="db-chart-panel__total-num">{{ number_format($totalMahasiswa) }}</span>
                        <span class="db-chart-panel__total-label">total mahasiswa</span>
                    </div> -->
                </div>

                <div class="db-rank-badges">
                    @foreach($provinsiTerbanyak as $i => $item)
                    <div class="db-rank-badge">
                        <span class="db-rank-badge__num db-rank-badge__num--{{ $i }}">
                            #{{ $i + 1 }}
                        </span>
                        <span class="db-rank-badge__name">{{ Str::limit($item->provinsi, 14) }}</span>
                        <span class="db-rank-badge__pct">{{ $item->persen }}%</span>
                    </div>
                    @endforeach
                </div>

                <div class="db-chart-wrap">
                    <canvas id="chartProvinsi"
                            role="img"
                            aria-label="Bar chart vertikal top 5 provinsi asal mahasiswa">
                        Data sebaran provinsi mahasiswa.
                    </canvas>
                </div>
            </div>

            {{-- Menu Kelola --}}
            @if (auth()->user()->role === 'admin')
            <div class="db-menu-panel">
                <div class="db-menu-panel__header">
                    <h2 class="db-menu-panel__title">Menu Kelola</h2>
                    <p class="db-menu-panel__sub">Akses cepat manajemen data</p>
                </div>

                <div class="db-menu-list">

                    <a href="{{ route('admin.kaprodi.index') }}" class="db-menu-item db-menu-item--violet">
                        <div class="db-menu-item__icon db-menu-item__icon--violet">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="db-menu-item__text">
                            <p class="db-menu-item__name">Kelola Kaprodi</p>
                            <p class="db-menu-item__desc">Manajemen data kaprodi</p>
                        </div>
                        <svg class="db-menu-item__arrow" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('admin.mahasiswa.index') }}" class="db-menu-item db-menu-item--blue">
                        <div class="db-menu-item__icon db-menu-item__icon--blue">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div class="db-menu-item__text">
                            <p class="db-menu-item__name">Kelola Mahasiswa</p>
                            <p class="db-menu-item__desc">Tambah, edit, hapus, lihat data</p>
                        </div>
                        <svg class="db-menu-item__arrow" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('admin.peta.index') }}" class="db-menu-item db-menu-item--emerald">
                        <div class="db-menu-item__icon db-menu-item__icon--emerald">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                        
                        <div class="db-menu-item__text">
                            <p class="db-menu-item__name">Peta Sebaran</p>
                            <p class="db-menu-item__desc">Visualisasi lokasi mahasiswa</p>
                        </div>
                        <svg class="db-menu-item__arrow" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <div class="db-menu-divider">
                        <p class="db-menu-divider__date">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    </div>

                </div>
            </div>

            @endif

            @if(auth()->user()->role === 'kaprodi')
        <div class="db-menu-panel">
            <div class="db-menu-panel__header">
                <h2 class="db-menu-panel__title">Akses Kaprodi</h2>
                <p class="db-menu-panel__sub">Monitoring dan analisis mahasiswa</p>
            </div>

            <div class="db-menu-list">

                <a href="{{ route('admin.mahasiswa.index') }}"
                class="db-menu-item db-menu-item--blue">
                    <div class="db-menu-item__icon db-menu-item__icon--blue">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/>
                        </svg>
                    </div>
                    <div class="db-menu-item__text">
                        <p class="db-menu-item__name">Data Mahasiswa</p>
                        <p class="db-menu-item__desc">Lihat dan export data mahasiswa</p>
                    </div>
                </a>

                <a href="{{ route('admin.peta.index') }}"
                class="db-menu-item db-menu-item--emerald">
                    <div class="db-menu-item__icon db-menu-item__icon--emerald">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7"/>
                        </svg>
                    </div>
                    <div class="db-menu-item__text">
                        <p class="db-menu-item__name">Peta Sebaran</p>
                        <p class="db-menu-item__desc">Analisis wilayah asal mahasiswa</p>
                    </div>
                </a>

                 <div class="db-menu-divider">
                        <p class="db-menu-divider__date">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                    </div>


            </div>
        </div>
        @endif

        </div>

    </div>
</div>

<style>
/* ============================================================
   PAGE
   ============================================================ */
.db-page {
    background: #f8fafc;
    min-height: 100vh;
}

/* ============================================================
   TOP BAR
   ============================================================ */
.db-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem 1.75rem 0.75rem;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.db-topbar__eyebrow {
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 0.3rem;
}
.db-topbar__title {
    font-size: 1.375rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.01em;
}
.db-topbar__name { color: #2563eb; }
.db-topbar__right { display: flex; align-items: center; gap: 0.75rem; }
.db-date-chip {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    color: #64748b;
    background: #fff;
    border: 1px solid #e2e8f0;
    padding: 0.45rem 0.875rem;
    border-radius: 8px;
}

/* ============================================================
   BODY
   ============================================================ */
.db-body {
    padding: 1rem 1.75rem 2rem;
    display: flex;
    flex-direction: column;
    gap: 1.125rem;
}

/* ============================================================
   KPI CARDS
   ============================================================ */
.db-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}
@media (max-width: 1024px) { .db-kpi-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 560px)  { .db-kpi-grid { grid-template-columns: 1fr; } }

.db-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1.25rem;
    transition: box-shadow 0.15s, transform 0.15s;
    position: relative;
    overflow: hidden;
}
.db-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 0 0 14px 14px;
}
.db-card--blue::after   { background: #2563eb; }
.db-card--violet::after { background: #7c3aed; }
.db-card--cyan::after   { background: #0891b2; }
.db-card--amber::after  { background: #d97706; }

.db-card:hover {
    box-shadow: 0 6px 24px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.db-card__top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.db-card__icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.db-card__icon--blue   { background: #eff6ff; color: #2563eb; }
.db-card__icon--violet { background: #f5f3ff; color: #7c3aed; }
.db-card__icon--cyan   { background: #ecfeff; color: #0891b2; }
.db-card__icon--amber  { background: #fffbeb; color: #d97706; }

.db-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.22rem 0.55rem;
    border-radius: 6px;
}
.db-badge--green { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.db-badge--cyan  { background: #ecfeff; color: #0e7490; border: 1px solid #a5f3fc; }
.db-badge--amber { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }

.db-card__value {
    font-size: 2rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.03em;
    line-height: 1;
    margin-bottom: 0.3rem;
}
.db-card__label {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 500;
}

/* ============================================================
   ROW 2 LAYOUT
   ============================================================ */
.db-row2 {
    display: grid;
    grid-template-columns: 3fr 2fr;
    gap: 1rem;
}
@media (max-width: 1024px) { .db-row2 { grid-template-columns: 1fr; } }

/* ============================================================
   CHART PANEL
   ============================================================ */
.db-chart-panel {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
}
.db-chart-panel__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1.25rem 1.5rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.db-chart-panel__title-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}
.db-chart-panel__accent {
    width: 3px; height: 16px;
    border-radius: 99px;
    background: #2563eb;
    flex-shrink: 0;
}
.db-chart-panel__title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #0f172a;
}
.db-chart-panel__sub {
    font-size: 0.75rem;
    color: #94a3b8;
    padding-left: 0.625rem;
}
.db-chart-panel__total { text-align: right; }
.db-chart-panel__total-num {
    display: block;
    font-size: 1.75rem;
    font-weight: 700;
    color: #2563eb;
    letter-spacing: -0.02em;
    line-height: 1;
}
.db-chart-panel__total-label {
    font-size: 0.72rem;
    color: #94a3b8;
}

.db-rank-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    padding: 0.875rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
}
.db-rank-badge {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.3rem 0.625rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 7px;
}
.db-rank-badge__num {
    font-size: 0.72rem;
    font-weight: 700;
}
.db-rank-badge__num--0 { color: #f59e0b; }
.db-rank-badge__num--1 { color: #94a3b8; }
.db-rank-badge__num--2 { color: #f97316; }
.db-rank-badge__num--3,
.db-rank-badge__num--4 { color: #cbd5e1; }
.db-rank-badge__name {
    font-size: 0.75rem;
    font-weight: 500;
    color: #374151;
}
.db-rank-badge__pct {
    font-size: 0.72rem;
    font-weight: 700;
    color: #2563eb;
}

.db-chart-wrap {
    padding: 1rem 1.5rem 1.25rem;
    position: relative;
    height: 260px;
}
.db-chart-wrap canvas {
    width: 100% !important;
    height: 100% !important;
}

/* ============================================================
   MENU PANEL
   ============================================================ */
.db-menu-panel {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1.25rem;
}
.db-menu-panel__header { margin-bottom: 1rem; }
.db-menu-panel__title {
    font-size: 0.9rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.2rem;
}
.db-menu-panel__sub { font-size: 0.75rem; color: #94a3b8; }

.db-menu-list { display: flex; flex-direction: column; gap: 0.625rem; }

.db-menu-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    text-decoration: none;
    transition: border-color 0.15s, background 0.15s;
}
.db-menu-item--violet:hover { border-color: #a78bfa; background: #f5f3ff; }
.db-menu-item--blue:hover   { border-color: #93c5fd; background: #eff6ff; }
.db-menu-item--emerald:hover{ border-color: #6ee7b7; background: #f0fdf4; }

.db-menu-item__icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.db-menu-item__icon--violet  { background: #ede9fe; color: #7c3aed; }
.db-menu-item__icon--blue    { background: #dbeafe; color: #2563eb; }
.db-menu-item__icon--emerald { background: #d1fae5; color: #059669; }

.db-menu-item__text { flex: 1; min-width: 0; }
.db-menu-item__name {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 0.15rem;
}
.db-menu-item__desc {
    font-size: 0.72rem;
    color: #94a3b8;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.db-menu-item__arrow { color: #cbd5e1; flex-shrink: 0; }
.db-menu-item--violet:hover .db-menu-item__arrow { color: #a78bfa; }
.db-menu-item--blue:hover   .db-menu-item__arrow { color: #60a5fa; }
.db-menu-item--emerald:hover .db-menu-item__arrow { color: #34d399; }

.db-menu-divider {
    border-top: 1px solid #f1f5f9;
    padding-top: 0.75rem;
    margin-top: 0.25rem;
    text-align: center;
}
.db-menu-divider__date { font-size: 0.72rem; color: #94a3b8; }
</style>

{{-- ── CHART.JS ── --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
(function() {

    const htmlEl = document.getElementById('html-root') || document.documentElement;

    function applyTheme(theme) {
        if (theme === 'dark') {
            htmlEl.classList.add('dark');
        } else {
            htmlEl.classList.remove('dark');
        }
    }

    window.toggleTheme = function() {
        const isDark = htmlEl.classList.contains('dark');
        const next = isDark ? 'light' : 'dark';
        localStorage.setItem('theme', next);
        applyTheme(next);
        renderChart();
    };

    const saved = localStorage.getItem('theme') || 'dark';
    applyTheme(saved);

    const provinsiData = @json($provinsiTerbanyak);

    let chartInstance = null;

    const topLabelPlugin = {
        id: 'topLabel',
        afterDatasetsDraw(chart) {
            const { ctx, data, scales: { x, y } } = chart;
            const isDk = document.getElementById('html-root')
                ? document.getElementById('html-root').classList.contains('dark')
                : document.documentElement.classList.contains('dark');
            ctx.save();
            data.datasets[0].data.forEach((val, i) => {
                const bar    = chart.getDatasetMeta(0).data[i];
                const xPos   = bar.x;
                const yPos   = bar.y - 7;
                ctx.fillStyle    = isDk ? '#94a3b8' : '#374151';
                ctx.font         = '600 11px system-ui, sans-serif';
                ctx.textAlign    = 'center';
                ctx.textBaseline = 'bottom';
                ctx.fillText(val.toFixed(1) + '%', xPos, yPos);
            });
            ctx.restore();
        }
    };

    function renderChart() {
        const isDark = htmlEl.classList.contains('dark');

        const labels = provinsiData.map(d => d.provinsi);
        const values = provinsiData.map(d => parseFloat(d.persen));

        const gridColor  = isDark ? 'rgba(255,255,255,0.04)' : 'rgba(0,0,0,0.06)';
        const tickColor  = isDark ? '#475569' : '#9ca3af';
        const labelColor = isDark ? '#94a3b8' : '#374151';

        const barBg = isDark
            ? ['#1e40af','#1d4ed8','#2563eb','#3b82f6','#60a5fa']
            : ['#1e3a8a','#1e40af','#1d4ed8','#2563eb','#3b82f6'];

        const barHover = isDark
            ? ['#1d4ed8','#2563eb','#3b82f6','#60a5fa','#93c5fd']
            : ['#1d4ed8','#2563eb','#3b82f6','#60a5fa','#93c5fd'];

        if (chartInstance) chartInstance.destroy();

        const ctx = document.getElementById('chartProvinsi').getContext('2d');
        chartInstance = new Chart(ctx, {
            type: 'bar',
            plugins: [topLabelPlugin],
            data: {
                labels,
                datasets: [{
                    label: '% Mahasiswa',
                    data: values,
                    backgroundColor: barBg,
                    hoverBackgroundColor: barHover,
                    borderRadius: { topLeft: 6, topRight: 6 },
                    borderSkipped: 'bottom',
                    barThickness: 38
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 24 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#0f172a' : '#1e293b',
                        titleColor: isDark ? '#94a3b8' : '#cbd5e1',
                        bodyColor: '#f1f5f9',
                        borderColor: isDark ? '#1e293b' : '#334155',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            title: items => items[0].label,
                            label: c => '  ' + c.parsed.y.toFixed(1) + '% dari total mahasiswa'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            color: labelColor,
                            font: { size: 11, weight: '500' },
                            maxRotation: 0,
                            autoSkip: false
                        }
                    },
                    y: {
                        grid: { color: gridColor, drawBorder: false },
                        border: { display: false },
                        ticks: {
                            color: tickColor,
                            font: { size: 11 },
                            callback: v => v + '%',
                            stepSize: 5
                        },
                        min: 0,
                        max: Math.ceil(Math.max(...values) * 1.3)
                    }
                },
                animation: {
                    duration: 700,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    renderChart();

})();
</script>

@endsection