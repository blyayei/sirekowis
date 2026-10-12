@extends('layouts.admin')

@section('title', 'Sarana Fasilitas')
@section('page_title', 'Master Data Fasilitas & Sarana Pariwisata')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm">
        <div>
            <h3 class="font-black text-base text-slate-900">Daftar Fasilitas & Sarana Prasarana</h3>
            <p class="text-xs text-slate-400">Digunakan untuk Multi-Hot Encoding dan Cosine Similarity fasilitas pada algoritma CBF (Bobot 0.30)</p>
        </div>
        <a href="{{ route('admin.fasilitas.create') }}" class="px-5 py-2.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-600/20 flex items-center gap-2 transition-all">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Fasilitas</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-sm">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-600 font-bold uppercase border-b border-slate-200">
                <tr>
                    <th class="p-4">No</th>
                    <th class="p-4">Icon</th>
                    <th class="p-4">Nama Fasilitas</th>
                    <th class="p-4 font-mono">Class Icon FontAwesome</th>
                    <th class="p-4 text-center">Jumlah Destinasi</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($fasilitases as $f)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 font-bold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="p-4">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm shadow-sm border border-emerald-100">
                                <i class="fa-solid {{ $f->icon ?? 'fa-check' }}"></i>
                            </div>
                        </td>
                        <td class="p-4 font-black text-sm text-slate-900">{{ $f->nama_fasilitas }}</td>
                        <td class="p-4 font-mono text-[11px] text-slate-500">{{ $f->icon ?: 'fa-check' }}</td>
                        <td class="p-4 text-center">
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-800 font-bold">
                                {{ $f->wisatas_count }} Destinasi
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.fasilitas.edit', $f->id) }}" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-amber-50 hover:text-amber-700 text-slate-600 flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.fasilitas.destroy', $f->id) }}" method="POST" onsubmit="return confirm('Hapus fasilitas ini?');">
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
                        <td colspan="6" class="p-8 text-center text-slate-400">Belum ada fasilitas terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
