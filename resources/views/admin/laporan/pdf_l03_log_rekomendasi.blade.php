<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>L-03 Laporan Log Rekomendasi Pengunjung</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 8.5pt; color: #1f2937; margin: 20px; }
        .title { text-align: center; font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .subtitle { text-align: center; font-size: 8.5pt; color: #4b5563; margin-bottom: 15px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 15px; }
        table.data th, table.data td { border: 1px solid #9ca3af; padding: 5px 7px; }
        table.data th { background-color: #f3f4f6; font-size: 8pt; text-transform: uppercase; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .section-heading { font-weight: bold; font-size: 9pt; color: #111827; margin-top: 10px; }
        .ttd-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
    </style>
</head>
<body>

    @include('admin.laporan.kop_surat')

    <div class="title">LAPORAN RIWAYAT & LOG AKTIVITAS REKOMENDASI SISTEM</div>
    <div class="subtitle">Nomor Dokumen: 556 / 03 / LOG-CBF / DISPORABUDPAR / {{ date('Y') }}</div>

    <div class="section-heading">A. Ringkasan Objek Wisata yang Paling Sering Direkomendasikan Sistem</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Objek Wisata Rekomendasi</th>
                <th style="width: 100px;">Total Rekomendasi</th>
                <th style="width: 130px;">Rerata Similarity Score</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapWisata as $i => $rw)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td style="font-weight: bold;">{{ $rw->wisataRekomendasi->nama_wisata ?? '-' }}</td>
                    <td class="text-center font-bold">{{ $rw->total }} kali</td>
                    <td class="text-center font-bold" style="color: #065f46;">{{ number_format($rw->avg_score, 4) }} ({{ round($rw->avg_score * 100, 1) }}%)</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Belum ada data rekapitulasi.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-heading" style="margin-top: 15px;">B. Rincian Log Aktivitas Rekomendasi Terakhir</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 100px;">Waktu Transaksi</th>
                <th>Wisata yang Dibuka</th>
                <th>Hasil Rekomendasi Serupa</th>
                <th style="width: 80px;">Skor CBF</th>
                <th style="width: 90px;">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs->take(15) as $i => $log)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="text-center">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->wisataAsal->nama_wisata ?? '-' }}</td>
                    <td style="font-weight: bold;">{{ $log->wisataRekomendasi->nama_wisata ?? '-' }}</td>
                    <td class="text-center font-bold">{{ number_format($log->similarity_score, 4) }}</td>
                    <td class="text-center" style="font-family: monospace;">{{ $log->ip_address ?: '127.0.0.1' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Belum ada transaksi log.</td></tr>
            @endforelse
        </tbody>
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
