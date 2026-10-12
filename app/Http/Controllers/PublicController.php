<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\Kategori;
use App\Models\Fasilitas;
use App\Services\ContentBasedFilteringService;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Halaman Beranda Publik
     */
    public function index()
    {
        $wisatas = Wisata::with(['kategori', 'fasilitases'])
            ->where('status', 'aktif')
            ->get();

        $kategoris = Kategori::withCount(['wisatas' => function ($q) {
            $q->where('status', 'aktif');
        }])->get();

        $unggulan = Wisata::with('kategori')
            ->where('status', 'aktif')
            ->orderBy('rating_google', 'desc')
            ->take(3)
            ->get();

        $stats = [
            'total_wisata' => $wisatas->count(),
            'total_kategori' => $kategoris->count(),
            'total_fasilitas' => Fasilitas::count(),
            'avg_rating' => round($wisatas->avg('rating_google'), 1),
        ];

        // Format geojson/array untuk Peta Leaflet Beranda
        $mapData = $wisatas->map(function ($w) {
            return [
                'id' => $w->id,
                'nama' => $w->nama_wisata,
                'slug' => $w->slug,
                'kategori' => $w->kategori->nama_kategori ?? 'Umum',
                'lat' => (float)$w->latitude,
                'lng' => (float)$w->longitude,
                'rating' => (float)$w->rating_google,
                'harga' => $w->formatted_harga,
                'foto' => $w->foto_url,
                'alamat' => $w->alamat,
            ];
        });

        return view('public.beranda', compact('wisatas', 'kategoris', 'unggulan', 'stats', 'mapData'));
    }

    /**
     * Halaman Katalog Destinasi Wisata dengan Filter
     */
    public function katalog(Request $request)
    {
        $query = Wisata::with(['kategori', 'fasilitases'])
            ->where('status', 'aktif');

        // Filter Pencarian Teks
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_wisata', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%")
                  ->orWhere('alamat', 'like', "%{$keyword}%");
            });
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('slug', $request->kategori)
                  ->orWhere('id', $request->kategori);
            });
        }

        // Filter Kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', $request->kecamatan);
        }

        // Filter Rentang Harga Maksimal
        if ($request->filled('harga_max')) {
            $query->where('harga_tiket', '<=', (int)$request->harga_max);
        }

        // Filter Fasilitas (Semua fasilitas terpilih harus ada)
        if ($request->filled('fasilitas') && is_array($request->fasilitas)) {
            foreach ($request->fasilitas as $fasilitasId) {
                $query->whereHas('fasilitases', function ($q) use ($fasilitasId) {
                    $q->where('fasilitases.id', $fasilitasId);
                });
            }
        }

        // Sorting
        $sort = $request->get('sort', 'rating_desc');
        switch ($sort) {
            case 'harga_asc':
                $query->orderBy('harga_tiket', 'asc');
                break;
            case 'harga_desc':
                $query->orderBy('harga_tiket', 'desc');
                break;
            case 'nama_asc':
                $query->orderBy('nama_wisata', 'asc');
                break;
            case 'rating_desc':
            default:
                $query->orderBy('rating_google', 'desc')->orderBy('jumlah_review_google', 'desc');
                break;
        }

        $wisatas = $query->paginate(9)->withQueryString();
        $kategoris = Kategori::all();
        $fasilitases = Fasilitas::all();
        $kecamatans = Wisata::distinct()->pluck('kecamatan')->filter()->values();

        return view('public.katalog', compact('wisatas', 'kategoris', 'fasilitases', 'kecamatans'));
    }

    /**
     * Halaman Detail Wisata + Peta Leaflet + Widget Rekomendasi CBF
     */
    public function detail(string $slug, ContentBasedFilteringService $cbfService)
    {
        $wisata = Wisata::with(['kategori', 'fasilitases'])
            ->where('slug', $slug)
            ->where('status', 'aktif')
            ->firstOrFail();

        // Ambil Top-4 Rekomendasi Wisata Serupa menggunakan Content-Based Filtering
        $rekomendasi = $cbfService->getRecommendations(
            $wisata,
            4,
            request('q'),
            request()->ip()
        );

        return view('public.detail', compact('wisata', 'rekomendasi'));
    }

    /**
     * Halaman Edukasi & Simulasi CBF Interaktif
     */
    public function simulasiCbf(Request $request, ContentBasedFilteringService $cbfService)
    {
        $allWisatas = Wisata::with(['kategori', 'fasilitases'])->where('status', 'aktif')->get();

        $selectedA = null;
        $selectedB = null;
        $result = null;

        if ($request->filled('wisata_1') && $request->filled('wisata_2')) {
            $selectedA = $allWisatas->firstWhere('id', $request->wisata_1);
            $selectedB = $allWisatas->firstWhere('id', $request->wisata_2);

            if ($selectedA && $selectedB) {
                $result = $cbfService->calculateSimilarity($selectedA, $selectedB);
            }
        }

        return view('public.simulasi-cbf', compact('allWisatas', 'selectedA', 'selectedB', 'result'));
    }
}
