@extends('layouts.app')
@section('title', 'Detail Retur — ' . $itemReturn->return_number)

@section('content')
@php
    $statusColor = match($itemReturn->status) {
        'draft'     => 'bg-gray-100 text-gray-600',
        'sent'      => 'bg-blue-50 text-blue-700',
        'confirmed' => 'bg-green-50 text-green-700',
        'cancelled' => 'bg-red-50 text-red-700',
        default     => 'bg-gray-100 text-gray-600',
    };
    $statusLabel = match($itemReturn->status) {
        'draft'     => 'Draft',
        'sent'      => 'Terkirim',
        'confirmed' => 'Dikonfirmasi Supplier',
        'cancelled' => 'Dibatalkan',
        default     => $itemReturn->status,
    };
@endphp

<div class="max-w-3xl space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex justify-between items-start mb-5">
            <div>
                <p class="text-xs text-gray-400 font-mono">{{ $itemReturn->return_number }}</p>
                <h3 class="font-semibold text-gray-900 text-lg mt-0.5">Retur Barang ke Supplier</h3>
            </div>
            <span class="px-3 py-1 text-sm rounded-full font-medium {{ $statusColor }}">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="grid grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Supplier</p>
                <p class="font-medium mt-0.5">{{ $itemReturn->supplier->name }}</p>
                <p class="text-xs text-gray-400">{{ $itemReturn->supplier->code }}</p>
            </div>
            <div>
                <p class="text-gray-500">Gudang Asal</p>
                <p class="font-medium mt-0.5">{{ $itemReturn->warehouse->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal Retur</p>
                <p class="font-medium mt-0.5">{{ $itemReturn->return_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Dibuat Oleh</p>
                <p class="font-medium mt-0.5">{{ $itemReturn->creator->name }}</p>
            </div>
            @if($itemReturn->purchaseOrder)
            <div>
                <p class="text-gray-500">Referensi PO</p>
                <a href="{{ route('purchasing.orders.show', $itemReturn->purchaseOrder) }}"
                   class="font-mono text-blue-600 hover:underline mt-0.5 block text-xs">
                    {{ $itemReturn->purchaseOrder->po_number }}
                </a>
            </div>
            @endif
            <div class="col-span-3">
                <p class="text-gray-500">Alasan Retur</p>
                <p class="mt-0.5 bg-gray-50 rounded-lg p-3 border border-gray-100">
                    {{ $itemReturn->reason }}
                </p>
            </div>
            @if($itemReturn->notes)
            <div class="col-span-3">
                <p class="text-gray-500">Catatan</p>
                <p class="mt-0.5">{{ $itemReturn->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Action buttons --}}
        <div class="flex gap-2 mt-5 pt-5 border-t border-gray-100">
            @can('create return')
            @if($itemReturn->status === 'draft')
                <form action="{{ route('returns.send', $itemReturn) }}" method="POST"
                      onsubmit="return confirm('Kirim retur ini? Stok akan langsung dikurangi.')">
                    @csrf
                    <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                        Kirim ke Supplier
                    </button>
                </form>
                <form action="{{ route('returns.cancel', $itemReturn) }}" method="POST"
                      onsubmit="return confirm('Batalkan retur ini?')">
                    @csrf
                    <button class="px-4 py-2 border border-red-300 text-red-600 text-sm rounded-lg hover:bg-red-50">
                        Batalkan
                    </button>
                </form>
            @endif

            @if($itemReturn->status === 'sent')
                <form action="{{ route('returns.confirm', $itemReturn) }}" method="POST"
                      onsubmit="return confirm('Konfirmasi bahwa supplier sudah menerima retur ini?')">
                    @csrf
                    <button class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                        Konfirmasi Diterima Supplier
                    </button>
                </form>
                <form action="{{ route('returns.cancel', $itemReturn) }}" method="POST"
                      onsubmit="return confirm('Batalkan retur ini? Stok akan dikembalikan.')">
                    @csrf
                    <button class="px-4 py-2 border border-red-300 text-red-600 text-sm rounded-lg hover:bg-red-50">
                        Batalkan & Kembalikan Stok
                    </button>
                </form>
            @endif
            @endcan
        </div>
    </div>

    {{-- Daftar barang --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
            <h4 class="text-sm font-semibold text-gray-700">Barang yang Diretur</h4>
            <p class="text-xs text-gray-400">{{ $itemReturn->items->count() }} jenis barang</p>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Barang</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Qty</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Harga Satuan</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Subtotal</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php $grandTotal = 0; @endphp
                @foreach($itemReturn->items as $item)
                @php
                    $subtotal   = $item->quantity * $item->unit_price;
                    $grandTotal += $subtotal;
                @endphp
                <tr>
                    <td class="px-4 py-2">
                        <p class="font-medium text-gray-900">{{ $item->item->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $item->item->code }}</p>
                    </td>
                    <td class="px-4 py-2 text-right font-semibold text-gray-900">
                        {{ number_format($item->quantity) }} {{ $item->item->unit->abbreviation }}
                    </td>
                    <td class="px-4 py-2 text-right text-gray-600">
                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-2 text-right font-medium text-gray-900">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-2 text-gray-500 text-xs">{{ $item->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 border-t border-gray-200">
                <tr>
                    <td colspan="3" class="px-4 py-2 text-right text-sm font-semibold text-gray-700">
                        Total Nilai Retur
                    </td>
                    <td class="px-4 py-2 text-right font-semibold text-gray-900">
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <a href="{{ route('returns.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
        &larr; Kembali
    </a>
</div>
@endsection