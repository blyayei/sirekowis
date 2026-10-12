@extends('layouts.admin')

@section('title', 'Pusat Cetak Laporan PDF')
@section('page_title', 'Pusat Cetak 6 Modul Laporan Resmi Dinas')

@section('content')
<div class="space-y-8">

    <!-- Header Description & Compliance Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold mb-3">
                <i class="fa-solid fa-stamp"></i> Standar Resmi DISPORABUDPAR Kota Banjarbaru
            </div>
            <h2 class="text-2xl font-black text-slate-900">Modul Pelaporan Resmi Pariwisata Kota Banjarbaru</h2>
            <p class="text-xs text-slate-500 max-w-2xl mt-1 leading-relaxed">
                Setiap dokumen laporan di bawah ini dibuat dengan format cetak A4 ber-<strong>Kop Surat Resmi Dinas Kepemudaan, Olahraga, Kebudayaan dan Pariwisata Kota Banjarbaru</strong> serta kolom tanda tangan pengesahan pejabat struktural.
            </p>
        </div>
        <div class="text-right">
            <span class="text-3xl font-black text-brand-600 block">6 Modul</span>
            <span class="text-[11px] font-bold text-slate-400">Laporan Resmi Terintegrasi</span>
        </div>
    </div>

    <!-- 6 Modules Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($laporanList as $lap)
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <!-- Badge & Code -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-xl bg-slate-900 text-white font-mono font-black text-xs">
                            {{ $lap['kode'] }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $lap['orientasi'] == 'Landscape' ? 'bg-amber-100 text-amber-800' : 'bg-teal-100 text-teal-800' }}">
                            {{ $lap['orientasi'] }} A4
                        </span>
                    </div>

                    <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block mb-1">
                        {{ $lap['kategori'] }}
                    </span>
                    <h3 class="font-black text-base text-slate-900 mb-2 leading-snug">
                        {{ $lap['judul'] }}
                    </h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-6">
                        {{ $lap['deskripsi'] }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex items-center gap-2">
                    <a href="{{ route('admin.laporan.cetak', ['kode' => $lap['kode'], 'stream' => 1]) }}" target="_blank" class="flex-1 py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs text-center transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-regular fa-eye"></i>
                        <span>Pratinjau</span>
                    </a>
                    <a href="{{ route('admin.laporan.cetak', ['kode' => $lap['kode']]) }}" class="flex-1 py-2 px-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs text-center transition-colors flex items-center justify-center gap-1.5 shadow-sm shadow-rose-600/20">
                        <i class="fa-solid fa-download"></i>
                        <span>Unduh PDF</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
