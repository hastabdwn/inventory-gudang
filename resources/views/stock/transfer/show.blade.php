@extends('layouts.app')
@section('title', 'Detail Transfer Stok')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-xs text-gray-400 font-mono">{{ $stockTransfer->transfer_number }}</p>
                <h3 class="font-semibold text-gray-900 mt-0.5">Transfer Stok</h3>
            </div>
            <span class="px-2 py-1 text-xs rounded-full
                {{ $stockTransfer->status === 'completed' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                {{ $stockTransfer->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
            </span>
        </div>

        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Dari Gudang</dt>
                <dd class="font-medium mt-0.5">{{ $stockTransfer->fromWarehouse->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Ke Gudang</dt>
                <dd class="font-medium mt-0.5">{{ $stockTransfer->toWarehouse->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Tanggal</dt>
                <dd class="mt-0.5">{{ $stockTransfer->transfer_date->format('d M Y') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Dilakukan Oleh</dt>
                <dd class="mt-0.5">{{ $stockTransfer->transferredBy->name }}</dd>
            </div>
            @if($stockTransfer->notes)
            <div class="col-span-2">
                <dt class="text-gray-500">Catatan</dt>
                <dd class="mt-0.5">{{ $stockTransfer->notes }}</dd>
            </div>
            @endif
        </dl>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h4 class="text-sm font-semibold text-gray-700">Barang yang Ditransfer</h4>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Barang</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Qty</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($stockTransfer->items as $item)
                <tr>
                    <td class="px-4 py-2">
                        <p class="font-medium text-gray-900">{{ $item->item->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $item->item->code }}</p>
                    </td>
                    <td class="px-4 py-2 text-right font-medium">
                        {{ number_format($item->quantity) }} {{ $item->item->unit->abbreviation }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('stock.transfer.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
        &larr; Kembali
    </a>
</div>
@endsection