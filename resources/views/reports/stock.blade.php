@extends('layouts.app')
@section('title', 'Laporan Stok')

@section('content')
{{-- Filter & Export --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
               placeholder="Cari barang..."
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-48 focus:outline-none focus:ring-2 focus:ring-blue-500">

        <select name="warehouse_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Gudang</option>
            @foreach($warehouses as $wh)
                <option value="{{ $wh->id }}" {{ ($filters['warehouse_id'] ?? '') == $wh->id ? 'selected' : '' }}>
                    {{ $wh->name }}
                </option>
            @endforeach
        </select>

        <select name="category_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <label class="flex items-center gap-1.5 px-3 py-2 border border-gray-300 rounded-lg text-sm cursor-pointer">
            <input type="checkbox" name="low_stock" value="1"
                   {{ !empty($filters['low_stock']) ? 'checked' : '' }}
                   class="w-3.5 h-3.5">
            <span>Stok Rendah / Habis</span>
        </label>

        <button type="submit"
                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
            Filter
        </button>
        @if(array_filter($filters))
        <a href="{{ route('reports.stock') }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif

        @can('export report')
        <div class="ml-auto flex gap-2">
            <a href="{{ route('reports.stock.export', array_merge(['format' => 'excel'], $filters)) }}"
               class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                Ekspor Excel
            </a>
            <a href="{{ route('reports.stock.export', array_merge(['format' => 'pdf'], $filters)) }}"
               class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">
                Ekspor PDF
            </a>
        </div>
        @endcan
    </form>
</div>

{{-- Summary --}}
<div class="grid grid-cols-3 gap-4 mb-4">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Total Record</p>
        <p class="text-xl font-semibold text-gray-900 mt-1">{{ number_format($data->count()) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Total Stok</p>
        <p class="text-xl font-semibold text-gray-900 mt-1">{{ number_format($data->sum('quantity')) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-yellow-200 p-4">
        <p class="text-xs text-yellow-600">Stok Rendah / Habis</p>
        <p class="text-xl font-semibold text-yellow-600 mt-1">
            {{ $data->filter(fn($s) => $s->quantity <= $s->item->min_stock)->count() }}
        </p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Kode</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Nama Barang</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Kategori</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Gudang</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Stok</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Min. Stok</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $i => $stock)
            @php $isLow = $stock->quantity <= $stock->item->min_stock; @endphp
            <tr class="{{ $stock->quantity == 0 ? 'bg-red-50' : ($isLow ? 'bg-yellow-50' : 'hover:bg-gray-50') }}">
                <td class="px-4 py-2 text-gray-400 text-xs">{{ $i + 1 }}</td>
                <td class="px-4 py-2 font-mono text-xs text-gray-600">{{ $stock->item->code }}</td>
                <td class="px-4 py-2 font-medium text-gray-900">{{ $stock->item->name }}</td>
                <td class="px-4 py-2 text-gray-600 text-xs">{{ $stock->item->category->name }}</td>
                <td class="px-4 py-2 text-gray-600">{{ $stock->warehouse->name }}</td>
                <td class="px-4 py-2 text-right font-semibold
                    {{ $stock->quantity == 0 ? 'text-red-600' : ($isLow ? 'text-yellow-600' : 'text-gray-900') }}">
                    {{ number_format($stock->quantity) }} {{ $stock->item->unit->abbreviation }}
                </td>
                <td class="px-4 py-2 text-right text-gray-400 text-xs">
                    {{ number_format($stock->item->min_stock) }}
                </td>
                <td class="px-4 py-2 text-center">
                    @if($stock->quantity == 0)
                        <span class="px-2 py-1 bg-red-50 text-red-700 rounded-full text-xs">Habis</span>
                    @elseif($isLow)
                        <span class="px-2 py-1 bg-yellow-50 text-yellow-700 rounded-full text-xs">Rendah</span>
                    @else
                        <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs">Normal</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-8 text-center text-gray-400">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection