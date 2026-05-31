@extends('layouts.app')

@section('title', 'Data Sekolah Asal')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

    .sch-wrap * { font-family: 'Inter', sans-serif; }
    .sch-wrap .serif { font-family: 'EB Garamond', serif; }

    /* ── BANNER HEADER ── */
    .sch-hero {
        background: var(--blue);
        border-radius: 6px;
        padding: 30px 40px;
        margin-bottom: 24px;
    }
    .sch-hero h2 {
        font-family: 'EB Garamond', serif;
        font-size: 28px;
        font-weight: 500;
        color: #fff;
        margin-bottom: 4px;
    }
    .sch-hero p {
        font-size: 13px;
        color: rgba(255,255,255,0.65);
        font-weight: 300;
    }

    /* ── CONTAINER LAYOUT ── */
    .sch-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    @media (min-width: 1024px) {
        .sch-grid { grid-template-columns: 4fr 5fr; }
    }

    .sch-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 24px 28px;
        height: fit-content;
    }

    .sch-title {
        font-size: 10px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: 20px;
    }

    /* ── FORMS & INPUTS ── */
    .form-group { margin-bottom: 16px; }
    .form-group label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--blue);
        font-weight: 600;
        margin-bottom: 6px;
    }
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

    /* ── BUTTONS & BADGES ── */
    .btn-primary {
        background: var(--blue);
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 11px 20px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        transition: background .2s;
        width: 100%;
    }
    .btn-primary:hover { background: var(--blue-dark); }

    .btn-danger-link {
        background: none;
        border: none;
        color: #b94040;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        padding: 0;
        text-decoration: underline;
    }
    .btn-danger-link:hover { color: #8a2f2f; }

    .sch-badge {
        display: inline-block;
        font-size: 10px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        background: var(--blue-light);
        color: var(--blue);
    }

    /* ── MAP ── */
    #map {
        height: 480px;
        width: 100%;
        border-radius: 4px;
        border: 1px solid var(--border);
    }

    /* ── DATA INFO BOX ── */
    .info-box {
        background: #fafafa;
        border: 1px dashed var(--border);
        border-radius: 4px;
        padding: 14px 18px;
        margin-bottom: 20px;
    }
    .info-box h4 {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin: 0 0 4px 0;
    }
    .info-box p {
        font-size: 12px;
        color: var(--muted);
        margin: 0;
    }
</style>
@endpush

@section('content')
<div class="sch-wrap">
    
    {{-- ── HERO BANNER ── --}}
    <div class="sch-hero">
        <h2>Data Sekolah Asal</h2>
        <p>Kelola informasi asal instansi sekolah menengah Anda beserta pemetaan lokasinya di peta klaster digital.</p>
    </div>

    {{-- ALERT NOTIFIKASI ── --}}
    @if(session('success'))
        <div style="background: #eaf5ec; border: 1px solid #c3e6cb; color: #1e7e34; padding: 14px 20px; border-radius: 4px; font-size: 13px; margin-bottom: 20px;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="sch-grid">
        
        {{-- PANEL FORM INPUT --}}
        <div class="sch-card">
            <p class="sch-title">Formulir Sekolah</p>

            {{-- TAMPILKAN INFO JIKA DATA SUDAH TERSEDIA (MODE EDIT BERJALAN) --}}
            @if($sekolah)
                <div class="info-box">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <h4>{{ $sekolah->nama_sekolah }}</h4>
                            <p>📍 {{ $sekolah->kecamatan }}, {{ $sekolah->kabupaten }}, {{ $sekolah->provinsi }}</p>
                        </div>
                        <span class="sch-badge">Terdaftar</span>
                    </div>
                </div>
            @endif

            <form action="/sekolah" method="POST" style="display: flex; flex-direction: column; gap: 4px;">
                @csrf

                <div class="form-group">
                    <label>Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" class="form-control" placeholder="Contoh: SMAN 1 Madiun" value="{{ $sekolah->nama_sekolah ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label>Provinsi</label>
                    <select name="provinsi" id="provinsi" class="form-control" required>
                        <option value="">Pilih Provinsi</option>
                        @foreach($provinces as $province)
                            {{-- Pada Laravolt, jika data lama disimpan berupa nama teks, kita cek kecocokan namanya --}}
                            <option value="{{ $province->code }}" {{ ($sekolah && $sekolah->provinsi == $province->name) ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Kabupaten / Kota</label>
                    <select name="kabupaten" id="kabupaten" class="form-control" required>
                        <option value="">Pilih Kabupaten</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kecamatan</label>
                    <select name="kecamatan" id="kecamatan" class="form-control" required>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 10px;">
                    {{ $sekolah ? 'Perbarui Data Sekolah' : 'Simpan Data Sekolah' }}
                </button>
            </form>

            {{-- TOMBOL DELETE / RESET DATA --}}
            @if($sekolah)
                <div style="margin-top: 16px; text-align: center; border-top: 1px solid var(--border); padding-top: 14px;">
                    <form action="/sekolah/{{ $sekolah->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/reset data sekolah ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger-link">
                            ✕ Hapus & Reset Sekolah Saya
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- PANEL PETA --}}
        <div class="sch-card">
            <p class="sch-title">Visualisasi Wilayah Sekolah</p>
            <div id="map"></div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async function () {
        // 1. INITIATE LEAFLET VOYAGER MAP STYLE
        const map = L.map('map', { maxZoom: 19 }).setView([-2.5, 118], 5);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        let marker;

        // Jembatan Geocoding Nominatim
        async function flyToWilayah(query, zoomLevel) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(query)}`);
                const data = await response.json();
                if(data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);

                    if(marker) { map.removeLayer(marker); }
                    marker = L.marker([lat, lon]).addTo(map);
                    map.flyTo([lat, lon], zoomLevel, { animate: true, duration: 1.2 });
                }
            } catch(e) { console.error("Gagal melacak titik wilayah sekolah:", e); }
        }

        // Jalankan pelacakan awal jika data lama sekolah sudah terdaftar
        @if($sekolah)
            const queryAwal = "{{ $sekolah->kecamatan }}, {{ $sekolah->kabupaten }}, {{ $sekolah->provinsi }}, Indonesia";
            flyToWilayah(queryAwal, 14);
        @endif

        // ── DROPDOWN CASCADE LOADER (LARAVOLT DRIVER) ──
        document.getElementById('provinsi').addEventListener('change', function() { loadKabupaten(this.value); });
        document.getElementById('kabupaten').addEventListener('change', function() { loadKecamatan(this.value); });
        document.getElementById('kecamatan').addEventListener('change', async function() {
            const textKec = this.options[this.selectedIndex].text;
            const textKab = document.getElementById('kabupaten').options[document.getElementById('kabupaten').selectedIndex].text;
            const textProv = document.getElementById('provinsi').options[document.getElementById('provinsi').selectedIndex].text;
            
            await flyToWilayah(`${textKec}, ${textKab}, ${textProv}, Indonesia`, 14);
        });

        async function loadKabupaten(provCode, selectedName = '') {
            if(!provCode) return;
            const res = await fetch(`/cities/${provCode}`);
            const data = await res.json();
            let html = '<option value="">Pilih Kabupaten</option>';
            data.forEach(item => {
                // Cek nama teks jika pencocokan data dari Edit Mode database
                const isSelected = (selectedName === item.name) ? 'selected' : '';
                html += `<option value="${item.code}" ${isSelected}>${item.name}</option>`;
            });
            document.getElementById('kabupaten').innerHTML = html;
            document.getElementById('kecamatan').innerHTML = '<option value="">Pilih Kecamatan</option>';

            if(selectedName) {
                const activeKabCode = document.getElementById('kabupaten').value;
                await loadKecamatan(activeKabCode, "{{ $sekolah->kecamatan ?? '' }}");
            }
        }

        async function loadKecamatan(kabCode, selectedName = '') {
            if(!kabCode) return;
            const res = await fetch(`/districts/${kabCode}`);
            const data = await res.json();
            let html = '<option value="">Pilih Kecamatan</option>';
            data.forEach(item => {
                // Laravolt mengembalikan item.name untuk kecamatan, simpan kodenya di value jika ingin konsisten atau namanya langsung
                const isSelected = (selectedName === item.name) ? 'selected' : '';
                html += `<option value="${item.code}" ${isSelected}>${item.name}</option>`;
            });
            document.getElementById('kecamatan').innerHTML = html;

            if(!selectedName) {
                const textKab = document.getElementById('kabupaten').options[document.getElementById('kabupaten').selectedIndex].text;
                const textProv = document.getElementById('provinsi').options[document.getElementById('provinsi').selectedIndex].text;
                await flyToWilayah(`${textKab}, ${textProv}, Indonesia`, 12);
            }
        }

        // TRIGGER CASCADE DETEKSI AWAL JIKA USER BERADA DI MODE EDIT
        const activeProvCode = document.getElementById('provinsi').value;
        if(activeProvCode) {
            // Jalankan pencarian berantai mencocokkan string nama teks kabupaten lama
            await loadKabupaten(activeProvCode, "{{ $sekolah->kabupaten ?? '' }}");
        }
    });
</script>
@endpush