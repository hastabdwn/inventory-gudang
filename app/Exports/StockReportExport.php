<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockReportExport implements
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
        return 'Laporan Stok';
    }

    public function headings(): array
    {
        return [
            'No', 'Kode Barang', 'Nama Barang', 'Kategori',
            'Gudang', 'Stok', 'Satuan', 'Stok Minimum', 'Status',
        ];
    }

    public function map($row): array
    {
        static $i = 0;
        $i++;
        $status = $row->quantity == 0 ? 'Habis'
            : ($row->quantity <= $row->item->min_stock ? 'Rendah' : 'Normal');

        return [
            $i,
            $row->item->code,
            $row->item->name,
            $row->item->category->name,
            $row->warehouse->name,
            $row->quantity,
            $row->item->unit->abbreviation,
            $row->item->min_stock,
            $status,
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