@extends('layouts.app')
@section('title', 'Histori Mutasi Stok')

@section('content')
{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="item_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Barang</option>
            @foreach($items as $item)
                <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
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

        <select name="type"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Tipe</option>
            @foreach($types as $val => $label)
                <option value="{{ $val }}" {{ request('type') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>

        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
            Filter
        </button>
        @if(request()->hasAny(['item_id','warehouse_id','type','date_from','date_to']))
        <a href="{{ route('stock.movements.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Waktu</th>
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
            @forelse($movements as $mv)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                    {{ $mv->moved_at->format('d M Y H:i') }}
                </td>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-900">{{ $mv->item->name }}</p>
                    <p class="text-xs text-gray-400 font-mono">{{ $mv->item->code }}</p>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $mv->warehouse->name }}</td>
                <td class="px-4 py-3 text-center">
                    @php
                        $typeColor = match($mv->type) {
                            'in', 'transfer_in'  => 'bg-green-50 text-green-700',
                            'out', 'transfer_out' => 'bg-red-50 text-red-700',
                            'retur'              => 'bg-blue-50 text-blue-700',
                            'adjustment'         => 'bg-yellow-50 text-yellow-700',
                            default              => 'bg-gray-100 text-gray-600',
                        };
                        $typeLabel = match($mv->type) {
                            'in'           => 'Masuk',
                            'out'          => 'Keluar',
                            'transfer_in'  => 'Transfer Masuk',
                            'transfer_out' => 'Transfer Keluar',
                            'retur'        => 'Retur',
                            'adjustment'   => 'Penyesuaian',
                            default        => $mv->type,
                        };
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $typeColor }}">
                        {{ $typeLabel }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right font-semibold text-gray-900">
                    {{ number_format($mv->quantity) }}
                    <span class="text-xs font-normal text-gray-400">{{ $mv->item->unit->abbreviation }}</span>
                </td>
                <td class="px-4 py-3 text-right text-gray-500">{{ number_format($mv->stock_before) }}</td>
                <td class="px-4 py-3 text-right text-gray-900 font-medium">{{ number_format($mv->stock_after) }}</td>
                <td class="px-4 py-3 text-gray-600 text-xs">{{ $mv->user->name }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs max-w-xs truncate">{{ $mv->notes ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-4 py-8 text-center text-gray-400">Belum ada histori mutasi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">{{ $movements->total() }} record</p>
        {{ $movements->links() }}
    </div>
</div>
@endsection