<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Kategori;
use App\Models\Fasilitas;
use App\Models\LogRekomendasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalWisata = Wisata::count();
        $totalKategori = Kategori::count();
        $totalFasilitas = Fasilitas::count();
        $totalLog = LogRekomendasi::count();
        $totalUsers = User::count();

        // Data sebaran kategori
        $kategoriDistribution = Kategori::withCount('wisatas')->get();

        // 5 Wisata Paling Sering Direkomendasikan
        $topRecommended = LogRekomendasi::select('wisata_rekomendasi_id', DB::raw('count(*) as total_rekomendasi'), DB::raw('avg(similarity_score) as avg_score'))
            ->groupBy('wisata_rekomendasi_id')
            ->orderBy('total_rekomendasi', 'desc')
            ->with('wisataRekomendasi.kategori')
            ->take(5)
            ->get();

        // 5 Wisata Rating Tertinggi
        $topRated = Wisata::with('kategori')
            ->orderBy('rating_google', 'desc')
            ->orderBy('jumlah_review_google', 'desc')
            ->take(5)
            ->get();

        // Log Rekomendasi Terbaru
        $recentLogs = LogRekomendasi::with(['wisataAsal', 'wisataRekomendasi'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalWisata',
            'totalKategori',
            'totalFasilitas',
            'totalLog',
            'totalUsers',
            'kategoriDistribution',
            'topRecommended',
            'topRated',
            'recentLogs'
        ));
    }
}
