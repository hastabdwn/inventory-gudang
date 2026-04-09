@extends('layouts.app')
@section('title', 'Detail Penerimaan — ' . $goodsReceipt->receipt_number)

@section('content')
<div class="max-w-3xl space-y-6">

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <p class="text-xs text-gray-400 font-mono mb-0.5">{{ $goodsReceipt->receipt_number }}</p>
        <h3 class="font-semibold text-gray-900 text-lg mb-5">Bukti Penerimaan Barang</h3>

        <div class="grid grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">No. PO</p>
                <a href="{{ route('purchasing.orders.show', $goodsReceipt->purchaseOrder) }}"
                   class="font-mono font-medium text-blue-600 hover:underline mt-0.5 block">
                    {{ $goodsReceipt->purchaseOrder->po_number }}
                </a>
            </div>
            <div>
                <p class="text-gray-500">Supplier</p>
                <p class="font-medium mt-0.5">{{ $goodsReceipt->purchaseOrder->supplier->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Gudang</p>
                <p class="font-medium mt-0.5">{{ $goodsReceipt->warehouse->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal Terima</p>
                <p class="font-medium mt-0.5">{{ $goodsReceipt->receipt_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Diterima Oleh</p>
                <p class="font-medium mt-0.5">{{ $goodsReceipt->receiver->name }}</p>
            </div>
            @if($goodsReceipt->notes)
            <div>
                <p class="text-gray-500">Catatan</p>
                <p class="mt-0.5">{{ $goodsReceipt->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h4 class="text-sm font-semibold text-gray-700">Barang Diterima</h4>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Barang</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Qty Order</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Qty Diterima</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($goodsReceipt->items as $item)
                <tr>
                    <td class="px-4 py-2">
                        <p class="font-medium text-gray-900">{{ $item->item->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $item->item->code }}</p>
                    </td>
                    <td class="px-4 py-2 text-right text-gray-600">
                        {{ number_format($item->poItem->qty_ordered) }} {{ $item->item->unit->abbreviation }}
                    </td>
                    <td class="px-4 py-2 text-right font-semibold text-green-600">
                        {{ number_format($item->qty_received) }} {{ $item->item->unit->abbreviation }}
                    </td>
                    <td class="px-4 py-2 text-gray-500 text-xs">{{ $item->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('purchasing.receipts.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
        &larr; Kembali
    </a>
</div>
@endsection