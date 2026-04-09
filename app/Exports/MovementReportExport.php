<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MovementReportExport implements
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
        return 'Mutasi Stok';
    }

    public function headings(): array
    {
        return [
            'No', 'Tanggal', 'Kode Barang', 'Nama Barang',
            'Gudang', 'Tipe', 'Qty', 'Stok Sebelum', 'Stok Sesudah',
            'User', 'Keterangan',
        ];
    }

    public function map($row): array
    {
        static $i = 0;
        $i++;

        $typeLabel = match($row->type) {
            'in'           => 'Masuk',
            'out'          => 'Keluar',
            'transfer_in'  => 'Transfer Masuk',
            'transfer_out' => 'Transfer Keluar',
            'retur'        => 'Retur',
            'adjustment'   => 'Penyesuaian',
            default        => $row->type,
        };

        return [
            $i,
            $row->moved_at->format('d/m/Y H:i'),
            $row->item->code,
            $row->item->name,
            $row->warehouse->name,
            $typeLabel,
            $row->quantity,
            $row->stock_before,
            $row->stock_after,
            $row->user->name,
            $row->notes ?? '-',
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