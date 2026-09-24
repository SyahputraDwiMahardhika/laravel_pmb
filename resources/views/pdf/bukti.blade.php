<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bukti Pendaftaran - {{ $pendaftaran->nomor_pendaftaran }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1e293b; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; color: #2563eb; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data td { padding: 4px 6px; vertical-align: top; }
        table.data td.label { width: 200px; font-weight: bold; }
        .foto-box { text-align: center; }
        .foto-box img { width: 120px; height: 150px; object-fit: cover; border: 1px solid #cbd5e1; }
        .status { display: inline-block; padding: 3px 10px; border-radius: 4px; color: #fff; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PMB ONLINE</h2>
        <p>Bukti Pendaftaran Mahasiswa Baru</p>
    </div>

    <table class="data">
        <tr>
            <td style="width:75%;">
                <table class="data">
                    <tr><td class="label">Nomor Pendaftaran</td><td>: {{ $pendaftaran->nomor_pendaftaran }}</td></tr>
                    <tr><td class="label">Nama Lengkap</td><td>: {{ $pendaftaran->nama_lengkap }}</td></tr>
                    <tr><td class="label">NIK</td><td>: {{ $pendaftaran->nik }}</td></tr>
                    <tr><td class="label">Tempat, Tanggal Lahir</td><td>: {{ $pendaftaran->tempat_lahir }}, {{ $pendaftaran->tanggal_lahir->format('d-m-Y') }}</td></tr>
                    <tr><td class="label">Jenis Kelamin</td><td>: {{ $pendaftaran->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                    <tr><td class="label">Agama</td><td>: {{ $pendaftaran->religion->name }}</td></tr>
                </table>
            </td>
            <td class="foto-box">
                @if($pendaftaran->foto && file_exists(public_path('storage/'.$pendaftaran->foto)))
                    <img src="{{ public_path('storage/'.$pendaftaran->foto) }}">
                @else
                    <div style="border:1px solid #cbd5e1; width:120px; height:150px; line-height:150px; text-align:center; color:#94a3b8;">Tanpa Foto</div>
                @endif
            </td>
        </tr>
    </table>

    <table class="data">
        <tr><td class="label">Nomor HP</td><td>: {{ $pendaftaran->nomor_hp }}</td></tr>
        <tr><td class="label">Email</td><td>: {{ $pendaftaran->email }}</td></tr>
        <tr><td class="label">Alamat</td><td>: {{ $pendaftaran->alamat }}, {{ $pendaftaran->kelurahan }}, {{ $pendaftaran->kecamatan }}</td></tr>
        <tr><td class="label">Kabupaten/Kota</td><td>: {{ $pendaftaran->regency->name }}</td></tr>
        <tr><td class="label">Provinsi</td><td>: {{ $pendaftaran->province->name }}</td></tr>
        <tr><td class="label">Asal Sekolah</td><td>: {{ $pendaftaran->asal_sekolah }} ({{ $pendaftaran->jurusan_asal_sekolah }})</td></tr>
        <tr><td class="label">Program Studi</td><td>: {{ $pendaftaran->programStudi->nama }}</td></tr>
        <tr><td class="label">Jalur Pendaftaran</td><td>: {{ $pendaftaran->jalur_pendaftaran }}</td></tr>
        <tr><td class="label">Tanggal Pendaftaran</td><td>: {{ $pendaftaran->created_at->format('d F Y') }}</td></tr>
        <tr><td class="label">Status</td><td>:
            @php
                $color = match($pendaftaran->status_pendaftaran) {
                    'Menunggu' => '#f59e0b', 'Diverifikasi' => '#3b82f6',
                    'Diterima' => '#22c55e', 'Ditolak' => '#ef4444', default => '#94a3b8',
                };
            @endphp
            <span class="status" style="background:{{ $color }};">{{ $pendaftaran->status_pendaftaran }}</span>
        </td></tr>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d F Y H:i') }} WIB
    </div>
</body>
</html>
