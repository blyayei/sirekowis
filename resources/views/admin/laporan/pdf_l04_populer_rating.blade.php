<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>L-04 Laporan Destinasi Terpopuler & Rating</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 9pt; color: #1f2937; margin: 20px; }
        .title { text-align: center; font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .subtitle { text-align: center; font-size: 8.5pt; color: #4b5563; margin-bottom: 15px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 15px; }
        table.data th, table.data td { border: 1px solid #9ca3af; padding: 6px 8px; }
        table.data th { background-color: #f3f4f6; font-size: 8pt; text-transform: uppercase; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .rank-badge { font-weight: bold; background-color: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 4px; }
        .rating-star { font-family: 'DejaVu Sans', sans-serif; color: #f59e0b; }
        .ttd-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
    </style>
</head>
<body>

    @include('admin.laporan.kop_surat')

    <div class="title">LAPORAN PERINGKAT DESTINASI TERPOPULER & RATING GOOGLE MAPS</div>
    <div class="subtitle">Nomor Dokumen: 556 / 04 / POPULER-RATING / DISPORABUDPAR / {{ date('Y') }}</div>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 50px;">Peringkat</th>
                <th>Nama Objek Wisata</th>
                <th>Kategori</th>
                <th>Kecamatan</th>
                <th style="width: 80px;">Rating</th>
                <th style="width: 100px;">Jumlah Ulasan</th>
                <th style="width: 90px;">Harga Tiket</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wisatas as $i => $w)
                <tr>
                    <td class="text-center">
                        <span class="rank-badge">#{{ $i + 1 }}</span>
                    </td>
                    <td style="font-weight: bold;">{{ $w->nama_wisata }}</td>
                    <td>{{ $w->kategori->nama_kategori }}</td>
                    <td>Kec. {{ $w->kecamatan }}</td>
                    <td class="text-center" style="font-weight: bold; color: #b45309;">
                        <span class="rating-star">&#9733;</span> {{ number_format($w->rating_google, 1) }} / 5.0
                    </td>
                    <td class="text-right">{{ number_format($w->jumlah_review_google) }} ulasan</td>
                    <td class="text-right">{{ $w->formatted_harga }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 60%; font-size: 8.5pt; margin-top: 10px;">
        <tr>
            <td><strong>Rata-rata Rating Wisata Banjarbaru</strong></td>
            <td>: {{ number_format($wisatas->avg('rating_google'), 2) }} / 5.00</td>
        </tr>
        <tr>
            <td><strong>Total Akumulasi Ulasan Wisatawan</strong></td>
            <td>: {{ number_format($wisatas->sum('jumlah_review_google')) }} ulasan terverifikasi</td>
        </tr>
    </table>

    <table class="ttd-table">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center; font-size: 8.5pt;">
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
