@extends('layouts.app')

@section('title', 'Minat, Bakat & Jurusan')

@push('styles')
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

    .mb-wrap * { font-family: 'Inter', sans-serif; }
    .mb-wrap .serif { font-family: 'EB Garamond', serif; }

    /* ── BANNER HEADER ── */
    .mb-hero {
        background: var(--blue);
        border-radius: 6px;
        padding: 30px 40px;
        margin-bottom: 24px;
    }
    .mb-hero h2 {
        font-family: 'EB Garamond', serif;
        font-size: 28px;
        font-weight: 500;
        color: #fff;
        margin-bottom: 4px;
    }
    .mb-hero p {
        font-size: 13px;
        color: rgba(255,255,255,0.65);
        font-weight: 300;
    }

    /* ── GRID LAYOUT ── */
    .mb-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    @media (min-width: 1024px) {
        .mb-grid { grid-template-columns: 1fr 1fr; }
    }

    .mb-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 24px 28px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .mb-title {
        font-size: 10px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 10px;
    }

    .group-sub-title {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--blue);
        font-weight: 700;
        margin-bottom: 12px;
        margin-top: 16px;
    }

    /* ── CUSTOM CHECKBOX & RADIO COMPONENT ── */
    .selection-label {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 4px;
        margin-bottom: 8px;
        cursor: pointer;
        font-size: 13px;
        color: var(--text);
        transition: background 0.2s, border-color 0.2s;
    }
    .selection-label:hover {
        background: #fafafa;
        border-color: var(--blue-mid);
    }

    /* Style tambahan jika input di-disable */
    .selection-label.disabled-state {
        cursor: not-allowed;
        background: #f5f5f5;
        opacity: 0.72;
    }
    .selection-label.disabled-state:hover {
        border-color: var(--border);
        background: #f5f5f5;
    }

    /* Modifikasi input bawaan browser */
    .selection-label input[type="checkbox"],
    .selection-label input[type="radio"] {
        accent-color: var(--blue);
        width: 16px;
        height: 16px;
        cursor: pointer;
    }
    .selection-label input:disabled {
        cursor: not-allowed;
    }

    /* ── INPUT LAINNYA ── */
    .form-control {
        width: 100%;
        border: 1px solid var(--border);
        background: #fafafa;
        border-radius: 4px;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--text);
        transition: border-color 0.2s, background 0.2s;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--blue-mid);
        background: #fff;
    }
    .form-control:disabled {
        background: #f5f5f5;
        cursor: not-allowed;
    }

    /* ── BUTTONS ── */
    .btn-submit-container {
        grid-column: 1 / -1;
        text-align: right;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .btn-primary {
        background: var(--blue);
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 12px 32px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        transition: background .2s;
    }
    .btn-primary:hover { background: var(--blue-dark); }

    .btn-secondary {
        background: #6c757d;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 12px 24px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        transition: background .2s;
    }
    .btn-secondary:hover { background: #5a6268; }
</style>
@endpush

@section('content')
<div class="mb-wrap">
    
    {{-- ── HERO BANNER ── --}}
    <div class="mb-hero">
        <h2>Minat, Bakat, & Jurusan Asal</h2>
        <p>Sesuaikan preferensi bidang keahlian lama Anda untuk pemetaan klaster kompetensi mahasiswa baru.</p>
    </div>

    {{-- ALERT NOTIFIKASI ── --}}
    @if(session('success'))
        <div style="background: #eaf5ec; border: 1px solid #c3e6cb; color: #1e7e34; padding: 14px 20px; border-radius: 4px; font-size: 13px; margin-bottom: 20px;">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Ambil data lama jika ada untuk auto-check (Edit Mode) --}}
   @php
$hasData = false;

$oldMinat = $minatBakat->minat ?? '';
$oldJurusan = $minatBakat->jurusan ?? '';


$daftarMinat = [
    'Programming/Robotik',
    'Desain Grafis',
    'Seni',
    'Olahraga',
    'Organisasi',
    'Kewirausahaan',
    'Public Speaking',
    'Menulis',
    'Penelitian',
    'e-sports/gaming',
    'lainnya'
];

$daftarJurusan = [
    'IPA',
    'IPS',
    'Bahasa',
    'Agama',
    'Teknik Komputer & Jaringan',
    'Rekayasa Perangkat Lunak',
    'Multimedia',
    'Akuntansi',
    'Administrasi Perkantoran',
    'Teknik Otomotif',
    'lainnya'
];


if (!empty($oldMinat) || !empty($oldJurusan)) {
    $hasData = true;
}
@endphp

    {{-- Satukan ke dalam satu form utama agar penyimpanan serentak & rapi --}}
    <form id="competencyForm" action="/minat" method="POST">
        @csrf
        
        <div class="mb-grid">
            
            {{-- PANEL KIRI: MINAT & BAKAT --}}
            <div class="mb-card">
                <div>
                    <p class="mb-title">Minat & Bakat Saat SMA</p>
                    <div style="display: flex; flex-direction: column;">
                        
                     <label class="selection-label">
            <input type="radio"
                name="minat"
                value="Programming/Robotik"
                {{ $oldMinat == 'Programming/Robotik' ? 'checked' : '' }}
                {{ $hasData ? 'disabled' : '' }}>
            <span>Programming / Robotik</span>
            </label>

            <label class="selection-label">
                <input type="radio"
                    name="minat"
                    value="Desain Grafis"
                    {{ $oldMinat == 'Desain Grafis' ? 'checked' : '' }}
                    {{ $hasData ? 'disabled' : '' }}>
                <span>Desain Grafis</span>
            </label>

            <label class="selection-label">
                <input type="radio"
                    name="minat"
                    value="Seni"
                    {{ $oldMinat == 'Seni' ? 'checked' : '' }}
                    {{ $hasData ? 'disabled' : '' }}>
                <span>Seni</span>
            </label>

            <label class="selection-label">
                <input type="radio"
                    name="minat"
                    value="Olahraga"
                    {{ $oldMinat == 'Olahraga' ? 'checked' : '' }}
                    {{ $hasData ? 'disabled' : '' }}>
                <span>Olahraga</span>
            </label>

            <label class="selection-label">
                <input type="radio"
                    name="minat"
                    value="Organisasi"
                    {{ $oldMinat == 'Organisasi' ? 'checked' : '' }}
                    {{ $hasData ? 'disabled' : '' }}>
                <span>Organisasi</span>
            </label>

            <label class="selection-label">
                <input type="radio"
                    name="minat"
                    value="Kewirausahaan"
                    {{ $oldMinat == 'Kewirausahaan' ? 'checked' : '' }}
                    {{ $hasData ? 'disabled' : '' }}>
                <span>Kewirausahaan</span>
            </label>

            <label class="selection-label">
                <input type="radio"
                    name="minat"
                    value="Public Speaking"
                    {{ $oldMinat == 'Public Speaking' ? 'checked' : '' }}
                    {{ $hasData ? 'disabled' : '' }}>
                <span>Public Speaking</span>
            </label>

            <label class="selection-label">
                <input type="radio"
                    name="minat"
                    value="Menulis"
                    {{ $oldMinat == 'Menulis' ? 'checked' : '' }}
                    {{ $hasData ? 'disabled' : '' }}>
                <span>Menulis</span>
            </label>

            <label class="selection-label">
                <input type="radio"
                    name="minat"
                    value="Penelitian"
                    {{ $oldMinat == 'Penelitian' ? 'checked' : '' }}
                    {{ $hasData ? 'disabled' : '' }}>
                <span>Penelitian</span>
            </label>

                   <label class="selection-label">
                <input type="radio"
                    name="minat"
                    value="E-Sport/Gaming"
                    {{ $oldMinat == 'E-Sport/Gaming' ? 'checked' : '' }}
                    {{ $hasData ? 'disabled' : '' }}>
                <span>E-Sport/Gaming</span>
            </label>
 

            
            <label class="selection-label">
                <input type="radio"
                    name="minat"
                    value="Lainnya"
                    {{ $oldMinat == 'Lainnya' ? 'checked' : '' }}
                    {{ $hasData ? 'disabled' : '' }}>
                <span>Lainnya</span>
            </label>
                  </div>
                </div>

            </div>

            {{-- PANEL KANAN: JURUSAN ASAL --}}
            <div class="mb-card">
                <div>
                    <p class="mb-title">Jurusan Saat SMA / SMK</p>
                    
                    <p class="group-sub-title" style="margin-top:0;">Kategori SMA</p>
                    <div style="display: flex; flex-direction: column; margin-bottom: 12px;">
                        <label class="selection-label {{ $hasData ? 'disabled-state' : '' }}">
                            <input type="radio" name="jurusan" value="IPA" {{ $oldJurusan == 'IPA' ? 'checked' : '' }} {{ $hasData ? 'disabled' : '' }}>
                            <span>IPA (Ilmu Pengetahuan Alam)</span>
                        </label>
                        <label class="selection-label {{ $hasData ? 'disabled-state' : '' }}">
                            <input type="radio" name="jurusan" value="IPS" {{ $oldJurusan == 'IPS' ? 'checked' : '' }} {{ $hasData ? 'disabled' : '' }}>
                            <span>IPS (Ilmu Pengetahuan Sosial)</span>
                        </label>
                        <label class="selection-label {{ $hasData ? 'disabled-state' : '' }}">
                            <input type="radio" name="jurusan" value="Bahasa" {{ $oldJurusan == 'Bahasa' ? 'checked' : '' }} {{ $hasData ? 'disabled' : '' }}>
                            <span>Bahasa & Budaya</span>
                        </label>
                        <label class="selection-label {{ $hasData ? 'disabled-state' : '' }}">
                            <input type="radio" name="jurusan" value="Agama" {{ $oldJurusan == 'Agama' ? 'checked' : '' }} {{ $hasData ? 'disabled' : '' }}>
                            <span>Agama / Keagamaan</span>
                        </label>

                            <label class="selection-label {{ $hasData ? 'disabled-state' : '' }}">
                            <input type="radio" name="jurusan" value="Lainnya" {{ $oldJurusan == 'Lainnya' ? 'checked' : '' }} {{ $hasData ? 'disabled' : '' }}>
                            <span>Lainnya</span>
                        </label>
                    </div>

                    <p class="group-sub-title">Kategori SMK</p>
                    <div style="display: grid; grid-template-columns: 1fr; gap: 2px;">
                        @php
                            $smkJurusans = [
                                'Teknik Komputer & Jaringan', 'Rekayasa Perangkat Lunak', 
                                'Multimedia', 'Akuntansi', 'Administrasi Perkantoran', 'Teknik Otomotif', 'lainnya'
                            ];
                        @endphp
                        @foreach($smkJurusans as $smk)
                            <label class="selection-label {{ $hasData ? 'disabled-state' : '' }}">
                                <input type="radio" name="jurusan" value="{{ $smk }}" {{ $oldJurusan == $smk ? 'checked' : '' }} {{ $hasData ? 'disabled' : '' }}>
                                <span>{{ $smk }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- TOMBOL SUBMIT & RESET DI PALING BAWAH GRID --}}
            <div class="btn-submit-container">
                @if($hasData)
                    <button type="button" id="btnResetLock" class="btn-secondary">Ubah Data</button>
                @endif
                <button type="submit" id="btnSubmitForm" class="btn-primary" style="{{ $hasData ? 'display: none;' : '' }}">
                    {{ isset($minatBakat) ? 'Perbarui Preferensi Saya' : 'Simpan Data Kompetensi' }}
                </button>
            </div>

        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('competencyForm');
    const btnResetLock = document.getElementById('btnResetLock');
    const btnSubmitForm = document.getElementById('btnSubmitForm');

    const formInputs = form.querySelectorAll('input[type="radio"]');
    const selectionLabels = form.querySelectorAll('.selection-label');

    if (btnResetLock) {
        btnResetLock.addEventListener('click', function () {

            // Aktifkan kembali semua radio button
            formInputs.forEach(input => {
                input.disabled = false;
            });

            // Hapus style disabled
            selectionLabels.forEach(label => {
                label.classList.remove('disabled-state');
            });

            // Tampilkan tombol simpan
            btnSubmitForm.style.display = 'inline-block';

            // Sembunyikan tombol ubah data
            btnResetLock.style.display = 'none';
        });
    }

    // Sebelum submit pastikan semua radio aktif
    form.addEventListener('submit', function () {
        formInputs.forEach(input => {
            input.disabled = false;
        });
    });

});
</script>
@endsection