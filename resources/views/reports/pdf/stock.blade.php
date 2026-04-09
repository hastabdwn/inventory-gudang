<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 10px; color: #111; }
    .header { padding: 12px 16px; border-bottom: 2px solid #1d4ed8; margin-bottom: 12px; }
    .header h1 { font-size: 14px; font-weight: bold; color: #1d4ed8; }
    .header p { font-size: 9px; color: #555; margin-top: 2px; }
    .meta { padding: 0 16px 10px; font-size: 9px; color: #666; }
    table { width: 100%; border-collapse: collapse; margin: 0 16px; width: calc(100% - 32px); }
    th { background: #1d4ed8; color: white; padding: 5px 6px; text-align: left; font-size: 9px; }
    td { padding: 4px 6px; border-bottom: 1px solid #e5e7eb; font-size: 9px; }
    tr:nth-child(even) td { background: #f9fafb; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .badge-normal { background: #dcfce7; color: #166534; padding: 1px 4px; border-radius: 3px; }
    .badge-low    { background: #fef9c3; color: #854d0e; padding: 1px 4px; border-radius: 3px; }
    .badge-empty  { background: #fee2e2; color: #991b1b; padding: 1px 4px; border-radius: 3px; }
    .footer { margin-top: 12px; padding: 8px 16px; font-size: 8px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
</style>
</head>
<body>
<div class="header">
    <h1>Laporan Posisi Stok</h1>
    <p>{{ config('app.name') }} &mdash; Dicetak: {{ now()->format('d M Y H:i') }}</p>
</div>
<div class="meta">
    Total record: {{ $data->count() }} &nbsp;|&nbsp;
    Total stok: {{ number_format($data->sum('quantity')) }} &nbsp;|&nbsp;
    Stok rendah/habis: {{ $data->filter(fn($s) => $s->quantity <= $s->item->min_stock)->count() }}
</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Gudang</th>
            <th class="text-right">Stok</th>
            <th class="text-right">Min</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $stock)
        @php
            $status = $stock->quantity == 0 ? 'Habis'
                : ($stock->quantity <= $stock->item->min_stock ? 'Rendah' : 'Normal');
            $badge = $stock->quantity == 0 ? 'badge-empty'
                : ($stock->quantity <= $stock->item->min_stock ? 'badge-low' : 'badge-normal');
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $stock->item->code }}</td>
            <td>{{ $stock->item->name }}</td>
            <td>{{ $stock->item->category->name }}</td>
            <td>{{ $stock->warehouse->name }}</td>
            <td class="text-right">{{ number_format($stock->quantity) }}</td>
            <td class="text-right">{{ number_format($stock->item->min_stock) }}</td>
            <td class="text-center"><span class="{{ $badge }}">{{ $status }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">
    Laporan ini dibuat otomatis oleh sistem. Tanggal cetak: {{ now()->format('d M Y H:i:s') }}
</div>
</body>
</html>