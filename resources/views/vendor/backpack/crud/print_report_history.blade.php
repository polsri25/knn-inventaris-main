<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan History Barang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 18px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .header .info {
            font-size: 11px;
            color: #95a5a6;
        }

        .summary {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            border-left: 4px solid #3498db;
        }

        .summary h3 {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .summary-item {
            background: #fff;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #bdc3c7;
        }

        .summary-item .label {
            font-size: 11px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 3px;
        }

        .priority-tinggi { color: #e74c3c; }
        .priority-sedang { color: #f39c12; }
        .priority-rendah { color: #27ae60; }

        .table-container {
            margin-bottom: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        thead {
            background: #34495e;
            color: #fff;
        }

        th, td {
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #bdc3c7;
            font-size: 11px;
        }

        th {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-size: 10px;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        tbody tr:hover {
            background: #e8f4f8;
        }

        .priority-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-tinggi {
            background: #fee;
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        .badge-sedang {
            background: #fff8e1;
            color: #f39c12;
            border: 1px solid #f39c12;
        }

        .badge-rendah {
            background: #e8f5e8;
            color: #27ae60;
            border: 1px solid #27ae60;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-style: italic;
        }

        /* Print Styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container {
                padding: 10px;
                max-width: none;
            }

            .header {
                margin-bottom: 15px;
                padding-bottom: 10px;
            }

            .summary {
                margin-bottom: 15px;
                padding: 10px;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }
        }

        @page {
            size: A4;
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>LAPORAN HISTORY BARANG</h1>
            <h2>Sistem Klasifikasi KNN PT GLOBAL INTERNET DATA</h2>
            <div class="info">
                Dicetak pada: {{ $tanggalCetak }}
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary">
            <h3>Ringkasan Data</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="label">Total Semua Barang</div>
                    <div class="value">{{ number_format($totalSemua) }}</div>
                </div>
                <div class="summary-item">
                    <div class="label">Prioritas Tinggi</div>
                    <div class="value priority-tinggi">{{ number_format($totalTinggi) }}</div>
                </div>
                <div class="summary-item">
                    <div class="label">Prioritas Sedang</div>
                    <div class="value priority-sedang">{{ number_format($totalSedang) }}</div>
                </div>
                <div class="summary-item">
                    <div class="label">Prioritas Rendah</div>
                    <div class="value priority-rendah">{{ number_format($totalRendah) }}</div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-container">
            @if($items->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 15%">Tanggal</th>
                            <th style="width: 15%">Gudang</th>
                            <th style="width: 20%">Jenis Barang</th>
                            <th style="width: 10%">Jumlah</th>
                            <th style="width: 12%">Prioritas</th>
                            <th style="width: 10%">Nilai K</th>
                            <th style="width: 10%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td>{{ $item->gudang->nama ?? '-' }}</td>
                            <td>{{ $item->jenisbarang->nama ?? '-' }}</td>
                            <td style="text-align: right">{{ number_format($item->jumlah) }}</td>
                            <td>
                                <span class="priority-badge badge-{{ strtolower($item->prioritas) }}">
                                    {{ ucfirst($item->prioritas) }}
                                </span>
                            </td>
                            <td style="text-align: center">
                                {{ $item->knnClassification->nilai_k ?? '-' }}
                            </td>
                            <td style="text-align: center">
                                @if($item->knnClassification == null)
                                    <span class="badge badge-secondary">Data Master</span>
                                @elseif($item->knnClassification != null)
                                    <span class="badge badge-success">Sudah Diklasifikasi</span>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    <p>Tidak ada data untuk ditampilkan</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ $tanggalCetak }}</p>
            <p>Total {{ $items->count() }} record data history barang</p>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            // Delay printing to ensure CSS is fully loaded
            setTimeout(function() {
                window.print();
            }, 1000);
        };
    </script>
</body>
</html>
