<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIREKOWIS') - Sistem Rekomendasi Wisata Kota Banjarbaru</title>
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
                            200: '#a7f3d0',
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

    <!-- Leaflet.js CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- Navigation Header -->
    <nav class="sticky top-0 z-50 glass-nav border-b border-slate-200/80 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Brand / Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-md shadow-brand-500/20 group-hover:scale-105 transition-transform overflow-hidden">
                        <img src="{{ asset('images/logo-kota-banjarbaru-transparan.png') }}" alt="Lambang Kota Banjarbaru" class="w-10 h-10 object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-xl tracking-tight text-slate-900 group-hover:text-brand-600 transition-colors">SIREKOWIS</span>
                            <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-brand-100 text-brand-800">CBF</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">DISPORABUDPAR Kota Banjarbaru</p>
                    </div>
                </a>

                <!-- Nav Menu Desktop -->
                <div class="hidden md:flex items-center gap-1 lg:gap-2">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition-colors">
                        <i class="fa-solid fa-house mr-1.5 text-xs"></i> Beranda
                    </a>
                    <a href="{{ route('katalog') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('katalog') ? 'bg-brand-50 text-brand-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition-colors">
                        <i class="fa-solid fa-compass mr-1.5 text-xs"></i> Katalog Wisata
                    </a>
                    <a href="{{ route('simulasi.cbf') }}" class="px-4 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('simulasi.cbf') ? 'bg-brand-50 text-brand-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition-colors">
                        <i class="fa-solid fa-calculator mr-1.5 text-xs"></i> Simulasi CBF
                    </a>
                </div>

                <!-- Admin Action Button -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold shadow-md transition-all">
                            <i class="fa-solid fa-gauge-high text-xs"></i> Panel Admin
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold shadow-md shadow-brand-600/20 transition-all hover:translate-y-[-1px]">
                            <i class="fa-solid fa-right-to-bracket text-xs"></i> Login Staf Dinas
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 mt-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1 -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('images/logo-kota-banjarbaru-transparan.png') }}" alt="Lambang Kota Banjarbaru" class="w-8 h-8 object-contain">
                        </div>
                        <span class="font-bold text-xl text-white">SIREKOWIS Banjarbaru</span>
                    </div>
                    <p class="text-sm text-slate-400 max-w-md leading-relaxed">
                        Sistem Rekomendasi Destinasi Wisata Kota Banjarbaru berbasis Web menggunakan algoritma <strong>Content-Based Filtering (CBF)</strong> dengan pembobotan Weighted Cosine Similarity dan TF-IDF.
                    </p>
                    <div class="flex items-center gap-4 text-xs text-slate-400">
                        <span><i class="fa-solid fa-building mr-1"></i> Disporabudpar Kota Banjarbaru</span>
                        <span><i class="fa-solid fa-graduation-cap mr-1"></i> FTI UNISKA Banjarmasin</span>
                    </div>
                </div>

                <!-- Col 2: Navigasi -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Navigasi Utama</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-400 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('katalog') }}" class="hover:text-emerald-400 transition-colors">Katalog Semua Wisata</a></li>
                        <li><a href="{{ route('simulasi.cbf') }}" class="hover:text-emerald-400 transition-colors">Simulasi Algoritma CBF</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-400 transition-colors">Login Admin & Operator</a></li>
                    </ul>
                </div>

                <!-- Col 3: Kontak Dinas -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Kantor Dinas</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-3">
                        Jl. Pangeran Suriansyah No. 1, Kel. Mentaos, Kec. Banjarbaru Utara, Kota Banjarbaru, Kalimantan Selatan 70711
                    </p>
                    <p class="text-xs text-slate-400"><i class="fa-solid fa-phone mr-1.5"></i> (0511) 4772589</p>
                    <p class="text-xs text-slate-400 mt-1"><i class="fa-solid fa-envelope mr-1.5"></i> disporabudpar@banjarbarukota.go.id</p>
                </div>
            </div>

            <div class="mt-12 pt-6 border-t border-slate-800 text-center text-xs text-slate-500 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p>&copy; {{ date('Y') }} SIREKOWIS - Dinas Kepemudaan, Olahraga, Kebudayaan dan Pariwisata Kota Banjarbaru.</p>
                <p class="text-slate-400">Implementasi Algoritma Content-Based Filtering (Cosine Similarity & TF-IDF)</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
