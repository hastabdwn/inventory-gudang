@extends('layouts.app')
@section('title', 'Detail Supplier')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex justify-between items-start mb-4">
            <h3 class="font-semibold text-gray-900">{{ $supplier->name }}</h3>
            <span class="px-2 py-1 text-xs rounded-full {{ $supplier->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $supplier->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Kode</dt>
                <dd class="font-mono font-medium mt-0.5">{{ $supplier->code }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Contact Person</dt>
                <dd class="mt-0.5">{{ $supplier->contact_person ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Telepon</dt>
                <dd class="mt-0.5">{{ $supplier->phone ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Email</dt>
                <dd class="mt-0.5">{{ $supplier->email ?? '-' }}</dd>
            </div>
            <div class="col-span-2">
                <dt class="text-gray-500">Alamat</dt>
                <dd class="mt-0.5">{{ $supplier->address ?? '-' }}</dd>
            </div>
        </dl>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
            <h4 class="text-sm font-semibold text-gray-700">Riwayat Purchase Order</h4>
            <span class="text-xs text-gray-400">{{ $supplier->purchaseOrders->count() }} PO</span>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">No. PO</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Tanggal</th>
                    <th class="px-4 py-2 text-center text-gray-600 font-medium">Status</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($supplier->purchaseOrders->take(10) as $po)
                <tr>
                    <td class="px-4 py-2 font-mono text-xs text-gray-700">{{ $po->po_number }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $po->order_date->format('d M Y') }}</td>
                    <td class="px-4 py-2 text-center">
                        <span class="px-2 py-0.5 text-xs rounded-full
                            @if($po->status === 'completed') bg-green-50 text-green-700
                            @elseif($po->status === 'cancelled') bg-red-50 text-red-700
                            @elseif($po->status === 'approved') bg-blue-50 text-blue-700
                            @else bg-yellow-50 text-yellow-700 @endif">
                            {{ ucfirst(str_replace('_', ' ', $po->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-right font-medium">
                        Rp {{ number_format($po->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada riwayat PO.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('master.suppliers.index') }}" class="text-sm text-blue-600 hover:underline">
            &larr; Kembali
        </a>
        @can('manage master-data')
        <a href="{{ route('master.suppliers.edit', $supplier) }}" class="text-sm text-blue-600 hover:underline">
            Edit Supplier
        </a>
        @endcan
    </div>
</div>
@endsection