<?php

namespace App\Services;

use App\Models\Wisata;
use App\Models\Fasilitas;
use App\Models\LogRekomendasi;
use Illuminate\Support\Collection;

class ContentBasedFilteringService
{
    // Bobot Atribut sesuai PRD SIREKOWIS v2.0 Bab 5
    protected float $wKategori = 0.30;
    protected float $wFasilitas = 0.30;
    protected float $wHarga = 0.15;
    protected float $wRating = 0.15;
    protected float $wDeskripsi = 0.10;

    protected array $stopwords = [
        'yang', 'di', 'dan', 'ke', 'dari', 'ini', 'itu', 'untuk', 'pada', 'adalah',
        'dengan', 'serta', 'sebagai', 'menjadi', 'dalam', 'oleh', 'karena', 'bisa',
        'dapat', 'juga', 'ada', 'akan', 'atau', 'lebih', 'sangat', 'terdapat', 'memiliki',
        'merupakan', 'tempat', 'wisata', 'kawasan', 'kota', 'banjarbaru', 'para', 'para',
        'setiap', 'bagi', 'nya', 'saat', 'seperti', 'seluas', 'berada', 'menawarkan',
        'hingga', 'ke', 'sudah', 'telah', 'masih', 'banyak', 'satu', 'dua', 'tiga',
    ];

    /**
     * Dapatkan daftar rekomendasi tempat wisata serupa untuk target wisata.
     */
    public function getRecommendations(Wisata $targetWisata, int $limit = 5, ?string $keyword = null, ?string $ip = null): array
    {
        $allWisatas = Wisata::with(['kategori', 'fasilitases'])
            ->where('status', 'aktif')
            ->get();

        if ($allWisatas->count() <= 1) {
            return [];
        }

        // Hitung TF-IDF korpus untuk semua deskripsi
        $corpusTfidf = $this->calculateCorpusTfidf($allWisatas);

        // Precompute min & max harga
        $minHarga = $allWisatas->min('harga_tiket') ?? 0;
        $maxHarga = $allWisatas->max('harga_tiket') ?? 0;
        $rangeHarga = max($maxHarga - $minHarga, 1);

        // Daftar semua ID fasilitas untuk One/Multi-Hot Encoding
        $allFasilitasIds = Fasilitas::pluck('id')->all();

        $recommendations = [];

        foreach ($allWisatas as $candidate) {
            // Jangan bandingkan dengan diri sendiri
            if ($candidate->id === $targetWisata->id) {
                continue;
            }

            $scoreDetail = $this->calculateSimilarity(
                $targetWisata,
                $candidate,
                $allFasilitasIds,
                $rangeHarga,
                $corpusTfidf
            );

            $recommendations[] = [
                'wisata' => $candidate,
                'score' => $scoreDetail['total'],
                'score_percent' => round($scoreDetail['total'] * 100, 1),
                'breakdown' => $scoreDetail,
            ];
        }

        // Urutkan dari skor tertinggi
        usort($recommendations, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        // Ambil Top-N
        $topRecommendations = array_slice($recommendations, 0, $limit);

        // Catat ke log_rekomendasis jika ada request detail
        foreach ($topRecommendations as $rec) {
            try {
                LogRekomendasi::create([
                    'wisata_asal_id' => $targetWisata->id,
                    'wisata_rekomendasi_id' => $rec['wisata']->id,
                    'similarity_score' => $rec['score'],
                    'keyword_pencarian' => $keyword,
                    'ip_address' => $ip ?? request()->ip(),
                ]);
            } catch (\Exception $e) {
                // Ignore duplicate or logging errors
            }
        }

        return $topRecommendations;
    }

    /**
     * Hitung kesamaan Weighted Cosine Similarity antara dua destinasi wisata.
     */
    public function calculateSimilarity(
        Wisata $a,
        Wisata $b,
        array $allFasilitasIds = [],
        int $rangeHarga = 1,
        array $corpusTfidf = []
    ): array {
        // 1. Kategori (One-Hot Cosine Similarity: 1 jika sama, 0 jika beda)
        $simKategori = ($a->kategori_id === $b->kategori_id) ? 1.0 : 0.0;

        // 2. Fasilitas (Multi-Hot Vector Cosine Similarity)
        if (empty($allFasilitasIds)) {
            $allFasilitasIds = Fasilitas::pluck('id')->all();
        }
        $simFasilitas = $this->calculateFasilitasCosine($a, $b, $allFasilitasIds);

        // 3. Harga Tiket (Min-Max Normalization: 1 - (|harga_a - harga_b| / range))
        if ($rangeHarga <= 0) {
            $rangeHarga = 1;
        }
        $diffHarga = abs($a->harga_tiket - $b->harga_tiket);
        $simHarga = max(0.0, 1.0 - ($diffHarga / $rangeHarga));

        // 4. Rating Google (Normalisasi Skala 0 - 5.0: 1 - (|rat_a - rat_b| / 5.0))
        $diffRating = abs((float)$a->rating_google - (float)$b->rating_google);
        $simRating = max(0.0, 1.0 - ($diffRating / 5.0));

        // 5. Deskripsi (TF-IDF Vector Cosine Similarity)
        if (isset($corpusTfidf[$a->id]) && isset($corpusTfidf[$b->id])) {
            $simDeskripsi = $this->cosineSimilarityVectors($corpusTfidf[$a->id], $corpusTfidf[$b->id]);
        } else {
            $simDeskripsi = $this->calculatePairwiseTfidf($a->deskripsi, $b->deskripsi);
        }

        // Weighted Total
        $total = ($this->wKategori * $simKategori) +
                 ($this->wFasilitas * $simFasilitas) +
                 ($this->wHarga * $simHarga) +
                 ($this->wRating * $simRating) +
                 ($this->wDeskripsi * $simDeskripsi);

        return [
            'total' => round($total, 4),
            'kategori' => round($simKategori, 4),
            'fasilitas' => round($simFasilitas, 4),
            'harga' => round($simHarga, 4),
            'rating' => round($simRating, 4),
            'deskripsi' => round($simDeskripsi, 4),
            'weights' => [
                'w1_kategori' => $this->wKategori,
                'w2_fasilitas' => $this->wFasilitas,
                'w3_harga' => $this->wHarga,
                'w4_rating' => $this->wRating,
                'w5_deskripsi' => $this->wDeskripsi,
            ]
        ];
    }

    /**
     * Hitung Matriks Kemiripan (NxN) untuk semua objek wisata aktif (Laporan L-02).
     */
    public function buildSimilarityMatrix(): array
    {
        $wisatas = Wisata::with(['kategori', 'fasilitases'])
            ->where('status', 'aktif')
            ->orderBy('id', 'asc')
            ->get();

        $allFasilitasIds = Fasilitas::pluck('id')->all();
        $minHarga = $wisatas->min('harga_tiket') ?? 0;
        $maxHarga = $wisatas->max('harga_tiket') ?? 0;
        $rangeHarga = max($maxHarga - $minHarga, 1);
        $corpusTfidf = $this->calculateCorpusTfidf($wisatas);

        $matrix = [];
        $names = [];

        foreach ($wisatas as $w) {
            $names[$w->id] = $w->nama_wisata;
        }

        foreach ($wisatas as $row) {
            $matrix[$row->id] = [];
            foreach ($wisatas as $col) {
                if ($row->id === $col->id) {
                    $matrix[$row->id][$col->id] = 1.0000;
                } else {
                    $res = $this->calculateSimilarity($row, $col, $allFasilitasIds, $rangeHarga, $corpusTfidf);
                    $matrix[$row->id][$col->id] = $res['total'];
                }
            }
        }

        return [
            'wisatas' => $wisatas,
            'names' => $names,
            'matrix' => $matrix,
        ];
    }

    /**
     * Cosine similarity pada multi-hot binary vector fasilitas.
     */
    protected function calculateFasilitasCosine(Wisata $a, Wisata $b, array $allFasilitasIds): float
    {
        $aFasIds = $a->fasilitases->pluck('id')->all();
        $bFasIds = $b->fasilitases->pluck('id')->all();

        $dotProduct = 0;
        $magA = 0;
        $magB = 0;

        foreach ($allFasilitasIds as $fid) {
            $valA = in_array($fid, $aFasIds) ? 1 : 0;
            $valB = in_array($fid, $bFasIds) ? 1 : 0;

            $dotProduct += ($valA * $valB);
            $magA += ($valA * $valA);
            $magB += ($valB * $valB);
        }

        if ($magA === 0 || $magB === 0) {
            return 0.0;
        }

        return $dotProduct / (sqrt($magA) * sqrt($magB));
    }

    /**
     * Hitung TF-IDF untuk seluruh dokumen dalam korpus.
     */
    protected function calculateCorpusTfidf(Collection $wisatas): array
    {
        $documents = [];
        $vocab = [];

        foreach ($wisatas as $w) {
            $tokens = $this->tokenize($w->deskripsi);
            $documents[$w->id] = $tokens;
            foreach ($tokens as $token) {
                $vocab[$token] = true;
            }
        }

        $allTerms = array_keys($vocab);
        $totalDocs = count($documents);

        // Document frequency (DF)
        $docFreq = [];
        foreach ($allTerms as $term) {
            $df = 0;
            foreach ($documents as $tokens) {
                if (in_array($term, $tokens)) {
                    $df++;
                }
            }
            $docFreq[$term] = $df;
        }

        // Inverse Document Frequency (IDF)
        $idf = [];
        foreach ($allTerms as $term) {
            $idf[$term] = log(($totalDocs + 1) / ($docFreq[$term] + 1)) + 1.0;
        }

        // TF-IDF vectors
        $vectors = [];
        foreach ($documents as $docId => $tokens) {
            $termCounts = array_count_values($tokens);
            $totalTokens = max(count($tokens), 1);
            $vector = [];

            foreach ($allTerms as $term) {
                $tf = isset($termCounts[$term]) ? ($termCounts[$term] / $totalTokens) : 0;
                $vector[$term] = $tf * ($idf[$term] ?? 1.0);
            }
            $vectors[$docId] = $vector;
        }

        return $vectors;
    }

    /**
     * Cosine similarity antara dua associative vector.
     */
    protected function cosineSimilarityVectors(array $vecA, array $vecB): float
    {
        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        foreach ($vecA as $term => $valA) {
            $valB = $vecB[$term] ?? 0.0;
            $dot += ($valA * $valB);
            $normA += ($valA * $valA);
        }

        foreach ($vecB as $term => $valB) {
            $normB += ($valB * $valB);
        }

        if ($normA <= 0.0 || $normB <= 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Fallback TF-IDF berpasangan sederhana.
     */
    protected function calculatePairwiseTfidf(string $textA, string $textB): float
    {
        $tokensA = $this->tokenize($textA);
        $tokensB = $this->tokenize($textB);

        $allTerms = array_unique(array_merge($tokensA, $tokensB));
        if (empty($allTerms)) {
            return 0.0;
        }

        $countsA = array_count_values($tokensA);
        $countsB = array_count_values($tokensB);

        $vecA = [];
        $vecB = [];
        foreach ($allTerms as $term) {
            $vecA[$term] = $countsA[$term] ?? 0;
            $vecB[$term] = $countsB[$term] ?? 0;
        }

        return $this->cosineSimilarityVectors($vecA, $vecB);
    }

    /**
     * Case folding, tokenisasi, filter karakter dan pembersihan stopwords.
     */
    public function tokenize(string $text): array
    {
        // Case folding ke huruf kecil
        $clean = mb_strtolower($text, 'UTF-8');

        // Ganti tanda baca dan simbol menjadi spasi
        $clean = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $clean);

        // Pisahkan kata berdasarkan spasi
        $words = preg_split('/\s+/', $clean, -1, PREG_SPLIT_NO_EMPTY);

        // Hapus stopwords dan kata dengan panjang <= 2 karakter
        $filtered = [];
        foreach ($words as $w) {
            if (mb_strlen($w) > 2 && !in_array($w, $this->stopwords)) {
                $filtered[] = $w;
            }
        }

        return $filtered;
    }
}
