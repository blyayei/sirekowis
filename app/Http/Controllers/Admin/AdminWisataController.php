<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Kategori;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminWisataController extends Controller
{
    public function index(Request $request)
    {
        $query = Wisata::with(['kategori', 'fasilitases']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nama_wisata', 'like', "%{$q}%")
                  ->orWhere('kecamatan', 'like', "%{$q}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $wisatas = $query->latest()->paginate(10)->withQueryString();
        $kategoris = Kategori::all();

        return view('admin.wisata.index', compact('wisatas', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $fasilitases = Fasilitas::all();
        $kecamatans = [
            'Banjarbaru Utara',
            'Banjarbaru Selatan',
            'Cempaka',
            'Landasan Ulin',
            'Liang Anggang',
        ];

        return view('admin.wisata.create', compact('kategoris', 'fasilitases', 'kecamatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_wisata' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategoris,id',
            'kecamatan' => 'required|string|max:50',
            'alamat' => 'required|string',
            'deskripsi' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'harga_tiket' => 'required|integer|min:0',
            'rating_google' => 'required|numeric|between:0,5',
            'jumlah_review_google' => 'required|integer|min:0',
            'jam_operasional' => 'nullable|string|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'foto_url_input' => 'nullable|url|max:255',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitases,id',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('wisata', 'public');
        } elseif ($request->filled('foto_url_input')) {
            $fotoPath = $request->foto_url_input;
        }

        $slug = Str::slug($validated['nama_wisata']);
        $originalSlug = $slug;
        $counter = 1;
        while (Wisata::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $wisata = Wisata::create([
            'nama_wisata' => $validated['nama_wisata'],
            'slug' => $slug,
            'kategori_id' => $validated['kategori_id'],
            'kecamatan' => $validated['kecamatan'],
            'alamat' => $validated['alamat'],
            'deskripsi' => $validated['deskripsi'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'harga_tiket' => $validated['harga_tiket'],
            'rating_google' => $validated['rating_google'],
            'jumlah_review_google' => $validated['jumlah_review_google'],
            'jam_operasional' => $validated['jam_operasional'] ?? '08:00 - 17:00 WITA',
            'status' => $validated['status'],
            'foto_utama' => $fotoPath,
        ]);

        if (!empty($validated['fasilitas'])) {
            $wisata->fasilitases()->sync($validated['fasilitas']);
        }

        return redirect()->route('admin.wisata.index')->with('success', 'Destinasi wisata berhasil ditambahkan!');
    }

    public function edit($wisata)
    {
        $wisata = $wisata instanceof Wisata ? $wisata : Wisata::with('fasilitases')->findOrFail($wisata);
        $kategoris = Kategori::all();
        $fasilitases = Fasilitas::all();
        $kecamatans = [
            'Banjarbaru Utara',
            'Banjarbaru Selatan',
            'Cempaka',
            'Landasan Ulin',
            'Liang Anggang',
        ];
        $selectedFasilitas = $wisata->fasilitases->pluck('id')->toArray();

        return view('admin.wisata.edit', compact('wisata', 'kategoris', 'fasilitases', 'kecamatans', 'selectedFasilitas'));
    }

    public function update(Request $request, $wisata)
    {
        $wisata = $wisata instanceof Wisata ? $wisata : Wisata::findOrFail($wisata);

        $validated = $request->validate([
            'nama_wisata' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategoris,id',
            'kecamatan' => 'required|string|max:50',
            'alamat' => 'required|string',
            'deskripsi' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'harga_tiket' => 'required|integer|min:0',
            'rating_google' => 'required|numeric|between:0,5',
            'jumlah_review_google' => 'required|integer|min:0',
            'jam_operasional' => 'nullable|string|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'foto_url_input' => 'nullable|url|max:255',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitases,id',
        ]);

        $fotoPath = $wisata->foto_utama;
        if ($request->hasFile('foto')) {
            if ($fotoPath && !str_starts_with($fotoPath, 'http') && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('wisata', 'public');
        } elseif ($request->filled('foto_url_input')) {
            $fotoPath = $request->foto_url_input;
        }

        $slug = $wisata->slug;
        if ($wisata->nama_wisata !== $validated['nama_wisata']) {
            $baseSlug = Str::slug($validated['nama_wisata']);
            $slug = $baseSlug;
            $counter = 1;
            while (Wisata::where('slug', $slug)->where('id', '!=', $wisata->id)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
        }

        $wisata->update([
            'nama_wisata' => $validated['nama_wisata'],
            'slug' => $slug,
            'kategori_id' => $validated['kategori_id'],
            'kecamatan' => $validated['kecamatan'],
            'alamat' => $validated['alamat'],
            'deskripsi' => $validated['deskripsi'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'harga_tiket' => $validated['harga_tiket'],
            'rating_google' => $validated['rating_google'],
            'jumlah_review_google' => $validated['jumlah_review_google'],
            'jam_operasional' => $validated['jam_operasional'],
            'status' => $validated['status'],
            'foto_utama' => $fotoPath,
        ]);

        $wisata->fasilitases()->sync($validated['fasilitas'] ?? []);

        return redirect()->route('admin.wisata.index')->with('success', 'Data destinasi wisata berhasil diperbarui!');
    }

    public function destroy($wisata)
    {
        $wisata = $wisata instanceof Wisata ? $wisata : Wisata::findOrFail($wisata);

        if ($wisata->foto_utama && !str_starts_with($wisata->foto_utama, 'http') && Storage::disk('public')->exists($wisata->foto_utama)) {
            Storage::disk('public')->delete($wisata->foto_utama);
        }

        $wisata->fasilitases()->detach();
        $wisata->delete();

        return redirect()->route('admin.wisata.index')->with('success', 'Destinasi wisata berhasil dihapus!');
    }
}
