@extends('layouts.app')
@section('title', 'Laporan Mutasi Stok')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="item_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Barang</option>
            @foreach($items as $item)
                <option value="{{ $item->id }}" {{ ($filters['item_id'] ?? '') == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
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

        <select name="type"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Tipe</option>
            @foreach($types as $val => $label)
                <option value="{{ $val }}" {{ ($filters['type'] ?? '') == $val ? 'selected' : '' }}>
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
        <a href="{{ route('reports.movements') }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif

        @can('export report')
        <div class="ml-auto flex gap-2">
            <a href="{{ route('reports.movements.export', array_merge(['format' => 'excel'], $filters)) }}"
               class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">Ekspor Excel</a>
            <a href="{{ route('reports.movements.export', array_merge(['format' => 'pdf'], $filters)) }}"
               class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">Ekspor PDF</a>
        </div>
        @endcan
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-3 mb-4 flex gap-6 text-sm">
    <div>
        <span class="text-gray-500">Total Record:</span>
        <span class="font-semibold ml-1">{{ number_format($data->count()) }}</span>
    </div>
    <div>
        <span class="text-gray-500">Total Masuk:</span>
        <span class="font-semibold text-green-600 ml-1">
            {{ number_format($data->whereIn('type', ['in','transfer_in'])->sum('quantity')) }}
        </span>
    </div>
    <div>
        <span class="text-gray-500">Total Keluar:</span>
        <span class="font-semibold text-red-600 ml-1">
            {{ number_format($data->whereIn('type', ['out','transfer_out','retur'])->sum('quantity')) }}
        </span>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Tanggal</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Barang</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Gudang</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Tipe</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Qty</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Sebelum</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Sesudah</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">User</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Keterangan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $i => $mv)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 text-gray-400 text-xs">{{ $i + 1 }}</td>
                <td class="px-4 py-2 text-gray-500 text-xs whitespace-nowrap">
                    {{ $mv->moved_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-4 py-2">
                    <p class="font-medium text-gray-900">{{ $mv->item->name }}</p>
                    <p class="text-xs text-gray-400 font-mono">{{ $mv->item->code }}</p>
                </td>
                <td class="px-4 py-2 text-gray-600 text-xs">{{ $mv->warehouse->name }}</td>
                <td class="px-4 py-2 text-center">
                    @php
                        $c = match($mv->type) {
                            'in','transfer_in'   => 'bg-green-50 text-green-700',
                            'out','transfer_out' => 'bg-red-50 text-red-700',
                            'retur'              => 'bg-blue-50 text-blue-700',
                            'adjustment'         => 'bg-yellow-50 text-yellow-700',
                            default              => 'bg-gray-100 text-gray-600',
                        };
                        $l = $types[$mv->type] ?? $mv->type;
                    @endphp
                    <span class="px-2 py-0.5 rounded text-xs {{ $c }}">{{ $l }}</span>
                </td>
                <td class="px-4 py-2 text-right font-semibold">{{ number_format($mv->quantity) }}</td>
                <td class="px-4 py-2 text-right text-gray-500 text-xs">{{ number_format($mv->stock_before) }}</td>
                <td class="px-4 py-2 text-right font-medium">{{ number_format($mv->stock_after) }}</td>
                <td class="px-4 py-2 text-gray-500 text-xs">{{ $mv->user->name }}</td>
                <td class="px-4 py-2 text-gray-400 text-xs max-w-xs truncate">{{ $mv->notes ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="px-4 py-8 text-center text-gray-400">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection