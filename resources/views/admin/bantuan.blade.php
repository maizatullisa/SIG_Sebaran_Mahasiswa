@extends('layouts.admin')

@section('page-title', 'Bantuan')

@section('content')

<div class="hl-page">

    <div class="hl-heading">
        <h1 class="hl-title">Bantuan</h1>
        <p class="hl-subtitle">Panduan penggunaan Sistem Informasi Geografis Sebaran Mahasiswa</p>
    </div>

    <p class="hl-section-label">Fitur sistem</p>

    <div class="hl-grid">

        <div class="hl-card">
            <div class="hl-icon" style="background:#eff6ff">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            <div>
                <h3 class="hl-card-title">Dashboard</h3>
                <p class="hl-card-text">Ringkasan sistem — jumlah mahasiswa, kaprodi, statistik sebaran, dan informasi penting lainnya.</p>
            </div>
        </div>

        <div class="hl-card">
            <div class="hl-icon" style="background:#f0fdf4">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            </div>
            <div>
                <h3 class="hl-card-title">Peta sebaran</h3>
                <p class="hl-card-text">Visualisasi lokasi mahasiswa pada peta. Tersedia pencarian, detail, dan analisis per wilayah.</p>
            </div>
        </div>

        <div class="hl-card">
            <div class="hl-icon" style="background:#faf5ff">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <h3 class="hl-card-title">Data mahasiswa</h3>
                <p class="hl-card-text">Tambah, ubah, hapus, dan lihat data mahasiswa yang terdaftar dalam sistem.</p>
            </div>
        </div>

        <div class="hl-card">
            <div class="hl-icon" style="background:#fff7ed">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <h3 class="hl-card-title">Data kaprodi</h3>
                <p class="hl-card-text">Kelola data ketua program studi yang memiliki akses ke sistem.</p>
            </div>
        </div>

        <div class="hl-card">
            <div class="hl-icon" style="background:#fdf2f8">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#db2777" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <h3 class="hl-card-title">Statistik</h3>
                <p class="hl-card-text">Grafik data mahasiswa berdasarkan program studi, angkatan, wilayah, dan lainnya.</p>
            </div>
        </div>

    </div>

    <p class="hl-section-label" style="margin-top:1.75rem">Tips penggunaan</p>

    <div class="hl-tips">
        <div class="hl-tips-header">
            <div class="hl-tips-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            </div>
            <span class="hl-tips-heading">3 tips untuk admin</span>
        </div>
        <div class="hl-tip-item">
            <svg class="hl-tip-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <p>Gunakan fitur pencarian untuk menemukan data lebih cepat.</p>
        </div>
        <div class="hl-tip-item">
            <svg class="hl-tip-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>
            <p>Perbarui data mahasiswa secara berkala agar informasi tetap akurat.</p>
        </div>
        <div class="hl-tip-item">
            <svg class="hl-tip-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <p>Gunakan menu statistik untuk analisis persebaran mahasiswa.</p>
        </div>
    </div>

</div>

<style>
.hl-page { padding: 1.75rem; background: #f8fafc; min-height: 100vh; }

.hl-heading { margin-bottom: 1.5rem; }
.hl-title { font-size: 1.375rem; font-weight: 700; color: #0f172a; margin: 0 0 0.25rem; letter-spacing: -0.01em; }
.hl-subtitle { font-size: 0.875rem; color: #64748b; margin: 0; }

.hl-section-label {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    color: #94a3b8;
    margin: 0 0 0.625rem;
}

.hl-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 10px;
}

.hl-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem 1.125rem;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.hl-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.hl-card-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #0f172a;
    margin: 0 0 0.25rem;
}
.hl-card-text {
    font-size: 0.8rem;
    color: #64748b;
    margin: 0;
    line-height: 1.6;
}

.hl-tips {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 12px;
    padding: 1rem 1.125rem;
}
.hl-tips-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}
.hl-tips-icon {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    background: #dbeafe;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.hl-tips-heading {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1d4ed8;
}
.hl-tip-item {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 7px 0;
    border-top: 1px solid #bfdbfe;
}
.hl-tip-item:first-of-type { border-top: none; padding-top: 0; }
.hl-tip-icon { flex-shrink: 0; margin-top: 1px; }
.hl-tip-item p { font-size: 0.8rem; color: #1e40af; margin: 0; line-height: 1.55; }
</style>

@endsection