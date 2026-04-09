<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseOrderReportExport implements
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
        return 'Laporan PO';
    }

    public function headings(): array
    {
        return [
            'No', 'No. PO', 'Tanggal Order', 'Supplier',
            'Gudang Tujuan', 'Total Item', 'Total Nilai (Rp)', 'Status', 'Dibuat Oleh',
        ];
    }

    public function map($row): array
    {
        static $i = 0;
        $i++;

        $statusLabel = match($row->status) {
            'draft'            => 'Draft',
            'waiting_approval' => 'Menunggu Approval',
            'approved'         => 'Disetujui',
            'partial'          => 'Diterima Sebagian',
            'completed'        => 'Selesai',
            'cancelled'        => 'Dibatalkan',
            default            => $row->status,
        };

        return [
            $i,
            $row->po_number,
            $row->order_date->format('d/m/Y'),
            $row->supplier->name,
            $row->warehouse->name,
            $row->items->count(),
            $row->total_amount,
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