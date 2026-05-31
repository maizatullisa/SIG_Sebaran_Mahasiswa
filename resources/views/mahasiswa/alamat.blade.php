@extends('layouts.app')

@section('title', 'Alamat Mahasiswa')

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

    .al-wrap * { font-family: 'Inter', sans-serif; }
    .al-wrap .serif { font-family: 'EB Garamond', serif; }

    /* ── BANNER HEADER ── */
    .al-hero {
        background: var(--blue);
        border-radius: 6px;
        padding: 30px 40px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .al-hero h2 {
        font-family: 'EB Garamond', serif;
        font-size: 28px;
        font-weight: 500;
        color: #fff;
        margin-bottom: 4px;
    }
    .al-hero p {
        font-size: 13px;
        color: rgba(255,255,255,0.65);
        font-weight: 300;
    }

    /* ── CONTAINER CARDS ── */
    .al-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    @media (min-width: 1024px) {
        .al-grid { grid-template-columns: 4fr 5fr; }
    }

    .al-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 24px 28px;
        height: fit-content;
    }

    .al-title {
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

    /* ── BADGES & BUTTONS ── */
    .al-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .al-badge.active { background: #e8f5ee; color: #1a7a4a; }
    .al-badge.manual { background: #fff7ed; color: #c2410c; }
    .al-badge-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }

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

    .btn-outline {
        background: #fff;
        color: var(--blue);
        border: 1px solid var(--blue);
        font-size: 12px;
        font-weight: 500;
        padding: 8px 14px;
        border-radius: 4px;
        cursor: pointer;
        transition: all .2s;
    }
    .btn-outline:hover { background: var(--blue-light); }

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

    /* ── MAP ELEMENTS ── */
    #map {
        height: 520px;
        width: 100%;
        border-radius: 4px;
        border: 1px solid var(--border);
    }
    .autocomplete-box {
        position: absolute;
        z-index: 1000;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 4px;
        margin-top: 2px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        max-h: 200px;
        overflow-y: auto;
        width: 100%;
    }
    .autocomplete-item {
        padding: 10px 14px;
        font-size: 12px;
        cursor: pointer;
        border-bottom: 1px solid var(--border);
    }
    .autocomplete-item:last-child { border-bottom: none; }
    .autocomplete-item:hover { background: var(--blue-light); }

    /* Leaflet popup customization to match dashboard */
    .leaflet-popup-content-wrapper {
        background: rgba(10,30,55,0.92) !important;
        border: 1px solid rgba(79,195,247,0.25) !important;
        border-radius: 4px !important;
        color: #fff !important;
    }
    .leaflet-popup-tip { background: rgba(10,30,55,0.92) !important; }
</style>
@endpush

@section('content')
<div class="al-wrap">
    
    {{-- ── HERO BANNER ── --}}
    <div class="al-hero">
        <h2>Kelola Lokasi Rumah</h2>
        <p>Atur data spasial koordinat tempat tinggal asli Anda untuk pemetaan sebaran mahasiswa.</p>
    </div>

    {{-- ALERT NOTIFIKASI ── --}}
    @if(session('success'))
        <div style="background: #eaf5ec; border: 1px solid #c3e6cb; color: #1e7e34; padding: 14px 20px; border-radius: 4px; font-size: 13px; margin-bottom: 20px;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="al-grid">
        
        {{-- PANEL FORM INPUT --}}
        <div class="al-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <p class="al-title" style="margin-bottom: 0;">Formulir Alamat</p>
                <span id="gpsBadge" class="al-badge manual"><span class="al-badge-dot"></span> Memuat...</span>
            </div>

            <form action="/alamat" method="POST" style="display: flex; flex-direction: column; gap: 4px;">
                @csrf

                <div class="form-group">
                    <label>Provinsi</label>
                    <select name="provinsi" id="provinsi" class="form-control">
                        <option value="">Pilih Provinsi</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->code }}" {{ ($alamat && $alamat->provinsi == $province->code) ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Kabupaten / Kota</label>
                    <select name="kabupaten" id="kabupaten" class="form-control">
                        <option value="">Pilih Kabupaten</option>
                        {{-- Akan diisi via AJAX jika mode edit --}}
                    </select>
                </div>

                <div class="form-group">
                    <label>Kecamatan</label>
                    <select name="kecamatan" id="kecamatan" class="form-control">
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Desa / Kelurahan</label>
                    <select name="desa" id="desa" class="form-control">
                        <option value="">Pilih Desa</option>
                    </select>
                </div>

                <div class="form-group" style="position: relative;">
                    <label>Cari Jalan / Lokasi Sekitar</label>
                    <input type="text" id="search-location" placeholder="Ketik nama jalan tempat tinggal..." class="form-control" autocomplete="off">
                </div>

                <div class="form-group">
                    <label>Detail Alamat / Catatan Rumah</label>
                    <textarea name="detail_alamat" id="detail_alamat" rows="3" class="form-control" placeholder="Nama jalan, nomor rumah, RT/RW, atau patokan objek terdekat">{{ $alamat->detail_alamat ?? '' }}</textarea>
                </div>

                {{-- Hidden Fields untuk Spasial --}}
                <input type="hidden" name="latitude" id="latitude" value="{{ $alamat->latitude ?? '' }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ $alamat->longitude ?? '' }}">

                <button type="submit" class="btn-primary" style="margin-top: 10px;">
                    {{ $alamat ? 'Perbarui Alamat Terdaftar' : 'Simpan Koordinat Alamat' }}
                </button>
            </form>

            {{-- TOMBOL DELETE / RESET DATA JIKA DATA ADA --}}
            @if($alamat)
                <div style="margin-top: 16px; text-align: center; border-top: 1px solid var(--border); padding-top: 14px;">
                    <form action="/alamat" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/reset lokasi ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger-link">
                            ✕ Hapus & Reset Lokasi Saya
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- PANEL PETA --}}
        <div class="al-card" style="display: flex; flex-direction: column; gap: 14px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <p class="al-title" style="margin-bottom: 0;">Peta Titik Hunian</p>
                <button type="button" id="btn-location" class="btn-outline">🎯 Deteksi GPS Saya</button>
            </div>
            
            <div id="map"></div>
            <p id="gpsStatus" style="font-size: 11px; color: var(--muted); margin: 0;"></p>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async function () {
        // 1. INISIALISASI DASAR PETA (VOYAGER STYLE)
        const map = L.map('map', { maxZoom: 19 }).setView([-2.5, 118], 5);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        let marker;
        let desaBoundingBox = null;

        // Ambil data binding dari backend Laravel (jika ada)
        const savedLat = document.getElementById('latitude').value;
        const savedLng = document.getElementById('longitude').value;
        
        // Pemicu Terbang & Setup Marker Pin Peta
        async function setLocation(lat, lng, zoomLevel = 18, updateAddressField = false) {
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            if(marker) { map.removeLayer(marker); }

            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            map.flyTo([lat, lng], zoomLevel, { animate: true, duration: 1.2 });

            if (updateAddressField) {
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
                    const data = await response.json();
                    if (data && data.display_name) {
                        document.getElementById('detail_alamat').value = data.display_name;
                    }
                } catch (e) { console.error("Reverse geocoding bermasalah:", e); }
            }

            marker.on('dragend', function() {
                const pos = marker.getLatLng();
                setLocation(pos.lat, pos.lng, map.getZoom(), true);
            });
        }

        // Cek Apakah ada data alamat terdaftar sebelumnya
        if (savedLat && savedLng) {
            setLocation(parseFloat(savedLat), parseFloat(savedLng), 17, false);
            document.getElementById('gpsBadge').className = "al-badge active";
            document.getElementById('gpsBadge').innerHTML = "<span class='al-badge-dot'></span> Data Tersimpan";
        } else {
            getCurrentLocation(); // Jika kosong, jalankan auto-detect GPS bawaan browser
        }

        // DETEKSI GEOLOCATION GPS
        function getCurrentLocation() {
            if(navigator.geolocation) {
                document.getElementById('gpsStatus').innerText = "Sedang mencari sinyal satelit GPS...";
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        setLocation(position.coords.latitude, position.coords.longitude, 17, true);
                        document.getElementById('gpsBadge').className = "al-badge active";
                        document.getElementById('gpsBadge').innerHTML = "<span class='al-badge-dot'></span> GPS Aktif";
                        document.getElementById('gpsStatus').innerText = "Lokasi akurat terdeteksi satelit.";
                    },
                    function() {
                        document.getElementById('gpsStatus').innerText = "Akses GPS ditolak, beralih ke penentuan wilayah manual.";
                        document.getElementById('gpsBadge').className = "al-badge manual";
                        document.getElementById('gpsBadge').innerHTML = "<span class='al-badge-dot'></span> Manual Mode";
                    }
                );
            }
        }

        document.getElementById('btn-location').addEventListener('click', getCurrentLocation);
        map.on('click', function(e) { setLocation(e.latlng.lat, e.latlng.lng, map.getZoom(), true); });

        // ── KELOLA DRIVER DROPDOWN REGIONAL INDONESIA (LARAVOLT) ──
        async function zoomToWilayah() {
            const elProv = document.getElementById('provinsi');
            const elKab  = document.getElementById('kabupaten');
            const elKec  = document.getElementById('kecamatan');
            const elDesa = document.getElementById('desa');

            const provinsi  = elProv.value ? elProv.selectedOptions[0].text : '';
            const kabupaten = elKab.value ? elKab.selectedOptions[0].text : '';
            const kecamatan = elKec.value ? elKec.selectedOptions[0].text : '';
            const desa      = elDesa.value ? elDesa.selectedOptions[0].text : '';

            if (!kabupaten) return;

            let queries = [];
            let targetZoom = 12;
            let isDesaLevel = false;

            if (desa) {
                queries.push({ query: `Desa ${desa}, ${kecamatan}, ${kabupaten}, ${provinsi}, Indonesia` });
                queries.push({ query: `${desa}, ${kecamatan}, ${kabupaten}, Indonesia` });
                targetZoom = 17;
                isDesaLevel = true;
            } else if (kecamatan) {
                queries.push({ query: `Kecamatan ${kecamatan}, ${kabupaten}, ${provinsi}, Indonesia` });
                targetZoom = 14;
            } else {
                queries.push({ query: `${kabupaten}, ${provinsi}, Indonesia` });
                targetZoom = 12;
            }

            for (let step of queries) {
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(step.query)}`);
                    const data = await response.json();
                    if (data && data.length > 0) {
                        desaBoundingBox = isDesaLevel ? data[0].boundingbox : null;
                        setLocation(parseFloat(data[0].lat), parseFloat(data[0].lon), targetZoom, false);
                        break;
                    }
                } catch (e) { console.error("Pelacakan wilayah Nominatim error:", e); }
            }
        }

        // Logic Cascade & Load dropdown data terisi saat halaman dirender lama (Old Data Check)
        const initialProv = document.getElementById('provinsi').value;
        if(initialProv) {
            await loadKabupaten(initialProv, "{{ $alamat->kabupaten ?? '' }}");
        }

        document.getElementById('provinsi').addEventListener('change', function() { loadKabupaten(this.value); });
        document.getElementById('kabupaten').addEventListener('change', function() { loadKecamatan(this.value); });
        document.getElementById('kecamatan').addEventListener('change', function() { loadDesa(this.value); });
        document.getElementById('desa').addEventListener('change', zoomToWilayah);

        async function loadKabupaten(provCode, selectedCode = '') {
            if(!provCode) return;
            const res = await fetch(`/cities/${provCode}`);
            const data = await res.json();
            let html = '<option value="">Pilih Kabupaten</option>';
            data.forEach(item => {
                html += `<option value="${item.code}" ${selectedCode == item.code ? 'selected' : ''}>${item.name}</option>`;
            });
            document.getElementById('kabupaten').innerHTML = html;
            
            if(selectedCode) {
                await loadKecamatan(selectedCode, "{{ $alamat->kecamatan ?? '' }}");
            } else {
                zoomToWilayah();
            }
        }

        async function loadKecamatan(kabCode, selectedCode = '') {
            if(!kabCode) return;
            const res = await fetch(`/districts/${kabCode}`);
            const data = await res.json();
            let html = '<option value="">Pilih Kecamatan</option>';
            data.forEach(item => {
                html += `<option value="${item.code}" ${selectedCode == item.code ? 'selected' : ''}>${item.name}</option>`;
            });
            document.getElementById('kecamatan').innerHTML = html;

            if(selectedCode) {
                await loadDesa(selectedCode, "{{ $alamat->desa ?? '' }}");
            } else {
                zoomToWilayah();
            }
        }

        async function loadDesa(kecCode, selectedCode = '') {
            if(!kecCode) return;
            const res = await fetch(`/villages/${kecCode}`);
            const data = await res.json();
            let html = '<option value="">Pilih Desa</option>';
            data.forEach(item => {
                html += `<option value="${item.code}" ${selectedCode == item.code ? 'selected' : ''}>${item.name}</option>`;
            });
            document.getElementById('desa').innerHTML = html;
            zoomToWilayah();
        }

        // ── 3. AUTOCOMPLETE BOX LOGIC ──
        const searchInput = document.getElementById('search-location');
        const suggBox = document.createElement('div');
        suggBox.className = 'autocomplete-box';
        searchInput.parentNode.appendChild(suggBox);

        let timer;
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            const val = this.value.trim();
            if(val.length < 3) { suggBox.innerHTML = ''; return; }

            timer = setTimeout(async () => {
                let url = `https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=5&q=${encodeURIComponent(val)}`;
                if (desaBoundingBox) {
                    const viewbox = `${desaBoundingBox[2]},${desaBoundingBox[1]},${desaBoundingBox[3]},${desaBoundingBox[0]}`;
                    url += `&viewbox=${viewbox}&bounded=1`;
                }
                try {
                    const res = await fetch(url);
                    const data = await res.json();
                    suggBox.innerHTML = '';
                    
                    if(data.length === 0) {
                        suggBox.innerHTML = `<div class="p-3 text-slate-400 text-xs">Jalan tidak ketemu di peta desa ini. Klik manual pada blok peta saja.</div>`;
                        return;
                    }

                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'autocomplete-item';
                        const name = item.name || val;
                        div.innerHTML = `<strong>${name}</strong><br><span style="color:#999;font-size:10px;">${item.display_name}</span>`;
                        div.addEventListener('click', function() {
                            searchInput.value = name;
                            document.getElementById('detail_alamat').value = item.display_name;
                            setLocation(parseFloat(item.lat), parseFloat(item.lon), 18, false);
                            suggBox.innerHTML = '';
                        });
                        suggBox.appendChild(div);
                    });
                } catch(e) { console.error(e); }
            }, 400);
        });

        document.addEventListener('click', function(e) {
            if(e.target !== searchInput) suggBox.innerHTML = '';
        });
    });
</script>
@endpush