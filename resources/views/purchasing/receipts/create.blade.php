@extends('layouts.app')
@section('title', 'Terima Barang — ' . $purchaseOrder->po_number)

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">

        {{-- Info PO --}}
        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-sm">
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <p class="text-gray-500 text-xs">No. PO</p>
                    <p class="font-mono font-medium">{{ $purchaseOrder->po_number }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs">Supplier</p>
                    <p class="font-medium">{{ $purchaseOrder->supplier->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs">Gudang Tujuan</p>
                    <p class="font-medium">{{ $purchaseOrder->warehouse->name }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('purchasing.receipts.store', $purchaseOrder) }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Terima <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="receipt_date"
                           value="{{ old('receipt_date', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <input type="text" name="notes" value="{{ old('notes') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Opsional">
                </div>
            </div>

            {{-- Tabel barang --}}
            <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-700">Qty Diterima</span>
                    <span class="text-xs text-gray-400 ml-2">Isi 0 jika barang belum diterima</span>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-600 font-medium">Barang</th>
                            <th class="px-4 py-2 text-right text-gray-600 font-medium">Qty Order</th>
                            <th class="px-4 py-2 text-right text-gray-600 font-medium">Sudah Diterima</th>
                            <th class="px-4 py-2 text-right text-gray-600 font-medium">Sisa</th>
                            <th class="px-4 py-2 text-right text-gray-600 font-medium w-32">Qty Terima Kini</th>
                            <th class="px-4 py-2 text-left text-gray-600 font-medium">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($purchaseOrder->items as $i => $poItem)
                        <tr>
                            <input type="hidden" name="items[{{ $i }}][po_item_id]" value="{{ $poItem->id }}">
                            <td class="px-4 py-2">
                                <p class="font-medium text-gray-900">{{ $poItem->item->name }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $poItem->item->code }}</p>
                            </td>
                            <td class="px-4 py-2 text-right text-gray-600">
                                {{ number_format($poItem->qty_ordered) }} {{ $poItem->item->unit->abbreviation }}
                            </td>
                            <td class="px-4 py-2 text-right text-green-600 font-medium">
                                {{ number_format($poItem->qty_received) }}
                            </td>
                            <td class="px-4 py-2 text-right font-semibold
                                {{ $poItem->remaining_qty > 0 ? 'text-orange-600' : 'text-gray-400' }}">
                                {{ number_format($poItem->remaining_qty) }}
                            </td>
                            <td class="px-4 py-2">
                                <input type="number"
                                       name="items[{{ $i }}][qty_received]"
                                       value="{{ old("items.{$i}.qty_received", 0) }}"
                                       min="0"
                                       max="{{ $poItem->remaining_qty }}"
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm text-right focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       {{ $poItem->remaining_qty <= 0 ? 'disabled' : '' }}>
                            </td>
                            <td class="px-4 py-2">
                                <input type="text"
                                       name="items[{{ $i }}][notes]"
                                       value="{{ old("items.{$i}.notes") }}"
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Opsional"
                                       {{ $poItem->remaining_qty <= 0 ? 'disabled' : '' }}>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Simpan Penerimaan
                </button>
                <a href="{{ route('purchasing.orders.show', $purchaseOrder) }}"
                   class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection