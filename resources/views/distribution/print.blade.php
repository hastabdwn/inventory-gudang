<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan — {{ $distribution->dist_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #111;
            padding: 32px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            border-bottom: 2px solid #111;
            padding-bottom: 16px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
        }

        .company-sub {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
        }

        .doc-title {
            text-align: right;
        }

        .doc-title h2 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .doc-title .doc-number {
            font-family: monospace;
            font-size: 12px;
            color: #333;
            margin-top: 4px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        .info-item dt {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-item dd {
            font-weight: 600;
            margin-top: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
        }

        thead th {
            background: #f5f5f5;
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tbody td {
            border: 1px solid #ddd;
            padding: 8px 10px;
        }

        tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 32px;
            margin-top: 48px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-box .role {
            font-size: 11px;
            color: #555;
            margin-bottom: 60px;
        }

        .signature-box .line {
            border-top: 1px solid #333;
            padding-top: 4px;
            font-size: 11px;
        }

        .notes-section {
            margin-top: 16px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .notes-section dt {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        @media print {
            body {
                padding: 16px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    {{-- Print button --}}
    <div class="no-print" style="margin-bottom: 16px; display: flex; gap: 8px;">
        <button onclick="window.print()"
            style="padding: 8px 16px; background: #1d4ed8; color: white; border: none; border-radius: 6px; font-size: 13px; cursor: pointer;">
            Cetak
        </button>
        <a href="{{ route('distribution.show', $distribution) }}"
            style="padding: 8px 16px; background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; text-decoration: none;">
            Kembali
        </a>
    </div>

    {{-- Document header --}}
    <div class="header">
        <div>
            <div class="company-name">{{ config('app.name') }}</div>
            <div class="company-sub">Sistem Manajemen Inventory Gudang</div>
        </div>
        <div class="doc-title">
            <h2>Surat Jalan</h2>
            <div class="doc-number">{{ $distribution->dist_number }}</div>
        </div>
    </div>

    {{-- Info distribusi --}}
    <div class="info-grid">
        <dl class="info-item">
            <dt>Gudang Asal</dt>
            <dd>{{ $distribution->warehouse->name }}</dd>
        </dl>
        <dl class="info-item">
            <dt>Tanggal</dt>
            <dd>{{ $distribution->dist_date->format('d F Y') }}</dd>
        </dl>
        <dl class="info-item">
            <dt>Tujuan / Divisi</dt>
            <dd>{{ $distribution->destination }}</dd>
        </dl>
        <dl class="info-item">
            <dt>Nama Penerima</dt>
            <dd>{{ $distribution->recipient ?? '-' }}</dd>
        </dl>
        <dl class="info-item">
            <dt>Disiapkan Oleh</dt>
            <dd>{{ $distribution->issuer->name }}</dd>
        </dl>
        <dl class="info-item">
            <dt>Status</dt>
            <dd>Diterbitkan</dd>
        </dl>
    </div>

    {{-- Tabel barang --}}
    <table>
        <thead>
            <tr>
                <th style="width: 40px;" class="text-center">No.</th>
                <th style="width: 100px;">Kode</th>
                <th>Nama Barang</th>
                <th style="width: 80px;" class="text-right">Qty</th>
                <th style="width: 80px;" class="text-center">Satuan</th>
                <th style="width: 120px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($distribution->items as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td style="font-family: monospace;">{{ $item->item->code }}</td>
                <td>{{ $item->item->name }}</td>
                <td class="text-right" style="font-weight: 600;">{{ number_format($item->quantity) }}</td>
                <td class="text-center">{{ $item->item->unit->abbreviation }}</td>
                <td></td>
            </tr>
            @endforeach
            {{-- Baris kosong tambahan untuk kebutuhan tulis tangan --}}
            @for($j = 0; $j < max(0, 5 - $distribution->items->count()); $j++)
                <tr>
                    <td class="text-center" style="color: #ccc;">{{ $distribution->items->count() + $j + 1 }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
        </tbody>
    </table>

    {{-- Catatan --}}
    @if($distribution->notes)
    <div class="notes-section">
        <dt>Catatan</dt>
        <dd>{{ $distribution->notes }}</dd>
    </div>
    @endif

    {{-- Tanda tangan --}}
    <div class="signature-section">
        <div class="signature-box">
            <div class="role">Disiapkan Oleh</div>
            <div class="line">{{ $distribution->issuer->name }}</div>
        </div>
        <div class="signature-box">
            <div class="role">Disetujui Oleh</div>
            <div class="line">( ........................ )</div>
        </div>
        <div class="signature-box">
            <div class="role">Diterima Oleh</div>
            <div class="line">{{ $distribution->recipient ?? '( ........................ )' }}</div>
        </div>
    </div>

</body>

</html>