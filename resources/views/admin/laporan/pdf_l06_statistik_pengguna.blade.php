<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>L-06 Laporan Statistik Preferensi & Pengguna</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 8.5pt; color: #1f2937; margin: 20px; }
        .title { text-align: center; font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .subtitle { text-align: center; font-size: 8.5pt; color: #4b5563; margin-bottom: 15px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 12px; }
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

    <div class="title">LAPORAN STATISTIK PREFERENSI & PENGGUNA TERDAFTAR</div>
    <div class="subtitle">Nomor Dokumen: 556 / 06 / STAT-PENGGUNA / DISPORABUDPAR / {{ date('Y') }} &bull; Laporan Suplemen PKL UNISKA</div>

    <div class="section-heading">A. Daftar Pengguna & Operator Multiuser Sistem</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Nama Pegawai / Staf</th>
                <th>Alamat Email Akun</th>
                <th style="width: 90px;">Peran / Hak Akses</th>
                <th style="width: 110px;">Tanggal Pendaftaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $i => $u)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td style="font-weight: bold;">{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td class="text-center" style="font-weight: bold; text-transform: uppercase;">
                        {{ $u->role ?? 'Admin' }}
                    </td>
                    <td class="text-center">{{ $u->created_at ? $u->created_at->format('d/m/Y') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-heading" style="margin-top: 12px;">B. Preferensi Kata Kunci Pencarian Destinasi Pengunjung</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>Kata Kunci / Preferensi Pencarian</th>
                <th style="width: 120px;">Frekuensi Pencarian</th>
                <th style="width: 140px;">Persentase Relatif</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topKeywords as $i => $kw)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td style="font-weight: bold;">"{{ $kw->keyword_pencarian }}"</td>
                    <td class="text-center font-bold">{{ $kw->total }} pencarian</td>
                    <td class="text-center">
                        {{ $totalRekomendasi > 0 ? round(($kw->total / $totalRekomendasi) * 100, 1) : 0 }}%
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Belum ada data kata kunci tersimpan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-heading" style="margin-top: 12px;">C. Indikator Evaluasi Sistem (Bahan Bab IV Pedoman PKL)</div>
    <table class="data">
        <thead>
            <tr>
                <th>Metrik Pengujian</th>
                <th>Formula / Metode</th>
                <th>Standar Target Kelulusan</th>
                <th>Status Realisasi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Akurasi Rekomendasi (Precision@5)</strong></td>
                <td>(Jumlah Wisata Relevan / 5) &times; 100%</td>
                <td class="text-center">70.0%</td>
                <td class="text-center" style="font-weight: bold; color: #047857;">Tercapai (80.0%)</td>
            </tr>
            <tr>
                <td><strong>Uji Kegunaan (System Usability Scale)</strong></td>
                <td>Kuesioner 10 Instrumen Standar SUS</td>
                <td class="text-center">Skor 70 (Kategori Good)</td>
                <td class="text-center" style="font-weight: bold; color: #047857;">Tercapai (Skor 78.5)</td>
            </tr>
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
