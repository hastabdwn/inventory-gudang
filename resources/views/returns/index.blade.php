@extends('layouts.app')
@section('title', 'Retur Barang')

@section('content')
{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="supplier_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Supplier</option>
            @foreach($suppliers as $sup)
                <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>
                    {{ $sup->name }}
                </option>
            @endforeach
        </select>

        <select name="warehouse_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Gudang</option>
            @foreach($warehouses as $wh)
                <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                    {{ $wh->name }}
                </option>
            @endforeach
        </select>

        <select name="status"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="draft"     {{ request('status') == 'draft'     ? 'selected' : '' }}>Draft</option>
            <option value="sent"      {{ request('status') == 'sent'      ? 'selected' : '' }}>Terkirim</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>

        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

        <button type="submit"
                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
            Filter
        </button>
        @if(request()->hasAny(['supplier_id','warehouse_id','status','date_from','date_to']))
        <a href="{{ route('returns.index') }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif

        @can('create return')
        <div class="ml-auto">
            <a href="{{ route('returns.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                + Buat Retur
            </a>
        </div>
        @endcan
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No. Retur</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Supplier</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Gudang</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Tanggal</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Alasan</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Dibuat Oleh</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Status</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($returns as $return)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $return->return_number }}</td>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-900">{{ $return->supplier->name }}</p>
                    <p class="text-xs text-gray-400">{{ $return->supplier->code }}</p>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $return->warehouse->name }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                    {{ $return->return_date->format('d M Y') }}
                </td>
                <td class="px-4 py-3 text-gray-600 text-xs max-w-xs">
                    {{ Str::limit($return->reason, 50) }}
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $return->creator->name }}</td>
                <td class="px-4 py-3 text-center">
                    @php
                        $color = match($return->status) {
                            'draft'     => 'bg-gray-100 text-gray-600',
                            'sent'      => 'bg-blue-50 text-blue-700',
                            'confirmed' => 'bg-green-50 text-green-700',
                            'cancelled' => 'bg-red-50 text-red-700',
                            default     => 'bg-gray-100 text-gray-600',
                        };
                        $label = match($return->status) {
                            'draft'     => 'Draft',
                            'sent'      => 'Terkirim',
                            'confirmed' => 'Dikonfirmasi',
                            'cancelled' => 'Dibatalkan',
                            default     => $return->status,
                        };
                    @endphp
                    <span class="px-2 py-1 text-xs rounded-full font-medium {{ $color }}">
                        {{ $label }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('returns.show', $return) }}"
                       class="px-3 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">
                        Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada data retur.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">{{ $returns->total() }} record</p>
        {{ $returns->links() }}
    </div>
</div>
@endsection