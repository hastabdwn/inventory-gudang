@extends('layouts.app')
@section('title', 'Buat Retur Barang')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">

        {{-- Info PO jika dari halaman PO --}}
        @if($selectedPo)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-sm">
            <p class="text-blue-700 font-medium mb-1">Retur dari PO: {{ $selectedPo->po_number }}</p>
            <p class="text-blue-600 text-xs">Supplier: {{ $selectedPo->supplier->name }}</p>
        </div>
        @endif

        <form action="{{ route('returns.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-6">
                {{-- Supplier --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Supplier <span class="text-red-500">*</span>
                    </label>
                    <select name="supplier_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('supplier_id') border-red-400 @enderror">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}"
                                {{ old('supplier_id', $selectedPo?->supplier_id) == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gudang --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Gudang Asal <span class="text-red-500">*</span>
                    </label>
                    <select name="warehouse_id" id="warehouse_id" required
                            onchange="updateStockInfo()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('warehouse_id') border-red-400 @enderror">
                        <option value="">-- Pilih Gudang --</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No. PO (opsional) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Referensi PO (Opsional)
                    </label>
                    <select name="purchase_order_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Tanpa referensi PO --</option>
                        @foreach(\App\Models\PurchaseOrder::whereIn('status', ['completed','partial'])->latest()->get() as $po)
                            <option value="{{ $po->id }}"
                                {{ old('purchase_order_id', $selectedPo?->id) == $po->id ? 'selected' : '' }}>
                                {{ $po->po_number }} — {{ $po->supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Retur <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="return_date"
                           value="{{ old('return_date', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Alasan --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Alasan Retur <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reason') border-red-400 @enderror"
                              placeholder="Contoh: Barang rusak, tidak sesuai spesifikasi, kelebihan pengiriman, dll.">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                    <textarea name="notes" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Opsional">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Item rows --}}
            <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Daftar Barang yang Diretur</span>
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
                            <th class="px-3 py-2 text-left text-gray-600 font-medium w-28">Qty Retur</th>
                            <th class="px-3 py-2 text-left text-gray-600 font-medium w-36">Harga Satuan (Rp)</th>
                            <th class="px-3 py-2 text-left text-gray-600 font-medium">Keterangan</th>
                            <th class="px-3 py-2 w-10"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body">
                        <tr id="row-0" class="border-b border-gray-100">
                            <td class="px-3 py-2">
                                <select name="items[0][item_id]" required
                                        onchange="updateAvailableStock(0)"
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}"
                                                data-price="{{ $item->purchase_price }}"
                                                data-stocks="{{ json_encode($item->stocks->pluck('quantity', 'warehouse_id')) }}">
                                            {{ $item->code }} — {{ $item->name }} ({{ $item->unit->abbreviation }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-3 py-2">
                                <span id="avail-0" class="text-gray-400 text-xs">Pilih gudang dulu</span>
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" name="items[0][quantity]"
                                       min="1" required
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" name="items[0][unit_price]"
                                       min="0" step="1" id="price-0"
                                       placeholder="0"
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-3 py-2">
                                <input type="text" name="items[0][notes]"
                                       placeholder="Kondisi barang, dll."
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
                    Simpan sebagai Draft
                </button>
                <a href="{{ route('returns.index') }}"
                   class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<meta name="items-data"
      content="{{ htmlspecialchars(json_encode($items->map(fn($i) => [
          'id'     => $i->id,
          'code'   => $i->code,
          'name'   => $i->name,
          'unit'   => $i->unit->abbreviation,
          'price'  => $i->purchase_price,
          'stocks' => $i->stocks->pluck('quantity', 'warehouse_id'),
      ])), ENT_QUOTES, 'UTF-8') }}">

<script>
let rowCount  = 1;
const itemsData = JSON.parse(
    document.querySelector('meta[name="items-data"]').getAttribute('content')
);

function buildOptions() {
    return itemsData.map(item =>
        '<option value="' + item.id + '"' +
        ' data-price="' + item.price + '"' +
        ' data-stocks=\'' + JSON.stringify(item.stocks) + '\'>' +
        item.code + ' — ' + item.name + ' (' + item.unit + ')' +
        '</option>'
    ).join('');
}

function addRow() {
    const i    = rowCount++;
    const body = document.getElementById('items-body');
    const tr   = document.createElement('tr');
    tr.id      = 'row-' + i;
    tr.className = 'border-b border-gray-100';
    tr.innerHTML = `
        <td class="px-3 py-2">
            <select name="items[${i}][item_id]" required
                    onchange="updateAvailableStock(${i}); fillPrice(${i})"
                    class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Barang --</option>
                ${buildOptions()}
            </select>
        </td>
        <td class="px-3 py-2">
            <span id="avail-${i}" class="text-gray-400 text-xs">Pilih gudang dulu</span>
        </td>
        <td class="px-3 py-2">
            <input type="number" name="items[${i}][quantity]" min="1" required
                   class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </td>
        <td class="px-3 py-2">
            <input type="number" name="items[${i}][unit_price]" min="0" step="1"
                   id="price-${i}" placeholder="0"
                   class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </td>
        <td class="px-3 py-2">
            <input type="text" name="items[${i}][notes]"
                   placeholder="Kondisi barang, dll."
                   class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </td>
        <td class="px-3 py-2 text-center">
            <button type="button" onclick="removeRow(${i})"
                    class="text-red-400 hover:text-red-600 text-xs">✕</button>
        </td>`;
    body.appendChild(tr);
}

function removeRow(i) {
    const row = document.getElementById('row-' + i);
    if (row) row.remove();
}

function updateAvailableStock(i) {
    const select      = document.querySelector('[name="items[' + i + '][item_id]"]');
    const warehouseId = document.getElementById('warehouse_id').value;
    const option      = select.options[select.selectedIndex];
    const availEl     = document.getElementById('avail-' + i);

    if (!warehouseId) {
        availEl.textContent = 'Pilih gudang dulu';
        availEl.className   = 'text-gray-400 text-xs';
        return;
    }

    if (!option || !option.dataset.stocks) {
        availEl.textContent = '-';
        availEl.className   = 'text-gray-400 text-xs';
        return;
    }

    const stocks = JSON.parse(option.dataset.stocks);
    const avail  = stocks[warehouseId] ?? 0;
    availEl.textContent = avail + ' tersedia';
    availEl.className   = avail > 0
        ? 'text-green-600 text-xs font-medium'
        : 'text-red-500 text-xs font-medium';
}

function fillPrice(i) {
    const select  = document.querySelector('[name="items[' + i + '][item_id]"]');
    const option  = select.options[select.selectedIndex];
    const priceEl = document.getElementById('price-' + i);
    if (option && option.dataset.price && priceEl) {
        priceEl.value = option.dataset.price || 0;
    }
}

function updateStockInfo() {
    const rows = document.querySelectorAll('[id^="row-"]');
    rows.forEach((row, i) => updateAvailableStock(i));
}

document.querySelector('[name="items[0][item_id]"]')
    ?.addEventListener('change', () => { updateAvailableStock(0); fillPrice(0); });
</script>
@endsection