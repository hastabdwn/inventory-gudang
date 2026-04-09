@extends('layouts.app')
@section('title', 'Posisi Stok')

@section('content')
{{-- Summary cards --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Total Jenis Barang</p>
        <p class="text-2xl font-semibold text-gray-900 mt-1">{{ number_format($totalItems) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-yellow-200 p-4">
        <p class="text-xs text-yellow-600">Stok Rendah</p>
        <p class="text-2xl font-semibold text-yellow-600 mt-1">{{ number_format($lowStockCount) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-red-200 p-4">
        <p class="text-xs text-red-600">Stok Habis</p>
        <p class="text-2xl font-semibold text-red-600 mt-1">{{ number_format($emptyCount) }}</p>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama / kode barang..."
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-56 focus:outline-none focus:ring-2 focus:ring-blue-500">

        <select name="warehouse_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Gudang</option>
            @foreach($warehouses as $wh)
                <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                    {{ $wh->name }}
                </option>
            @endforeach
        </select>

        <select name="category_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <label class="flex items-center gap-1.5 px-3 py-2 border border-gray-300 rounded-lg text-sm cursor-pointer hover:bg-gray-50">
            <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }}
                   class="w-3.5 h-3.5 text-red-500">
            <span class="text-gray-700">Stok Rendah</span>
        </label>

        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
            Filter
        </button>
        @if(request()->hasAny(['search','warehouse_id','category_id','low_stock']))
        <a href="{{ route('stock.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif

        @can('adjust stock')
        <div class="ml-auto">
            <a href="{{ route('stock.adjustment.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                + Penyesuaian Stok
            </a>
        </div>
        @endcan
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Barang</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Kategori</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Gudang</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Stok</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Min. Stok</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Kondisi</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($stocks as $stock)
            @php $isLow = $stock->quantity <= $stock->item->min_stock; @endphp
            <tr class="hover:bg-gray-50 {{ $isLow && $stock->quantity > 0 ? 'bg-yellow-50' : '' }} {{ $stock->quantity == 0 ? 'bg-red-50' : '' }}">
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-900">{{ $stock->item->name }}</p>
                    <p class="text-xs text-gray-400 font-mono">{{ $stock->item->code }}</p>
                </td>
                <td class="px-4 py-3 text-gray-600 text-xs">{{ $stock->item->category->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $stock->warehouse->name }}</td>
                <td class="px-4 py-3 text-right font-semibold {{ $stock->quantity == 0 ? 'text-red-600' : ($isLow ? 'text-yellow-600' : 'text-gray-900') }}">
                    {{ number_format($stock->quantity) }}
                    <span class="text-xs font-normal text-gray-400">{{ $stock->item->unit->abbreviation }}</span>
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
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <a href="{{ route('stock.movements.index', ['item_id' => $stock->item_id, 'warehouse_id' => $stock->warehouse_id]) }}"
                           class="px-2.5 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">
                            Mutasi
                        </a>
                        @can('adjust stock')
                        <a href="{{ route('stock.adjustment.create', ['item_id' => $stock->item_id, 'warehouse_id' => $stock->warehouse_id]) }}"
                           class="px-2.5 py-1 text-xs border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50">
                            Sesuaikan
                        </a>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada data stok.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">{{ $stocks->total() }} record</p>
        {{ $stocks->links() }}
    </div>
</div>
@endsection