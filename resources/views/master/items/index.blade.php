@extends('layouts.app')
@section('title', 'Data Barang')

@section('content')
<div class="flex justify-between items-center mb-6">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama / kode / barcode..."
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
        <button type="submit"
                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
            Cari
        </button>
        @if(request()->hasAny(['search', 'category_id', 'low_stock']))
        <a href="{{ route('master.items.index') }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>

    @can('manage master-data')
    <a href="{{ route('master.items.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
        + Tambah Barang
    </a>
    @endcan
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Barang</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Kategori</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Satuan</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Stok Total</th>
                <th class="px-4 py-3 text-right text-gray-600 font-medium">Min. Stok</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Status</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($items as $item)
            <tr class="hover:bg-gray-50 {{ $item->is_low_stock ? 'bg-red-50' : '' }}">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                 class="w-9 h-9 rounded-lg object-cover border border-gray-200">
                        @else
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs">
                                IMG
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $item->code }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $item->category->name }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded font-mono text-xs">
                        {{ $item->unit->abbreviation }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <span class="font-medium {{ $item->is_low_stock ? 'text-red-600' : 'text-gray-900' }}">
                        {{ number_format($item->stocks_sum_quantity ?? 0) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right text-gray-500">
                    {{ number_format($item->min_stock) }}
                </td>
                <td class="px-4 py-3 text-center">
                    @if($item->is_low_stock)
                        <span class="px-2 py-1 bg-red-50 text-red-700 rounded-full text-xs font-medium">Stok Rendah</span>
                    @elseif($item->is_active)
                        <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-medium">Nonaktif</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <a href="{{ route('master.items.show', $item) }}"
                           class="px-2.5 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">Detail</a>
                        <a href="{{ route('master.items.barcode', $item) }}"
                           class="px-2.5 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">Barcode</a>
                        <a href="{{ route('master.items.qrcode', $item) }}"
                           class="px-2.5 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">QR</a>
                        @can('manage master-data')
                        <a href="{{ route('master.items.edit', $item) }}"
                           class="px-2.5 py-1 text-xs border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50">Edit</a>
                        <form action="{{ route('master.items.destroy', $item) }}" method="POST"
                              onsubmit="return confirm('Hapus barang ini?')">
                            @csrf @method('DELETE')
                            <button class="px-2.5 py-1 text-xs border border-red-300 text-red-600 rounded-lg hover:bg-red-50">Hapus</button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada data barang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">Total {{ $items->total() }} barang</p>
        {{ $items->links() }}
    </div>
</div>
@endsection