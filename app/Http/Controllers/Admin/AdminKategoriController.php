<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminKategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('wisatas')->get();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategoris,nama_kategori',
            'deskripsi' => 'nullable|string',
        ]);

        Kategori::create([
            'nama_kategori' => $validated['nama_kategori'],
            'slug' => Str::slug($validated['nama_kategori']),
            'deskripsi' => $validated['deskripsi'],
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori wisata berhasil ditambahkan!');
    }

    public function edit($kategori)
    {
        $kategori = $kategori instanceof Kategori ? $kategori : Kategori::findOrFail($kategori);
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $kategori)
    {
        $kategori = $kategori instanceof Kategori ? $kategori : Kategori::findOrFail($kategori);
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategoris,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update([
            'nama_kategori' => $validated['nama_kategori'],
            'slug' => Str::slug($validated['nama_kategori']),
            'deskripsi' => $validated['deskripsi'],
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori wisata berhasil diperbarui!');
    }

    public function destroy($kategori)
    {
        $kategori = $kategori instanceof Kategori ? $kategori : Kategori::findOrFail($kategori);
        if ($kategori->wisatas()->count() > 0) {
            return redirect()->route('admin.kategori.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki destinasi wisata terkait!');
        }

        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori wisata berhasil dihapus!');
    }
}
