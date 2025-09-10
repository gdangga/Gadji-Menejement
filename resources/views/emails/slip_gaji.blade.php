<!DOCTYPE html>
<html>
<head>
    <title>Arsip Slip Gaji</title>
</head>
<body style="font-family: sans-serif; color: #333;">
    <h2>Arsip Slip Gaji Karyawan</h2>
    <p>
        Halo,
    </p>
    <p>
        Berikut adalah salinan slip gaji untuk karyawan:
    </p>
    <ul>
        <li><strong>Nama Karyawan:</strong> {{ $karyawan->nama_lengkap }}</li>
        <li><strong>Periode:</strong> {{ $periode }}</li>
    </ul>
    <p>
        File PDF terlampir dalam email ini.
    </p>
    <br>
    <p>
        Terima kasih,
        <br>
        Sistem Manajemen Gaji
    </p>
</body>
</html>