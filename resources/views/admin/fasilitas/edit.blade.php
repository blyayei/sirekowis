@extends('layouts.admin')

@section('title', 'Edit Fasilitas')
@section('page_title', 'Perbarui Data Sarana Fasilitas')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.fasilitas.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Fasilitas
        </a>
    </div>

    <form action="{{ route('admin.fasilitas.update', $fasilitas->id) }}" method="POST" class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Fasilitas *</label>
            <input type="text" name="nama_fasilitas" value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 outline-none">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Class Icon FontAwesome</label>
            <input type="text" name="icon" value="{{ old('icon', $fasilitas->icon) }}" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-300 font-mono focus:ring-2 focus:ring-brand-500 outline-none">
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.fasilitas.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-100 text-xs font-bold">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-600/20">
                Perbarui Fasilitas
            </button>
        </div>
    </form>

</div>
@endsection
