@extends('layouts.admin')

@section('title', 'Data Destinasi Wisata')
@section('page_title', 'Kelola Destinasi Wisata Kota Banjarbaru')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.wisata.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="relative flex-grow sm:w-64">
                <i class="fa-solid fa-search absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau kecamatan..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-brand-500 outline-none">
            </div>

            <select name="kategori_id" onchange="this.form.submit()" class="py-2 px-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-brand-500 outline-none">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>

            <select name="status" onchange="this.form.submit()" class="py-2 px-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-brand-500 outline-none">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            @if(request()->anyFilled(['q', 'kategori_id', 'status']))
                <a href="{{ route('admin.wisata.index') }}" class="text-xs font-semibold text-rose-600 hover:underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.wisata.create') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-600/20 flex items-center justify-center gap-2 transition-all flex-shrink-0">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Destinasi Baru</span>
        </a>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase border-b border-slate-200">
                    <tr>
                        <th class="p-4">Foto</th>
                        <th class="p-4">Nama Destinasi & Kategori</th>
                        <th class="p-4">Wilayah Kecamatan</th>
                        <th class="p-4">Harga Tiket</th>
                        <th class="p-4">Rating Google</th>
                        <th class="p-4">Fasilitas</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($wisatas as $w)
                        <tr class="hover:bg-slate-50/80">
                            <td class="p-4">
                                <img src="{{ $w->foto_url }}" alt="{{ $w->nama_wisata }}" class="w-14 h-14 rounded-xl object-cover border border-slate-200">
                            </td>
                            <td class="p-4">
                                <span class="font-extrabold text-sm text-slate-900 block">{{ $w->nama_wisata }}</span>
                                <span class="text-[11px] text-brand-700 font-bold uppercase">{{ $w->kategori->nama_kategori }}</span>
                            </td>
                            <td class="p-4 font-medium text-slate-600">
                                Kec. {{ $w->kecamatan }}
                            </td>
                            <td class="p-4 font-bold text-emerald-700">
                                {{ $w->formatted_harga }}
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-amber-600 flex items-center gap-1">
                                    ⭐ {{ number_format($w->rating_google, 1) }}
                                </span>
                                <span class="text-[10px] text-slate-400">({{ number_format($w->jumlah_review_google) }} ulasan)</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold">
                                    {{ $w->fasilitases->count() }} Fasilitas
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                @if($w->status == 'aktif')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold uppercase text-[10px]">Aktif</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-slate-200 text-slate-600 font-bold uppercase text-[10px]">Nonaktif</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('wisata.detail', $w->slug) }}" target="_blank" title="Lihat di Portal" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-cyan-50 hover:text-cyan-700 text-slate-600 flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.wisata.edit', $w->id) }}" title="Edit" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-600 flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.wisata.destroy', $w->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus wisata {{ $w->nama_wisata }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-700 text-slate-600 flex items-center justify-center transition-colors">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">Tidak ada data tempat wisata yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $wisatas->links() }}
        </div>
    </div>

</div>
@endsection
