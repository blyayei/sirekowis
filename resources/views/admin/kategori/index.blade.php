@extends('layouts.admin')

@section('title', 'Kategori Wisata')
@section('page_title', 'Master Data Kategori Wisata')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <h3 class="font-black text-base text-slate-900">Daftar Kategori Objek Wisata</h3>
            <p class="text-xs text-slate-400">Digunakan untuk klasifikasi destinasi dan fitur One-Hot Encoding pada CBF (Bobot 0.30)</p>
        </div>
        <a href="{{ route('admin.kategori.create') }}" class="px-5 py-2.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-600/20 flex items-center gap-2 transition-all">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Kategori</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-600 font-bold uppercase border-b border-slate-200">
                <tr>
                    <th class="p-4">No</th>
                    <th class="p-4">Nama Kategori</th>
                    <th class="p-4">Slug URL</th>
                    <th class="p-4">Deskripsi</th>
                    <th class="p-4 text-center">Jumlah Wisata</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($kategoris as $k)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 font-bold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="p-4 font-extrabold text-sm text-slate-900">{{ $k->nama_kategori }}</td>
                        <td class="p-4 font-mono text-[11px] text-brand-700">{{ $k->slug }}</td>
                        <td class="p-4 text-slate-500 max-w-sm">{{ $k->deskripsi ?: '-' }}</td>
                        <td class="p-4 text-center">
                            <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold">
                                {{ $k->wisatas_count }} Wisata
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.kategori.edit', $k->id) }}" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-600 flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-rose-50 hover:text-rose-700 text-slate-600 flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">Belum ada kategori wisata.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
