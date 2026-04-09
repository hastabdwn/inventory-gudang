@extends('layouts.app')
@section('title', 'Transfer Stok')

@section('content')
<div class="flex justify-between items-center mb-6">
    <form method="GET" class="flex gap-2">
        <select name="status"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Filter</button>
    </form>

    @can('create transfer')
    <a href="{{ route('stock.transfer.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
        + Transfer Stok
    </a>
    @endcan
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No. Transfer</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Dari Gudang</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Ke Gudang</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Tanggal</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Oleh</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Status</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($transfers as $transfer)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $transfer->transfer_number }}</td>
                <td class="px-4 py-3 text-gray-900">{{ $transfer->fromWarehouse->name }}</td>
                <td class="px-4 py-3 text-gray-900">{{ $transfer->toWarehouse->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $transfer->transfer_date->format('d M Y') }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $transfer->transferredBy->name }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-1 text-xs rounded-full
                        {{ $transfer->status === 'completed' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                        {{ $transfer->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('stock.transfer.show', $transfer) }}"
                       class="px-3 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada data transfer.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $transfers->links() }}
    </div>
</div>
@endsection