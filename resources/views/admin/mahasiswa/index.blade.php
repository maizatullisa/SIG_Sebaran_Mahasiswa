@extends('layouts.admin')

@section('page-title', 'Data Mahasiswa')

@section('content')

<div class="mhs-page">

    {{-- ── HEADER ── --}}
    <div class="mhs-header">
        <div>
            <div class="mhs-header__title-row">
                <div class="mhs-header__accent"></div>
                <h1 class="mhs-header__title">Data Mahasiswa</h1>
            </div>
            <p class="mhs-header__sub">Kelola seluruh data mahasiswa terdaftar</p>
        </div>

        
@if(auth()->user()->role === 'kaprodi')
<a href="{{ route('admin.mahasiswa.export') }}"
   class="mhs-btn-primary">
    Export Excel
</a>
@endif

        @if(auth()->user()->role === 'admin')
        <button onclick="openTambah()" class="mhs-btn-primary">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Mahasiswa
        </button>
        @endif
    </div>

    {{-- ── FILTER ── --}}
    <div class="mhs-panel mhs-filter">
        <form method="GET">
            <div class="mhs-filter__grid">

                <div class="mhs-input-wrap">
                    <svg class="mhs-input-icon" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama atau NIM..."
                           class="mhs-input mhs-input--icon">
                </div>

                <select name="angkatan" class="mhs-select">
                    <option value="">Semua Angkatan</option>
                    @for($tahun = date('y'); $tahun >= 20; $tahun--)
                        <option value="{{ $tahun }}" {{ request('angkatan') == $tahun ? 'selected' : '' }}>
                            20{{ $tahun }}
                        </option>
                    @endfor
                </select>

                <select name="prodi" class="mhs-select">
                    <option value="">Semua Prodi</option>
                    @foreach($prodi as $item)
                        <option value="{{ $item }}" {{ request('prodi') == $item ? 'selected' : '' }}>
                            {{ $item }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="mhs-btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                    Filter
                </button>

            </div>
        </form>
    </div>

    {{-- ── TABEL ── --}}
    <div class="mhs-panel mhs-table-panel">

        <div class="mhs-table-info">
            <p class="mhs-table-info__text">
                Menampilkan <strong>{{ $mahasiswa->count() }}</strong>
                dari <strong>{{ $mahasiswa->total() }}</strong> mahasiswa
            </p>
            <span class="mhs-table-info__page">
                Halaman {{ $mahasiswa->currentPage() }} / {{ $mahasiswa->lastPage() }}
            </span>
        </div>

        <div class="mhs-table-scroll">
            <table class="mhs-table">
                <thead>
                    <tr class="mhs-thead-row">
                        <th class="mhs-th">NIM</th>
                        <th class="mhs-th">Nama</th>
                        <th class="mhs-th mhs-th--md">Prodi</th>
                        <th class="mhs-th mhs-th--sm">Angkatan</th>
                        <th class="mhs-th mhs-th--center">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($mahasiswa as $index => $m)
                    <tr class="mhs-tr">
                        <td class="mhs-td">
                            <span class="mhs-nim-badge">{{ $m->nim }}</span>
                        </td>
                        <td class="mhs-td">
                            <div class="mhs-name-cell">
                                <div class="mhs-avatar mhs-avatar--{{ $index % 4 }}" style="overflow: hidden; padding: 0;">
                                    @if($m->foto)
                                        <img 
                                            src="/storage/foto-profil/{{ $m->foto }}" 
                                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 7px;"
                                            onerror="this.style.display='none'; this.parentElement.innerHTML='{{ strtoupper(substr($m->nama, 0, 1)) }}'"
                                        >
                                    @else
                                        {{ strtoupper(substr($m->nama, 0, 1)) }}
                                    @endif
                                </div>
                                <span class="mhs-name-text">{{ $m->nama }}</span>
                            </div>
                        </td>
                        <td class="mhs-td mhs-td--md">
                            <span class="mhs-prodi-badge">{{ $m->prodi }}</span>
                        </td>
                        <td class="mhs-td mhs-td--sm">
                            <span class="mhs-angkatan">20{{ substr($m->nim, 0, 2) }}</span>
                        </td>
                        <td class="mhs-td mhs-td--center">
                            <div class="mhs-actions">
                                <button onclick="openModal({{ $m->id }})" class="mhs-btn-detail">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9.458 0C6.992 7.663 9.268 5 12 5c2.732 0 5.008 2.663 5.458 7-.45 4.337-2.726 7-5.458 7-2.732 0-5.008-2.663-5.458-7z"/>
                                    </svg>
                                    Detail
                                </button>

                                @if(auth()->user()->role === 'admin')
                                <button onclick="konfirmasiHapus({{ $m->id }}, '{{ $m->nama }}')" class="mhs-btn-hapus">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="mhs-empty">
                            <div class="mhs-empty__inner">
                                <div class="mhs-empty__icon">
                                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <p class="mhs-empty__title">Belum ada data mahasiswa</p>
                                <p class="mhs-empty__sub">Coba ubah filter pencarian</p>
                            </div>
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

        @if($mahasiswa->hasPages())
        <div class="mhs-pagination">
            {{ $mahasiswa->links() }}
        </div>
        @endif

    </div>

</div>


{{-- ════════════════════════════════════════════════
     MODAL DETAIL MAHASISWA
════════════════════════════════════════════════ --}}
<div id="detailModal" class="mhs-modal-backdrop hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">

    <div onclick="closeModal()" class="mhs-modal-overlay"></div>

    <div class="mhs-modal">

        <div class="mhs-modal__header">
            <div class="mhs-modal__header-left">
                <div id="modal-avatar" class="mhs-modal__avatar">
                    MA
                </div>
                <div>
                    <h2 id="modal-title" class="mhs-modal__name">Detail Mahasiswa</h2>
                    <p id="modal-nim" class="mhs-modal__nim">—</p>
                </div>
            </div>
            <button onclick="closeModal()" class="mhs-modal__close">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="mhs-modal__body">
            <div id="detailContent" class="mhs-modal__content"></div>
        </div>

       <div class="mhs-modal__footer">

    <span class="mhs-modal__footer-label">
        SIG Mahasiswa • Data Akademik
    </span>

    @if(auth()->user()->role === 'admin')
    <div style="display:flex;gap:.5rem;align-items:center;">

        <button
            type="button"
            id="editBtn"
            class="mhs-btn-detail">
            ✏️ Edit
        </button>

        <button
            type="button"
            id="saveBtn"
            class="mhs-btn-primary hidden">
            💾 Simpan
        </button>

        <button
            id="modal-hapus-btn"
            type="button"
            class="mhs-btn-hapus">

            <svg width="13" height="13"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>

            Hapus Data

        </button>

    </div>
    @endif

</div>

    </div>
</div>

<div id="tambahModal" class="mhs-modal-backdrop hidden">

    <div onclick="closeTambah()" class="mhs-modal-overlay"></div>

    <div class="mhs-modal">

        <form method="POST" action="{{ route('admin.mahasiswa.store') }}">
            @csrf

            <div class="mhs-modal__header">
                <h2 class="mhs-modal__name">
                    Tambah Mahasiswa
                </h2>

                <button type="button"
                        onclick="closeTambah()"
                        class="mhs-modal__close">
                    ✕
                </button>
            </div>

            <div class="mhs-modal__body">
                <div class="mhs-modal__content">

                    <div>
                        <label class="mhs-field__label">
                            NIM
                        </label>

                        <input
                            type="text"
                            name="nim"
                            class="mhs-input"
                            required>
                    </div>

                    <div>
                        <label class="mhs-field__label">
                            Nama
                        </label>

                        <input
                            type="text"
                            name="nama"
                            class="mhs-input"
                            required>
                    </div>

                    <div>
                        <label class="mhs-field__label">
                            Program Studi
                        </label>

                        <select
                            name="prodi"
                            class="mhs-select"
                            required>

                            <option value="">
                                Pilih Prodi
                            </option>

                            @foreach($prodi as $item)
                                <option value="{{ $item }}">
                                    {{ $item }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                </div>
            </div>

            <div class="mhs-modal__footer">
                <button
                    type="button"
                    onclick="closeTambah()"
                    class="mhs-btn-detail">
                    Batal
                </button>

                <button
                    type="submit"
                    class="mhs-btn-primary">
                    Simpan
                </button>
            </div>

        </form>

    </div>

</div>

<!-- LIGHTBOX PHOTO VIEWER -->
<div id="photoViewer">
    <div id="photoViewerOverlay"></div>
    <button type="button" class="viewer-close-btn" onclick="closePhotoViewer()">✕</button>
    <img id="viewerImg" src="">
</div>



<style>

/* ════════════════════════════════════
   LIGHTBOX PHOTO VIEWER
   ════════════════════════════════════ */
#photoViewer {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0, 0, 0, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

#photoViewer.show {
    opacity: 1;
    pointer-events: auto;
}

#photoViewerOverlay {
    position: absolute;
    inset: 0;
    cursor: pointer;
}

#viewerImg {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.viewer-close-btn {
    position: fixed;
    top: 20px;
    right: 20px;
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border: none;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    font-size: 28px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
    z-index: 10000;
}

.viewer-close-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}
/* ============================================================
   PAGE
   ============================================================ */
.mhs-page {
    padding: 2rem 1.75rem;
    background: #f8fafc;
    min-height: 100vh;
}

/* ============================================================
   HEADER
   ============================================================ */
.mhs-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}
.mhs-header__title-row {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    margin-bottom: 0.25rem;
}
.mhs-header__accent {
    width: 3px;
    height: 20px;
    border-radius: 99px;
    background: #2563eb;
    flex-shrink: 0;
}
.mhs-header__title {
    font-size: 1.375rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.01em;
}
.mhs-header__sub {
    font-size: 0.8rem;
    color: #64748b;
    padding-left: 0.875rem;
}

/* ============================================================
   BUTTONS
   ============================================================ */
.mhs-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #2563eb;
    color: #fff;
    font-size: 0.8125rem;
    font-weight: 600;
    padding: 0.55rem 1rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
}
.mhs-btn-primary:hover { background: #1d4ed8; }

.mhs-btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #2563eb;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    padding: 0.32rem 0.7rem;
    cursor: pointer;
    transition: background 0.15s;
}
.mhs-btn-detail:hover { background: #dbeafe; }

.mhs-btn-hapus {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #dc2626;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 6px;
    padding: 0.32rem 0.7rem;
    cursor: pointer;
    transition: background 0.15s;
}
.mhs-btn-hapus:hover { background: #fee2e2; }

/* ============================================================
   SHARED PANEL
   ============================================================ */
.mhs-panel {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
}

/* ============================================================
   FILTER
   ============================================================ */
.mhs-filter {
    padding: 1rem 1.125rem;
    margin-bottom: 1.125rem;
}
.mhs-filter__grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr auto;
    gap: 0.625rem;
    align-items: center;
}
@media (max-width: 900px) {
    .mhs-filter__grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 560px) {
    .mhs-filter__grid { grid-template-columns: 1fr; }
}

.mhs-input-wrap { position: relative; }
.mhs-input-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
}
.mhs-input {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.55rem 0.875rem;
    font-size: 0.8125rem;
    color: #0f172a;
    background: #f8fafc;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.mhs-input--icon { padding-left: 2.25rem; }
.mhs-input:focus {
    border-color: #93c5fd;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    background: #fff;
}
.mhs-input::placeholder { color: #94a3b8; }

.mhs-select {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.55rem 0.875rem;
    font-size: 0.8125rem;
    color: #0f172a;
    background: #f8fafc;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    cursor: pointer;
}
.mhs-select:focus {
    border-color: #93c5fd;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    background: #fff;
}

/* ============================================================
   TABLE
   ============================================================ */
.mhs-table-panel { overflow: hidden; }

.mhs-table-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
}
.mhs-table-info__text {
    font-size: 0.75rem;
    color: #94a3b8;
}
.mhs-table-info__text strong { color: #475569; font-weight: 600; }
.mhs-table-info__page {
    font-size: 0.75rem;
    color: #94a3b8;
}

.mhs-table-scroll { overflow-x: auto; }

.mhs-table {
    width: 100%;
    border-collapse: collapse;
}

.mhs-thead-row { background: #f8fafc; }

.mhs-th {
    padding: 0.75rem 1.25rem;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #64748b;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
    white-space: nowrap;
}
.mhs-th--center { text-align: center; }
.mhs-th--md { display: none; }
.mhs-th--sm { display: none; }
@media (min-width: 640px)  { .mhs-th--sm { display: table-cell; } }
@media (min-width: 768px)  { .mhs-th--md { display: table-cell; } }

.mhs-tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.1s;
}
.mhs-tr:last-child { border-bottom: none; }
.mhs-tr:hover { background: #f8fafc; }

.mhs-td {
    padding: 0.75rem 1.25rem;
    font-size: 0.8125rem;
    color: #1e293b;
    vertical-align: middle;
}
.mhs-td--center { text-align: center; }
.mhs-td--md { display: none; }
.mhs-td--sm { display: none; }
@media (min-width: 640px)  { .mhs-td--sm { display: table-cell; } }
@media (min-width: 768px)  { .mhs-td--md { display: table-cell; } }

/* NIM badge */
.mhs-nim-badge {
    font-size: 0.72rem;
    font-family: ui-monospace, monospace;
    font-weight: 600;
    color: #2563eb;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    padding: 0.22rem 0.55rem;
    border-radius: 6px;
    white-space: nowrap;
}

/* Name cell */
.mhs-name-cell {
    display: flex;
    align-items: center;
    gap: 0.625rem;
}
.mhs-avatar {
    width: 30px;
    height: 30px;
    border-radius: 7px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    font-weight: 700;
    color: #fff;
}
.mhs-avatar--0 { background: #2563eb; }
.mhs-avatar--1 { background: #7c3aed; }
.mhs-avatar--2 { background: #059669; }
.mhs-avatar--3 { background: #d97706; }

.mhs-name-text {
    font-size: 0.8125rem;
    font-weight: 500;
    color: #0f172a;
}

/* Prodi badge */
.mhs-prodi-badge {
    font-size: 0.72rem;
    font-weight: 500;
    color: #475569;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    padding: 0.22rem 0.55rem;
    border-radius: 6px;
    white-space: nowrap;
}

/* Angkatan */
.mhs-angkatan {
    font-size: 0.8125rem;
    color: #64748b;
    font-weight: 500;
}

/* Actions */
.mhs-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
}

/* Empty state */
.mhs-empty { padding: 4rem 1rem; text-align: center; }
.mhs-empty__inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}
.mhs-empty__icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    margin-bottom: 0.25rem;
}
.mhs-empty__title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #475569;
}
.mhs-empty__sub {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Pagination */
.mhs-pagination {
    padding: 0.875rem 1.25rem;
    border-top: 1px solid #f1f5f9;
}

/* ============================================================
   MODAL
   ============================================================ */
.mhs-modal-backdrop {
    position: fixed;
    inset: 0;
    z-index: 50;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.mhs-modal-backdrop.flex { display: flex; }

.mhs-modal-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.5);
}

.mhs-modal {
    position: relative;
    width: 100%;
    max-width: 640px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

.mhs-modal__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
}
.mhs-modal__header-left {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.mhs-modal__avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.875rem;
    font-weight: 700;
    flex-shrink: 0;
    user-select: none;
}
.mhs-modal__name {
    font-size: 0.9375rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.15rem;
}
.mhs-modal__nim {
    font-size: 0.72rem;
    font-family: ui-monospace, monospace;
    color: #2563eb;
}
.mhs-modal__close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f1f5f9;
    border: none;
    color: #64748b;
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
    flex-shrink: 0;
}
.mhs-modal__close:hover { background: #fee2e2; color: #dc2626; }

.mhs-modal__body { overflow-y: auto; max-height: 60vh; }
.mhs-modal__content { padding: 1.25rem; display: flex; flex-direction: column; gap: 1.25rem; }

.mhs-modal__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.875rem 1.25rem;
    border-top: 1px solid #f1f5f9;
}
.mhs-modal__footer-label {
    font-size: 0.7rem;
    color: #94a3b8;
}

/* Modal section & field */
.mhs-section { }
.mhs-section__header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.875rem;
}
.mhs-section__icon {
    width: 18px;
    height: 18px;
    color: #2563eb;
    flex-shrink: 0;
}
.mhs-section__title {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #64748b;
    white-space: nowrap;
}
.mhs-section__divider {
    flex: 1;
    height: 1px;
    background: #f1f5f9;
}
.mhs-section__fields {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.875rem;
    padding-left: 0.25rem;
}
.mhs-field__label {
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #94a3b8;
    margin-bottom: 0.2rem;
}
.mhs-field__value {
    font-size: 0.8125rem;
    font-weight: 500;
    color: #1e293b;
}
.mhs-field__empty {
    font-size: 0.8125rem;
    font-style: italic;
    color: #cbd5e1;
}
.mhs-field--full { grid-column: 1 / -1; }

.hidden{
    display:none;
}


</style>


{{-- ════════════════════════════════════════════════
     DATA & SCRIPTS
════════════════════════════════════════════════ --}}
@php
$mahasiswaData = $mahasiswa->map(fn($m) => [
    'id'           => $m->id,
    'nim'          => $m->nim,
    'nama'         => $m->nama,
    'prodi'        => $m->prodi,
    'jurusan'      => $m->jurusan ?? null,
    'minat'        => $m->minat   ?? null,

    'email'   => $m->email ?? null,
    'no_telp' => $m->no_telp ?? null,
    'foto' => $m->foto ?? null,

    'provinsi'     => $m->provinsi     ?? null,
    'kabupaten'    => $m->kabupaten    ?? null,
    'kecamatan'    => $m->kecamatan    ?? null,
    'desa'         => $m->desa         ?? null,
    'detail_alamat' => $m->detail_alamat ?? null,

    'nama_sekolah' => $m->nama_sekolah ?? null,
    'kecamatan_sekolah' => $m->kecamatan_sekolah ?? null,
]);
@endphp



<script>
let currentMahasiswa = null;
let editMode = false;

const mahasiswaData = @json($mahasiswaData);

const avatarColors = [
    '#2563eb','#7c3aed','#059669','#d97706','#e11d48','#0891b2'
];
function colorFor(str) {
    const code = (str || 'A').charCodeAt(0);
    return avatarColors[code % avatarColors.length];
}

function field(label, value, full = false) {
    const isEmpty = !value;
    const valHtml = isEmpty
        ? `<span class="mhs-field__empty">Tidak tersedia</span>`
        : `<span class="mhs-field__value">${value}</span>`;
    const cls = full ? 'mhs-field--full' : '';
    return `
        <div class="${cls}">
            <p class="mhs-field__label">${label}</p>
            ${valHtml}
        </div>`;
}

function section(title, iconPath, content) {
    return `
        <div class="mhs-section">
            <div class="mhs-section__header">
                <svg class="mhs-section__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="${iconPath}"/>
                </svg>
                <span class="mhs-section__title">${title}</span>
                <div class="mhs-section__divider"></div>
            </div>
            <div class="mhs-section__fields">${content}</div>
        </div>`;
}

function openModal(id) {
    const m = mahasiswaData.find(item => item.id == id);
    if (!m) return;

    const angkatan = '20' + m.nim.substring(0, 2);
    const initials = m.nama.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
    const color    = colorFor(m.nama);
    const avatar = document.getElementById('modal-avatar');

    if (m.foto) {
    avatar.innerHTML = `
        <img
            src="/storage/foto-profil/${m.foto}"
            style="
                width:100%;
                height:100%;
                object-fit:cover;
                border-radius:10px;
                cursor: pointer;
            "
            onclick="openPhotoViewer('/storage/foto-profil/${m.foto}')"
        >
    `;
} else {
    avatar.style.background = color;
    avatar.innerHTML = initials;
}


    document.getElementById('modal-title').textContent = m.nama;
    document.getElementById('modal-nim').textContent   = m.nim;

    const hapusBtn = document.getElementById('modal-hapus-btn');
    if (hapusBtn) hapusBtn.onclick = () => konfirmasiHapus(m.id, m.nama);

    const html =
        section(
            'Identitas Akademik',
            'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
            field('NIM', m.nim) +
            field('Nama Lengkap', m.nama) +
            field('Program Studi', m.prodi) +
            field('Angkatan', angkatan) +
            field('Email', m.email) +
            field('No. Telepon', m.no_telp)

        ) +
       section(
            'Riwayat Pendidikan',
            'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            field('Nama Sekolah', m.nama_sekolah) +
            field('Kecamatan Sekolah', m.kecamatan_sekolah) +
            field('Jurusan SMA/SMK', m.jurusan) +
            field('Minat / Bidang', m.minat)
        ) +
        section(
            'Alamat Asal',
            'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
            field('Provinsi', m.provinsi) +
            field('Kabupaten / Kota', m.kabupaten) +
            field('Kecamatan', m.kecamatan) +
            field('Desa / Kelurahan', m.desa) +
            field('Jalan / Alamat Lengkap', m.detail_alamat, true)
        );

    document.getElementById('detailContent').innerHTML = html;

    const modal = document.getElementById('detailModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';

    currentMahasiswa = m;
}

function closeModal() {
    const modal = document.getElementById('detailModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function konfirmasiHapus(id, nama) {
    if (confirm(`Hapus data mahasiswa "${nama}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/mahasiswa/${id}`;
        form.innerHTML = `
            @csrf
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

function openTambah() {
    const modal = document.getElementById('tambahModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.body.style.overflow = 'hidden';
}

function closeTambah() {
    const modal = document.getElementById('tambahModal');

    modal.classList.remove('flex');
    modal.classList.add('hidden');

    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
});

document.getElementById('editBtn')?.addEventListener('click', () => {

    if (!currentMahasiswa) return;

    editMode = true;

    document.querySelectorAll('.mhs-field__value').forEach(el => {

        const label = el.previousElementSibling.innerText;

        if (
            label === 'NIM' ||
            label === 'NAMA LENGKAP' ||
            label === 'PROGRAM STUDI' ||
            label === 'EMAIL' ||
            label === 'NO. TELEPON'
        ) {
            const value = el.innerText;

            el.innerHTML = `
                <input
                    class="mhs-input"
                    value="${value}"
                    data-field="${label}">
            `;
        }
    });

    document.getElementById('saveBtn')
        .classList.remove('hidden');
});

document.getElementById('saveBtn')?.addEventListener('click', async () => {

    const data = {};

    document.querySelectorAll('[data-field]')
        .forEach(input => {

            switch(input.dataset.field){

                case 'NIM':
                    data.nim = input.value;
                    break;

                case 'NAMA LENGKAP':
                    data.nama = input.value;
                    break;

                case 'PROGRAM STUDI':
                    data.prodi = input.value;
                    break;

                case 'EMAIL':
                    data.email = input.value;
                    break;

                case 'NO. TELEPON':
                    data.no_telp = input.value;
                    break;
            }
        });

    const response = await fetch(
        `/mahasiswa/${currentMahasiswa.id}`,
        {
            method:'POST',
            headers:{
                'X-CSRF-TOKEN':
                document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type':'application/json'
            },
            body:JSON.stringify({
                ...data,
                _method:'PUT'
            })
        }
    );

    if(response.ok){
        location.reload();
    }
});

// ── LIGHTBOX UNTUK FOTO FULLSCREEN ──
function openPhotoViewer(fotoUrl) {
    if (!fotoUrl) return;
    
    const viewer = document.getElementById('photoViewer');
    const viewerImg = document.getElementById('viewerImg');
    
    viewerImg.src = fotoUrl;
    viewer.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closePhotoViewer() {
    const viewer = document.getElementById('photoViewer');
    viewer.classList.remove('show');
    document.body.style.overflow = '';
}

// Close saat klik overlay
document.getElementById('photoViewerOverlay')?.addEventListener('click', closePhotoViewer);

// Close dengan keyboard ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closePhotoViewer();
});

</script>

@endsection