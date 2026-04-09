@extends('layouts.app')
@section('title', 'Detail Barang')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- Info utama --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex gap-6">
            {{-- Foto --}}
            <div class="flex-shrink-0">
                @if($item->image)
                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                         class="w-28 h-28 object-cover rounded-xl border border-gray-200">
                @else
                    <div class="w-28 h-28 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
                        No Image
                    </div>
                @endif
            </div>

            {{-- Detail --}}
            <div class="flex-1">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-gray-900 text-lg">{{ $item->name }}</h3>
                        <p class="text-sm font-mono text-gray-500 mt-0.5">{{ $item->code }}</p>
                    </div>
                    <div class="flex gap-2">
                        @if($item->is_low_stock)
                            <span class="px-2 py-1 bg-red-50 text-red-700 rounded-full text-xs font-medium">Stok Rendah</span>
                        @endif
                        <span class="px-2 py-1 text-xs rounded-full {{ $item->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                <dl class="grid grid-cols-3 gap-4 text-sm mt-4">
                    <div>
                        <dt class="text-gray-500">Kategori</dt>
                        <dd class="font-medium mt-0.5">{{ $item->category->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Satuan</dt>
                        <dd class="font-medium mt-0.5">{{ $item->unit->name }} ({{ $item->unit->abbreviation }})</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Harga Beli</dt>
                        <dd class="font-medium mt-0.5">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Stok Total</dt>
                        <dd class="font-medium mt-0.5 {{ $item->is_low_stock ? 'text-red-600' : '' }}">
                            {{ number_format($item->total_stock) }} {{ $item->unit->abbreviation }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Stok Minimum</dt>
                        <dd class="font-medium mt-0.5">{{ number_format($item->min_stock) }} {{ $item->unit->abbreviation }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Barcode</dt>
                        <dd class="font-mono text-xs mt-0.5">{{ $item->barcode ?? '-' }}</dd>
                    </div>
                </dl>

                @if($item->description)
                    <p class="text-sm text-gray-600 mt-3 pt-3 border-t border-gray-100">{{ $item->description }}</p>
                @endif
            </div>
        </div>

        {{-- Tombol aksi --}}
        <div class="flex gap-2 mt-5 pt-5 border-t border-gray-100">
            <a href="{{ route('master.items.barcode', $item) }}"
               class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">
                Cetak Barcode
            </a>
            <a href="{{ route('master.items.qrcode', $item) }}"
               class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">
                Cetak QR Code
            </a>
            @can('manage master-data')
            <a href="{{ route('master.items.edit', $item) }}"
               class="px-4 py-2 border border-blue-300 text-blue-600 text-sm rounded-lg hover:bg-blue-50">
                Edit Barang
            </a>
            @endcan
        </div>
    </div>

    {{-- Stok per gudang --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h4 class="text-sm font-semibold text-gray-700">Stok per Gudang</h4>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Gudang</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Lokasi</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Stok</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($item->stocks as $stock)
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-900">{{ $stock->warehouse->name }}</td>
                    <td class="px-4 py-2 text-gray-500">{{ $stock->warehouse->location ?? '-' }}</td>
                    <td class="px-4 py-2 text-right font-medium">
                        {{ number_format($stock->quantity) }} {{ $item->unit->abbreviation }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada stok di gudang manapun.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('master.items.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
        &larr; Kembali
    </a>
</div>
@endsection