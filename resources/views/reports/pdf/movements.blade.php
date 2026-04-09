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
    .in   { background: #dcfce7; color: #166534; padding: 1px 4px; border-radius: 3px; }
    .out  { background: #fee2e2; color: #991b1b; padding: 1px 4px; border-radius: 3px; }
    .adj  { background: #fef9c3; color: #854d0e; padding: 1px 4px; border-radius: 3px; }
    .footer { margin-top: 12px; padding: 8px 16px; font-size: 8px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
</style>
</head>
<body>
<div class="header">
    <h1>Laporan Mutasi Stok</h1>
    <p>{{ config('app.name') }} &mdash; Dicetak: {{ now()->format('d M Y H:i') }}</p>
</div>
<div class="meta">
    Total record: {{ $data->count() }} &nbsp;|&nbsp;
    Total masuk: {{ number_format($data->whereIn('type',['in','transfer_in'])->sum('quantity')) }} &nbsp;|&nbsp;
    Total keluar: {{ number_format($data->whereIn('type',['out','transfer_out','retur'])->sum('quantity')) }}
</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Barang</th>
            <th>Gudang</th>
            <th class="text-center">Tipe</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Sebelum</th>
            <th class="text-right">Sesudah</th>
            <th>User</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $mv)
        @php
            $cls = in_array($mv->type, ['in','transfer_in']) ? 'in'
                : (in_array($mv->type, ['out','transfer_out','retur']) ? 'out' : 'adj');
            $lbl = match($mv->type) {
                'in'           => 'Masuk',
                'out'          => 'Keluar',
                'transfer_in'  => 'T.Masuk',
                'transfer_out' => 'T.Keluar',
                'retur'        => 'Retur',
                'adjustment'   => 'Sesuai',
                default        => $mv->type,
            };
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $mv->moved_at->format('d/m/Y H:i') }}</td>
            <td>{{ $mv->item->name }}</td>
            <td>{{ $mv->warehouse->name }}</td>
            <td class="text-center"><span class="{{ $cls }}">{{ $lbl }}</span></td>
            <td class="text-right">{{ number_format($mv->quantity) }}</td>
            <td class="text-right">{{ number_format($mv->stock_before) }}</td>
            <td class="text-right">{{ number_format($mv->stock_after) }}</td>
            <td>{{ $mv->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">Dicetak: {{ now()->format('d M Y H:i:s') }}</div>
</body>
</html>