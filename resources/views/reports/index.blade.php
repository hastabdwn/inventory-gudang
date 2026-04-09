@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Total Barang Aktif</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ number_format($summary['total_items']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Total Stok (semua gudang)</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ number_format($summary['total_stock']) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Total Nilai PO</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">
            Rp {{ number_format($summary['total_po_value'], 0, ',', '.') }}
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
    @php
        $reports = [
            [
                'title'       => 'Laporan Stok',
                'description' => 'Posisi stok barang per gudang, filter kategori & status stok.',
                'route'       => 'reports.stock',
                'color'       => 'blue',
            ],
            [
                'title'       => 'Laporan Mutasi Stok',
                'description' => 'Histori pergerakan stok masuk, keluar, transfer, dan penyesuaian.',
                'route'       => 'reports.movements',
                'color'       => 'green',
            ],
            [
                'title'       => 'Laporan Purchase Order',
                'description' => 'Rekap seluruh PO beserta status dan nilai pembelian.',
                'route'       => 'reports.purchase-orders',
                'color'       => 'purple',
            ],
            [
                'title'       => 'Laporan Distribusi',
                'description' => 'Rekap pengeluaran dan distribusi barang ke divisi/tujuan.',
                'route'       => 'reports.distributions',
                'color'       => 'amber',
            ],
            [
                'title'       => 'Laporan Retur',
                'description' => 'Rekap retur barang ke supplier beserta alasan dan status.',
                'route'       => 'reports.returns',
                'color'       => 'red',
            ],
        ];
    @endphp

    @foreach($reports as $report)
    @php
        $bg     = "bg-{$report['color']}-50";
        $border = "border-{$report['color']}-200";
        $text   = "text-{$report['color']}-700";
        $btn    = "bg-{$report['color']}-600 hover:bg-{$report['color']}-700";
    @endphp
    <a href="{{ route($report['route']) }}"
       class="block bg-white rounded-xl border border-gray-200 p-5 hover:border-blue-300 hover:shadow-sm transition-all">
        <h3 class="font-semibold text-gray-900 mb-1">{{ $report['title'] }}</h3>
        <p class="text-xs text-gray-500 mb-4">{{ $report['description'] }}</p>
        <span class="text-xs text-blue-600 font-medium">Buka Laporan →</span>
    </a>
    @endforeach
</div>
@endsection