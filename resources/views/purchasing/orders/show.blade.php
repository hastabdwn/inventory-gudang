@extends('layouts.app')
@section('title', 'Detail PO — ' . $purchaseOrder->po_number)

@section('content')
@php
    $statusColor = match($purchaseOrder->status) {
        'draft'            => 'bg-gray-100 text-gray-600',
        'waiting_approval' => 'bg-yellow-50 text-yellow-700',
        'approved'         => 'bg-blue-50 text-blue-700',
        'partial'          => 'bg-orange-50 text-orange-700',
        'completed'        => 'bg-green-50 text-green-700',
        'cancelled'        => 'bg-red-50 text-red-700',
        default            => 'bg-gray-100 text-gray-600',
    };
    $statusLabel = match($purchaseOrder->status) {
        'draft'            => 'Draft',
        'waiting_approval' => 'Menunggu Approval',
        'approved'         => 'Disetujui',
        'partial'          => 'Diterima Sebagian',
        'completed'        => 'Selesai',
        'cancelled'        => 'Dibatalkan',
        default            => $purchaseOrder->status,
    };
@endphp

<div class="max-w-4xl space-y-6">

    {{-- Header PO --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex justify-between items-start mb-5">
            <div>
                <p class="text-xs text-gray-400 font-mono">{{ $purchaseOrder->po_number }}</p>
                <h3 class="font-semibold text-gray-900 text-lg mt-0.5">Purchase Order</h3>
            </div>
            <span class="px-3 py-1 text-sm rounded-full font-medium {{ $statusColor }}">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="grid grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Supplier</p>
                <p class="font-medium mt-0.5">{{ $purchaseOrder->supplier->name }}</p>
                <p class="text-xs text-gray-400">{{ $purchaseOrder->supplier->code }}</p>
            </div>
            <div>
                <p class="text-gray-500">Gudang Tujuan</p>
                <p class="font-medium mt-0.5">{{ $purchaseOrder->warehouse->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Dibuat Oleh</p>
                <p class="font-medium mt-0.5">{{ $purchaseOrder->creator->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal Order</p>
                <p class="font-medium mt-0.5">{{ $purchaseOrder->order_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Estimasi Terima</p>
                <p class="font-medium mt-0.5">
                    {{ $purchaseOrder->expected_date?->format('d M Y') ?? '-' }}
                </p>
            </div>
            <div>
                <p class="text-gray-500">Disetujui Oleh</p>
                <p class="font-medium mt-0.5">
                    {{ $purchaseOrder->approver?->name ?? '-' }}
                    @if($purchaseOrder->approved_at)
                        <span class="text-xs text-gray-400 block">
                            {{ $purchaseOrder->approved_at->format('d M Y H:i') }}
                        </span>
                    @endif
                </p>
            </div>
            @if($purchaseOrder->notes)
            <div class="col-span-3">
                <p class="text-gray-500">Catatan</p>
                <p class="mt-0.5">{{ $purchaseOrder->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Action buttons --}}
        <div class="flex gap-2 mt-5 pt-5 border-t border-gray-100">
            @if($purchaseOrder->status === 'draft')
                @can('create purchase-order')
                <form action="{{ route('purchasing.orders.submit', $purchaseOrder) }}" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-yellow-500 text-white text-sm rounded-lg hover:bg-yellow-600">
                        Ajukan Approval
                    </button>
                </form>
                <form action="{{ route('purchasing.orders.cancel', $purchaseOrder) }}" method="POST"
                      onsubmit="return confirm('Batalkan PO ini?')">
                    @csrf
                    <button class="px-4 py-2 border border-red-300 text-red-600 text-sm rounded-lg hover:bg-red-50">
                        Batalkan
                    </button>
                </form>
                @endcan
            @endif

            @if($purchaseOrder->status === 'waiting_approval')
                @can('approve purchase-order')
                <form action="{{ route('purchasing.orders.approve', $purchaseOrder) }}" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                        Setujui PO
                    </button>
                </form>
                @endcan
                @can('create purchase-order')
                <form action="{{ route('purchasing.orders.cancel', $purchaseOrder) }}" method="POST"
                      onsubmit="return confirm('Batalkan PO ini?')">
                    @csrf
                    <button class="px-4 py-2 border border-red-300 text-red-600 text-sm rounded-lg hover:bg-red-50">
                        Batalkan
                    </button>
                </form>
                @endcan
            @endif

            @if(in_array($purchaseOrder->status, ['approved', 'partial']))
                @can('receive goods')
                <a href="{{ route('purchasing.receipts.create', $purchaseOrder) }}"
                   class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Terima Barang
                </a>
                @endcan
            @endif
        </div>
    </div>

    {{-- Daftar barang --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
            <h4 class="text-sm font-semibold text-gray-700">Daftar Barang</h4>
            <p class="text-sm font-semibold text-gray-900">
                Total: Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}
            </p>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Barang</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Qty Order</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Qty Diterima</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Sisa</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Harga Satuan</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($purchaseOrder->items as $item)
                <tr>
                    <td class="px-4 py-2">
                        <p class="font-medium text-gray-900">{{ $item->item->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $item->item->code }}</p>
                    </td>
                    <td class="px-4 py-2 text-right">
                        {{ number_format($item->qty_ordered) }} {{ $item->item->unit->abbreviation }}
                    </td>
                    <td class="px-4 py-2 text-right text-green-600 font-medium">
                        {{ number_format($item->qty_received) }}
                    </td>
                    <td class="px-4 py-2 text-right {{ $item->remaining_qty > 0 ? 'text-orange-600' : 'text-gray-400' }}">
                        {{ number_format($item->remaining_qty) }}
                    </td>
                    <td class="px-4 py-2 text-right text-gray-600">
                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-2 text-right font-medium text-gray-900">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Riwayat penerimaan --}}
    @if($purchaseOrder->receipts->count() > 0)
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h4 class="text-sm font-semibold text-gray-700">Riwayat Penerimaan Barang</h4>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">No. GR</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Tanggal</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Diterima Oleh</th>
                    <th class="px-4 py-2 text-center text-gray-600 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($purchaseOrder->receipts as $receipt)
                <tr>
                    <td class="px-4 py-2 font-mono text-xs text-gray-700">{{ $receipt->receipt_number }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $receipt->receipt_date->format('d M Y') }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $receipt->receiver->name }}</td>
                    <td class="px-4 py-2 text-center">
                        <a href="{{ route('purchasing.receipts.show', $receipt) }}"
                           class="px-3 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <a href="{{ route('purchasing.orders.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
        &larr; Kembali
    </a>
</div>
@endsection