@extends('layouts.app')
@section('title', 'Penerimaan Barang')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex gap-3">
        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
            Filter
        </button>
        @if(request()->hasAny(['date_from','date_to']))
        <a href="{{ route('purchasing.receipts.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">
            Reset
        </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No. GR</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No. PO</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Supplier</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Gudang</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Tgl Terima</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Diterima Oleh</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($receipts as $receipt)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $receipt->receipt_number }}</td>
                <td class="px-4 py-3 font-mono text-xs text-gray-500">
                    <a href="{{ route('purchasing.orders.show', $receipt->purchaseOrder) }}"
                       class="hover:text-blue-600 hover:underline">
                        {{ $receipt->purchaseOrder->po_number }}
                    </a>
                </td>
                <td class="px-4 py-3 text-gray-900">{{ $receipt->purchaseOrder->supplier->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $receipt->warehouse->name }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $receipt->receipt_date->format('d M Y') }}</td>
                <td class="px-4 py-3 text-gray-600 text-xs">{{ $receipt->receiver->name }}</td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('purchasing.receipts.show', $receipt) }}"
                       class="px-3 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada data penerimaan barang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">{{ $receipts->total() }} record</p>
        {{ $receipts->links() }}
    </div>
</div>
@endsection