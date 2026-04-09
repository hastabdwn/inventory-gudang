@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Total Barang</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ number_format($totalItems) }}</p>
        <p class="text-xs text-gray-400 mt-1">jenis barang aktif</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Gudang Aktif</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ number_format($totalWarehouses) }}</p>
        <p class="text-xs text-gray-400 mt-1">lokasi gudang</p>
    </div>
    <div class="bg-white rounded-xl border {{ $lowStockItems > 0 ? 'border-yellow-300' : 'border-gray-200' }} p-4">
        <p class="text-xs {{ $lowStockItems > 0 ? 'text-yellow-600' : 'text-gray-500' }}">Stok Rendah</p>
        <p class="text-2xl font-semibold {{ $lowStockItems > 0 ? 'text-yellow-600' : 'text-gray-900' }} mt-1">
            {{ number_format($lowStockItems) }}
        </p>
        <p class="text-xs text-gray-400 mt-1">item perlu restock</p>
    </div>
    <div class="bg-white rounded-xl border {{ $emptyStockItems > 0 ? 'border-red-300' : 'border-gray-200' }} p-4">
        <p class="text-xs {{ $emptyStockItems > 0 ? 'text-red-600' : 'text-gray-500' }}">Stok Habis</p>
        <p class="text-2xl font-semibold {{ $emptyStockItems > 0 ? 'text-red-600' : 'text-gray-900' }} mt-1">
            {{ number_format($emptyStockItems) }}
        </p>
        <p class="text-xs text-gray-400 mt-1">item kehabisan stok</p>
    </div>
    <div class="bg-white rounded-xl border {{ $pendingPo > 0 ? 'border-blue-300' : 'border-gray-200' }} p-4">
        <p class="text-xs {{ $pendingPo > 0 ? 'text-blue-600' : 'text-gray-500' }}">PO Berjalan</p>
        <p class="text-2xl font-semibold {{ $pendingPo > 0 ? 'text-blue-600' : 'text-gray-900' }} mt-1">
            {{ number_format($pendingPo) }}
        </p>
        <p class="text-xs text-gray-400 mt-1">purchase order aktif</p>
    </div>
    <div class="bg-white rounded-xl border {{ $pendingReturns > 0 ? 'border-orange-300' : 'border-gray-200' }} p-4">
        <p class="text-xs {{ $pendingReturns > 0 ? 'text-orange-600' : 'text-gray-500' }}">Retur Pending</p>
        <p class="text-2xl font-semibold {{ $pendingReturns > 0 ? 'text-orange-600' : 'text-gray-900' }} mt-1">
            {{ number_format($pendingReturns) }}
        </p>
        <p class="text-xs text-gray-400 mt-1">retur belum selesai</p>
    </div>
</div>

{{-- Alert Stok --}}
@if($emptyStockAlerts->count() > 0 || $lowStockAlerts->count() > 0)
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

    @if($emptyStockAlerts->count() > 0)
    <div class="bg-red-50 rounded-xl border border-red-200 p-4">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-sm font-semibold text-red-700">Stok Habis</h3>
            <a href="{{ route('stock.index', ['low_stock' => 1]) }}"
               class="text-xs text-red-600 hover:underline">Lihat semua</a>
        </div>
        <div class="space-y-2">
            @foreach($emptyStockAlerts as $item)
            <div class="flex justify-between items-center bg-white rounded-lg px-3 py-2 border border-red-100">
                <div>
                    <p class="text-xs font-medium text-gray-900">{{ $item->name }}</p>
                    <p class="text-xs text-gray-400 font-mono">{{ $item->code }}</p>
                </div>
                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-medium">Habis</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($lowStockAlerts->count() > 0)
    <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-4">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-sm font-semibold text-yellow-700">Stok Rendah</h3>
            <a href="{{ route('stock.index', ['low_stock' => 1]) }}"
               class="text-xs text-yellow-600 hover:underline">Lihat semua</a>
        </div>
        <div class="space-y-2">
            @foreach($lowStockAlerts as $stock)
            <div class="flex justify-between items-center bg-white rounded-lg px-3 py-2 border border-yellow-100">
                <div>
                    <p class="text-xs font-medium text-gray-900">{{ $stock->item->name }}</p>
                    <p class="text-xs text-gray-400">{{ $stock->warehouse->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-yellow-700">
                        {{ number_format($stock->quantity) }} / {{ number_format($stock->item->min_stock) }}
                        {{ $stock->item->unit->abbreviation }}
                    </p>
                    <div class="w-20 bg-gray-200 rounded-full h-1 mt-1 ml-auto">
                        @php
                            $pct = $stock->item->min_stock > 0
                                ? min(100, round(($stock->quantity / $stock->item->min_stock) * 100))
                                : 0;
                        @endphp
                        <div class="bg-yellow-500 h-1 rounded-full" style="width: <?= $pct ?>%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endif

{{-- Charts Row 1 --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

    {{-- Chart: Mutasi Stok 30 Hari --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Mutasi Stok — 30 Hari Terakhir</h3>
            <a href="{{ route('stock.movements.index') }}"
               class="text-xs text-blue-600 hover:underline">Detail</a>
        </div>
        <canvas id="movementChart" height="120"></canvas>
    </div>

    {{-- Chart: Stok per Kategori --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Stok per Kategori</h3>
        </div>
        <canvas id="categoryChart" height="200"></canvas>
    </div>

</div>

{{-- Charts Row 2 --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

    {{-- Chart: Nilai PO 6 Bulan --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Nilai Purchase Order — 6 Bulan</h3>
            <a href="{{ route('purchasing.orders.index') }}"
               class="text-xs text-blue-600 hover:underline">Detail</a>
        </div>
        <canvas id="poChart" height="140"></canvas>
    </div>

    {{-- Recent PO --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-semibold text-gray-700">Purchase Order Terbaru</h3>
            <a href="{{ route('purchasing.orders.index') }}"
               class="text-xs text-blue-600 hover:underline">Lihat semua</a>
        </div>
        <div class="space-y-2">
            @forelse($recentPo as $po)
            <a href="{{ route('purchasing.orders.show', $po) }}"
               class="flex items-center justify-between p-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition-colors">
                <div>
                    <p class="text-xs font-mono text-gray-600">{{ $po->po_number }}</p>
                    <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $po->supplier->name }}</p>
                    <p class="text-xs text-gray-400">{{ $po->order_date->format('d M Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-900">
                        Rp {{ number_format($po->total_amount, 0, ',', '.') }}
                    </p>
                    @php
                        $poColor = match($po->status) {
                            'draft'            => 'bg-gray-100 text-gray-600',
                            'waiting_approval' => 'bg-yellow-50 text-yellow-700',
                            'approved'         => 'bg-blue-50 text-blue-700',
                            'partial'          => 'bg-orange-50 text-orange-700',
                            'completed'        => 'bg-green-50 text-green-700',
                            'cancelled'        => 'bg-red-50 text-red-700',
                            default            => 'bg-gray-100 text-gray-600',
                        };
                        $poLabel = match($po->status) {
                            'draft'            => 'Draft',
                            'waiting_approval' => 'Menunggu',
                            'approved'         => 'Disetujui',
                            'partial'          => 'Sebagian',
                            'completed'        => 'Selesai',
                            'cancelled'        => 'Batal',
                            default            => $po->status,
                        };
                    @endphp
                    <span class="mt-1 inline-block px-2 py-0.5 text-xs rounded-full {{ $poColor }}">
                        {{ $poLabel }}
                    </span>
                </div>
            </a>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">Belum ada PO.</p>
            @endforelse
        </div>
    </div>

</div>

{{-- Recent Movements --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-sm font-semibold text-gray-700">Aktivitas Stok Terbaru</h3>
        <a href="{{ route('stock.movements.index') }}"
           class="text-xs text-blue-600 hover:underline">Lihat semua</a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-gray-600 font-medium text-xs">Waktu</th>
                <th class="px-4 py-2 text-left text-gray-600 font-medium text-xs">Barang</th>
                <th class="px-4 py-2 text-left text-gray-600 font-medium text-xs">Gudang</th>
                <th class="px-4 py-2 text-center text-gray-600 font-medium text-xs">Tipe</th>
                <th class="px-4 py-2 text-right text-gray-600 font-medium text-xs">Qty</th>
                <th class="px-4 py-2 text-right text-gray-600 font-medium text-xs">Stok Akhir</th>
                <th class="px-4 py-2 text-left text-gray-600 font-medium text-xs">User</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($recentMovements as $mv)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 text-gray-400 text-xs whitespace-nowrap">
                    {{ $mv->moved_at->diffForHumans() }}
                </td>
                <td class="px-4 py-2">
                    <p class="font-medium text-gray-900 text-xs">{{ $mv->item->name }}</p>
                </td>
                <td class="px-4 py-2 text-gray-500 text-xs">{{ $mv->warehouse->name }}</td>
                <td class="px-4 py-2 text-center">
                    @php
                        $mvColor = match($mv->type) {
                            'in','transfer_in'   => 'bg-green-50 text-green-700',
                            'out','transfer_out' => 'bg-red-50 text-red-700',
                            'retur'              => 'bg-blue-50 text-blue-700',
                            'adjustment'         => 'bg-yellow-50 text-yellow-700',
                            default              => 'bg-gray-100 text-gray-600',
                        };
                        $mvLabel = match($mv->type) {
                            'in'           => 'Masuk',
                            'out'          => 'Keluar',
                            'transfer_in'  => 'T.Masuk',
                            'transfer_out' => 'T.Keluar',
                            'retur'        => 'Retur',
                            'adjustment'   => 'Sesuai',
                            default        => $mv->type,
                        };
                    @endphp
                    <span class="px-1.5 py-0.5 rounded text-xs {{ $mvColor }}">{{ $mvLabel }}</span>
                </td>
                <td class="px-4 py-2 text-right font-medium text-xs">
                    {{ number_format($mv->quantity) }}
                </td>
                <td class="px-4 py-2 text-right text-xs font-semibold text-gray-900">
                    {{ number_format($mv->stock_after) }}
                </td>
                <td class="px-4 py-2 text-gray-400 text-xs">{{ $mv->user->name }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-6 text-center text-gray-400 text-xs">
                    Belum ada aktivitas stok.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Data untuk charts — pakai script tag agar tidak ada encoding HTML --}}
<script id="chart-data" type="application/json">
{
    "movementDates":  {!! json_encode($movementDates) !!},
    "movementIn":     {!! json_encode($movementIn) !!},
    "movementOut":    {!! json_encode($movementOut) !!},
    "categoryLabels": {!! json_encode($categoryLabels) !!},
    "categoryValues": {!! json_encode($categoryValues) !!},
    "poMonths":       {!! json_encode($poMonths) !!},
    "poValues":       {!! json_encode($poValues) !!},
    "poCounts":       {!! json_encode($poCounts) !!}
}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const chartData      = JSON.parse(document.getElementById('chart-data').textContent);
const movementDates  = chartData.movementDates;
const movementIn     = chartData.movementIn;
const movementOut    = chartData.movementOut;
const categoryLabels = chartData.categoryLabels;
const categoryValues = chartData.categoryValues;
const poMonths       = chartData.poMonths;
const poValues       = chartData.poValues;
const poCounts       = chartData.poCounts;

const isDark    = window.matchMedia('(prefers-color-scheme: dark)').matches;
const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
const textColor = isDark ? '#9ca3af' : '#6b7280';

Chart.defaults.font.family = 'inherit';
Chart.defaults.font.size   = 11;

// ── Chart 1: Mutasi Stok 30 Hari ──────────────────────────
new Chart(document.getElementById('movementChart'), {
    type: 'bar',
    data: {
        labels: movementDates,
        datasets: [
            {
                label: 'Masuk',
                data: movementIn,
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderRadius: 3,
                borderSkipped: false,
            },
            {
                label: 'Keluar',
                data: movementOut,
                backgroundColor: 'rgba(239, 68, 68, 0.6)',
                borderRadius: 3,
                borderSkipped: false,
            },
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
                labels: { color: textColor, usePointStyle: true, pointStyleWidth: 8 },
            },
            tooltip: {
                callbacks: {
                    label: ctx => ' ' + ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString('id-ID'),
                },
            },
        },
        scales: {
            x: {
                grid: { color: gridColor },
                ticks: {
                    color: textColor,
                    maxTicksLimit: 10,
                    maxRotation: 0,
                },
            },
            y: {
                grid: { color: gridColor },
                ticks: {
                    color: textColor,
                    callback: val => val.toLocaleString('id-ID'),
                },
                beginAtZero: true,
            },
        },
    },
});

// ── Chart 2: Stok per Kategori (Doughnut) ─────────────────
const palette = [
    '#3b82f6','#10b981','#f59e0b','#ef4444',
    '#8b5cf6','#06b6d4','#f97316','#84cc16',
    '#ec4899','#6366f1',
];

new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: categoryLabels,
        datasets: [{
            data: categoryValues,
            backgroundColor: palette.slice(0, categoryLabels.length),
            borderWidth: 2,
            borderColor: isDark ? '#1f2937' : '#ffffff',
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: textColor,
                    usePointStyle: true,
                    pointStyleWidth: 8,
                    padding: 12,
                },
            },
            tooltip: {
                callbacks: {
                    label: ctx => ' ' + ctx.label + ': ' + ctx.parsed.toLocaleString('id-ID'),
                },
            },
        },
    },
});

// ── Chart 3: Nilai PO 6 Bulan ──────────────────────────────
new Chart(document.getElementById('poChart'), {
    type: 'line',
    data: {
        labels: poMonths,
        datasets: [
            {
                label: 'Nilai PO (Rp)',
                data: poValues,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointRadius: 4,
                yAxisID: 'y',
            },
            {
                label: 'Jumlah PO',
                data: poCounts,
                borderColor: '#10b981',
                backgroundColor: 'transparent',
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointRadius: 4,
                borderDash: [4, 4],
                yAxisID: 'y1',
            },
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                position: 'top',
                labels: { color: textColor, usePointStyle: true, pointStyleWidth: 8 },
            },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        if (ctx.datasetIndex === 0) {
                            return ' Nilai: Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                        }
                        return ' Jumlah: ' + ctx.parsed.y + ' PO';
                    },
                },
            },
        },
        scales: {
            x: {
                grid: { color: gridColor },
                ticks: { color: textColor },
            },
            y: {
                grid: { color: gridColor },
                ticks: {
                    color: textColor,
                    callback: val => 'Rp ' + (val / 1000000).toFixed(1) + 'jt',
                },
                beginAtZero: true,
            },
            y1: {
                position: 'right',
                grid: { drawOnChartArea: false },
                ticks: {
                    color: textColor,
                    stepSize: 1,
                },
                beginAtZero: true,
            },
        },
    },
});
</script>
@endsection