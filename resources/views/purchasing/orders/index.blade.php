@extends('layouts.app')
@section('title', 'Purchase Order')

@section('content')
{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <select name="status"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            @foreach($statuses as $val => $label)
                <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>

        <select name="supplier_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Supplier</option>
            @foreach($suppliers as $sup)
                <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>
                    {{ $sup->name }}
                </option>
            @endforeach
        </select>

        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
            Filter
        </button>
        @if(request()->hasAny(['status','supplier_id','date_from','date_to']))
        <a href="{{ route('purchasing.orders.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">
            Reset
        </a>
        @endif

        @can('create purchase-order')
        <div class="ml-auto">
            <a href="{{ route('purchasing.orders.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                + Buat PO
            </a>
        </div>
        @endcan
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No. PO</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Supplier</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Gudang Tujuan</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Tgl Order</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Total</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Status</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($orders as $order)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $order->po_number }}</td>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-900">{{ $order->supplier->name }}</p>
                    <p class="text-xs text-gray-400">{{ $order->supplier->code }}</p>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $order->warehouse->name }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                    {{ $order->order_date->format('d M Y') }}
                </td>
                <td class="px-4 py-3 text-right font-medium text-gray-900">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-center">
                    @php
                        $statusColor = match($order->status) {
                            'draft'            => 'bg-gray-100 text-gray-600',
                            'waiting_approval' => 'bg-yellow-50 text-yellow-700',
                            'approved'         => 'bg-blue-50 text-blue-700',
                            'partial'          => 'bg-orange-50 text-orange-700',
                            'completed'        => 'bg-green-50 text-green-700',
                            'cancelled'        => 'bg-red-50 text-red-700',
                            default            => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <span class="px-2 py-1 text-xs rounded-full font-medium {{ $statusColor }}">
                        {{ $statuses[$order->status] ?? $order->status }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('purchasing.orders.show', $order) }}"
                       class="px-3 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">
                        Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada data Purchase Order.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">{{ $orders->total() }} PO</p>
        {{ $orders->links() }}
    </div>
</div>
@endsection