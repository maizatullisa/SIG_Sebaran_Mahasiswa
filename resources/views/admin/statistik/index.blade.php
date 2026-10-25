@extends('layouts.admin')

@section('page-title', 'Statistik Mahasiswa')

@section('content')

<div class="stats-page">

    {{-- HEADER --}}
    <div class="stats-header">
        <h1 class="stats-title">Statistik Mahasiswa</h1>
        <p class="stats-subtitle">Visualisasi dan analisis data mahasiswa</p>
    </div>

    <div class="stats-toggle" role="tablist">
        <button type="button" class="stats-toggle__btn is-active" data-target="page-ringkasan">
            Ringkasan Umum
        </button>
        <button type="button" class="stats-toggle__btn" data-target="page-sebaran">
            Sebaran Sekolah &amp; Wilayah
        </button>
    </div>


    <div class="stats-tabpage is-active" id="page-ringkasan">

        {{-- CARD STATISTIK --}}
        <div class="stats-grid">

            <div class="stat-card stat-card--indigo">
                <p class="stat-card__label">Total Mahasiswa</p>
                <p class="stat-card__value">{{ $totalMahasiswa }}</p>
                <div class="stat-card__footer">
                    <span class="stat-card__tag">Keseluruhan</span>
                </div>
            </div>

            <div class="stat-card stat-card--blue">
                <p class="stat-card__label">Total Prodi</p>
                <p class="stat-card__value">{{ $totalProdi }}</p>
                <div class="stat-card__footer">
                    <span class="stat-card__tag">Program Studi</span>
                </div>
            </div>

            <div class="stat-card stat-card--violet">
                <p class="stat-card__label">Prodi Terbanyak</p>
                <p class="stat-card__value stat-card__value--text">{{ $prodiStats->first()?->prodi ?? '-' }}</p>
                <div class="stat-card__footer">
                    <span class="stat-card__tag">Peringkat 1</span>
                </div>
            </div>

            <div class="stat-card stat-card--sky">
                <p class="stat-card__label">Mahasiswa Prodi Terbanyak</p>
                <p class="stat-card__value">{{ $prodiStats->first()?->total ?? 0 }}</p>
                <div class="stat-card__footer">
                    <span class="stat-card__tag">Mahasiswa</span>
                </div>
            </div>

        </div>

        {{-- GRAFIK PRODI --}}
        <div class="chart-panel">
            <div class="chart-panel__header">
                <div>
                    <h2 class="chart-panel__title">Grafik Mahasiswa per Prodi</h2>
                    <p class="chart-panel__sub">Distribusi mahasiswa berdasarkan program studi</p>
                </div>
            </div>
            <div class="chart-panel__body">
                <canvas id="prodiChart"></canvas>
            </div>
        </div>

        {{-- GRAFIK ANGKATAN --}}
        <div class="chart-panel">
            <div class="chart-panel__header">
                <div>
                    <h2 class="chart-panel__title">Grafik Mahasiswa per Angkatan</h2>
                    <p class="chart-panel__sub">Berdasarkan 2 digit awal NIM</p>
                </div>
            </div>
            <div class="chart-panel__body">
                <canvas id="angkatanChart"></canvas>
            </div>
        </div>

    </div>

    <div class="stats-tabpage" id="page-sebaran">

        {{-- GRAFIK TOP SEKOLAH --}}
        <div class="chart-panel">
            <div class="chart-panel__header">
                <div>
                    <h2 class="chart-panel__title">Top {{ $sekolahStats->count() }} Sekolah Asal Terbanyak</h2>
                    <p class="chart-panel__sub">Klik salah satu batang untuk melihat rincian prodi</p>
                </div>
            </div>
            <div class="chart-panel__body">
                <canvas id="sekolahChart"></canvas>
            </div>
            <div class="detail-panel" id="sekolahDetail">
                <p class="detail-panel__empty">Klik salah satu sekolah pada grafik di atas untuk melihat rincian per prodi.</p>
            </div>
        </div>

        {{-- GRAFIK TOP PROVINSI --}}
        <div class="chart-panel">
            <div class="chart-panel__header">
                <div>
                    <h2 class="chart-panel__title">Top {{ $provinsiStats->count() }} Provinsi Asal Mahasiswa</h2>
                    <p class="chart-panel__sub">Berdasarkan domisili/alamat mahasiswa &middot; klik untuk rincian prodi</p>
                </div>
            </div>
            <div class="chart-panel__body">
                <canvas id="provinsiChart"></canvas>
            </div>
            <div class="detail-panel" id="provinsiDetail">
                <p class="detail-panel__empty">Klik salah satu provinsi pada grafik di atas untuk melihat rincian per prodi.</p>
            </div>
        </div>

        {{-- GRAFIK TOP KABUPATEN --}}
        <div class="chart-panel">
            <div class="chart-panel__header">
                <div>
                    <h2 class="chart-panel__title">Top {{ $kabupatenStats->count() }} Kabupaten/Kota Asal Mahasiswa</h2>
                    <p class="chart-panel__sub">Berdasarkan domisili/alamat mahasiswa &middot; klik untuk rincian prodi</p>
                </div>
            </div>
            <div class="chart-panel__body">
                <canvas id="kabupatenChart"></canvas>
            </div>
            <div class="detail-panel" id="kabupatenDetail">
                <p class="detail-panel__empty">Klik salah satu kabupaten/kota pada grafik di atas untuk melihat rincian per prodi.</p>
            </div>
        </div>

    </div>

</div>

<style>

.stats-page {
    padding: 2rem 1.75rem;
    background: #f8fafc;
    min-height: 100vh;
}

.stats-header {
    margin-bottom: 1.75rem;
}
.stats-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.01em;
    margin-bottom: 0.25rem;
}
.stats-subtitle {
    font-size: 0.875rem;
    color: #64748b;
}


.stats-toggle {
    display: inline-flex;
    gap: 0.25rem;
    background: #eef2ff;
    border: 1px solid #c7d2fe;
    padding: 0.3rem;
    border-radius: 10px;
    margin-bottom: 1.75rem;
}

.stats-toggle__btn {
    appearance: none;
    border: none;
    background: transparent;
    color: #4338ca;
    font-size: 0.82rem;
    font-weight: 600;
    padding: 0.55rem 1.1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.15s ease, color 0.15s ease;
    white-space: nowrap;
}

.stats-toggle__btn:hover {
    background: rgba(67,56,202,0.08);
}

.stats-toggle__btn.is-active {
    background: #4338ca;
    color: #ffffff;
}

@media (max-width: 600px) {
    .stats-toggle { display: flex; width: 100%; }
    .stats-toggle__btn { flex: 1; text-align: center; padding: 0.55rem 0.5rem; }
}


.stats-tabpage { display: none; }
.stats-tabpage.is-active { display: block; }


.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .stats-grid { grid-template-columns: 1fr; }
}

.stat-card {
    border-radius: 12px;
    padding: 1.375rem 1.5rem 1.125rem;
    border: 1px solid transparent;
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
    transition: box-shadow 0.15s ease, transform 0.15s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}

.stat-card__label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    margin: 0 0 0.25rem;
}

.stat-card__value {
    font-size: 2.25rem;
    font-weight: 700;
    letter-spacing: -0.03em;
    line-height: 1.1;
    margin: 0;
}

.stat-card__value--text {
    font-size: 1.05rem;
    font-weight: 600;
    letter-spacing: -0.01em;
    line-height: 1.4;
}

.stat-card__footer {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
}

.stat-card__tag {
    font-size: 0.7rem;
    font-weight: 600;
    border-radius: 5px;
    padding: 0.22rem 0.6rem;
    display: inline-block;
    letter-spacing: 0.02em;
}


.stat-card--indigo {
    background: #eef2ff;
    border-color: #c7d2fe;
}
.stat-card--indigo .stat-card__label { color: #4338ca; }
.stat-card--indigo .stat-card__value { color: #3730a3; }
.stat-card--indigo .stat-card__footer { border-top: 1px solid #c7d2fe; }
.stat-card--indigo .stat-card__tag   { background: #c7d2fe; color: #3730a3; }


.stat-card--blue {
    background: #eff6ff;
    border-color: #bfdbfe;
}
.stat-card--blue .stat-card__label { color: #1d4ed8; }
.stat-card--blue .stat-card__value { color: #1e40af; }
.stat-card--blue .stat-card__footer { border-top: 1px solid #bfdbfe; }
.stat-card--blue .stat-card__tag   { background: #bfdbfe; color: #1e40af; }


.stat-card--violet {
    background: #f5f3ff;
    border-color: #ddd6fe;
}
.stat-card--violet .stat-card__label { color: #6d28d9; }
.stat-card--violet .stat-card__value { color: #5b21b6; }
.stat-card--violet .stat-card__footer { border-top: 1px solid #ddd6fe; }
.stat-card--violet .stat-card__tag   { background: #ddd6fe; color: #5b21b6; }


.stat-card--sky {
    background: #f0f9ff;
    border-color: #bae6fd;
}
.stat-card--sky .stat-card__label { color: #0369a1; }
.stat-card--sky .stat-card__value { color: #075985; }
.stat-card--sky .stat-card__footer { border-top: 1px solid #bae6fd; }
.stat-card--sky .stat-card__tag   { background: #bae6fd; color: #075985; }


.chart-panel {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    margin-bottom: 1.25rem;
    overflow: hidden;
}

.chart-panel__header {
    padding: 1.25rem 1.5rem 1rem;
    border-bottom: 1px solid #f1f5f9;
}

.chart-panel__title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.2rem;
}

.chart-panel__sub {
    font-size: 0.8rem;
    color: #94a3b8;
    margin: 0;
}

.chart-panel__body {
    padding: 1.25rem 1.5rem 1.5rem;
    height: 340px;
    position: relative;
}

.chart-panel__body canvas {
    width: 100% !important;
    height: 100% !important;
}

.detail-panel {
    margin: 0 1.5rem 1.5rem;
    padding: 1rem 1.25rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
}

.detail-panel__empty {
    font-size: 0.82rem;
    color: #94a3b8;
    font-style: italic;
    margin: 0;
    text-align: center;
    padding: 0.5rem 0;
}

.detail-panel__loading {
    font-size: 0.82rem;
    color: #64748b;
    margin: 0;
    text-align: center;
    padding: 0.5rem 0;
}

.detail-panel__title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.15rem;
}

.detail-panel__count {
    font-size: 0.78rem;
    color: #64748b;
    margin-bottom: 0.85rem;
}

.detail-panel__count strong {
    color: #2563eb;
}

.detail-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.75rem;
    padding: 0.55rem 0.85rem;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}

.detail-item__prodi {
    font-size: 0.82rem;
    color: #0f172a;
    font-weight: 500;
}

.detail-item__total {
    font-size: 0.78rem;
    font-weight: 700;
    color: #1e40af;
    background: #eff6ff;
    padding: 0.2rem 0.65rem;
    border-radius: 6px;
    white-space: nowrap;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const gridColor = 'rgba(148,163,184,0.12)';
    const tickColor = '#94a3b8';

    Chart.defaults.font.family = "'Inter', 'system-ui', sans-serif";
    Chart.defaults.font.size   = 12;

    const toggleButtons = document.querySelectorAll('.stats-toggle__btn');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            toggleButtons.forEach(b => b.classList.remove('is-active'));
            document.querySelectorAll('.stats-tabpage').forEach(p => p.classList.remove('is-active'));

            this.classList.add('is-active');
            document.getElementById(this.dataset.target).classList.add('is-active');
        });
    });

    const prodiLabels = @json($prodiStats->pluck('prodi'));
    const prodiData   = @json($prodiStats->pluck('total'));

    new Chart(document.getElementById('prodiChart'), {
        type: 'bar',
        data: {
            labels: prodiLabels,
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: prodiData,
                backgroundColor: 'rgba(37,99,235,0.12)',
                borderColor: '#2563eb',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
                hoverBackgroundColor: 'rgba(37,99,235,0.22)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#f1f5f9',
                    bodyColor: '#94a3b8',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => '  ' + ctx.parsed.y.toLocaleString('id-ID') + ' mahasiswa'
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: tickColor, maxRotation: 30 },
                    border: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: { color: tickColor, callback: v => v.toLocaleString('id-ID') },
                    border: { display: false }
                }
            }
        }
    });

    const angkatanLabels = @json($angkatanStats->pluck('angkatan'));
    const angkatanData   = @json($angkatanStats->pluck('total'));

    new Chart(document.getElementById('angkatanChart'), {
        type: 'line',
        data: {
            labels: angkatanLabels,
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: angkatanData,
                tension: 0.4,
                fill: true,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.06)',
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#f1f5f9',
                    bodyColor: '#94a3b8',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        title: ctx => 'Angkatan ' + ctx[0].label,
                        label: ctx => '  ' + ctx.parsed.y.toLocaleString('id-ID') + ' mahasiswa'
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: tickColor },
                    border: { display: false }
                },
                y: {
                    beginAtZero: false,
                    grid: { color: gridColor },
                    ticks: { color: tickColor, callback: v => v.toLocaleString('id-ID') },
                    border: { display: false }
                }
            }
        }
    });

    function renderDetailPanel(containerId, data) {
        const el = document.getElementById(containerId);

        if (!data.items || data.items.length === 0) {
            el.innerHTML = '<p class="detail-panel__empty">Tidak ada data prodi untuk item ini.</p>';
            return;
        }

        let html = `
            <div class="detail-panel__title">${data.label}</div>
            <div class="detail-panel__count">Total <strong>${data.total.toLocaleString('id-ID')}</strong> mahasiswa</div>
            <div class="detail-list">
        `;

        data.items.forEach(item => {
            html += `
                <div class="detail-item">
                    <span class="detail-item__prodi">${item.prodi}</span>
                    <span class="detail-item__total">${Number(item.total).toLocaleString('id-ID')} mhs</span>
                </div>
            `;
        });

        html += '</div>';
        el.innerHTML = html;
    }

    function showLoading(containerId) {
        document.getElementById(containerId).innerHTML =
            '<p class="detail-panel__loading">Memuat rincian...</p>';
    }

    function showError(containerId) {
        document.getElementById(containerId).innerHTML =
            '<p class="detail-panel__empty">Gagal memuat rincian, coba lagi.</p>';
    }

    function makeClickableBarChart(canvasId, labels, data, color, onBarClick) {
        return new Chart(document.getElementById(canvasId), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: data,
                    backgroundColor: color.fill,
                    borderColor: color.line,
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                    hoverBackgroundColor: color.hover,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                onClick: (evt, elements, chart) => {
                    if (!elements.length) return;
                    onBarClick(elements[0].index);
                },
                onHover: (evt, elements) => {
                    evt.native.target.style.cursor = elements.length ? 'pointer' : 'default';
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#f1f5f9',
                        bodyColor: '#94a3b8',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => '  ' + ctx.parsed.x.toLocaleString('id-ID') + ' mahasiswa'
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { color: tickColor, callback: v => v.toLocaleString('id-ID') },
                        border: { display: false }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: tickColor },
                        border: { display: false }
                    }
                }
            }
        });
    }


    const sekolahLabels = @json($sekolahStats->pluck('nama_sekolah'));
    const sekolahData    = @json($sekolahStats->pluck('total'));

    makeClickableBarChart(
        'sekolahChart', sekolahLabels, sekolahData,
        { fill: 'rgba(109,40,217,0.15)', line: '#6d28d9', hover: 'rgba(109,40,217,0.25)' },
        function (index) {
            const nama = sekolahLabels[index];
            showLoading('sekolahDetail');
            fetch(`{{ route('statistik.detail-sekolah') }}?nama=` + encodeURIComponent(nama))
                .then(res => res.json())
                .then(data => renderDetailPanel('sekolahDetail', data))
                .catch(() => showError('sekolahDetail'));
        }
    );


    const provinsiLabels = @json($provinsiStats->pluck('provinsi'));
    const provinsiData    = @json($provinsiStats->pluck('total'));
    const provinsiKodes   = @json($provinsiStats->pluck('kode_provinsi'));

    makeClickableBarChart(
        'provinsiChart', provinsiLabels, provinsiData,
        { fill: 'rgba(3,105,161,0.15)', line: '#0369a1', hover: 'rgba(3,105,161,0.25)' },
        function (index) {
            const kode = provinsiKodes[index];
            showLoading('provinsiDetail');
            fetch(`{{ route('statistik.detail-provinsi') }}?kode=` + encodeURIComponent(kode))
                .then(res => res.json())
                .then(data => renderDetailPanel('provinsiDetail', data))
                .catch(() => showError('provinsiDetail'));
        }
    );

    const kabupatenLabels = @json($kabupatenStats->pluck('kabupaten'));
    const kabupatenData    = @json($kabupatenStats->pluck('total'));
    const kabupatenKodes   = @json($kabupatenStats->pluck('kode_kabupaten'));

    makeClickableBarChart(
        'kabupatenChart', kabupatenLabels, kabupatenData,
        { fill: 'rgba(29,78,216,0.15)', line: '#1d4ed8', hover: 'rgba(29,78,216,0.25)' },
        function (index) {
            const kode = kabupatenKodes[index];
            showLoading('kabupatenDetail');
            fetch(`{{ route('statistik.detail-kabupaten') }}?kode=` + encodeURIComponent(kode))
                .then(res => res.json())
                .then(data => renderDetailPanel('kabupatenDetail', data))
                .catch(() => showError('kabupatenDetail'));
        }
    );

});
</script>

@endsection