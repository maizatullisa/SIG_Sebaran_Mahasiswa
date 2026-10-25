@extends('layouts.admin')

@section('page-title', 'Tentang')

@section('content')

<div class="ab-page">
<div class="ab-card">

    {{-- HEADER --}}
    <div class="ab-header">
        <div class="ab-logo">
            <img src="{{ asset('images/unipma-logo.png') }}" alt="UNIPMA" style="width:36px;height:36px;object-fit:contain">
        </div>
        <div>
            <p class="ab-univ">Universitas PGRI Madiun</p>
            <h1 class="ab-title">Sistem Informasi Geografis Sebaran Mahasiswa</h1>
        </div>
    </div>

    {{-- BODY --}}
    <div class="ab-body">

        <div class="ab-section">
            <p class="ab-section-label">Tentang sistem</p>
            <p class="ab-prose">
                Sistem Informasi Geografis (SIG) Sebaran Mahasiswa merupakan aplikasi
                yang digunakan untuk memvisualisasikan persebaran lokasi mahasiswa
                Universitas PGRI Madiun berdasarkan data wilayah dan koordinat geografis.
            </p>
        </div>

        <div class="ab-info-grid">

            <div class="ab-info-card">
                <h4 class="ab-info-heading">Informasi sistem</h4>
                <div class="ab-info-row">
                    <span class="ab-info-label">Versi</span>
                    <span class="ab-info-val">1.0</span>
                </div>
                <div class="ab-info-row">
                    <span class="ab-info-label">Tahun</span>
                    <span class="ab-info-val">2026</span>
                </div>
                <div class="ab-info-row">
                    <span class="ab-info-label">Platform</span>
                    <span class="ab-info-val">Web Based GIS</span>
                </div>
            </div>

            <div class="ab-info-card">
                <h4 class="ab-info-heading">Teknologi</h4>
                <div class="ab-tech-item"><span class="ab-tech-dot" style="background:#2563eb"></span><span class="ab-tech-name">Laravel 12</span></div>
                <div class="ab-tech-item"><span class="ab-tech-dot" style="background:#f97316"></span><span class="ab-tech-name">MySQL</span></div>
                <div class="ab-tech-item"><span class="ab-tech-dot" style="background:#22c55e"></span><span class="ab-tech-name">Leaflet JS</span></div>
                <div class="ab-tech-item"><span class="ab-tech-dot" style="background:#06b6d4"></span><span class="ab-tech-name">Tailwind CSS</span></div>
            </div>

        </div>

        {{-- DEVELOPER --}}
        <div class="ab-dev">
            <div class="ab-dev-avatar">MLA</div>
            <div>
                <p class="ab-dev-name">Maizatul Lisa Aisyah</p>
                <p class="ab-dev-prodi">Program Studi Teknik Informatika</p>
            </div>
            <span class="ab-dev-badge">Pengembang</span>
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="ab-footer">
        <span class="ab-footer-text">SIG Mahasiswa &nbsp;·&nbsp; v1.0 &nbsp;·&nbsp; 2026</span>
    </div>

</div>
</div>

<style>
.ab-page { padding: 2rem 1.75rem; background: #f8fafc; min-height: 100vh; }

.ab-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    max-width: 720px;
    margin: 0 auto;
}

/* Header */
.ab-header {
    padding: 1.75rem 2rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 1.125rem;
}
.ab-logo {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ab-univ {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: #94a3b8;
    margin: 0 0 4px;
}
.ab-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    line-height: 1.3;
}

/* Body */
.ab-body { padding: 1.75rem 2rem; }
.ab-section { margin-bottom: 1.5rem; }
.ab-section-label {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: #94a3b8;
    margin: 0 0 .625rem;
}
.ab-prose {
    font-size: .875rem;
    color: #475569;
    line-height: 1.75;
    margin: 0;
}

/* Info grid */
.ab-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.ab-info-card {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 10px;
    padding: .875rem 1rem;
}
.ab-info-heading {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: #94a3b8;
    margin: 0 0 .625rem;
}
.ab-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 0;
    border-bottom: 1px solid #f1f5f9;
}
.ab-info-row:last-child { border-bottom: none; padding-bottom: 0; }
.ab-info-label { font-size: .8rem; color: #64748b; }
.ab-info-val { font-size: .8rem; font-weight: 600; color: #0f172a; }

.ab-tech-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 5px 0;
    border-bottom: 1px solid #f1f5f9;
}
.ab-tech-item:last-child { border-bottom: none; padding-bottom: 0; }
.ab-tech-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.ab-tech-name { font-size: .8rem; font-weight: 500; color: #0f172a; }

/* Developer */
.ab-dev {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
    padding: 1rem 1.125rem;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 1.5rem;
}
.ab-dev-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: .875rem;
    font-weight: 700;
    flex-shrink: 0;
}
.ab-dev-name { font-size: .875rem; font-weight: 600; color: #1e40af; margin: 0; }
.ab-dev-prodi { font-size: .8rem; color: #3b82f6; margin: 2px 0 0; }
.ab-dev-badge {
    margin-left: auto;
    font-size: .72rem;
    font-weight: 600;
    background: #dbeafe;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    border-radius: 5px;
    padding: .2rem .55rem;
    white-space: nowrap;
}

/* Footer */
.ab-footer {
    padding: .875rem 2rem;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: center;
}
.ab-footer-text { font-size: .75rem; color: #94a3b8; }
</style>

@endsection