<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIG Mahasiswa — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }

        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        #sidebar { transition: transform 0.25s ease; }

        .nav-active {
            background: #eff6ff !important;
            color: #1d4ed8 !important;
        }
        .nav-active svg { color: #3b82f6 !important; }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">

<div id="overlay"
     onclick="closeSidebar()"
     class="fixed inset-0 bg-black/40 z-20 hidden lg:hidden">
</div>

<div class="flex min-h-screen">

    <aside id="sidebar"
           class="fixed lg:static inset-y-0 left-0 z-30
                  w-64 flex flex-col
                  bg-white
                  border-r border-gray-100
                  -translate-x-full lg:translate-x-0">

                {{-- Logo --}}
                <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100">

                    {{-- Logo UNIPMA --}}
                    <div class="w-9 h-9 flex-shrink-0">
                        <img src="{{ asset('images/unipma-logo.png') }}"
                            alt="UNIPMA"
                            class="w-full h-full object-contain">
                    </div>

                    <div>
                        <h1 class="text-sm font-semibold text-gray-900 leading-none">
                            SIG Fakultas Teknik 
                        </h1>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Sistem Informasi Geografis
                        </p>
                    </div>

                </div>
        {{-- User Profile --}}
        <div class="px-4 pt-3 pb-3 border-b border-gray-100">
            <!-- <p class="px-3 pb-2 text-xs font-semibold tracking-widest uppercase text-gray-400">
                Profile
            </p> -->
            <a href="{{ route('admin.profile') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl
                      bg-gradient-to-r from-blue-50 to-slate-50
                      border border-blue-100 shadow-sm
                      hover:from-blue-100 hover:to-blue-50 hover:shadow-md
                      {{ request()->routeIs('admin.profile') ? 'border-blue-300 ring-1 ring-blue-200' : '' }}
                      group transition-all duration-150">
                <div class="relative flex-shrink-0">
                   <div class="w-10 h-10 rounded-xl overflow-hidden bg-blue-600 flex items-center justify-center text-white text-sm font-semibold ring-2 ring-white shadow-sm">
    
                    @if(auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}"
                            class="w-full h-full object-cover"
                            alt="avatar">
                    @else
                        <span>
                            {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                        </span>
                    @endif

                </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 rounded-full ring-2 ring-white"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate leading-none mb-1 group-hover:text-blue-700 transition-colors">
                        {{ auth()->user()->name ?? 'Administrator' }}
                    </p>
                    <p class="text-xs text-gray-500 leading-none flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                        {{ ucfirst(auth()->user()->role ?? 'Admin') }}
                    </p>
                </div>
                <svg class="w-4 h-4 text-blue-300 flex-shrink-0 group-hover:text-blue-500 group-hover:translate-x-0.5 transition-all duration-150"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto sidebar-scroll px-3 py-3 space-y-0.5">

            <p class="px-3 py-2 text-xs font-semibold tracking-widest uppercase text-gray-400">
                Menu Utama
            </p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500
                      hover:bg-slate-50 hover:text-gray-800 group transition-colors
                      {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-500' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <div class="h-px bg-gray-100 mx-1 my-2"></div>

            <p class="px-3 py-2 text-xs font-semibold tracking-widest uppercase text-gray-400">
                Mahasiswa
            </p>

            <a href="{{ route('admin.mahasiswa.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500
                      hover:bg-slate-50 hover:text-gray-800 group transition-colors
                      {{ request()->routeIs('admin.mahasiswa.*') ? 'nav-active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.mahasiswa.*') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-500' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="text-sm font-medium">Kelola Mahasiswa</span>
            </a>

            <a href="{{ route('admin.peta.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500
                      hover:bg-slate-50 hover:text-gray-800 group transition-colors">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-blue-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <span class="text-sm font-medium">Peta Sebaran</span>
            </a>

            <a href="{{ route('admin.statistik') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500
                      hover:bg-slate-50 hover:text-gray-800 group transition-colors">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-blue-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="text-sm font-medium">Statistik</span>
            </a>

            @if(auth()->user()->role === 'admin')
            <div class="h-px bg-gray-100 mx-1 my-2"></div>

            <p class="px-3 py-2 text-xs font-semibold tracking-widest uppercase text-gray-400">
                Kaprodi
            </p>

                <a href="{{ route('admin.kaprodi.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500
                        hover:bg-slate-50 hover:text-gray-800 group transition-colors
                        {{ request()->routeIs('admin.kaprodi.*') ? 'nav-active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.kaprodi.*') ? 'text-blue-400' : 'text-gray-400 group-hover:text-blue-500' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-sm font-medium">Kelola Kaprodi</span>
                </a>
            @endif

            <!-- <div class="h-px bg-gray-100 mx-1 my-2"></div>

            <p class="px-3 py-1 text-xs font-semibold tracking-widest uppercase text-gray-400">
                SISTEM
            </p> -->

            {{--
            <a href="{{ route('admin.bantuan') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500
                      hover:bg-slate-50 hover:text-gray-800 group transition-colors">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-blue-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium">Bantuan</span>
            </a>
            --}}

            {{--
            <a href="{{ route('admin.tentang') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500
                      hover:bg-slate-50 hover:text-gray-800 group transition-colors">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-blue-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-sm font-medium">Tentang</span>
            </a>
            --}}

        </nav>

        {{-- Logout --}}
        <div class="px-3 pb-4 pt-3 border-t border-gray-100">
            <form method="POST" action="#">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl
                               text-red-500 hover:bg-red-50 group transition-colors">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="text-sm font-medium">Keluar</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- Main Area --}}
    <div class="flex-1 flex flex-col min-w-0">

        <header class="sticky top-0 z-10
                       bg-white/80 backdrop-blur-md
                       border-b border-gray-100
                       px-6 py-3 flex items-center justify-between">

            <button onclick="openSidebar()"
                    class="lg:hidden flex items-center justify-center w-9 h-9 rounded-xl
                           bg-slate-100 text-gray-500 hover:bg-slate-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="hidden lg:flex items-center gap-2 text-sm">
                <span class="text-gray-400">
                    @if(Auth::user()->role === 'admin')
                        Admin
                    @elseif(Auth::user()->role === 'kaprodi')
                        Kaprodi
                    @else
                        User
                    @endif
                </span>

                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>

                <span class="text-gray-700 font-medium">
                    @yield('page-title', 'Dashboard')
                </span>
            </div>
            <div class="flex items-center gap-2 ml-auto">

                {{--
                <button class="relative flex items-center justify-center w-9 h-9 rounded-xl
                               bg-slate-100 text-gray-500 hover:bg-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                </button>
                --}}

               <a href="{{ route('admin.profile') }}"
                  class="flex items-center gap-3 pl-2 border-l border-gray-100
                         hover:bg-slate-50 rounded-xl transition-colors cursor-pointer">

                <div class="w-8 h-8 rounded-xl overflow-hidden bg-gray-200 flex items-center justify-center text-white text-xs font-semibold">
                    
                    @if(auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}"
                            class="w-full h-full object-cover"
                            alt="avatar">
                    @else
                        <span>
                            {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                        </span>
                    @endif

                </div>

                <div class="leading-tight">
                    <div class="text-sm font-semibold text-gray-800">
                        {{ auth()->user()->name }}
                    </div>
                </div>
            </a>

            </div>
        </header>

        <main class="flex-1 overflow-y-auto">
            @yield('content')
        </main>

    </div>

</div>

<script>
(function () {
    window.openSidebar = function () {
        document.getElementById('sidebar').classList.remove('-translate-x-full');
        document.getElementById('overlay').classList.remove('hidden');
    };
    window.closeSidebar = function () {
        document.getElementById('sidebar').classList.add('-translate-x-full');
        document.getElementById('overlay').classList.add('hidden');
    };
})();
</script>

@stack('scripts')
@include('admin.ai.widget')

</body>
</html>