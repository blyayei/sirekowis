@extends('layouts.admin')

@section('title', 'Dashboard Statistik')
@section('page_title', 'Dashboard Utama & Statistik Pariwisata')

@section('content')
<div class="space-y-8">

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Wisata -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Destinasi</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $totalWisata }}</h3>
                <span class="text-[11px] font-semibold text-emerald-600 mt-1 inline-block">Terdata di Banjarbaru</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
        </div>

        <!-- Total Kategori -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Kategori Wisata</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $totalKategori }}</h3>
                <span class="text-[11px] font-semibold text-teal-600 mt-1 inline-block">Klasifikasi Objek</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        <!-- Total Fasilitas -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Fasilitas</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $totalFasilitas }}</h3>
                <span class="text-[11px] font-semibold text-amber-600 mt-1 inline-block">Sarana & Prasarana</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-icons"></i>
            </div>
        </div>

        <!-- Total Rekomendasi Dihasilkan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Log Rekomendasi CBF</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ $totalLog }}</h3>
                <span class="text-[11px] font-semibold text-indigo-600 mt-1 inline-block">Frekuensi Dihasilkan</span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-calculator"></i>
            </div>
        </div>
    </div>

    <!-- Quick Action / 6 Laporan Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-lg">
        <div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                Dokumen Resmi DISPORABUDPAR Kota Banjarbaru
            </span>
            <h3 class="text-2xl font-black mt-2">6 Modul Cetak Laporan PDF Resmi</h3>
            <p class="text-xs text-slate-300 max-w-xl mt-1 leading-relaxed">
                Tersedia 6 jenis laporan ber-Kop Surat resmi DISPORABUDPAR Kota Banjarbaru lengkap dengan data matriks kemiripan CBF, log analitik, dan fasilitas wisata siap cetak.
            </p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="px-6 py-3 rounded-2xl bg-white hover:bg-emerald-400 text-slate-950 font-bold text-xs transition-colors flex items-center gap-2 shadow-lg flex-shrink-0">
            <i class="fa-solid fa-print"></i>
            <span>Buka Pusat Cetak Laporan (6)</span>
        </a>
    </div>

    <!-- 2 Column Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Top Wisata Paling Sering Direkomendasikan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-black text-base text-slate-900">Wisata Paling Sering Direkomendasikan</h3>
                    <p class="text-xs text-slate-400">Hasil pencarian serupa wisatawan</p>
                </div>
                <span class="text-xs font-bold text-emerald-600">Top 5</span>
            </div>

            <div class="space-y-3">
                @forelse($topRecommended as $rec)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-xs">
                                {{ $loop->iteration }}
                            </div>
                            <div>
                                <h4 class="font-bold text-xs text-slate-900">{{ $rec->wisataRekomendasi->nama_wisata ?? '-' }}</h4>
                                <span class="text-[10px] text-slate-400">{{ $rec->wisataRekomendasi->kategori->nama_kategori ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-black text-slate-900 block">{{ $rec->total_rekomendasi }}x</span>
                            <span class="text-[10px] text-emerald-600 font-bold">Rerata Sim: {{ round($rec->avg_score * 100, 1) }}%</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada riwayat rekomendasi.</p>
                @endforelse
            </div>
        </div>

        <!-- Top Wisata Rating Tertinggi -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-black text-base text-slate-900">Peringkat Rating Google Maps</h3>
                    <p class="text-xs text-slate-400">Objek wisata dengan kepuasan tertinggi</p>
                </div>
                <span class="text-xs font-bold text-amber-500">Top 5</span>
            </div>

            <div class="space-y-3">
                @foreach($topRated as $w)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-black text-xs">
                                {{ $loop->iteration }}
                            </div>
                            <div>
                                <h4 class="font-bold text-xs text-slate-900">{{ $w->nama_wisata }}</h4>
                                <span class="text-[10px] text-slate-400">Kec. {{ $w->kecamatan }} &bull; {{ $w->formatted_harga }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-black text-amber-600 flex items-center gap-1 justify-end">
                                <i class="fa-solid fa-star text-[10px]"></i> {{ number_format($w->rating_google, 1) }}
                            </span>
                            <span class="text-[10px] text-slate-400">{{ number_format($w->jumlah_review_google) }} ulasan</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Riwayat Log Rekomendasi Terakhir -->
    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-black text-base text-slate-900">Aktivitas Engine Rekomendasi Terkini</h3>
                <p class="text-xs text-slate-400">Pencatatan real-time log sistem rekomendasi untuk audit & pengujian</p>
            </div>
            <a href="{{ route('admin.laporan.cetak', 'L-03') }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">
                <i class="fa-solid fa-file-pdf mr-1 text-rose-500"></i> Cetak L-03
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase border-b border-slate-200">
                    <tr>
                        <th class="p-4">Waktu</th>
                        <th class="p-4">Wisata Asal</th>
                        <th class="p-4">Wisata Direkomendasikan</th>
                        <th class="p-4 text-center">Similarity Score</th>
                        <th class="p-4">Kata Kunci Filter</th>
                        <th class="p-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($recentLogs as $log)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 font-medium text-slate-400">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4 font-bold text-slate-900">{{ $log->wisataAsal->nama_wisata ?? '-' }}</td>
                            <td class="p-4 font-bold text-emerald-700">{{ $log->wisataRekomendasi->nama_wisata ?? '-' }}</td>
                            <td class="p-4 text-center">
                                <span class="px-2 py-1 rounded-lg bg-emerald-100 text-emerald-800 font-extrabold">
                                    {{ number_format($log->similarity_score, 4) }} ({{ round($log->similarity_score * 100, 1) }}%)
                                </span>
                            </td>
                            <td class="p-4 text-slate-500">{{ $log->keyword_pencarian ?: '-' }}</td>
                            <td class="p-4 text-slate-400 font-mono">{{ $log->ip_address ?: '127.0.0.1' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400">Belum ada riwayat aktivitas log rekomendasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
