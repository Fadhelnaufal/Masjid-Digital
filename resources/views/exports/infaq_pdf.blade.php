<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Infaq - Masjid Besar Sambit {{ now()->format('d/m/Y') }}</title>
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            color: #000;
            line-height: 1.4;
            font-size: 9pt;
        }

        /* Watermark Style */
        #watermark {
            position: fixed;
            top: 45%;
            left: 5%;
            transform: rotate(-45deg);
            transform-origin: 50% 50%;
            opacity: .05;
            font-size: 40pt;
            font-weight: bold;
            color: #000;
            width: 100%;
            text-align: center;
            z-index: -1000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .title {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed;
        }

        th {
            border: 1px solid #000;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
            background-color: #f8fafc;
        }

        td {
            border: 1px solid #000;
            padding: 6px 5px;
            font-size: 8.5pt;
            vertical-align: top;
            word-wrap: break-word;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* Column Widths */
        .col-no { width: 25px; }
        .col-tgl { width: 65px; }
        .col-ket { width: auto; }
        .col-jml { width: 110px; }
        .col-pelaksana { width: 85px; }
        .col-keterangan { width: 50px; }

        .currency-symbol { float: left; padding-left: 2px; }
        .currency-value { float: right; padding-right: 2px; }
        .clearfix { clear: both; }

        /* Signature Section */
        .signature-wrapper {
            margin-top: 30px;
            width: 100%;
        }

        .signature-table {
            border: none !important;
        }

        .signature-table td {
            border: none !important;
            text-align: center;
            width: 50%;
            padding: 10px;
            font-size: 10pt;
        }

        .spacer-ttd { height: 65px; }
        .name-ttd { font-weight: bold; text-decoration: underline; }

        /* Security Footer */
        .security-footer {
            margin-top: 40px;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
            font-family: 'Courier', monospace;
            font-size: 7pt;
            color: #475569;
        }
        
        .qr-placeholder {
            float: right;
            text-align: right;
        }
    </style>
</head>

<body>
    <div id="watermark">DOKUMEN ASLI - MASJID BESAR SAMBIT</div>

    <div class="header">
        <div class="title">LAPORAN INFAQ MASJID BESAR SAMBIT</div>
        <div class="subtitle">TAHUN ANGGARAN {{ date('Y') }}/{{ date('Y') + 1 }}</div>
        <div style="font-size: 8pt; font-weight: normal; margin-top: 5px;">Alamat: Jl. Arif Rahman Hakim, Tamansari, Sambit, Kec. Sambit, Kabupaten Ponorogo, Jawa Timur 63474</div>
    </div>

    <p class="font-bold" style="text-decoration: underline;">A. RINCIAN PEMASUKAN KAS</p>

    <table>
        <thead>
            <tr>
                <th class="col-no">No.</th>
                <th class="col-tgl">Tgl.</th>
                <th class="col-ket">Keterangan / Jenis Pemasukan</th>
                <th class="col-jml">Jumlah (Rp)</th>
                <th class="col-pelaksana">Pelaksana</th>
                <th class="col-keterangan">Ket.</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($infaqs as $infaq)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($infaq->tanggal)->format('d/m/Y') }}</td>
                    <td style="text-transform: uppercase;">{{ $infaq->keterangan }}</td>
                    <td>
                        <span class="currency-symbol">Rp</span>
                        <span class="currency-value">{{ number_format($infaq->nominal, 0, ',', '.') }}</span>
                        <div class="clearfix"></div>
                    </td>
                    <td class="text-center">Takmir Masjid</td>
                    <td class="text-center">-</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8fafc;">
                <th colspan="3" class="text-right">TOTAL SALDO TERKUMPUL (SUM)</th>
                <th class="text-right">
                    <span class="currency-symbol">Rp</span>
                    <span class="currency-value" style="font-weight: bold;">{{ number_format($infaqs->sum('nominal'), 0, ',', '.') }}</span>
                    <div class="clearfix"></div>
                </th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>

    <div class="signature-wrapper">
        <table class="signature-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    <strong>Ketua Takmir Masjid Besar Sambit</strong>
                    <div class="spacer-ttd"></div>
                    <span class="name-ttd">( .............................................. )</span>
                    <br><span style="font-size: 8pt;">NIAT. .........................</span>
                </td>

                <td>
                    Sambit, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    <strong>Bendahara Umum</strong>
                    <div class="spacer-ttd"></div>
                    <span class="name-ttd">( .............................................. )</span>
                    <br><span style="font-size: 8pt;">NIAT. .........................</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="security-footer">
        <div class="qr-placeholder">
            SISTEM INFORMASI KAS MASJID v1.0<br>
            IP: {{ request()->ip() }}
        </div>
        <strong>SECURE TRACKING CODE:</strong><br>
        {{ $trackingCode ?? 'N/A' }} / GENERATED BY: {{ strtoupper(auth()->user()->name ?? 'SYSTEM') }} / {{ date('d-m-Y H:i:s') }}
    </div>
</body>

</html>