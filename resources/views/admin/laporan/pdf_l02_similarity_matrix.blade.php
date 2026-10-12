<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>L-02 Laporan Matriks Kemiripan CBF Banjarbaru</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 8pt; color: #1f2937; margin: 15px; }
        .title { text-align: center; font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .subtitle { text-align: center; font-size: 8pt; color: #4b5563; margin-bottom: 12px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 12px; }
        table.data th, table.data td { border: 1px solid #9ca3af; padding: 4px 6px; font-size: 7.5pt; }
        table.data th { background-color: #f3f4f6; text-transform: uppercase; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .score-high { background-color: #d1fae5; font-weight: bold; color: #065f46; }
        .score-diag { background-color: #e5e7eb; font-weight: bold; color: #374151; }
        .ttd-table { width: 100%; margin-top: 25px; border-collapse: collapse; }
    </style>
</head>
<body>

    @include('admin.laporan.kop_surat')

    <div class="title">LAPORAN MATRIKS KEMIRIPAN (SIMILARITY SCORE) ANTAR-DESTINASI WISATA</div>
    <div class="subtitle">Nomor Dokumen: 556 / 02 / MATRIKS-CBF / DISPORABUDPAR / {{ date('Y') }} &bull; Metode: Weighted Cosine Similarity & TF-IDF</div>

    <div style="font-size: 7.5pt; color: #4b5563; margin-bottom: 8px;">
        <strong>Parameter Pembobotan Algoritma:</strong> Kategori (w1 = 0.30), Fasilitas (w2 = 0.30), Harga Tiket (w3 = 0.15), Rating Google Maps (w4 = 0.15), Deskripsi TF-IDF (w5 = 0.10).
    </div>

    @php
        $wisatas = $matrixData['wisatas'];
        $matrix = $matrixData['matrix'];
    @endphp

    <table class="data">
        <thead>
            <tr>
                <th style="width: 20px;">No</th>
                <th style="width: 140px; text-align: left;">Nama Destinasi Wisata</th>
                @foreach($wisatas as $i => $col)
                    <th style="width: 65px;" title="{{ $col->nama_wisata }}">
                        W-0{{ $i + 1 }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($wisatas as $i => $row)
                <tr>
                    <td class="text-center font-bold">{{ $i + 1 }}</td>
                    <td style="font-weight: bold;">
                        W-0{{ $i + 1 }}. {{ $row->nama_wisata }}
                    </td>
                    @foreach($wisatas as $j => $col)
                        @php 
                            $val = $matrix[$row->id][$col->id] ?? 0;
                            $class = ($i === $j) ? 'score-diag' : (($val >= 0.70) ? 'score-high' : '');
                        @endphp
                        <td class="text-center {{ $class }}">
                            {{ number_format($val, 4) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="font-size: 7.5pt; margin-top: 5px;">
        <em>Keterangan: Skor bernilai antara 0.0000 s/d 1.0000. Kotak hijau muda menandakan tingkat kemiripan tinggi (&ge; 0.7000 / 70%) yang menjadi kandidat rekomendasi utama.</em>
    </div>

    <!-- Tanda Tangan Pejabat Struktural -->
    <table class="ttd-table">
        <tr>
            <td style="width: 70%;"></td>
            <td style="width: 30%; text-align: center; font-size: 8pt;">
                Kota Banjarbaru, {{ $tanggalCetak }}<br>
                <strong>Kepala Dinas DISPORABUDPAR<br>Kota Banjarbaru</strong>
                <br><br><br><br>
                <u><strong>Noor Purbani Sukma Alamsyah, ST.</strong></u><br>
                NIP. 19691217 200701 1 018
            </td>
        </tr>
    </table>

</body>
</html>
