<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan {{ ucfirst($jenis_laporan) }} Magang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 18px;
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .header h2 {
            font-size: 16px;
            margin: 0 0 5px 0;
            color: #34495e;
        }
        
        .header .subtitle {
            font-size: 14px;
            color: #7f8c8d;
            margin: 5px 0;
        }
        
        .info-section {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #495057;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 30%;
            padding: 3px 0;
            font-weight: bold;
            color: #495057;
        }
        
        .info-value {
            display: table-cell;
            padding: 3px 0;
            color: #6c757d;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .data-table th,
        .data-table td {
            border: 1px solid #dee2e6;
            padding: 8px 6px;
            text-align: left;
            vertical-align: top;
        }
        
        .data-table th {
            background-color: #343a40;
            color: white;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
        }
        
        .data-table td {
            font-size: 10px;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .data-table tr:hover {
            background-color: #e9ecef;
        }
        
        .summary-section {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border: 1px solid #b3d9ff;
        }
        
        .summary-section h3 {
            margin: 0 0 10px 0;
            color: #0066cc;
            font-size: 14px;
        }
        
        .total-count {
            font-size: 16px;
            font-weight: bold;
            color: #0066cc;
            text-align: center;
            padding: 10px;
            background-color: white;
            border-radius: 3px;
            margin-top: 10px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-aktif {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        @page {
            margin: 2cm;
        }
    </style>
</head>
<body>
    {{-- @dd($data); --}}
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN {{ strtoupper($jenis_laporan) }} MAGANG</h1>
        <h2>Aplikasi Pengolahan Data Peserta Magang</h2>
        <div class="subtitle">Dicetak pada: {{ $tanggal_cetak }}</div>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <h3>Informasi Laporan</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Jenis Laporan:</div>
                <div class="info-value">{{ ucfirst($jenis_laporan) }} Magang</div>
            </div>
            <div class="info-row">
                <div class="info-label">Periode Tanggal:</div>
                <div class="info-value">
                    {{ $filter_tanggal_mulai ? \Carbon\Carbon::parse($filter_tanggal_mulai)->format('d/m/Y') : 'Tidak ditentukan' }}
                    s.d 
                    {{ $filter_tanggal_selesai ? \Carbon\Carbon::parse($filter_tanggal_selesai)->format('d/m/Y') : 'Tidak ditentukan' }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Filter Sekolah:</div>
                <div class="info-value">{{ $filter_sekolah ?? 'Semua Sekolah' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Total Data:</div>
                <div class="info-value">{{ $total_data }} {{ $jenis_laporan }}</div>
                {{-- <div class="info-value">{{ $total_data }} {{ $jenis_laporan === 'peserta' ? 'Peserta' : 'Alumni' }}</div> --}}
            </div>
        </div>
    </div>

    <!-- Data Table -->
    @if($total_data > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 25%;">Nama</th>
                    <th style="width: 20%;">Jurusan</th>
                    <th style="width: 25%;">Sekolah</th>
                    <th style="width: 11%;">Tanggal Mulai</th>
                    <th style="width: 11%;">Tanggal Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data_laporan as $index => $data)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $data['nama'] }}</td>
                        <td>{{ $data['jurusan'] }}</td>
                        <td>{{ $data['sekolah'] }}</td>
                        <td style="text-align: center;">{{ \Carbon\Carbon::parse($data['tgl_mulai'])->format('d/m/Y') }}</td>
                        <td style="text-align: center;">{{ \Carbon\Carbon::parse($data['tgl_selesai'])->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <strong>Tidak ada data {{ $jenis_laporan }} magang yang ditemukan berdasarkan filter yang diterapkan.</strong>
            {{-- <strong>Tidak ada data {{ $jenis_laporan === 'peserta' ? 'peserta' : 'alumni' }} magang yang ditemukan berdasarkan filter yang diterapkan.</strong> --}}
        </div>
    @endif

    <!-- Summary Section -->
    <div class="summary-section">
        <h3>Ringkasan Laporan</h3>
        <div class="total-count">
            Total {{ $jenis_laporan }} Magang: {{ $total_data }}
            {{-- Total {{ $jenis_laporan === 'peserta' ? 'Peserta' : 'Alumni' }} Magang: {{ $total_data }} --}}
        </div>
        
        @if($filter_sekolah)
            <div style="text-align: center; margin-top: 10px; font-size: 11px;">
                <strong>Sekolah:</strong> {{ $filter_sekolah ?? 'Semua Sekolah' }}
            </div>
        @endif
        
        @if($filter_tanggal_mulai || $filter_tanggal_selesai)
            <div style="text-align: center; margin-top: 5px; font-size: 11px;">
                <strong>Periode:</strong> 
                {{ $filter_tanggal_mulai ? \Carbon\Carbon::parse($filter_tanggal_mulai)->format('d/m/Y') : 'Tidak ditentukan' }}
                s.d 
                {{ $filter_tanggal_selesai ? \Carbon\Carbon::parse($filter_tanggal_selesai)->format('d/m/Y') : 'Tidak ditentukan' }}
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>Laporan ini dibuat secara otomatis oleh Aplikasi Pengolahan Data Peserta Magang</div>
        <div>Dicetak pada: {{ $tanggal_cetak }}</div>
    </div>
</body>
</html>