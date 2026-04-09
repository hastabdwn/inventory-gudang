@extends('layouts.app')
@section('title', 'Laporan Retur')

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
            <option value="draft"     {{ ($filters['status'] ?? '') == 'draft'     ? 'selected' : '' }}>Draft</option>
            <option value="sent"      {{ ($filters['status'] ?? '') == 'sent'      ? 'selected' : '' }}>Terkirim</option>
            <option value="confirmed" {{ ($filters['status'] ?? '') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
            <option value="cancelled" {{ ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>

        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

        <button type="submit"
                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Filter</button>
        @if(array_filter($filters))
        <a href="{{ route('reports.returns') }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif

        @can('export report')
        <div class="ml-auto flex gap-2">
            <a href="{{ route('reports.returns.export', array_merge(['format' => 'excel'], $filters)) }}"
               class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">Ekspor Excel</a>
            <a href="{{ route('reports.returns.export', array_merge(['format' => 'pdf'], $filters)) }}"
               class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">Ekspor PDF</a>
        </div>
        @endcan
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-3 mb-4 flex gap-6 text-sm">
    <div>
        <span class="text-gray-500">Total Retur:</span>
        <span class="font-semibold ml-1">{{ number_format($data->count()) }}</span>
    </div>
    <div>
        <span class="text-gray-500">Dikonfirmasi:</span>
        <span class="font-semibold text-green-600 ml-1">{{ $data->where('status','confirmed')->count() }}</span>
    </div>
    <div>
        <span class="text-gray-500">Pending:</span>
        <span class="font-semibold text-yellow-600 ml-1">
            {{ $data->whereIn('status', ['draft','sent'])->count() }}
        </span>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No. Retur</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Tanggal</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Supplier</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Gudang</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Alasan</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Jml Item</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $i => $return)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 text-gray-400 text-xs">{{ $i + 1 }}</td>
                <td class="px-4 py-2 font-mono text-xs text-gray-700">
                    <a href="{{ route('returns.show', $return) }}"
                       class="hover:text-blue-600 hover:underline">{{ $return->return_number }}</a>
                </td>
                <td class="px-4 py-2 text-gray-500 text-xs">{{ $return->return_date->format('d/m/Y') }}</td>
                <td class="px-4 py-2 font-medium text-gray-900">{{ $return->supplier->name }}</td>
                <td class="px-4 py-2 text-gray-600 text-xs">{{ $return->warehouse->name }}</td>
                <td class="px-4 py-2 text-gray-500 text-xs max-w-xs truncate">
                    {{ Str::limit($return->reason, 50) }}
                </td>
                <td class="px-4 py-2 text-center text-gray-600">{{ $return->items->count() }}</td>
                <td class="px-4 py-2 text-center">
                    @php
                        $rc = match($return->status) {
                            'draft'     => 'bg-gray-100 text-gray-600',
                            'sent'      => 'bg-blue-50 text-blue-700',
                            'confirmed' => 'bg-green-50 text-green-700',
                            'cancelled' => 'bg-red-50 text-red-700',
                            default     => 'bg-gray-100 text-gray-600',
                        };
                        $rl = match($return->status) {
                            'draft'     => 'Draft',
                            'sent'      => 'Terkirim',
                            'confirmed' => 'Dikonfirmasi',
                            'cancelled' => 'Dibatalkan',
                            default     => $return->status,
                        };
                    @endphp
                    <span class="px-2 py-0.5 text-xs rounded-full {{ $rc }}">{{ $rl }}</span>
                </td>
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