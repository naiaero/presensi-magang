<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Presensi Magang - {{ $user->name }}</title>
    <style>
        @page {
            margin: 30px 35px;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.4;
        }
        
        .document-title {
            text-align: center;
            margin-bottom: 25px;
        }
        .document-title h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.5px;
            text-decoration: underline;
            color: #0f172a;
        }
        .document-title p {
            font-size: 9.5pt;
            color: #64748b;
            margin: 4px 0 0 0;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
            font-size: 10pt;
        }
        .meta-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .meta-label {
            width: 24%;
            color: #334155;
            font-weight: bold;
        }
        .meta-colon {
            width: 3%;
            text-align: center;
            color: #334155;
        }
        .meta-value {
            width: 73%;
            color: #0f172a;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }
        .data-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 6px;
            border: 1px solid #1e3a8a;
            text-align: center;
            font-size: 8.5pt;
            letter-spacing: 0.3px;
        }
        .data-table td {
            padding: 8px 6px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 8.5pt;
            font-weight: bold;
            border-radius: 4px;
            text-align: center;
        }
        .badge-hadir {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-telat {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-izin {
            background-color: #dbeafe;
            color: #1e40af;
        }
    </style>
</head>
<body>

    <!-- Judul Dokumen -->
    <div class="document-title">
        <h2>Riwayat Presensi Magang</h2>
    </div>

    <!-- Metadata User -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Nama Peserta</td>
            <td class="meta-colon">:</td>
            <td class="meta-value"><strong>{{ $user->name }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">Asal Instansi/Kampus</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $user->institution ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Jurusan/Prodi</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $user->major ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Periode Magang</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">
                @if($user->start_date && $user->end_date)
                    {{ \Carbon\Carbon::parse($user->start_date)->translatedFormat('d F Y') }} s.d {{ \Carbon\Carbon::parse($user->end_date)->translatedFormat('d F Y') }}
                @elseif($user->start_date)
                    {{ \Carbon\Carbon::parse($user->start_date)->translatedFormat('d F Y') }}
                @elseif($user->end_date)
                    s.d {{ \Carbon\Carbon::parse($user->end_date)->translatedFormat('d F Y') }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="meta-label">Total Kehadiran</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ count($attendances) }} Hari</td>
        </tr>
    </table>

    <!-- Tabel Data Presensi -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 27%;">Hari & Tanggal</th>
                <th style="width: 15%;">Jam Masuk</th>
                <th style="width: 15%;">Jam Pulang</th>
                <th style="width: 14%;">Status</th>
                <th style="width: 23%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d F Y') }}</td>
                    <td class="text-center">
                        {{ $item->time_in ? \Carbon\Carbon::parse($item->time_in)->format('H:i') . ' WITA' : '-' }}
                    </td>
                    <td class="text-center">
                        {{ $item->time_out ? \Carbon\Carbon::parse($item->time_out)->format('H:i') . ' WITA' : '-' }}
                    </td>
                    <td class="text-center">
                        @if($item->status == 'Hadir')
                            <span class="badge badge-hadir">Hadir</span>
                        @elseif($item->status == 'Telat')
                            <span class="badge badge-telat">Terlambat</span>
                        @else
                            <span class="badge badge-izin">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td>
                        {{ $item->early_leave_reason ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #94a3b8;">
                        Tidak ada riwayat presensi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
