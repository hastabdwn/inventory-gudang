@extends('layouts.app')
@section('title', 'Transfer Stok Antar Gudang')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form action="{{ route('stock.transfer.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Dari Gudang <span class="text-red-500">*</span>
                    </label>
                    <select name="from_warehouse_id" id="from_warehouse_id" required
                            onchange="updateStockInfo()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('from_warehouse_id') border-red-400 @enderror">
                        <option value="">-- Pilih Gudang Asal --</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ old('from_warehouse_id') == $wh->id ? 'selected' : '' }}>
                                {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('from_warehouse_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Ke Gudang <span class="text-red-500">*</span>
                    </label>
                    <select name="to_warehouse_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('to_warehouse_id') border-red-400 @enderror">
                        <option value="">-- Pilih Gudang Tujuan --</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ old('to_warehouse_id') == $wh->id ? 'selected' : '' }}>
                                {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('to_warehouse_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Transfer <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="transfer_date" value="{{ old('transfer_date', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <input type="text" name="notes" value="{{ old('notes') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Opsional">
                </div>
            </div>

            {{-- Item rows --}}
            <div class="border border-gray-200 rounded-lg overflow-hidden mb-4">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Daftar Barang</span>
                    <button type="button" onclick="addRow()"
                            class="px-3 py-1 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        + Tambah Baris
                    </button>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-3 py-2 text-left text-gray-600 font-medium">Barang</th>
                            <th class="px-3 py-2 text-left text-gray-600 font-medium">Stok Tersedia</th>
                            <th class="px-3 py-2 text-left text-gray-600 font-medium w-32">Qty Transfer</th>
                            <th class="px-3 py-2 w-10"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body">
                        <tr id="row-0" class="border-b border-gray-100">
                            <td class="px-3 py-2">
                                <select name="items[0][item_id]" required onchange="updateAvailableStock(0)"
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}"
                                            data-stocks="{{ json_encode($item->stocks->pluck('quantity', 'warehouse_id')) }}">
                                            {{ $item->code }} — {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-3 py-2">
                                <span id="avail-0" class="text-gray-400 text-xs">-</span>
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" name="items[0][quantity]" min="1" required
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-3 py-2 text-center">
                                <button type="button" onclick="removeRow(0)"
                                        class="text-red-400 hover:text-red-600 text-xs">✕</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @error('items')
                <p class="mb-3 text-xs text-red-500">{{ $message }}</p>
            @enderror

            <div class="flex gap-3">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Proses Transfer
                </button>
                <a href="{{ route('stock.transfer.index') }}"
                   class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let rowCount = 1;

const itemTemplate = `@foreach($items as $item)<option value="{{ $item->id }}" data-stocks='{{ json_encode($item->stocks->pluck('quantity', 'warehouse_id')) }}'>{{ $item->code }} — {{ $item->name }}</option>@endforeach`;

function addRow() {
    const i    = rowCount++;
    const body = document.getElementById('items-body');
    const tr   = document.createElement('tr');
    tr.id      = `row-${i}`;
    tr.className = 'border-b border-gray-100';
    tr.innerHTML = `
        <td class="px-3 py-2">
            <select name="items[${i}][item_id]" required onchange="updateAvailableStock(${i})"
                    class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Barang --</option>
                ${itemTemplate}
            </select>
        </td>
        <td class="px-3 py-2"><span id="avail-${i}" class="text-gray-400 text-xs">-</span></td>
        <td class="px-3 py-2">
            <input type="number" name="items[${i}][quantity]" min="1" required
                   class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </td>
        <td class="px-3 py-2 text-center">
            <button type="button" onclick="removeRow(${i})" class="text-red-400 hover:text-red-600 text-xs">✕</button>
        </td>`;
    body.appendChild(tr);
}

function removeRow(i) {
    const row = document.getElementById(`row-${i}`);
    if (row) row.remove();
}

function updateAvailableStock(i) {
    const select      = document.querySelector(`[name="items[${i}][item_id]"]`);
    const warehouseId = document.getElementById('from_warehouse_id').value;
    const option      = select.options[select.selectedIndex];
    const availEl     = document.getElementById(`avail-${i}`);

    if (!option || !option.dataset.stocks || !warehouseId) {
        availEl.textContent = '-';
        return;
    }

    const stocks = JSON.parse(option.dataset.stocks);
    const avail  = stocks[warehouseId] ?? 0;
    availEl.textContent = avail + ' tersedia';
    availEl.className   = avail > 0 ? 'text-green-600 text-xs font-medium' : 'text-red-500 text-xs';
}

function updateStockInfo() {
    document.querySelectorAll('[id^="avail-"]').forEach((el, i) => {
        updateAvailableStock(i);
    });
}
</script>
@endsection