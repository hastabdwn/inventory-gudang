@extends('layouts.app')
@section('title', 'Stok — ' . $warehouse->name)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-xs text-gray-400">Gudang</p>
        <h3 class="font-semibold text-gray-900">{{ $warehouse->name }}</h3>
        <p class="text-xs text-gray-500 mt-0.5">{{ $warehouse->location ?? '-' }}</p>
    </div>
    <a href="{{ route('stock.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Semua Stok</a>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama / kode barang..."
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
            Cari
        </button>
        @if(request('search'))
        <a href="{{ route('stock.by-warehouse', $warehouse) }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Barang</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Kategori</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Satuan</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Stok</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Min. Stok</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Kondisi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($stocks as $stock)
            @php $isLow = $stock->quantity <= $stock->item->min_stock; @endphp
            <tr class="hover:bg-gray-50 {{ $stock->quantity == 0 ? 'bg-red-50' : ($isLow ? 'bg-yellow-50' : '') }}">
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-900">{{ $stock->item->name }}</p>
                    <p class="text-xs text-gray-400 font-mono">{{ $stock->item->code }}</p>
                </td>
                <td class="px-4 py-3 text-gray-600 text-xs">{{ $stock->item->category->name }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded font-mono text-xs">
                        {{ $stock->item->unit->abbreviation }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right font-semibold
                    {{ $stock->quantity == 0 ? 'text-red-600' : ($isLow ? 'text-yellow-600' : 'text-gray-900') }}">
                    {{ number_format($stock->quantity) }}
                </td>
                <td class="px-4 py-3 text-right text-gray-400 text-xs">
                    {{ number_format($stock->item->min_stock) }}
                </td>
                <td class="px-4 py-3 text-center">
                    @if($stock->quantity == 0)
                        <span class="px-2 py-1 bg-red-50 text-red-700 rounded-full text-xs font-medium">Habis</span>
                    @elseif($isLow)
                        <span class="px-2 py-1 bg-yellow-50 text-yellow-700 rounded-full text-xs font-medium">Rendah</span>
                    @else
                        <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">Normal</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada stok di gudang ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">{{ $stocks->total() }} barang</p>
        {{ $stocks->links() }}
    </div>
</div>
@endsection