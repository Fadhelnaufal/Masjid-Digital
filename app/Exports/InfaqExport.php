<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class InfaqExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $infaqs;
    protected $trackingCode;
    protected $rowNumber = 0;

    public function __construct($infaqs, $trackingCode = null)
    {
        $this->infaqs = $infaqs;
        $this->trackingCode = $trackingCode;
    }

    public function collection()
    {
        return $this->infaqs;
    }

    // Header Kolom
    public function headings(): array
    {
        return [
            ['LAPORAN INFAQ MASJID BESAR SAMBIT'], // Baris 1: Judul
            ['Tracking Code: ' . ($this->trackingCode ?? '-')], // Baris 2: Metadata
            [''], // Baris 3: Kosong
            [
                'NO',
                'TANGGAL',
                'KETERANGAN / JENIS PEMASUKAN',
                'NOMINAL (RP)',
                'PELAKSANA',
                'KETERANGAN TAMBAHAN'
            ]
        ];
    }

    // Mapping Data per Baris
    public function map($infaq): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            \Carbon\Carbon::parse($infaq->tanggal)->format('d/m/Y'),
            strtoupper($infaq->keterangan),
            $infaq->nominal,
            'Takmir Masjid',
            '-'
        ];
    }

    // Format Kolom (Agar kolom Nominal otomatis jadi format IDR di Excel)
    public function columnFormats(): array
    {
        return [
            'D' => '#,##0', // Kolom D (Nominal) format angka ribuan
        ];
    }

    // Styling (Bikin Bold, Center, dll)
    public function styles(Worksheet $sheet)
    {
        // Merge cell untuk judul
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Styling Header Tabel (Baris ke-4)
        $styleHeader = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '334155'] // Warna Slate-700
            ],
            'alignment' => ['horizontal' => 'center']
        ];

        $sheet->getStyle('A4:F4')->applyFromArray($styleHeader);

        // Border untuk seluruh data
        $lastRow = $this->infaqs->count() + 4;
        $sheet->getStyle('A4:F' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [];
    }
}