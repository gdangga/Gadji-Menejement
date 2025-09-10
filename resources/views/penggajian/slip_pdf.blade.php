<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $karyawan->nama_lengkap }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #eee; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0 0; color: #555; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .details-table td { padding: 8px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .items-table th { background-color: #f9f9f9; font-weight: bold; }
        .text-right { text-align: right; }
        .total-section { float: right; width: 40%; margin-top: 20px; }
        .total-table { width: 100%; }
        .total-table td { padding: 8px; }
        .total-table .label { font-weight: bold; }
        .total-table .amount { font-size: 16px; font-weight: bold; text-align: right; }
        .footer { text-align: center; margin-top: 50px; font-size: 10px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SLIP GAJI KARYAWAN</h1>
            <p>Periode: {{ $periode }}</p>
        </div>

        <table class="details-table">
            <tr>
                <td width="150px"><strong>Nama Karyawan</strong></td>
                <td width="10px">:</td>
                <td>{{ $karyawan->nama_lengkap }}</td>
            </tr>
             <tr>
                <td><strong>Posisi</strong></td>
                <td>:</td>
                <td>{{ $karyawan->posisi ?? '-' }}</td>
            </tr>
             <tr>
                <td><strong>Tanggal Cetak</strong></td>
                <td>:</td>
                <td>{{ now()->format('d F Y') }}</td>
            </tr>
        </table>

        <h4>Rincian Upah Proyek</h4>
        <table class="items-table">
            <thead>
                <tr>
                    <th width="30px">No.</th>
                    <th width="100px">Tanggal</th>
                    <th>Nama Proyek</th>
                    <th class="text-right">Jumlah Upah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proyeks as $index => $proyek)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($proyek->tanggal_pengerjaan)->format('d/m/Y') }}</td>
                    <td>{{ $proyek->nama_proyek }}</td>
                    <td class="text-right">Rp {{ number_format($proyek->jumlah_upah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="total-section">
            <table class="total-table">
                <tr>
                    <td class="label">TOTAL DITERIMA</td>
                    <td class="amount">Rp {{ number_format($slipGaji->total_upah, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        
        <div style="clear: both;"></div>

        <div class="footer">
            Dokumen ini dibuat secara otomatis oleh sistem Manajemen Gaji.
        </div>
    </div>
</body>
</html>