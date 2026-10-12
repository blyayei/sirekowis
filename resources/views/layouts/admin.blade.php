<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - SIREKOWIS Banjarbaru</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-kota-banjarbaru-transparan.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Leaflet.js -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col border-r border-slate-800">
        <!-- Sidebar Brand -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800/80 gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md overflow-hidden">
                <img src="{{ asset('images/logo-kota-banjarbaru-transparan.png') }}" alt="Lambang Kota Banjarbaru" class="w-8 h-8 object-contain">
            </div>
            <div>
                <span class="font-extrabold text-white text-base tracking-tight block">SIREKOWIS</span>
                <span class="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider block">Panel DISPORABUDPAR</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="p-4 space-y-1.5 flex-grow">
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-3 py-2">Menu Utama</div>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                <span>Dashboard Statistik</span>
            </a>

            <a href="{{ route('admin.wisata.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.wisata.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                <i class="fa-solid fa-map-location-dot w-4 text-center"></i>
                <span>Data Destinasi Wisata</span>
            </a>

            <a href="{{ route('admin.kategori.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.kategori.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                <i class="fa-solid fa-layer-group w-4 text-center"></i>
                <span>Kategori Wisata</span>
            </a>

            <a href="{{ route('admin.fasilitas.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.fasilitas.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                <i class="fa-solid fa-icons w-4 text-center"></i>
                <span>Sarana Fasilitas</span>
            </a>

            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-3 pt-6 pb-2">Laporan & Output (PKL)</div>

            <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.laporan.*') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/70' }}">
                <i class="fa-solid fa-file-pdf w-4 text-center text-rose-400"></i>
                <div class="flex items-center justify-between w-full">
                    <span>6 Modul Laporan PDF</span>
                    <span class="text-[9px] px-1.5 py-0.5 rounded bg-rose-500/20 text-rose-300 font-extrabold">6</span>
                </div>
            </a>

            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-3 pt-6 pb-2">Sistem</div>

            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-800/70 transition-all">
                <i class="fa-solid fa-globe w-4 text-center text-cyan-400"></i>
                <span>Lihat Web Publik</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-auto"></i>
            </a>
        </nav>

        <!-- User Profile & Logout -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-xl bg-slate-800 flex items-center justify-center text-white font-bold text-xs">
                    {{ substr(Auth::user()->name ?? 'Admin', 0, 2) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <span class="text-[10px] px-1.5 py-0.2 rounded bg-emerald-500/20 text-emerald-400 font-medium uppercase">{{ Auth::user()->role ?? 'Admin' }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2 px-3 rounded-xl bg-slate-800 hover:bg-rose-950/50 hover:text-rose-400 text-slate-400 text-xs font-bold transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="h-20 bg-white border-b border-slate-200/80 px-6 sm:px-8 flex items-center justify-between">
            <h1 class="text-lg font-black text-slate-900">@yield('page_title', 'Dashboard')</h1>
            <div class="flex items-center gap-4 text-xs">
                <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 font-medium hidden sm:inline-block">
                    <i class="fa-regular fa-calendar mr-1.5"></i> {{ date('d M Y') }}
                </span>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-bold text-slate-700">Online Server</span>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="p-6 sm:p-8 flex-grow">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600 text-sm"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900">&times;</button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
