<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DistributionReportExport implements
    FromCollection, WithHeadings, WithMapping,
    WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(private \Illuminate\Support\Collection $data) {}

    public function collection(): \Illuminate\Support\Collection
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'Laporan Distribusi';
    }

    public function headings(): array
    {
        return [
            'No', 'No. Distribusi', 'Tanggal', 'Gudang Asal',
            'Tujuan', 'Penerima', 'Total Item', 'Status', 'Dibuat Oleh',
        ];
    }

    public function map($row): array
    {
        static $i = 0;
        $i++;

        $statusLabel = match($row->status) {
            'draft'     => 'Draft',
            'issued'    => 'Diterbitkan',
            'cancelled' => 'Dibatalkan',
            default     => $row->status,
        };

        return [
            $i,
            $row->dist_number,
            $row->dist_date->format('d/m/Y'),
            $row->warehouse->name,
            $row->destination,
            $row->recipient ?? '-',
            $row->items->count(),
            $statusLabel,
            $row->issuer->name,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '1d4ed8']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}