@extends('layouts.app')
@section('title', 'Penyesuaian Stok')

@php
    $stockJson = \App\Models\ItemStock::all()
        ->groupBy('item_id')
        ->map(fn($s) => $s->pluck('quantity', 'warehouse_id'));
@endphp

@section('content')
<meta name="stock-data" content="{{ htmlspecialchars(json_encode($stockJson), ENT_QUOTES, 'UTF-8') }}">

<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <p class="text-sm text-gray-500 mb-5">
            Gunakan fitur ini untuk mengoreksi stok yang tidak sesuai dengan kondisi fisik di gudang.
            Setiap penyesuaian akan dicatat di histori mutasi.
        </p>

        <form action="{{ route('stock.adjustment.store') }}" method="POST">
            @csrf
            <div class="space-y-4">

                {{-- Barang --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Barang <span class="text-red-500">*</span>
                    </label>
                    <select name="item_id" id="item_id" required
                            onchange="fetchStock()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('item_id') border-red-400 @enderror">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}"
                                {{ old('item_id', $selectedItem?->id) == $item->id ? 'selected' : '' }}>
                                {{ $item->code }} — {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gudang --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Gudang <span class="text-red-500">*</span>
                    </label>
                    <select name="warehouse_id" id="warehouse_id" required
                            onchange="fetchStock()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('warehouse_id') border-red-400 @enderror">
                        <option value="">-- Pilih Gudang --</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}"
                                {{ old('warehouse_id', $selectedWarehouse?->id) == $wh->id ? 'selected' : '' }}>
                                {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stok saat ini --}}
                <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500">Stok saat ini di sistem</p>
                    <p class="text-xl font-semibold text-gray-900 mt-1"
                       id="current-stock-display"
                       data-stock="{{ $currentStock }}">
                        {{ $currentStock }}
                    </p>
                </div>

                {{-- Stok baru --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Stok Sebenarnya (hasil hitung fisik) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="new_quantity" id="new_quantity"
                           value="{{ old('new_quantity') }}"
                           min="0" required
                           oninput="showDiff()"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('new_quantity') border-red-400 @enderror">
                    <div id="diff-display" class="mt-1 text-xs hidden"></div>
                    @error('new_quantity')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alasan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Alasan Penyesuaian <span class="text-red-500">*</span>
                    </label>
                    <textarea name="notes" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-400 @enderror"
                              placeholder="Contoh: Hasil stock opname tanggal 08/04/2026">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Simpan Penyesuaian
                </button>
                <a href="{{ route('stock.index') }}"
                   class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
const stockData = JSON.parse(
    document.querySelector('meta[name="stock-data"]').getAttribute('content')
);

function fetchStock() {
    const itemId      = document.getElementById('item_id').value;
    const warehouseId = document.getElementById('warehouse_id').value;
    const display     = document.getElementById('current-stock-display');
    let stock         = 0;

    if (itemId && warehouseId && stockData[itemId]) {
        stock = stockData[itemId][warehouseId] ?? 0;
    }

    display.textContent   = stock;
    display.dataset.stock = stock;
    showDiff();
}

function showDiff() {
    const current = parseInt(document.getElementById('current-stock-display').dataset.stock ?? 0);
    const newVal  = parseInt(document.getElementById('new_quantity').value);
    const diffEl  = document.getElementById('diff-display');

    if (isNaN(newVal)) {
        diffEl.classList.add('hidden');
        return;
    }

    const diff = newVal - current;
    diffEl.classList.remove('hidden');

    if (diff > 0) {
        diffEl.className   = 'mt-1 text-xs text-green-600';
        diffEl.textContent = 'Stok akan bertambah +' + diff;
    } else if (diff < 0) {
        diffEl.className   = 'mt-1 text-xs text-red-600';
        diffEl.textContent = 'Stok akan berkurang ' + diff;
    } else {
        diffEl.className   = 'mt-1 text-xs text-gray-400';
        diffEl.textContent = 'Tidak ada perubahan stok';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const itemId      = document.getElementById('item_id').value;
    const warehouseId = document.getElementById('warehouse_id').value;
    if (itemId && warehouseId) fetchStock();
});
</script>
@endsection