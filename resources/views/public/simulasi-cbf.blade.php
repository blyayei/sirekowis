@extends('layouts.public')

@section('title', 'Simulasi Perhitungan Algoritma CBF - SIREKOWIS')

@section('content')
<div class="bg-gradient-to-r from-slate-900 to-emerald-950 text-white py-12 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold mb-3">
            <i class="fa-solid fa-graduation-cap"></i> Modul Pengujian Simulator Content-Based Filtering (CBF)
        </div>
        <h1 class="text-3xl sm:text-4xl font-black mb-2">Simulator Content-Based Filtering (CBF)</h1>
        <p class="text-slate-300 text-sm max-w-3xl">
            Simulasi transparan perhitungan kecocokan destinasi wisata menggunakan <strong>Weighted Cosine Similarity</strong> dan <strong>TF-IDF Vectorization</strong>
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Form Pemilihan Destinasi -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm mb-10">
        <form action="{{ route('simulasi.cbf') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Objek Wisata Asal (Item A)</label>
                <select name="wisata_1" class="w-full py-2.5 px-3 text-sm rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">-- Pilih Wisata Asal --</option>
                    @foreach($allWisatas as $w)
                        <option value="{{ $w->id }}" {{ (request('wisata_1') == $w->id || ($selectedA && $selectedA->id == $w->id)) ? 'selected' : '' }}>
                            {{ $w->nama_wisata }} ({{ $w->kategori->nama_kategori }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Objek Wisata Pembanding (Item B)</label>
                <select name="wisata_2" class="w-full py-2.5 px-3 text-sm rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">-- Pilih Wisata Pembanding --</option>
                    @foreach($allWisatas as $w)
                        <option value="{{ $w->id }}" {{ (request('wisata_2') == $w->id || ($selectedB && $selectedB->id == $w->id)) ? 'selected' : '' }}>
                            {{ $w->nama_wisata }} ({{ $w->kategori->nama_kategori }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-1">
                <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calculator"></i>
                    <span>Hitung Nilai</span>
                </button>
            </div>
        </form>
    </div>

    @if($selectedA && $selectedB && $result)
        <!-- Result Presentation -->
        <div class="space-y-8">
            
            <!-- Score Highlights -->
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-950 text-white rounded-3xl p-8 border border-emerald-800/40 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                        Hasil Kalkulasi Weighted Cosine Similarity
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black mt-3">
                        {{ $selectedA->nama_wisata }} <span class="text-emerald-400">&harr;</span> {{ $selectedB->nama_wisata }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">
                        Rumus: Sim(A,B) = (0.30 &times; Kat) + (0.30 &times; Fas) + (0.15 &times; Hrg) + (0.15 &times; Rat) + (0.10 &times; TFIDF)
                    </p>
                </div>

                <div class="text-center md:text-right bg-white/10 p-6 rounded-2xl backdrop-blur border border-white/10 min-w-[220px]">
                    <p class="text-xs uppercase tracking-wider text-slate-300 font-bold">Total Nilai Kemiripan</p>
                    <p class="text-4xl sm:text-5xl font-black text-emerald-400 my-1">{{ number_format($result['total'], 4) }}</p>
                    <p class="text-sm font-extrabold text-white">Relevansi: {{ round($result['total'] * 100, 1) }}%</p>
                </div>
            </div>

            <!-- Breakdown Tabel Komponen 5 Atribut -->
            <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-black text-base text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-emerald-600"></i> Rincian 5 Parameter Atribut CBF
                    </h3>
                    <span class="text-xs font-bold text-slate-500">Total Bobot: 1.0 (100%)</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-700 text-xs font-bold uppercase border-b border-slate-200">
                            <tr>
                                <th class="p-4">Fitur / Atribut</th>
                                <th class="p-4">Nilai Wisata A</th>
                                <th class="p-4">Nilai Wisata B</th>
                                <th class="p-4 text-center">Bobot (w)</th>
                                <th class="p-4 text-center">Sub-Skor (0-1)</th>
                                <th class="p-4 text-right">Kontribusi Skor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                            <!-- Kategori -->
                            <tr class="hover:bg-slate-50/60">
                                <td class="p-4 font-bold text-slate-900">1. Kategori Wisata</td>
                                <td class="p-4">{{ $selectedA->kategori->nama_kategori }}</td>
                                <td class="p-4">{{ $selectedB->kategori->nama_kategori }}</td>
                                <td class="p-4 text-center font-bold text-emerald-600">0.30 (30%)</td>
                                <td class="p-4 text-center font-bold">{{ number_format($result['kategori'], 4) }}</td>
                                <td class="p-4 text-right font-extrabold text-slate-900">
                                    {{ number_format(0.30 * $result['kategori'], 4) }}
                                </td>
                            </tr>

                            <!-- Fasilitas -->
                            <tr class="hover:bg-slate-50/60">
                                <td class="p-4 font-bold text-slate-900">2. Fasilitas Wisata</td>
                                <td class="p-4">
                                    {{ $selectedA->fasilitases->pluck('nama_fasilitas')->join(', ') ?: '-' }}
                                </td>
                                <td class="p-4">
                                    {{ $selectedB->fasilitases->pluck('nama_fasilitas')->join(', ') ?: '-' }}
                                </td>
                                <td class="p-4 text-center font-bold text-emerald-600">0.30 (30%)</td>
                                <td class="p-4 text-center font-bold">{{ number_format($result['fasilitas'], 4) }}</td>
                                <td class="p-4 text-right font-extrabold text-slate-900">
                                    {{ number_format(0.30 * $result['fasilitas'], 4) }}
                                </td>
                            </tr>

                            <!-- Harga Tiket -->
                            <tr class="hover:bg-slate-50/60">
                                <td class="p-4 font-bold text-slate-900">3. Harga Tiket Masuk</td>
                                <td class="p-4">{{ $selectedA->formatted_harga }}</td>
                                <td class="p-4">{{ $selectedB->formatted_harga }}</td>
                                <td class="p-4 text-center font-bold text-emerald-600">0.15 (15%)</td>
                                <td class="p-4 text-center font-bold">{{ number_format($result['harga'], 4) }}</td>
                                <td class="p-4 text-right font-extrabold text-slate-900">
                                    {{ number_format(0.15 * $result['harga'], 4) }}
                                </td>
                            </tr>

                            <!-- Rating Google -->
                            <tr class="hover:bg-slate-50/60">
                                <td class="p-4 font-bold text-slate-900">4. Rating Google Maps</td>
                                <td class="p-4">⭐ {{ number_format($selectedA->rating_google, 1) }}</td>
                                <td class="p-4">⭐ {{ number_format($selectedB->rating_google, 1) }}</td>
                                <td class="p-4 text-center font-bold text-emerald-600">0.15 (15%)</td>
                                <td class="p-4 text-center font-bold">{{ number_format($result['rating'], 4) }}</td>
                                <td class="p-4 text-right font-extrabold text-slate-900">
                                    {{ number_format(0.15 * $result['rating'], 4) }}
                                </td>
                            </tr>

                            <!-- TF-IDF Deskripsi -->
                            <tr class="hover:bg-slate-50/60">
                                <td class="p-4 font-bold text-slate-900">5. Semantik Deskripsi (TF-IDF)</td>
                                <td class="p-4 max-w-xs truncate">{{ $selectedA->deskripsi }}</td>
                                <td class="p-4 max-w-xs truncate">{{ $selectedB->deskripsi }}</td>
                                <td class="p-4 text-center font-bold text-emerald-600">0.10 (10%)</td>
                                <td class="p-4 text-center font-bold">{{ number_format($result['deskripsi'], 4) }}</td>
                                <td class="p-4 text-right font-extrabold text-slate-900">
                                    {{ number_format(0.10 * $result['deskripsi'], 4) }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-emerald-50 text-emerald-950 font-black text-xs border-t-2 border-emerald-200">
                            <tr>
                                <td colspan="3" class="p-4">TOTAL SKOR WEIGHTED COSINE SIMILARITY</td>
                                <td class="p-4 text-center">1.00 (100%)</td>
                                <td class="p-4 text-center">-</td>
                                <td class="p-4 text-right text-sm text-emerald-700">{{ number_format($result['total'], 4) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    @else
        <!-- Empty State Prompt -->
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fa-solid fa-sliders"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Pilih Dua Objek Wisata</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                Silakan pilih Objek Wisata Asal dan Wisata Pembanding pada form di atas, lalu klik tombol "Hitung Nilai" untuk melihat simulasi langkah-langkah algoritma Content-Based Filtering secara lengkap.
            </p>
        </div>
    @endif
</div>
@endsection
