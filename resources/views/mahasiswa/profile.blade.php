@extends('layouts.app')

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
        --red:       #dc2626;
    }

    .pf-wrap * { font-family: 'Inter', sans-serif; }
    .pf-wrap .serif { font-family: 'EB Garamond', serif; }

    /* ── HERO BANNER LUXURY ── */
    .pf-hero {
        background: var(--blue);
        border-radius: 6px;
        padding: 30px 40px;
        margin-bottom: 24px;
    }
    .pf-hero h1 {
        font-family: 'EB Garamond', serif;
        font-size: 28px;
        font-weight: 500;
        color: #fff;
        margin-bottom: 4px;
    }
    .pf-hero p {
        font-size: 13px;
        color: rgba(255,255,255,0.65);
        font-weight: 300;
    }

    /* ── CARD MAIN ── */
    .pf-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 32px;
    }

    /* ── AVATAR COMPONENT ── */
    .avatar-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-bottom: 36px;
        padding-bottom: 30px;
        border-bottom: 1px solid var(--border);
        width: 100%;
    }

    .avatar-wrapper {
        width: 110px;
        height: 110px;
        min-width: 110px;
        min-height: 110px;
        border-radius: 50%;
        border: 1px solid var(--border);
        padding: 4px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .avatar-frame {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        object-position: center;
        display: block;
    }

    .avatar-default {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--blue-light);
        color: var(--blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 600;
    }

    .file-input-wrapper {
        margin-top: 14px;
        display: flex;
        justify-content: center;
    }
    
    .btn-upload-kustom {
        display: inline-block;
        padding: 6px 16px;
        background: #fafafa;
        border: 1px solid var(--border);
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        color: var(--text);
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }

    .btn-upload-kustom:hover {
        background: var(--blue-light);
        border-color: var(--blue-mid);
        color: var(--blue);
    }
    
    .hidden {
        display: none !important;
    }

    /* ── FORM CONTROL LUXURY ── */
    .pf-group {
        margin-bottom: 20px;
    }
    .pf-label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: 8px;
    }
    .form-control {
        width: 100%;
        border: 1px solid var(--border);
        background: #fff;
        border-radius: 4px;
        padding: 11px 14px;
        font-size: 13px;
        color: var(--text);
        transition: border-color 0.2s;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--blue-mid);
    }
    
    .form-control-readonly {
        background: #fafafa;
        color: #7c7c7c;
        cursor: not-allowed;
    }

    .password-lock-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .btn-edit-password {
        position: absolute;
        right: 12px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 4px;
        padding: 6px 10px;
        font-size: 11px;
        color: var(--blue);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }
    .btn-edit-password:hover {
        background: var(--blue-light);
        border-color: var(--blue-mid);
    }

    /* ── MODAL POPUP STYLES ── */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(3px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
    }
    .modal-overlay.show {
        opacity: 1;
        pointer-events: auto;
    }
    .modal-box {
        background: #fff;
        width: 100%;
        max-width: 420px;
        border-radius: 8px;
        padding: 28px;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        transform: scale(0.9);
        transition: transform 0.25s ease;
    }
    .modal-overlay.show .modal-box {
        transform: scale(1);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .modal-header h3 {
        font-size: 16px;
        font-weight: 600;
        color: var(--text);
    }
    .btn-close-modal {
        background: none;
        border: none;
        color: var(--muted);
        font-size: 20px;
        cursor: pointer;
    }
    .password-field-group {
        position: relative;
    }
    .eye-btn {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--muted);
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
    }

    /* ── BUTTON SUBMIT ── */
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
        background: #f3f4f6;
        color: var(--muted);
        font-size: 13px;
        font-weight: 500;
        padding: 12px 24px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }
    .btn-secondary:hover { background: #e5e7eb; }
</style>
@endpush

@section('content')
<div class="pf-wrap max-w-4xl mx-auto mt-4">
    
    {{-- ── HERO BANNER ── --}}
    <div class="pf-hero">
        <h1>Profile Mahasiswa</h1>
        <p>Kelola informasi pengaturan akun, nomor telepon, dan kredensial keamanan Anda.</p>
    </div>

    {{-- ── ALERT NOTIFIKASI ERROR / SUCCESS ── --}}
    @if(session('success'))
        <div style="background: #eaf5ec; border: 1px solid #c3e6cb; color: #1e7e34; padding: 14px 20px; border-radius: 4px; font-size: 13px; margin-bottom: 24px;">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Alert Khusus Error Validasi Input --}}
    @if($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fca5a5; color: var(--red); padding: 14px 20px; border-radius: 4px; font-size: 13px; margin-bottom: 24px;">
            ✕ {{ $errors->first() }}
        </div>
    @endif

    {{-- ── CARD UTAMA ── --}}
    <div class="pf-card">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- FOTO PROFILE SECTION --}}
            <div class="avatar-container">
                <div class="avatar-wrapper">
                    @if($user->foto)
                        <img id="avatarPreview" src="{{ asset('storage/foto-profil/' . $user->foto) }}" class="avatar-frame">
                    @else
                        <div id="avatarFallback" class="avatar-default">
                            {{ strtoupper(substr($mahasiswa->nama ?? 'M', 0, 1)) }}
                        </div>
                        <img id="avatarPreview" src="#" class="avatar-frame hidden">
                    @endif
                </div>

                <div class="file-input-wrapper">
                    <label for="fotoInput" class="btn-upload-kustom">
                        Pilih Foto
                    </label>
                    <input type="file" name="foto" id="fotoInput" accept="image/*" class="hidden">
                </div>
            </div>

            {{-- GRID INPUT DATA --}}
            <div class="grid md:grid-cols-2 gap-x-6 gap-y-2">

                {{-- NIM (READONLY) --}}
                <div class="pf-group">
                    <label class="pf-label">NIM</label>
                    <input type="text" value="{{ $mahasiswa->nim ?? '-' }}" readonly class="form-control form-control-readonly">
                </div>

                {{-- NAMA (READONLY) --}}
                <div class="pf-group">
                    <label class="pf-label">Nama Lengkap</label>
                    <input type="text" value="{{ $mahasiswa->nama ?? '-' }}" readonly class="form-control form-control-readonly">
                </div>

                {{-- PRODI (READONLY) --}}
                <div class="pf-group">
                    <label class="pf-label">Program Studi</label>
                    <input type="text" value="{{ $mahasiswa->prodi ?? '-' }}" readonly class="form-control form-control-readonly">
                </div>

                {{-- EMAIL (MUTABLE) --}}
                <div class="pf-group">
                    <label class="pf-label">Alamat Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control">
                </div>

                {{-- NO TELP (MUTABLE) --}}
                <div class="pf-group">
                    <label class="pf-label">Nomor Telepon</label>
                    <input type="text" name="no_telp" value="{{ $user->no_telp }}" placeholder="Masukkan nomor telepon" class="form-control">
                </div>

                {{-- PASSWORD TAMPILAN UTAMA (READONLY & FULL SENSOR) --}}
                <div class="pf-group">
                    <label class="pf-label">Keamanan Akun</label>
                    <div class="password-lock-wrapper">
                        <input type="text" value="••••••••••••••••" readonly class="form-control form-control-readonly" style="letter-spacing: 3px;">
                        <button type="button" class="btn-edit-password" onclick="openPasswordModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:13px; height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Ubah
                        </button>
                    </div>
                </div>

            </div>

            {{-- TOMBOL ACTION SIMPAN DATA PROFIL --}}
            <div style="margin-top: 32px; text-align: right; border-top: 1px solid var(--border); padding-top: 24px;">
                <button type="submit" name="action_type" value="update_profile" class="btn-primary">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

{{-- ── MODAL POP-UP GANTI PASSWORD (SEKARANG BERDIRI SENDIRI DI LUAR FORM UTAMA) ── --}}
<div id="passwordModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Ubah Password Akun</h3>
            <button type="button" class="btn-close-modal" onclick="closePasswordModal()">&times;</button>
        </div>
        
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            
            {{-- PASSWORD LAMA --}}
            <div class="pf-group">
                <label class="pf-label">Password Lama</label>
                <div class="password-field-group">
                    <input type="password" name="old_password" id="old_password" class="form-control" required placeholder="Masukkan password saat ini">
                    <button type="button" onclick="toggleField('old_password', 'eyeOpenOld', 'eyeCloseOld')" class="eye-btn">
                        <svg id="eyeOpenOld" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eyeCloseOld" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" class="hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 012.042-3.362m3.087-2.852A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.97 9.97 0 01-4.132 5.411M15 12a3 3 0 00-3-3m0 0a2.99 2.99 0 00-2.12.879M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- PASSWORD BARU --}}
            <div class="pf-group" style="margin-bottom: 28px;">
                <label class="pf-label">Password Baru</label>
                <div class="password-field-group">
                    <input type="password" name="password" id="new_password" class="form-control" required placeholder="Minimal 8 karakter baru">
                    <button type="button" onclick="toggleField('new_password', 'eyeOpenNew', 'eyeCloseNew')" class="eye-btn">
                        <svg id="eyeOpenNew" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eyeCloseNew" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;" class="hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 012.042-3.362m3.087-2.852A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.97 9.97 0 01-4.132 5.411M15 12a3 3 0 00-3-3m0 0a2.99 2.99 0 00-2.12.879M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

            <div style="text-align: right; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn-secondary" onclick="closePasswordModal()">Batal</button>
                <button type="submit" name="action_type" value="update_password" class="btn-primary" style="padding: 12px 24px;">Perbarui Password</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fitur live preview foto profile
    document.getElementById('fotoInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                const fallback = document.getElementById('avatarFallback');
                
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                
                if (fallback) {
                    fallback.classList.add('hidden');
                }
            }
            
            reader.readAsDataURL(file);
        }
    });

    // Manajemen Modal
    function openPasswordModal() {
        document.getElementById('passwordModal').classList.add('show');
    }

    // Mengosongkan data input kembali setelah modal di-close
    function closePasswordModal() {
        document.getElementById('passwordModal').classList.remove('show');
        document.getElementById('old_password').value = '';
        document.getElementById('new_password').value = '';
    }

    // Fitur toggle show/hide password dinamis berdasarkan ID field
    function toggleField(fieldId, openIconId, closeIconId) {
        const input = document.getElementById(fieldId);
        const eyeOpen = document.getElementById(openIconId);
        const eyeClose = document.getElementById(closeIconId);

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClose.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClose.classList.add('hidden');
        }
    }
</script>
@endsection