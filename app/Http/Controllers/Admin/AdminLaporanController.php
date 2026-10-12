<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Kategori;
use App\Models\Fasilitas;
use App\Models\LogRekomendasi;
use App\Models\User;
use App\Services\ContentBasedFilteringService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLaporanController extends Controller
{
    /**
     * Halaman Utama Menu Cetak 6 Laporan PDF
     */
    public function index()
    {
        $laporanList = [
            [
                'kode' => 'L-01',
                'judul' => 'Laporan Data Destinasi & Kategori Wisata',
                'deskripsi' => 'Rekapitulasi seluruh objek wisata di Banjarbaru berdasarkan kategori dan kecamatan.',
                'orientasi' => 'Portrait',
                'kategori' => 'Master Data',
            ],
            [
                'kode' => 'L-02',
                'judul' => 'Laporan Matriks Kemiripan (Similarity Score) Antar-Wisata',
                'deskripsi' => 'Menampilkan tabel hasil kalkulasi matriks Cosine Similarity antar-pasangan wisata hasil algoritma CBF.',
                'orientasi' => 'Landscape',
                'kategori' => 'Algoritma CBF',
            ],
            [
                'kode' => 'L-03',
                'judul' => 'Laporan Riwayat & Log Rekomendasi Pengunjung',
                'deskripsi' => 'Rekapitulasi wisata mana saja yang paling sering dicari dan direkomendasikan sistem.',
                'orientasi' => 'Portrait',
                'kategori' => 'Analitik Pengunjung',
            ],
            [
                'kode' => 'L-04',
                'judul' => 'Laporan Destinasi Terpopuler & Rating Google Maps',
                'deskripsi' => 'Peringkat destinasi wisata Banjarbaru berdasarkan rating bintang dan jumlah ulasan Google.',
                'orientasi' => 'Portrait',
                'kategori' => 'Reputasi Wisata',
            ],
            [
                'kode' => 'L-05',
                'judul' => 'Laporan Rekapitulasi Fasilitas Destinasi Wisata',
                'deskripsi' => 'Laporan matriks ketersediaan sarana dan prasarana (toilet, mushola, parkir, dll.) di setiap lokasi.',
                'orientasi' => 'Landscape',
                'kategori' => 'Sarana & Prasarana',
            ],
            [
                'kode' => 'L-06',
                'judul' => 'Laporan Statistik Preferensi & Pengguna Terdaftar',
                'deskripsi' => 'Rekapitulasi preferensi pencarian pengunjung untuk bahan evaluasi dinas dan data operator sistem.',
                'orientasi' => 'Portrait',
                'kategori' => 'Manajemen Sistem',
            ],
        ];

        return view('admin.laporan.index', compact('laporanList'));
    }

    /**
     * Generate & Cetak Laporan PDF berdasarkan Kode Dokumen
     */
    public function cetak(string $kode, Request $request, ContentBasedFilteringService $cbfService)
    {
        $tanggalCetak = $this->formatTanggalIndonesia(now());

        switch (strtoupper($kode)) {
            case 'L-01':
                $wisatas = Wisata::with(['kategori', 'fasilitases'])
                    ->orderBy('kategori_id')
                    ->orderBy('nama_wisata')
                    ->get();
                $totalWisata = $wisatas->count();
                $kategoris = Kategori::withCount('wisatas')->get();

                $pdf = Pdf::loadView('admin.laporan.pdf_l01_destinasi', compact('wisatas', 'kategoris', 'totalWisata', 'tanggalCetak'));
                $pdf->setPaper('a4', 'portrait');
                return $request->has('stream') 
                    ? $pdf->stream('L-01_Laporan_Data_Destinasi_Wisata.pdf') 
                    : $pdf->download('L-01_Laporan_Data_Destinasi_Wisata.pdf');

            case 'L-02':
                $matrixData = $cbfService->buildSimilarityMatrix();

                $pdf = Pdf::loadView('admin.laporan.pdf_l02_similarity_matrix', compact('matrixData', 'tanggalCetak'));
                $pdf->setPaper('a4', 'landscape');
                return $request->has('stream')
                    ? $pdf->stream('L-02_Laporan_Matriks_Kemiripan_CBF.pdf')
                    : $pdf->download('L-02_Laporan_Matriks_Kemiripan_CBF.pdf');

            case 'L-03':
                $logs = LogRekomendasi::with(['wisataAsal', 'wisataRekomendasi'])
                    ->latest()
                    ->take(50)
                    ->get();

                $rekapWisata = LogRekomendasi::select('wisata_rekomendasi_id', DB::raw('count(*) as total'), DB::raw('avg(similarity_score) as avg_score'))
                    ->groupBy('wisata_rekomendasi_id')
                    ->orderBy('total', 'desc')
                    ->with('wisataRekomendasi')
                    ->get();

                $pdf = Pdf::loadView('admin.laporan.pdf_l03_log_rekomendasi', compact('logs', 'rekapWisata', 'tanggalCetak'));
                $pdf->setPaper('a4', 'portrait');
                return $request->has('stream')
                    ? $pdf->stream('L-03_Laporan_Log_Rekomendasi.pdf')
                    : $pdf->download('L-03_Laporan_Log_Rekomendasi.pdf');

            case 'L-04':
                $wisatas = Wisata::with('kategori')
                    ->orderBy('rating_google', 'desc')
                    ->orderBy('jumlah_review_google', 'desc')
                    ->get();

                $pdf = Pdf::loadView('admin.laporan.pdf_l04_populer_rating', compact('wisatas', 'tanggalCetak'));
                $pdf->setPaper('a4', 'portrait');
                return $request->has('stream')
                    ? $pdf->stream('L-04_Laporan_Destinasi_Terpopuler_Rating.pdf')
                    : $pdf->download('L-04_Laporan_Destinasi_Terpopuler_Rating.pdf');

            case 'L-05':
                $wisatas = Wisata::with('fasilitases')->orderBy('nama_wisata')->get();
                $fasilitases = Fasilitas::orderBy('nama_fasilitas')->get();

                $pdf = Pdf::loadView('admin.laporan.pdf_l05_fasilitas', compact('wisatas', 'fasilitases', 'tanggalCetak'));
                $pdf->setPaper('a4', 'landscape');
                return $request->has('stream')
                    ? $pdf->stream('L-05_Laporan_Fasilitas_Wisata.pdf')
                    : $pdf->download('L-05_Laporan_Fasilitas_Wisata.pdf');

            case 'L-06':
                $users = User::orderBy('role')->orderBy('name')->get();
                $topKeywords = LogRekomendasi::select('keyword_pencarian', DB::raw('count(*) as total'))
                    ->whereNotNull('keyword_pencarian')
                    ->groupBy('keyword_pencarian')
                    ->orderBy('total', 'desc')
                    ->take(10)
                    ->get();

                $totalRekomendasi = LogRekomendasi::count();

                $pdf = Pdf::loadView('admin.laporan.pdf_l06_statistik_pengguna', compact('users', 'topKeywords', 'totalRekomendasi', 'tanggalCetak'));
                $pdf->setPaper('a4', 'portrait');
                return $request->has('stream')
                    ? $pdf->stream('L-06_Laporan_Statistik_Preferensi_Pengguna.pdf')
                    : $pdf->download('L-06_Laporan_Statistik_Preferensi_Pengguna.pdf');

            default:
                abort(404, 'Kode Laporan Tidak Dikenal');
        }
    }

    protected function formatTanggalIndonesia($date): string
    {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return $date->format('d') . ' ' . $bulan[(int)$date->format('m')] . ' ' . $date->format('Y');
    }
}
