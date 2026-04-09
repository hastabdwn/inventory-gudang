@extends('layouts.app')
@section('title', 'Laporan Purchase Order')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="supplier_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Supplier</option>
            @foreach($suppliers as $sup)
                <option value="{{ $sup->id }}" {{ ($filters['supplier_id'] ?? '') == $sup->id ? 'selected' : '' }}>
                    {{ $sup->name }}
                </option>
            @endforeach
        </select>

        <select name="warehouse_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Gudang</option>
            @foreach($warehouses as $wh)
                <option value="{{ $wh->id }}" {{ ($filters['warehouse_id'] ?? '') == $wh->id ? 'selected' : '' }}>
                    {{ $wh->name }}
                </option>
            @endforeach
        </select>

        <select name="status"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            @foreach($statuses as $val => $label)
                <option value="{{ $val }}" {{ ($filters['status'] ?? '') == $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

        <button type="submit"
                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Filter</button>
        @if(array_filter($filters))
        <a href="{{ route('reports.purchase-orders') }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif

        @can('export report')
        <div class="ml-auto flex gap-2">
            <a href="{{ route('reports.purchase-orders.export', array_merge(['format' => 'excel'], $filters)) }}"
               class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">Ekspor Excel</a>
            <a href="{{ route('reports.purchase-orders.export', array_merge(['format' => 'pdf'], $filters)) }}"
               class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">Ekspor PDF</a>
        </div>
        @endcan
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-3 mb-4 flex gap-6 text-sm">
    <div>
        <span class="text-gray-500">Total PO:</span>
        <span class="font-semibold ml-1">{{ number_format($data->count()) }}</span>
    </div>
    <div>
        <span class="text-gray-500">Total Nilai:</span>
        <span class="font-semibold ml-1">
            Rp {{ number_format($data->whereNotIn('status', ['cancelled'])->sum('total_amount'), 0, ',', '.') }}
        </span>
    </div>
    <div>
        <span class="text-gray-500">Selesai:</span>
        <span class="font-semibold text-green-600 ml-1">
            {{ $data->where('status', 'completed')->count() }}
        </span>
    </div>
    <div>
        <span class="text-gray-500">Dibatalkan:</span>
        <span class="font-semibold text-red-600 ml-1">
            {{ $data->where('status', 'cancelled')->count() }}
        </span>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No. PO</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Tgl Order</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Supplier</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Gudang</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Total Nilai</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Status</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Dibuat Oleh</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $i => $po)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 text-gray-400 text-xs">{{ $i + 1 }}</td>
                <td class="px-4 py-2 font-mono text-xs text-gray-700">
                    <a href="{{ route('purchasing.orders.show', $po) }}"
                       class="hover:text-blue-600 hover:underline">{{ $po->po_number }}</a>
                </td>
                <td class="px-4 py-2 text-gray-500 text-xs">{{ $po->order_date->format('d/m/Y') }}</td>
                <td class="px-4 py-2 font-medium text-gray-900">{{ $po->supplier->name }}</td>
                <td class="px-4 py-2 text-gray-600 text-xs">{{ $po->warehouse->name }}</td>
                <td class="px-4 py-2 text-right font-medium">
                    Rp {{ number_format($po->total_amount, 0, ',', '.') }}
                </td>
                <td class="px-4 py-2 text-center">
                    @php
                        $sc = match($po->status) {
                            'draft'            => 'bg-gray-100 text-gray-600',
                            'waiting_approval' => 'bg-yellow-50 text-yellow-700',
                            'approved'         => 'bg-blue-50 text-blue-700',
                            'partial'          => 'bg-orange-50 text-orange-700',
                            'completed'        => 'bg-green-50 text-green-700',
                            'cancelled'        => 'bg-red-50 text-red-700',
                            default            => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <span class="px-2 py-0.5 text-xs rounded-full {{ $sc }}">
                        {{ $statuses[$po->status] ?? $po->status }}
                    </span>
                </td>
                <td class="px-4 py-2 text-gray-500 text-xs">{{ $po->creator->name }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-8 text-center text-gray-400">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection