@extends('layouts.public')

@section('title', 'Beranda - Sistem Rekomendasi Wisata Banjarbaru')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white overflow-hidden py-20 lg:py-28">
    <!-- Decorative background glow -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(16,185,129,0.18),transparent_50%)]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,rgba(6,95,70,0.25),transparent_50%)]"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Sistem Resmi DISPORABUDPAR Kota Banjarbaru 
            </div>
            
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.15] mb-6">
                Eksplorasi Pesona <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400 bg-clip-text text-transparent">Kota Banjarbaru</span> dengan Rekomendasi Cerdas.
            </h1>
            
            <p class="text-lg text-slate-300 mb-8 leading-relaxed">
                Temukan destinasi wisata alam, edukasi, budaya, dan rekreasi keluarga yang paling cocok dengan preferensi Anda melalui sistem rekomendasi berbasis <strong class="text-emerald-400">Content-Based Filtering (CBF)</strong>.
            </p>

            <!-- Search Bar Component -->
            <form action="{{ route('katalog') }}" method="GET" class="bg-white/10 backdrop-blur-md p-2 rounded-2xl border border-white/20 shadow-2xl flex flex-col sm:flex-row gap-2 max-w-2xl">
                <div class="relative flex-grow flex items-center pl-4">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 mr-3"></i>
                    <input type="text" name="q" placeholder="Cari nama wisata, lokasi, atau kata kunci..." class="w-full bg-transparent border-0 text-white placeholder-slate-400 focus:outline-none focus:ring-0 text-sm">
                </div>
                <button type="submit" class="px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold rounded-xl text-sm transition-all shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2">
                    <span>Cari Destinasi</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            <!-- Quick tags -->
            <div class="mt-6 flex flex-wrap items-center gap-2 text-xs text-slate-400">
                <span class="font-medium text-slate-300">Destinasi Populer:</span>
                @foreach($wisatas->take(4) as $item)
                    <a href="{{ route('wisata.detail', $item->slug) }}" class="px-2.5 py-1 rounded-lg bg-white/5 hover:bg-white/15 border border-white/10 text-slate-300 transition-colors">
                        {{ $item->nama_wisata }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Stats Counter Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-white rounded-2xl shadow-xl p-6 border border-slate-100">
        <div class="flex items-center gap-4 p-2">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-tree"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">{{ $stats['total_wisata'] }}</p>
                <p class="text-xs text-slate-500 font-medium">Destinasi Terverifikasi</p>
            </div>
        </div>

        <div class="flex items-center gap-4 p-2 border-l border-slate-100">
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">{{ $stats['total_kategori'] }}</p>
                <p class="text-xs text-slate-500 font-medium">Kategori Pariwisata</p>
            </div>
        </div>

        <div class="flex items-center gap-4 p-2 border-l border-slate-100">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-star"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">{{ $stats['avg_rating'] }} <span class="text-xs text-amber-500 font-normal">/ 5.0</span></p>
                <p class="text-xs text-slate-500 font-medium">Rata-rata Rating Wisata</p>
            </div>
        </div>

        <div class="flex items-center gap-4 p-2 border-l border-slate-100">
            <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-calculator"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-900">5 Bobot</p>
                <p class="text-xs text-slate-500 font-medium">Algoritma CBF </p>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Map Section (Leaflet.js) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <div class="flex items-center gap-2 text-emerald-600 font-bold text-xs uppercase tracking-wider mb-2">
                <i class="fa-solid fa-map-location-dot"></i> Peta Interaktif OpenStreetMap
            </div>
            <h2 class="text-3xl font-black text-slate-900">Sebaran Destinasi Kota Banjarbaru</h2>
            <p class="text-slate-500 text-sm mt-1">Klik marker pada peta untuk melihat informasi ringkas dan rekomendasi tempat wisata serupa.</p>
        </div>
        <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-600 hover:text-emerald-700">
            Lihat Format Daftar <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>

    <div class="bg-white rounded-3xl p-3 border border-slate-200/80 shadow-lg">
        <div id="banjarbaruMap" class="w-full h-[460px] rounded-2xl z-10"></div>
    </div>
</section>

<!-- Featured Destinations Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
        <div>
            <div class="flex items-center gap-2 text-emerald-600 font-bold text-xs uppercase tracking-wider mb-2">
                <i class="fa-solid fa-trophy"></i> Wisata Populer
            </div>
            <h2 class="text-3xl font-black text-slate-900">Destinasi Rating Tertinggi</h2>
            <p class="text-slate-500 text-sm mt-1">Dipilih berdasarkan ulasan wisatawan dan rating Google Maps tertinggi.</p>
        </div>
        <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold transition-colors">
            Jelajahi Semua ({{ $stats['total_wisata'] }}) <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($unggulan as $item)
            <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col hover:-translate-y-1">
                <!-- Image & Badges -->
                <div class="relative h-56 overflow-hidden">
                    <img src="{{ $item->foto_url }}" alt="{{ $item->nama_wisata }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                    
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/90 backdrop-blur-md text-slate-800 shadow">
                            {{ $item->kategori->nama_kategori }}
                        </span>
                    </div>

                    <div class="absolute top-4 right-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-amber-400 text-amber-950 flex items-center gap-1 shadow">
                            <i class="fa-solid fa-star text-[10px]"></i> {{ number_format($item->rating_google, 1) }}
                        </span>
                    </div>

                    <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-white">
                        <span class="text-xs font-medium"><i class="fa-solid fa-location-dot mr-1"></i> Kec. {{ $item->kecamatan }}</span>
                        <span class="font-extrabold text-sm px-2.5 py-0.5 rounded-lg bg-emerald-600 text-white shadow">{{ $item->formatted_harga }}</span>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-xl text-slate-900 group-hover:text-emerald-600 transition-colors mb-2">
                            {{ $item->nama_wisata }}
                        </h3>
                        <p class="text-slate-500 text-xs leading-relaxed line-clamp-2 mb-4">
                            {{ $item->deskripsi }}
                        </p>

                        <!-- Facilities preview -->
                        <div class="flex flex-wrap gap-1.5 mb-6">
                            @foreach($item->fasilitases->take(4) as $fas)
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[11px] font-medium flex items-center gap-1">
                                    <i class="fa-solid {{ $fas->icon ?? 'fa-circle-check' }} text-[9px] text-emerald-600"></i>
                                    {{ $fas->nama_fasilitas }}
                                </span>
                            @endforeach
                            @if($item->fasilitases->count() > 4)
                                <span class="px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-400 text-[11px]">+{{ $item->fasilitases->count() - 4 }}</span>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('wisata.detail', $item->slug) }}" class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold text-center transition-all flex items-center justify-center gap-2">
                        <span>Lihat Detail & Rekomendasi</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Content-Based Filtering Explainer Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20">
    <div class="bg-gradient-to-br from-emerald-900 to-slate-900 rounded-3xl p-8 lg:p-12 text-white relative overflow-hidden shadow-xl">
        <div class="relative z-10 max-w-3xl">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-400/20 text-emerald-300 border border-emerald-400/30">
                Metode Algoritma
            </span>
            <h2 class="text-3xl font-extrabold mt-4 mb-4">Bagaimana Content-Based Filtering Bekerja?</h2>
            <p class="text-slate-300 text-sm leading-relaxed mb-6">
                Sistem menganalisis profil fitur setiap destinasi wisata secara matematis menggunakan <strong>Weighted Cosine Similarity</strong> dan <strong>TF-IDF</strong> tanpa memerlukan riwayat rating wisatawan terdahulu:
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur">
                    <p class="text-emerald-400 font-extrabold text-2xl">30% + 30%</p>
                    <p class="text-xs font-bold text-white mt-1">Kategori & Fasilitas</p>
                    <p class="text-[11px] text-slate-400 mt-1">One-hot & Multi-hot encoding kecocokan karakteristik.</p>
                </div>
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur">
                    <p class="text-teal-300 font-extrabold text-2xl">15% + 15%</p>
                    <p class="text-xs font-bold text-white mt-1">Harga Tiket & Rating</p>
                    <p class="text-[11px] text-slate-400 mt-1">Min-max normalization selisih anggaran & reputasi Google.</p>
                </div>
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur">
                    <p class="text-cyan-300 font-extrabold text-2xl">10%</p>
                    <p class="text-xs font-bold text-white mt-1">Deskripsi TF-IDF</p>
                    <p class="text-[11px] text-slate-400 mt-1">Kemiripan semantik kata kunci teks deskripsi wisata.</p>
                </div>
            </div>

            <a href="{{ route('simulasi.cbf') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-slate-900 font-bold text-sm hover:bg-emerald-400 transition-colors shadow-lg">
                <i class="fa-solid fa-flask"></i> Uji Simulasi Hitung CBF
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Koordinat Pusat Kota Banjarbaru (-3.457242, 114.810318)
        var map = L.map('banjarbaruMap').setView([-3.46, 114.82], 12);

        // OpenStreetMap Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var wisataData = @json($mapData);

        var bounds = [];

        wisataData.forEach(function (item) {
            if (item.lat && item.lng) {
                var marker = L.marker([item.lat, item.lng]).addTo(map);
                bounds.push([item.lat, item.lng]);

                var popupContent = `
                    <div style="min-width: 220px; font-family: 'Plus Jakarta Sans', sans-serif;">
                        <img src="${item.foto}" style="width: 100%; height: 110px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;" alt="${item.nama}">
                        <div style="font-size: 11px; font-weight: bold; color: #059669; text-transform: uppercase;">${item.kategori}</div>
                        <h4 style="font-size: 14px; font-weight: 800; color: #0f172a; margin: 2px 0 6px 0;">${item.nama}</h4>
                        <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 8px;">
                            <span style="color: #b45309; font-weight: bold;">⭐ ${item.rating}</span>
                            <span style="color: #047857; font-weight: bold;">${item.harga}</span>
                        </div>
                        <a href="/wisata/${item.slug}" style="display: block; text-align: center; background-color: #059669; color: white; padding: 6px 10px; border-radius: 6px; font-size: 11px; font-weight: bold; text-decoration: none;">
                            Lihat Detail & Rekomendasi
                        </a>
                    </div>
                `;

                marker.bindPopup(popupContent);
            }
        });

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    });
</script>
@endpush
