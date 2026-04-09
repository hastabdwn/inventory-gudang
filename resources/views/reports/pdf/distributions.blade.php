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
    .text-center { text-align: center; }
    .footer { margin-top: 12px; padding: 8px 16px; font-size: 8px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
</style>
</head>
<body>
<div class="header">
    <h1>Laporan Distribusi</h1>
    <p>{{ config('app.name') }} &mdash; Dicetak: {{ now()->format('d M Y H:i') }}</p>
</div>
<div class="meta">
    Total: {{ $data->count() }} &nbsp;|&nbsp;
    Diterbitkan: {{ $data->where('status','issued')->count() }}
</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No. Distribusi</th>
            <th>Tanggal</th>
            <th>Gudang</th>
            <th>Tujuan</th>
            <th>Penerima</th>
            <th class="text-center">Item</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $dist)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $dist->dist_number }}</td>
            <td>{{ $dist->dist_date->format('d/m/Y') }}</td>
            <td>{{ $dist->warehouse->name }}</td>
            <td>{{ $dist->destination }}</td>
            <td>{{ $dist->recipient ?? '-' }}</td>
            <td class="text-center">{{ $dist->items->count() }}</td>
            <td class="text-center">
                {{ match($dist->status) { 'draft' => 'Draft', 'issued' => 'Diterbitkan', 'cancelled' => 'Dibatalkan', default => $dist->status } }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">Dicetak: {{ now()->format('d M Y H:i:s') }}</div>
</body>
</html>