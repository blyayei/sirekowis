<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>L-01 Laporan Data Destinasi Wisata Banjarbaru</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 9pt; color: #1f2937; margin: 20px; }
        .title { text-align: center; font-size: 12pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .subtitle { text-align: center; font-size: 9pt; color: #4b5563; margin-bottom: 15px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 15px; }
        table.data th, table.data td { border: 1px solid #9ca3af; padding: 6px 8px; }
        table.data th { background-color: #f3f4f6; font-size: 8pt; text-transform: uppercase; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .ttd-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
    </style>
</head>
<body>

    @include('admin.laporan.kop_surat')

    <div class="title">LAPORAN DATA REKAPITULASI DESTINASI & KATEGORI WISATA</div>
    <div class="subtitle">Nomor Dokumen: 556 / 01 / LAP-WISATA / DISPORABUDPAR / {{ date('Y') }}</div>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Objek Wisata</th>
                <th>Kategori</th>
                <th>Wilayah Kecamatan</th>
                <th>Harga Tiket</th>
                <th>Rating Google</th>
                <th>Fasilitas</th>
                <th style="width: 50px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wisatas as $i => $w)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td style="font-weight: bold;">{{ $w->nama_wisata }}</td>
                    <td>{{ $w->kategori->nama_kategori }}</td>
                    <td>Kec. {{ $w->kecamatan }}</td>
                    <td class="text-right">{{ $w->formatted_harga }}</td>
                    <td class="text-center">{{ number_format($w->rating_google, 1) }} ({{ $w->jumlah_review_google }} ulasan)</td>
                    <td>{{ $w->fasilitases->pluck('nama_fasilitas')->join(', ') ?: '-' }}</td>
                    <td class="text-center">{{ ucfirst($w->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 50%; font-size: 8pt; margin-top: 10px;">
        <tr>
            <td><strong>Total Destinasi Terdata</strong></td>
            <td>: {{ $totalWisata }} Objek Wisata</td>
        </tr>
        <tr>
            <td><strong>Klasifikasi Kategori</strong></td>
            <td>: {{ $kategoris->count() }} Kategori Aktif</td>
        </tr>
    </table>

    <!-- Tanda Tangan Pejabat Struktural -->
    <table class="ttd-table">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center; font-size: 9pt;">
                Kota Banjarbaru, {{ $tanggalCetak }}<br>
                <strong>Kepala Dinas Kepemudaan, Olahraga,<br>Kebudayaan dan Pariwisata Kota Banjarbaru</strong>
                <br><br><br><br>
                <u><strong>Noor Purbani Sukma Alamsyah, ST.</strong></u><br>
                NIP. 19691217 200701 1 018
            </td>
        </tr>
    </table>

</body>
</html>
