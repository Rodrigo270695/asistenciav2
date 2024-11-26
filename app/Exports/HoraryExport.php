<?php

namespace App\Exports;

use App\Models\DetailHorary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class HoraryExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, WithTitle
{
    protected $detailHoraries;

    public function __construct($detailHoraries)
    {
        $this->detailHoraries = $detailHoraries;
    }

    public function collection()
    {
        return $this->detailHoraries;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Week',
            'Hora Inicio',
            'Receso Salida',
            'Receso Entrada',
            'Hora Salida',
        ];
    }

    public function map($detailHorary): array
    {
        return [
            $detailHorary->id,
            $detailHorary->horary->name,
            $detailHorary->week,
            $detailHorary->hi,
            $detailHorary->rs,
            $detailHorary->ri,
            $detailHorary->hs,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4CAF50'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->setAutoFilter($sheet->calculateWorksheetDimension());

                $sheet->getStyle('A1:G' . ($this->detailHoraries->count() + 1))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }

    public function title(): string
    {
        return 'Detail Horaries';
    }
}
