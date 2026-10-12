<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>L-05 Laporan Rekapitulasi Fasilitas Wisata Banjarbaru</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 8pt; color: #1f2937; margin: 15px; }
        .title { text-align: center; font-size: 11pt; font-weight: bold; text-transform: uppercase; margin-bottom: 2px; }
        .subtitle { text-align: center; font-size: 8pt; color: #4b5563; margin-bottom: 12px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 12px; }
        table.data th, table.data td { border: 1px solid #9ca3af; padding: 4px 6px; font-size: 7.5pt; }
        table.data th { background-color: #f3f4f6; text-transform: uppercase; text-align: center; }
        .text-center { text-align: center; }
        .has-facility { background-color: #ecfdf5; color: #047857; font-weight: bold; text-align: center; font-family: 'DejaVu Sans', sans-serif; }
        .check-symbol { font-family: 'DejaVu Sans', sans-serif; }
        .no-facility { color: #9ca3af; text-align: center; }
        .ttd-table { width: 100%; margin-top: 25px; border-collapse: collapse; }
    </style>
</head>
<body>

    @include('admin.laporan.kop_surat')

    <div class="title">LAPORAN REKAPITULASI FASILITAS & SARANA PRASARANA WISATA</div>
    <div class="subtitle">Nomor Dokumen: 556 / 05 / REKAP-FASILITAS / DISPORABUDPAR / {{ date('Y') }}</div>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 20px;">No</th>
                <th style="width: 140px; text-align: left;">Nama Destinasi Wisata</th>
                @foreach($fasilitases as $f)
                    <th style="font-size: 7pt;">{{ $f->nama_fasilitas }}</th>
                @endforeach
                <th style="width: 45px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wisatas as $i => $w)
                @php 
                    $wFasIds = $w->fasilitases->pluck('id')->toArray();
                @endphp
                <tr>
                    <td class="text-center font-bold">{{ $i + 1 }}</td>
                    <td style="font-weight: bold;">{{ $w->nama_wisata }}</td>
                    @foreach($fasilitases as $f)
                        @if(in_array($f->id, $wFasIds))
                            <td class="has-facility">&#10003;</td>
                        @else
                            <td class="no-facility">-</td>
                        @endif
                    @endforeach
                    <td class="text-center font-bold">{{ count($wFasIds) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f9fafb; font-weight: bold;">
                <td colspan="2" class="text-center">TOTAL PENYEDIAAN KOTA</td>
                @foreach($fasilitases as $f)
                    <td class="text-center">
                        {{ $wisatas->filter(fn($w) => $w->fasilitases->contains('id', $f->id))->count() }}
                    </td>
                @endforeach
                <td class="text-center">{{ $wisatas->sum(fn($w) => $w->fasilitases->count()) }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="font-size: 7.5pt; color: #4b5563; margin-top: 5px;">
        <em>Tanda centang (<span class="check-symbol">&#10003;</span>) menunjukkan fasilitas sarana dan prasarana tersedia aktif serta terverifikasi oleh petugas Disporabudpar.</em>
    </div>

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
