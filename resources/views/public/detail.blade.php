@extends('layouts.public')

@section('title', $wisata->nama_wisata . ' - Rekomendasi Wisata Banjarbaru')

@section('content')
<!-- Breadcrumbs -->
<div class="bg-slate-100 border-b border-slate-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center text-xs text-slate-500 gap-2">
            <a href="{{ route('home') }}" class="hover:text-emerald-600">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <a href="{{ route('katalog') }}" class="hover:text-emerald-600">Katalog Wisata</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <span class="text-slate-900 font-bold truncate">{{ $wisata->nama_wisata }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Main Content & Sidebar Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <!-- Main Column (Details & Map) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Hero Photo & Badges -->
            <div class="relative rounded-3xl overflow-hidden shadow-lg h-80 sm:h-96">
                <img src="{{ $wisata->foto_url }}" alt="{{ $wisata->nama_wisata }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                
                <div class="absolute top-6 left-6">
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-600 text-white shadow">
                        {{ $wisata->kategori->nama_kategori }}
                    </span>
                </div>

                <div class="absolute bottom-6 left-6 right-6 text-white">
                    <h1 class="text-2xl sm:text-4xl font-black mb-2">{{ $wisata->nama_wisata }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-xs">
                        <span class="flex items-center gap-1.5"><i class="fa-solid fa-location-dot text-emerald-400"></i> Kec. {{ $wisata->kecamatan }}, Kota Banjarbaru</span>
                        <span class="flex items-center gap-1 bg-amber-400/90 text-amber-950 px-2.5 py-0.5 rounded-full font-bold">
                            <i class="fa-solid fa-star"></i> {{ number_format($wisata->rating_google, 1) }} ({{ number_format($wisata->jumlah_review_google) }} ulasan Google)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Deskripsi Lengkap -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm">
                <h2 class="text-xl font-black text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-emerald-600"></i> Mengenai Destinasi
                </h2>
                <div class="text-slate-600 text-sm leading-relaxed space-y-4">
                    <p>{{ $wisata->deskripsi }}</p>
                </div>

                <!-- Fasilitas Tersedia -->
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Sarana & Prasarana Fasilitas</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @forelse($wisata->fasilitases as $fas)
                            <div class="flex items-center gap-3 p-3 rounded-2xl bg-emerald-50/60 border border-emerald-100 text-emerald-900 text-xs font-semibold">
                                <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm shadow-sm">
                                    <i class="fa-solid {{ $fas->icon ?? 'fa-check' }}"></i>
                                </div>
                                <span>{{ $fas->nama_fasilitas }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400">Tidak ada fasilitas terdaftar.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Peta Lokasi Leaflet.js -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-map-pin text-rose-600"></i> Lokasi & Navigasi Peta
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">{{ $wisata->alamat }}</p>
                    </div>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($wisata->nama_wisata . ', ' . $wisata->alamat) }}" target="_blank" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-colors inline-flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-diamond-turn-right text-emerald-400"></i> Navigasi Maps
                    </a>
                </div>

                <div id="detailMap" class="w-full h-80 rounded-2xl border border-slate-200"></div>

                <div class="flex items-center justify-between text-xs text-slate-400 pt-2">
                    <span>Koordinat: {{ $wisata->latitude }}, {{ $wisata->longitude }}</span>
                    <span>Kecamatan {{ $wisata->kecamatan }}</span>
                </div>
            </div>

        </div>

        <!-- Sidebar (Info Box + CBF Recommendations Widget) -->
        <div class="lg:col-span-1 space-y-8">
            
            <!-- Quick Summary Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="font-black text-base text-slate-900 pb-3 border-b border-slate-100">Informasi Kunjungan</h3>
                
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500">Harga Tiket Masuk</span>
                    <span class="text-base font-extrabold text-emerald-600">{{ $wisata->formatted_harga }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500">Jam Operasional</span>
                    <span class="text-xs font-bold text-slate-800">{{ $wisata->jam_operasional ?? '08:00 - 17:00 WITA' }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500">Rating Google Maps</span>
                    <span class="text-xs font-bold text-amber-600 flex items-center gap-1">
                        <i class="fa-solid fa-star text-[10px]"></i> {{ number_format($wisata->rating_google, 1) }} / 5.0
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500">Kategori</span>
                    <span class="text-xs font-bold text-slate-800">{{ $wisata->kategori->nama_kategori }}</span>
                </div>

                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($wisata->nama_wisata . ' Banjarbaru') }}" target="_blank" class="w-full mt-4 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs text-center block transition-all shadow-md shadow-emerald-600/20">
                    <i class="fa-solid fa-arrow-up-right-from-square mr-1"></i> Buka Ulasan Google Maps
                </a>
            </div>

            <!-- Content-Based Filtering Widget: DESTINASI SERUPA -->
            <div class="bg-gradient-to-b from-slate-900 to-emerald-950 text-white rounded-3xl p-6 border border-emerald-800/40 shadow-xl space-y-5">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-[10px] font-bold uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-microchip"></i> Engine CBF 
                    </div>
                    <h3 class="text-lg font-black text-white">Destinasi Wisata Serupa</h3>
                    <p class="text-[11px] text-slate-300 leading-relaxed mt-1">
                        Dihitung berdasarkan kesamaan kategori, fasilitas, harga, rating, dan semantik deskripsi.
                    </p>
                </div>

                @if(empty($rekomendasi))
                    <div class="p-4 rounded-2xl bg-white/5 text-center text-xs text-slate-400">
                        Belum ada destinasi serupa lain yang tersedia.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($rekomendasi as $rec)
                            @php 
                                $rWisata = $rec['wisata'];
                                $scorePercent = $rec['score_percent'];
                                $breakdown = $rec['breakdown'];
                            @endphp
                            <div class="p-3.5 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/10 transition-all space-y-2.5">
                                <div class="flex gap-3">
                                    <img src="{{ $rWisata->foto_url }}" alt="{{ $rWisata->nama_wisata }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                                    <div class="flex-grow min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider truncate">{{ $rWisata->kategori->nama_kategori }}</span>
                                            <span class="text-[11px] font-extrabold text-amber-400">⭐ {{ number_format($rWisata->rating_google, 1) }}</span>
                                        </div>
                                        <h4 class="font-bold text-sm text-white truncate">{{ $rWisata->nama_wisata }}</h4>
                                        <p class="text-[10px] text-slate-300">{{ $rWisata->formatted_harga }}</p>
                                    </div>
                                </div>

                                <!-- Similarity Bar -->
                                <div>
                                    <div class="flex justify-between text-[10px] font-bold mb-1">
                                        <span class="text-slate-300">Tingkat Kemiripan</span>
                                        <span class="text-emerald-300">{{ $scorePercent }}%</span>
                                    </div>
                                    <div class="w-full bg-black/40 h-2 rounded-full overflow-hidden">
                                        <div class="bg-gradient-to-r from-teal-400 to-emerald-400 h-full rounded-full transition-all duration-700" style="width: {{ $scorePercent }}%"></div>
                                    </div>
                                </div>

                                <!-- Breakdown pills -->
                                <div class="flex flex-wrap gap-1 text-[9px] text-slate-300 pt-1">
                                    <span class="px-1.5 py-0.5 rounded bg-white/5 border border-white/10">Kat: {{ round($breakdown['kategori'] * 100) }}%</span>
                                    <span class="px-1.5 py-0.5 rounded bg-white/5 border border-white/10">Fas: {{ round($breakdown['fasilitas'] * 100) }}%</span>
                                    <span class="px-1.5 py-0.5 rounded bg-white/5 border border-white/10">Hrg: {{ round($breakdown['harga'] * 100) }}%</span>
                                    <span class="px-1.5 py-0.5 rounded bg-white/5 border border-white/10">TF-IDF: {{ round($breakdown['deskripsi'] * 100) }}%</span>
                                </div>

                                <a href="{{ route('wisata.detail', $rWisata->slug) }}" class="w-full py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs text-center block transition-colors">
                                    Kunjungi Wisata Serupa
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                <a href="{{ route('simulasi.cbf', ['wisata_1' => $wisata->id]) }}" class="block text-center text-xs text-emerald-300 hover:underline pt-2">
                    <i class="fa-solid fa-square-root-variable mr-1"></i> Lihat Rincian Rumus CBF
                </a>
            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var lat = {{ $wisata->latitude }};
        var lng = {{ $wisata->longitude }};

        var map = L.map('detailMap').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup(`
            <div style="font-family: 'Plus Jakarta Sans', sans-serif;">
                <b style="color: #059669;">{{ $wisata->nama_wisata }}</b><br>
                <small>{{ $wisata->alamat }}</small>
            </div>
        `).openPopup();
    });
</script>
@endpush
