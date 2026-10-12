<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class AdminFasilitasController extends Controller
{
    public function index()
    {
        $fasilitases = Fasilitas::withCount('wisatas')->get();
        return view('admin.fasilitas.index', compact('fasilitases'));
    }

    public function create()
    {
        return view('admin.fasilitas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_fasilitas' => 'required|string|max:50|unique:fasilitases,nama_fasilitas',
            'icon' => 'nullable|string|max:50',
        ]);

        Fasilitas::create($validated);

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function edit($fasilitas)
    {
        $fasilitas = $fasilitas instanceof Fasilitas ? $fasilitas : Fasilitas::findOrFail($fasilitas);
        return view('admin.fasilitas.edit', compact('fasilitas'));
    }

    public function update(Request $request, $fasilitas)
    {
        $fasilitas = $fasilitas instanceof Fasilitas ? $fasilitas : Fasilitas::findOrFail($fasilitas);
        $validated = $request->validate([
            'nama_fasilitas' => 'required|string|max:50|unique:fasilitases,nama_fasilitas,' . $fasilitas->id,
            'icon' => 'nullable|string|max:50',
        ]);

        $fasilitas->update($validated);

        return redirect()->route('admin.fasilitas.index')->with('success', 'Data fasilitas berhasil diperbarui!');
    }

    public function destroy($fasilitas)
    {
        $fasilitas = $fasilitas instanceof Fasilitas ? $fasilitas : Fasilitas::findOrFail($fasilitas);
        $fasilitas->wisatas()->detach();
        $fasilitas->delete();

        return redirect()->route('admin.fasilitas.index')->with('success', 'Fasilitas berhasil dihapus!');
    }
}
