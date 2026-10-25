@extends('layouts.admin')

@section('page-title', 'Profil Saya')

@section('content')

<div class="min-h-screen bg-gray-50 dark:bg-[#0d1117] transition-colors duration-300">
    <div class="max-w-2xl mx-auto px-6 py-8">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-lg font-semibold text-gray-900 dark:text-slate-100">Profil</h1>
            <p class="text-sm text-gray-400 dark:text-slate-500 mt-0.5">Kelola informasi akun dan keamanan</p>
        </div>

        {{-- Flash sukses --}}
        @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-5
                    bg-emerald-50 dark:bg-emerald-950/40
                    border border-emerald-200 dark:border-emerald-900/60
                    text-emerald-700 dark:text-emerald-400 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="flex flex-col gap-4">

            {{-- ── FOTO PROFIL ── --}}
            <div class="bg-white dark:bg-[#161b22]
                        border border-gray-100 dark:border-[#21262d]
                        rounded-2xl p-5">

                <div class="flex items-center gap-5">

                    {{-- Avatar --}}
                    <div class="relative group flex-shrink-0">
                        <div id="avatar-container"
                             class="w-16 h-16 rounded-2xl overflow-hidden
                                    bg-blue-50 dark:bg-blue-950/40
                                    border border-gray-100 dark:border-[#21262d]
                                    flex items-center justify-center">
                            @if(auth()->user()->foto)
                                <img id="avatar-preview"
                                     src="{{ asset('storage/' . auth()->user()->foto) }}"
                                     alt="Foto profil"
                                     class="w-full h-full object-cover">
                            @else
                                <span id="avatar-initials"
                                      class="text-lg font-semibold text-blue-600 dark:text-blue-400 select-none">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                                </span>
                                <img id="avatar-preview" class="w-full h-full object-cover hidden" alt="Foto profil">
                            @endif
                        </div>

                        {{-- Overlay kamera saat hover --}}
                        <label for="avatar-input"
                               class="absolute inset-0 rounded-2xl cursor-pointer
                                      bg-black/40 opacity-0 group-hover:opacity-100
                                      transition-opacity flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </label>
                        <input type="file" id="avatar-input" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                    </div>

                    {{-- Info & actions --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-slate-200">
                            {{ auth()->user()->name ?? 'Administrator' }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5 mb-3">
                            {{ auth()->user()->email ?? 'admin@example.com' }}
                        </p>
                        <div class="flex items-center gap-2">
                            <label for="avatar-input"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium cursor-pointer
                                          bg-gray-100 dark:bg-[#21262d]
                                          text-gray-600 dark:text-slate-400
                                          hover:bg-gray-200 dark:hover:bg-[#2d3748]
                                          transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Ganti foto
                            </label>
                            <button type="button" onclick="removeAvatar()"
                                    id="remove-btn"
                                    class="{{ auth()->user()->foto ? '' : 'hidden' }} px-3 py-1.5 rounded-lg text-xs font-medium
                                           text-red-500 dark:text-red-400
                                           hover:bg-red-50 dark:hover:bg-red-950/30
                                           transition-colors">
                                Hapus
                            </button>
                        </div>
                    </div>

                    {{-- Badge role --}}
                    <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium
                                 text-violet-700 dark:text-violet-400
                                 bg-violet-50 dark:bg-violet-950/50
                                 px-2.5 py-1 rounded-lg flex-shrink-0">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        {{ ucfirst(auth()->user()->role ?? 'Administrator') }}
                    </span>

                </div>

                {{-- Form upload avatar (tersembunyi, submit via JS) --}}
                <form id="avatar-form" method="POST"
                      action="{{ route('admin.profile.avatar') }}"
                      enctype="multipart/form-data"
                      class="hidden">
                    @csrf
                    @method('PUT')
                    <input type="file" name="foto" id="avatar-form-input">
                </form>

            </div>

            {{-- ── EDIT PROFIL ── --}}
            <div class="bg-white dark:bg-[#161b22]
                        border border-gray-100 dark:border-[#21262d]
                        rounded-2xl overflow-hidden">

                <div class="flex items-center gap-2 px-5 py-3.5
                            border-b border-gray-100 dark:border-[#21262d]">
                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                    <h3 class="text-sm font-medium text-gray-800 dark:text-slate-200">
                        Informasi profil
                    </h3>
                </div>

                <form method="POST" action="{{ route('admin.profile.update') }}" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-slate-500 mb-1.5">
                            Nama lengkap
                        </label>

                        <input type="text"
                            name="name"
                            value="{{ old('name', auth()->user()->name) }}"
                            placeholder="Masukkan nama"
                            class="w-full px-3.5 py-2 text-sm rounded-xl
                                    bg-gray-50 dark:bg-[#0d1117]
                                    border border-gray-200 dark:border-[#21262d]
                                    text-gray-800 dark:text-slate-200
                                    focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400
                                    @error('name') border-red-400 @enderror">

                        @error('name')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-4 py-2 rounded-xl text-sm font-medium
                                    bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                            Update Nama
                        </button>
                    </div>
                </form>

                        {{-- EMAIL --}}
                        <form method="POST" action="{{ route('admin.profile.email') }}" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-slate-500 mb-1.5">
                            Email
                        </label>

                        <input type="email"
                            name="email"
                            value="{{ old('email', auth()->user()->email) }}"
                            placeholder="email@contoh.com"
                            class="w-full px-3.5 py-2 text-sm rounded-xl
                                    bg-gray-50 dark:bg-[#0d1117]
                                    border border-gray-200 dark:border-[#21262d]
                                    text-gray-800 dark:text-slate-200
                                    focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400
                                    @error('email') border-red-400 @enderror">

                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-4 py-2 rounded-xl text-sm font-medium
                                    bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                            Update Email
                        </button>
                    </div>
                </form>

            {{-- ── GANTI PASSWORD ── --}}
            <div class="bg-white dark:bg-[#161b22]
                        border border-gray-100 dark:border-[#21262d]
                        rounded-2xl overflow-hidden">

                <div class="flex items-center gap-2 px-5 py-3.5
                            border-b border-gray-100 dark:border-[#21262d]">
                    <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                    <h3 class="text-sm font-medium text-gray-800 dark:text-slate-200">
                        Ganti password
                    </h3>
                </div>

                <form method="POST" action="{{ route('admin.profile.password') }}" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-slate-500 mb-1.5">
                            Password saat ini
                        </label>
                        <div class="relative">
                            <input type="password"
                                   id="current_password"
                                   name="current_password"
                                   placeholder="Masukkan password saat ini"
                                   class="w-full px-3.5 py-2 pr-10 text-sm rounded-xl
                                          bg-gray-50 dark:bg-[#0d1117]
                                          border border-gray-200 dark:border-[#21262d]
                                          text-gray-800 dark:text-slate-200
                                          placeholder-gray-300 dark:placeholder-slate-700
                                          focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400
                                          transition-colors
                                          @error('current_password') border-red-400 @enderror">
                            <button type="button"
                                    onclick="togglePass('current_password','eye0')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2
                                           text-gray-300 dark:text-slate-600 hover:text-gray-500 dark:hover:text-slate-400 transition-colors">
                                <svg id="eye0" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-slate-500 mb-1.5">
                                Password baru
                            </label>
                            <div class="relative">
                                <input type="password"
                                       id="new_password"
                                       name="password"
                                       placeholder="Min. 8 karakter"
                                       class="w-full px-3.5 py-2 pr-10 text-sm rounded-xl
                                              bg-gray-50 dark:bg-[#0d1117]
                                              border border-gray-200 dark:border-[#21262d]
                                              text-gray-800 dark:text-slate-200
                                              placeholder-gray-300 dark:placeholder-slate-700
                                              focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400
                                              transition-colors
                                              @error('password') border-red-400 @enderror">
                                <button type="button"
                                        onclick="togglePass('new_password','eye1')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2
                                               text-gray-300 dark:text-slate-600 hover:text-gray-500 dark:hover:text-slate-400 transition-colors">
                                    <svg id="eye1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror

                            {{-- Strength bar --}}
                            <div id="strength-wrap" class="hidden mt-2">
                                <div class="flex gap-1">
                                    <div id="s1" class="h-0.5 flex-1 rounded-full bg-gray-200 dark:bg-[#21262d] transition-colors"></div>
                                    <div id="s2" class="h-0.5 flex-1 rounded-full bg-gray-200 dark:bg-[#21262d] transition-colors"></div>
                                    <div id="s3" class="h-0.5 flex-1 rounded-full bg-gray-200 dark:bg-[#21262d] transition-colors"></div>
                                    <div id="s4" class="h-0.5 flex-1 rounded-full bg-gray-200 dark:bg-[#21262d] transition-colors"></div>
                                </div>
                                <span id="strength-label" class="text-xs text-gray-400 mt-1 inline-block"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-slate-500 mb-1.5">
                                Konfirmasi password
                            </label>
                            <div class="relative">
                                <input type="password"
                                       id="confirm_password"
                                       name="password_confirmation"
                                       placeholder="Ulangi password baru"
                                       class="w-full px-3.5 py-2 pr-10 text-sm rounded-xl
                                              bg-gray-50 dark:bg-[#0d1117]
                                              border border-gray-200 dark:border-[#21262d]
                                              text-gray-800 dark:text-slate-200
                                              placeholder-gray-300 dark:placeholder-slate-700
                                              focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400
                                              transition-colors">
                                <button type="button"
                                        onclick="togglePass('confirm_password','eye2')"
                                        class="absolute right-3 top-1/2 -translate-y-1/2
                                               text-gray-300 dark:text-slate-600 hover:text-gray-500 dark:hover:text-slate-400 transition-colors">
                                    <svg id="eye2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-1">
                        <button type="reset"
                                onclick="resetStrength()"
                                class="px-3.5 py-2 rounded-xl text-sm font-medium
                                       text-gray-500 dark:text-slate-400
                                       bg-gray-100 dark:bg-[#21262d]
                                       hover:bg-gray-200 dark:hover:bg-[#2d3748]
                                       transition-colors">
                            Reset
                        </button>
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-medium
                                       bg-amber-500 hover:bg-amber-600 text-white transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Perbarui
                        </button>
                    </div>
                </form>
            </div>

            {{-- ── ZONA BAHAYA ── --}}
            <div class="bg-white dark:bg-[#161b22]
                        border border-red-100 dark:border-red-900/30
                        rounded-2xl p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-slate-200">Keluar dari sesi ini</p>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">Anda akan diarahkan ke halaman login</p>
                    </div>
                    <form method="POST" action="{{ route('admin.profile.logout') }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-sm font-medium
                                       text-red-500 dark:text-red-400
                                       bg-red-50 dark:bg-red-950/40
                                       border border-red-100 dark:border-red-900/40
                                       hover:bg-red-100 dark:hover:bg-red-900/60
                                       transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Toggle show/hide password
function togglePass(inputId, eyeId) {
    const input = document.getElementById(inputId);
    const eye   = document.getElementById(eyeId);
    const show  = input.type === 'password';
    input.type  = show ? 'text' : 'password';
    eye.innerHTML = show
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                 d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                 d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
}

// Password strength
const newPassInput = document.getElementById('new_password');
newPassInput.addEventListener('input', function () {
    const val  = this.value;
    const wrap = document.getElementById('strength-wrap');
    if (!val) { wrap.classList.add('hidden'); resetStrength(); return; }
    wrap.classList.remove('hidden');

    let score = 0;
    if (val.length >= 8)           score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;

    const colors = ['', '#ef4444', '#f59e0b', '#3b82f6', '#10b981'];
    const labels = ['', 'Lemah', 'Cukup', 'Kuat', 'Sangat kuat'];

    for (let i = 1; i <= 4; i++) {
        const el = document.getElementById('s' + i);
        el.style.background = i <= score
            ? colors[score]
            : '';
        el.className = 'h-0.5 flex-1 rounded-full transition-colors ' +
            (i <= score ? '' : 'bg-gray-200 dark:bg-[#21262d]');
    }

    const lbl = document.getElementById('strength-label');
    lbl.textContent = labels[score];
    lbl.style.color = colors[score];
});

function resetStrength() {
    for (let i = 1; i <= 4; i++) {
        const el = document.getElementById('s' + i);
        el.style.background = '';
        el.className = 'h-0.5 flex-1 rounded-full bg-gray-200 dark:bg-[#21262d] transition-colors';
    }
    document.getElementById('strength-label').textContent = '';
    document.getElementById('strength-wrap').classList.add('hidden');
}

// Preview & upload avatar
function previewAvatar(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const preview  = document.getElementById('avatar-preview');
        const initials = document.getElementById('avatar-initials');

        preview.src = e.target.result;
        preview.classList.remove('hidden');
        if (initials) initials.classList.add('hidden');
        document.getElementById('remove-btn').classList.remove('hidden');

        // Sync ke form upload dan submit otomatis
        const formInput = document.getElementById('avatar-form-input');
        const dt = new DataTransfer();
        dt.items.add(file);
        formInput.files = dt.files;
        document.getElementById('avatar-form').submit();
    };
    reader.readAsDataURL(file);
}

function removeAvatar() {
    if (!confirm('Hapus foto profil?')) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('admin.profile.avatar.delete') }}";

    form.innerHTML = `
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    `;

    document.body.appendChild(form);
    form.submit();
}
</script>

@endsection