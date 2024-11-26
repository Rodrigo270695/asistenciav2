<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class ReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents, WithTitle
{

    protected $assists;

    public function __construct($assists)
    {
        $this->assists = $assists;
    }

    public function collection()
    {
        return $this->assists;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Fecha',
            'Trabajador',
            'Tipo Documento',
            'Documento',
            'Zonal',
            'Punto de Venta',
            'Ingreso Laboral',
            'Estado Ingreso',
            'Receso Salida',
            'Estado Receso Salida',
            'Receso Ingreso',
            'Estado Receso Ingreso',
            'Hora Salida',
            'Estado Salida',
        ];
    }

    public function map($assist): array
    {
        return [
            $assist->id,
            $assist->current_date,
            $assist->worker->lastname . ' ' . $assist->worker->name,
            $assist->worker->document_type,
            $assist->worker->num_document,
            $assist->worker->pdv->zonal->name,
            $assist->worker->pdv->spot,
            $assist->hi,
            $assist->status_entry === 2 ? 'Tolerancia' : ($assist->status_entry === 1 ? 'OK' : 'Tarde'),
            $assist->rs,
            $assist->status_foodout === 1 ? 'OK' : 'Tarde',
            $assist->ri,
            $assist->status_foodentry === 2 ? 'Tolerancia' : ($assist->status_foodentry === 1 ? 'OK' : 'Tarde'),
            $assist->hs,
            $assist->status_out === 1 ? 'OK' : 'Tarde',
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

                // Aplicar bordes a toda la tabla
                $sheet->getStyle('A1:O' . ($this->assists->count() + 1))->applyFromArray([
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
        return 'Reporte de Asistencias';
    }
}
