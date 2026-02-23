<?php

namespace App\Exports;

use App\Models\LaporanKondisi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LaporanKondisiExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    protected array $filters;
    protected int $rowNumber = 0;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = LaporanKondisi::with(['wilayah', 'petugas']);

        if (!empty($this->filters['wilayah_id'])) {
            $query->where('wilayah_id', $this->filters['wilayah_id']);
        }

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('tanggal_inspeksi', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('tanggal_inspeksi', '<=', $this->filters['end_date']);
        }

        return $query->orderBy('tanggal_inspeksi', 'desc');
    }

    public function headings(): array
    {
        return ['NO', 'TANGGAL INSPEKSI', 'WILAYAH', 'KECAMATAN', 'PETUGAS', 'CATATAN'];
    }

    public function map($row): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            Carbon::parse($row->tanggal_inspeksi)->format('d/m/Y'),
            $row->wilayah?->nama ?? '-',
            $row->wilayah?->kecamatan ?? '-',
            $row->petugas?->name ?? '-',
            $row->catatan ?? '-',
        ];
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2563EB'],
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet;
                $rows       = $sheet->getHighestRow();
                $lastColumn = 'F';

                //  Periode
                $dateRange = 'Semua Tanggal';
                if (!empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
                    $dateRange = Carbon::parse($this->filters['start_date'])->format('d M Y')
                        . ' s/d '
                        . Carbon::parse($this->filters['end_date'])->format('d M Y');
                } elseif (!empty($this->filters['start_date'])) {
                    $dateRange = 'Mulai ' . Carbon::parse($this->filters['start_date'])->format('d M Y');
                } elseif (!empty($this->filters['end_date'])) {
                    $dateRange = 'Sampai ' . Carbon::parse($this->filters['end_date'])->format('d M Y');
                }

                //  Row 1: Judul
                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->setCellValue('A1', 'LAPORAN KONDISI WILAYAH');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                ]);

                //  Row 2: Periode
                $sheet->mergeCells("A2:{$lastColumn}2");
                $sheet->setCellValue('A2', 'Periode: ' . $dateRange);
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 11],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                ]);

                //  Row 3: Dicetak pada
                $sheet->mergeCells("A3:{$lastColumn}3");
                $sheet->setCellValue('A3', 'Dicetak pada: ' . now()->format('d/m/Y H:i'));
                $sheet->getStyle('A3')->applyFromArray([
                    'font'      => ['size' => 10],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                ]);

                //  Borders
                if ($rows >= 5) {
                    $sheet->getStyle("A5:{$lastColumn}{$rows}")->applyFromArray([
                        'borders'   => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color'       => ['argb' => 'FF000000'],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                }
            },
        ];
    }
}
