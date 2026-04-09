@extends('layouts.app')
@section('title', 'Detail Gudang')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex justify-between items-start mb-4">
            <h3 class="font-semibold text-gray-900">{{ $warehouse->name }}</h3>
            <span class="px-2 py-1 text-xs rounded-full {{ $warehouse->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $warehouse->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Kode</dt>
                <dd class="font-mono font-medium mt-0.5">{{ $warehouse->code }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Lokasi</dt>
                <dd class="mt-0.5">{{ $warehouse->location ?? '-' }}</dd>
            </div>
            <div class="col-span-2">
                <dt class="text-gray-500">Deskripsi</dt>
                <dd class="mt-0.5">{{ $warehouse->description ?? '-' }}</dd>
            </div>
        </dl>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h4 class="text-sm font-semibold text-gray-700">Stok di Gudang Ini</h4>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Barang</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Stok</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($warehouse->stocks as $stock)
                <tr>
                    <td class="px-4 py-2 text-gray-900">{{ $stock->item->name }}</td>
                    <td class="px-4 py-2 text-right font-medium">{{ number_format($stock->quantity) }} {{ $stock->item->unit->abbreviation ?? '' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-4 py-6 text-center text-gray-400">Belum ada stok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('master.warehouses.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
        &larr; Kembali
    </a>
</div>
@endsection