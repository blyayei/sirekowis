@extends('layouts.public')

@section('title', 'Katalog Wisata - SIREKOWIS Banjarbaru')

@section('content')
<!-- Header Banner -->
<div class="bg-gradient-to-r from-slate-900 to-emerald-950 text-white py-12 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl sm:text-4xl font-black mb-2">Katalog Destinasi Wisata</h1>
        <p class="text-slate-300 text-sm max-w-2xl">
            Jelajahi seluruh tempat wisata Kota Banjarbaru yang terverifikasi DISPORABUDPAR. Gunakan filter untuk menemukan destinasi yang sesuai dengan rencana liburan Anda.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Filter Sidebar -->
        <div class="lg:col-span-1">
            <form action="{{ route('katalog') }}" method="GET" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm sticky top-28 space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="font-black text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-emerald-600"></i> Filter Destinasi
                    </h3>
                    <a href="{{ route('katalog') }}" class="text-xs font-semibold text-rose-600 hover:underline">Reset</a>
                </div>

                <!-- Kata Kunci -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pencarian</label>
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik nama atau kata kunci..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    </div>
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Wisata</label>
                    <select name="kategori" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->slug }}" {{ request('kategori') == $kat->slug ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kecamatan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Wilayah Kecamatan</label>
                    <select name="kecamatan" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        <option value="">Semua Wilayah</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>
                                Kec. {{ $kec }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Harga Maksimal -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Anggaran Tiket Maksimal</label>
                    <input type="number" name="harga_max" value="{{ request('harga_max') }}" placeholder="Contoh: 15000" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    <p class="text-[10px] text-slate-400 mt-1">Isi 0 untuk wisata gratis.</p>
                </div>

                <!-- Fasilitas Multi-Checkbox -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Fasilitas Tersedia</label>
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                        @foreach($fasilitases as $fas)
                            <label class="flex items-center gap-2 text-xs text-slate-600 cursor-pointer hover:text-slate-900">
                                <input type="checkbox" name="fasilitas[]" value="{{ $fas->id }}" 
                                    {{ is_array(request('fasilitas')) && in_array($fas->id, request('fasilitas')) ? 'checked' : '' }}
                                    class="rounded text-emerald-600 focus:ring-emerald-500">
                                <span>{{ $fas->nama_fasilitas }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Urutan (Sort) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Urutan Tampilan</label>
                    <select name="sort" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="harga_asc" {{ request('sort') == 'harga_asc' ? 'selected' : '' }}>Harga Tiket Termurah</option>
                        <option value="harga_desc" {{ request('sort') == 'harga_desc' ? 'selected' : '' }}>Harga Tiket Tertinggi</option>
                        <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama Wisata (A - Z)</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-all shadow-md shadow-emerald-600/20">
                    Terapkan Filter
                </button>
            </form>
        </div>

        <!-- Catalog Items Grid -->
        <div class="lg:col-span-3">
            <!-- Top Controls -->
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200">
                <p class="text-sm text-slate-600">
                    Menampilkan <span class="font-bold text-slate-900">{{ $wisatas->total() }}</span> destinasi wisata terverifikasi
                </p>
            </div>

            @if($wisatas->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl mx-auto mb-4">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Destinasi Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6">
                        Coba sesuaikan kata kunci atau bersihkan filter fasilitas dan rentang harga untuk melihat lebih banyak pilihan.
                    </p>
                    <a href="{{ route('katalog') }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs shadow-md">
                        Bersihkan Semua Filter
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($wisatas as $item)
                        <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col hover:-translate-y-1">
                            <!-- Image Header -->
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ $item->foto_url }}" alt="{{ $item->nama_wisata }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-white/95 text-slate-800 shadow">
                                        {{ $item->kategori->nama_kategori }}
                                    </span>
                                </div>

                                <div class="absolute top-3 right-3">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-400 text-amber-950 flex items-center gap-1 shadow">
                                        ⭐ {{ number_format($item->rating_google, 1) }}
                                    </span>
                                </div>

                                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between text-white text-xs">
                                    <span><i class="fa-solid fa-location-dot mr-1"></i> Kec. {{ $item->kecamatan }}</span>
                                    <span class="font-bold px-2 py-0.5 rounded bg-emerald-600 text-white">{{ $item->formatted_harga }}</span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="font-black text-lg text-slate-900 group-hover:text-emerald-600 transition-colors mb-2">
                                        {{ $item->nama_wisata }}
                                    </h3>
                                    <p class="text-slate-500 text-xs leading-relaxed line-clamp-2 mb-4">
                                        {{ $item->deskripsi }}
                                    </p>

                                    <!-- Facilities preview -->
                                    <div class="flex flex-wrap gap-1 mb-5">
                                        @foreach($item->fasilitases->take(3) as $fas)
                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-medium flex items-center gap-1">
                                                <i class="fa-solid {{ $fas->icon ?? 'fa-check' }} text-[8px] text-emerald-600"></i>
                                                {{ $fas->nama_fasilitas }}
                                            </span>
                                        @endforeach
                                        @if($item->fasilitases->count() > 3)
                                            <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-400 text-[10px]">+{{ $item->fasilitases->count() - 3 }}</span>
                                        @endif
                                    </div>
                                </div>

                                <a href="{{ route('wisata.detail', $item->slug) }}" class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold text-center transition-all flex items-center justify-center gap-1.5">
                                    <span>Detail & Rekomendasi CBF</span>
                                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $wisatas->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
