<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReturnReportExport implements
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
        return 'Laporan Retur';
    }

    public function headings(): array
    {
        return [
            'No', 'No. Retur', 'Tanggal', 'Supplier',
            'Gudang', 'Alasan', 'Total Item', 'Status', 'Dibuat Oleh',
        ];
    }

    public function map($row): array
    {
        static $i = 0;
        $i++;

        $statusLabel = match($row->status) {
            'draft'     => 'Draft',
            'sent'      => 'Terkirim',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            default     => $row->status,
        };

        return [
            $i,
            $row->return_number,
            $row->return_date->format('d/m/Y'),
            $row->supplier->name,
            $row->warehouse->name,
            $row->reason,
            $row->items->count(),
            $statusLabel,
            $row->creator->name,
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