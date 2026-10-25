@extends('layouts.admin')

@section('page-title', 'Data Kaprodi')

@section('content')

<div class="kp-page">

    {{-- HEADER --}}
    <div class="kp-header">
        <div>
            <h1 class="kp-title">Data Kaprodi</h1>
            <p class="kp-subtitle">Kelola akun kepala program studi</p>
        </div>
        <button class="kp-btn-primary" onclick="openTambahKaprodi()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Kaprodi
        </button>
    </div>

    {{-- SEARCH --}}
    <div class="kp-search-panel">
        <form method="GET" class="kp-search-form">
            <div class="kp-search-wrap">
                <svg class="kp-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau email..."
                    class="kp-search-input">
            </div>
            <button type="submit" class="kp-btn-primary">
                Cari
            </button>
        </form>
    </div>

    {{-- TABEL --}}
    <div class="kp-table-panel">
        <table class="kp-table">
            <thead>
                <tr>
                    <th class="kp-th">Nama</th>
                    <th class="kp-th">Email</th>
                    <th class="kp-th">Role</th>
                    <th class="kp-th kp-th--center">Aksi</th>
                </tr>
            </thead>
            <tbody>

            @forelse($kaprodi as $k)
                <tr class="kp-tr">
                    <td class="kp-td">
                        <div class="kp-name-cell">
                            <div class="kp-avatar">{{ strtoupper(substr($k->name, 0, 1)) }}</div>
                            <span class="kp-name-text">{{ $k->name }}</span>
                        </div>
                    </td>
                    <td class="kp-td kp-td--muted">{{ $k->email }}</td>
                    <td class="kp-td">
                        <span class="kp-badge">{{ ucfirst($k->role) }}</span>
                    </td>
                    <td class="kp-td kp-td--center">
                        <div class="kp-actions">
                            <button class="kp-btn-detail"
                                onclick="showDetail(
                                    '{{ $k->name }}',
                                    '{{ $k->email }}',
                                    '{{ ucfirst($k->role) }}',
                                    '{{ $k->created_at->format('d M Y') }}'
                                )">Detail</button>

                                 <button
                                class="kp-btn-detail"
                                onclick="openEditKaprodi(
                                    {{ $k->id }},
                                    '{{ $k->name }}',
                                    '{{ $k->email }}'
                                )">
                                Edit
                            </button>

                             <button
                                class="kp-btn-hapus"
                                onclick="hapusKaprodi({{ $k->id }}, '{{ $k->name }}')">
                                Hapus
                            </button>
        
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="kp-empty">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:#cbd5e1;margin-bottom:0.75rem"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <p>Belum ada data kaprodi</p>
                    </td>
                </tr>
            @endforelse

            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="kp-pagination">
        {{ $kaprodi->links() }}
    </div>

</div>

{{-- MODAL DETAIL --}}
<div id="modal-detail" class="kp-modal-overlay" onclick="closeDetail(event)">
    <div class="kp-modal">
        <div class="kp-modal-header">
            <h2 class="kp-modal-title">Detail Kaprodi</h2>
            <button class="kp-modal-close" onclick="closeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="kp-modal-body">
            <div class="kp-modal-avatar-wrap">
                <div class="kp-modal-avatar" id="modal-avatar"></div>
            </div>
            <div class="kp-modal-fields">
                <div class="kp-modal-field">
                    <span class="kp-modal-label">Nama</span>
                    <span class="kp-modal-value" id="modal-name"></span>
                </div>
                <div class="kp-modal-field">
                    <span class="kp-modal-label">Email</span>
                    <span class="kp-modal-value" id="modal-email"></span>
                </div>
                <div class="kp-modal-field">
                    <span class="kp-modal-label">Role</span>
                    <span class="kp-modal-value"><span class="kp-badge" id="modal-role"></span></span>
                </div>
                <div class="kp-modal-field">
                    <span class="kp-modal-label">Terdaftar</span>
                    <span class="kp-modal-value" id="modal-created"></span>
                </div>
            </div>
        </div>
        <div class="kp-modal-footer">
            <button class="kp-btn-hapus" style="padding:0.5rem 1.25rem" onclick="closeModal()">Tutup</button>
        </div>
    </div>
</div>

<style>
/* ============================================================
   PAGE
   ============================================================ */
.kp-page {
    padding: 2rem 1.75rem;
    background: #f8fafc;
    min-height: 100vh;
}

/* ============================================================
   HEADER
   ============================================================ */
.kp-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.kp-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.01em;
    margin-bottom: 0.2rem;
}
.kp-subtitle {
    font-size: 0.875rem;
    color: #64748b;
}

/* ============================================================
   BUTTONS
   ============================================================ */
.kp-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: #2563eb;
    color: #fff;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.6rem 1.125rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: background 0.15s;
    white-space: nowrap;
}
.kp-btn-primary:hover { background: #1d4ed8; }

.kp-btn-detail {
    font-size: 0.8rem;
    font-weight: 600;
    color: #2563eb;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    padding: 0.35rem 0.875rem;
    cursor: pointer;
    transition: background 0.15s;
}
.kp-btn-detail:hover { background: #dbeafe; }

.kp-btn-hapus {
    font-size: 0.8rem;
    font-weight: 600;
    color: #dc2626;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 6px;
    padding: 0.35rem 0.875rem;
    cursor: pointer;
    transition: background 0.15s;
}
.kp-btn-hapus:hover { background: #fee2e2; }

/* ============================================================
   SEARCH
   ============================================================ */
.kp-search-panel {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
}
.kp-search-form {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}
.kp-search-wrap {
    flex: 1;
    position: relative;
}
.kp-search-icon {
    position: absolute;
    left: 0.875rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
}
.kp-search-input {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.6rem 0.875rem 0.6rem 2.375rem;
    font-size: 0.875rem;
    color: #0f172a;
    background: #f8fafc;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.kp-search-input:focus {
    border-color: #93c5fd;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.08);
    background: #fff;
}
.kp-search-input::placeholder { color: #94a3b8; }

/* ============================================================
   TABLE
   ============================================================ */
.kp-table-panel {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}
.kp-table {
    width: 100%;
    border-collapse: collapse;
}
.kp-th {
    padding: 0.875rem 1.25rem;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #64748b;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
}
.kp-th--center { text-align: center; }

.kp-tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.1s;
}
.kp-tr:last-child { border-bottom: none; }
.kp-tr:hover { background: #f8fafc; }

.kp-td {
    padding: 0.875rem 1.25rem;
    font-size: 0.875rem;
    color: #1e293b;
    vertical-align: middle;
}
.kp-td--muted { color: #64748b; }
.kp-td--center { text-align: center; }

/* Name cell with avatar */
.kp-name-cell {
    display: flex;
    align-items: center;
    gap: 0.625rem;
}
.kp-avatar {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #eff6ff;
    color: #2563eb;
    font-size: 0.8rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.kp-name-text {
    font-weight: 500;
    color: #0f172a;
}

/* Role badge */
.kp-badge {
    font-size: 0.72rem;
    font-weight: 600;
    color: #1d4ed8;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-radius: 5px;
    padding: 0.22rem 0.6rem;
    white-space: nowrap;
}

/* Actions */
.kp-actions {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

/* Empty state */
.kp-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #94a3b8;
    font-size: 0.875rem;
    display: table-cell;
}
.kp-empty p { margin: 0; }

/* ============================================================
   PAGINATION
   ============================================================ */
.kp-pagination {
    margin-top: 1.25rem;
}

/* ============================================================
   MODAL
   ============================================================ */
.kp-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.4);
    z-index: 50;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.kp-modal-overlay.active {
    display: flex;
}
.kp-modal {
    background: #fff;
    border-radius: 16px;
    width: 100%;
    max-width: 420px;
    border: 1px solid #e2e8f0;
    animation: kp-modal-in 0.18s ease;
}
@keyframes kp-modal-in {
    from { opacity: 0; transform: translateY(10px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
.kp-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.125rem 1.375rem 0;
}
.kp-modal-title {
    font-size: 1rem;
    font-weight: 700;
    color: #0f172a;
}
.kp-modal-close {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s;
}
.kp-modal-close:hover { background: #f1f5f9; }
.kp-modal-body {
    padding: 1.25rem 1.375rem;
}
.kp-modal-avatar-wrap {
    display: flex;
    justify-content: center;
    margin-bottom: 1.25rem;
}
.kp-modal-avatar {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    background: #eff6ff;
    color: #2563eb;
    font-size: 1.375rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}
.kp-modal-fields {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.kp-modal-field {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.625rem 0.875rem;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #f1f5f9;
}
.kp-modal-label {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 500;
}
.kp-modal-value {
    font-size: 0.875rem;
    color: #0f172a;
    font-weight: 500;
    text-align: right;
}
.kp-modal-footer {
    padding: 0 1.375rem 1.25rem;
    display: flex;
    justify-content: flex-end;
}
</style>


<div id="modal-tambah-kaprodi" class="kp-modal-overlay">

    <div class="kp-modal">

        <div class="kp-modal-header">
            <h2 class="kp-modal-title">Tambah Kaprodi</h2>

            <button class="kp-modal-close" onclick="closeTambahKaprodi()">
                ✕
            </button>
        </div>

        <form method="POST" action="{{ route('admin.kaprodi.store') }}">

            @csrf

            <div class="kp-modal-body">

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text"
                        name="name"
                        class="kp-search-input"
                        required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email"
                        name="email"
                        class="kp-search-input"
                        required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password"
                        name="password"
                        class="kp-search-input"
                        required>
                </div>

            </div>

            <div class="kp-modal-footer">
                <button type="submit" class="kp-btn-primary">
                    Simpan
                </button>
            </div>

        </form>

    </div>

</div>

<div id="modal-edit-kaprodi" class="kp-modal-overlay">

    <div class="kp-modal">

        <div class="kp-modal-header">
            <h2 class="kp-modal-title">Edit Kaprodi</h2>

            <button class="kp-modal-close" onclick="closeEditKaprodi()">
                ✕
            </button>
        </div>

        <form method="POST" id="form-edit-kaprodi">

            @csrf
            @method('PUT')

            <div class="kp-modal-body">

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text"
                        name="name"
                        id="edit-name"
                        class="kp-search-input"
                        required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email"
                        name="email"
                        id="edit-email"
                        class="kp-search-input"
                        required>
                </div>

                <div class="mb-3">
                    <label>Password Baru</label>
                    <input type="password"
                        name="password"
                        class="kp-search-input">
                </div>

            </div>

            <div class="kp-modal-footer">
                <button type="submit" class="kp-btn-primary">
                    Update
                </button>
            </div>

        </form>

    </div>

</div>

@push('scripts')
<script>
function showDetail(name, email, role, created) {
    document.getElementById('modal-avatar').textContent = name.charAt(0).toUpperCase();
    document.getElementById('modal-name').textContent    = name;
    document.getElementById('modal-email').textContent   = email;
    document.getElementById('modal-role').textContent    = role;
    document.getElementById('modal-created').textContent = created;
    document.getElementById('modal-detail').classList.add('active');
}

function closeModal() {
    document.getElementById('modal-detail').classList.remove('active');
}

function closeDetail(event) {
    if (event.target === document.getElementById('modal-detail')) closeModal();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});


function openTambahKaprodi() {
    document
        .getElementById('modal-tambah-kaprodi')
        .classList.add('active');
}

function closeTambahKaprodi() {
    document
        .getElementById('modal-tambah-kaprodi')
        .classList.remove('active');
}

function openEditKaprodi(id, name, email) {

    document.getElementById('edit-name').value = name;
    document.getElementById('edit-email').value = email;

    document.getElementById('form-edit-kaprodi').action =
        `/admin/kaprodi/${id}`;

    document
        .getElementById('modal-edit-kaprodi')
        .classList.add('active');
}

function closeEditKaprodi() {
    document
        .getElementById('modal-edit-kaprodi')
        .classList.remove('active');
}

function hapusKaprodi(id, nama) {

    if (!confirm(`Hapus akun kaprodi ${nama}?`)) {
        return;
    }

    const form = document.createElement('form');

    form.method = 'POST';
    form.action = `/admin/kaprodi/${id}`;

    form.innerHTML = `
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    `;

    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush

@endsection