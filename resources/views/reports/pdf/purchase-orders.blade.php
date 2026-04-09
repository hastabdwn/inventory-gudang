<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 9px; color: #111; }
    .header { padding: 12px 16px; border-bottom: 2px solid #1d4ed8; margin-bottom: 12px; }
    .header h1 { font-size: 14px; font-weight: bold; color: #1d4ed8; }
    .header p { font-size: 9px; color: #555; margin-top: 2px; }
    .meta { padding: 0 16px 10px; font-size: 9px; color: #666; }
    table { width: calc(100% - 32px); margin: 0 16px; border-collapse: collapse; }
    th { background: #1d4ed8; color: white; padding: 5px 6px; text-align: left; font-size: 9px; }
    td { padding: 4px 6px; border-bottom: 1px solid #e5e7eb; font-size: 9px; }
    tr:nth-child(even) td { background: #f9fafb; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .footer { margin-top: 12px; padding: 8px 16px; font-size: 8px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
</style>
</head>
<body>
<div class="header">
    <h1>Laporan Purchase Order</h1>
    <p>{{ config('app.name') }} &mdash; Dicetak: {{ now()->format('d M Y H:i') }}</p>
</div>
<div class="meta">
    Total PO: {{ $data->count() }} &nbsp;|&nbsp;
    Total Nilai: Rp {{ number_format($data->whereNotIn('status',['cancelled'])->sum('total_amount'), 0, ',', '.') }}
</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No. PO</th>
            <th>Tgl Order</th>
            <th>Supplier</th>
            <th>Gudang</th>
            <th class="text-right">Total Nilai</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $po)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $po->po_number }}</td>
            <td>{{ $po->order_date->format('d/m/Y') }}</td>
            <td>{{ $po->supplier->name }}</td>
            <td>{{ $po->warehouse->name }}</td>
            <td class="text-right">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
            <td class="text-center">
                @php
                    $sl = match($po->status) {
                        'draft'            => 'Draft',
                        'waiting_approval' => 'Menunggu',
                        'approved'         => 'Disetujui',
                        'partial'          => 'Sebagian',
                        'completed'        => 'Selesai',
                        'cancelled'        => 'Dibatalkan',
                        default            => $po->status,
                    };
                @endphp
                {{ $sl }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">Dicetak: {{ now()->format('d M Y H:i:s') }}</div>
</body>
</html>