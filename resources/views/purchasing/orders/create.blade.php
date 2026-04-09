@extends('layouts.app')
@section('title', 'Buat Purchase Order')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form action="{{ route('purchasing.orders.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Supplier <span class="text-red-500">*</span>
                    </label>
                    <select name="supplier_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('supplier_id') border-red-400 @enderror">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Gudang Tujuan <span class="text-red-500">*</span>
                    </label>
                    <select name="warehouse_id" required
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

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Order <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="order_date"
                           value="{{ old('order_date', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Estimasi Tanggal Terima
                    </label>
                    <input type="date" name="expected_date"
                           value="{{ old('expected_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Item rows --}}
            <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
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
                            <th class="px-3 py-2 text-left text-gray-600 font-medium w-28">Qty</th>
                            <th class="px-3 py-2 text-left text-gray-600 font-medium w-36">Harga Satuan (Rp)</th>
                            <th class="px-3 py-2 text-right text-gray-600 font-medium w-36">Subtotal</th>
                            <th class="px-3 py-2 w-10"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body">
                        <tr id="row-0" class="border-b border-gray-100">
                            <td class="px-3 py-2">
                                <select name="items[0][item_id]" required
                                        class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}"
                                                data-price="{{ $item->purchase_price }}">
                                            {{ $item->code }} — {{ $item->name }} ({{ $item->unit->abbreviation }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" name="items[0][qty_ordered]"
                                       min="1" required
                                       oninput="calcSubtotal(0)"
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" name="items[0][unit_price]"
                                       min="0" step="1"
                                       oninput="calcSubtotal(0)"
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       id="price-0">
                            </td>
                            <td class="px-3 py-2 text-right font-medium text-gray-900" id="subtotal-0">
                                Rp 0
                            </td>
                            <td class="px-3 py-2 text-center">
                                <button type="button" onclick="removeRow(0)"
                                        class="text-red-400 hover:text-red-600 text-xs">✕</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="3" class="px-3 py-2 text-right text-sm font-semibold text-gray-700">
                                Total
                            </td>
                            <td class="px-3 py-2 text-right font-semibold text-gray-900" id="grand-total">
                                Rp 0
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
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
                <a href="{{ route('purchasing.orders.index') }}"
                   class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let rowCount = 1;

const itemOptions = `@foreach($items as $item)<option value="{{ $item->id }}" data-price="{{ $item->purchase_price }}">{{ $item->code }} — {{ $item->name }} ({{ $item->unit->abbreviation }})</option>@endforeach`;

function addRow() {
    const i    = rowCount++;
    const body = document.getElementById('items-body');
    const tr   = document.createElement('tr');
    tr.id      = 'row-' + i;
    tr.className = 'border-b border-gray-100';
    tr.innerHTML = `
        <td class="px-3 py-2">
            <select name="items[${i}][item_id]" required onchange="fillPrice(${i})"
                    class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Pilih Barang --</option>
                ${itemOptions}
            </select>
        </td>
        <td class="px-3 py-2">
            <input type="number" name="items[${i}][qty_ordered]" min="1" required
                   oninput="calcSubtotal(${i})"
                   class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </td>
        <td class="px-3 py-2">
            <input type="number" name="items[${i}][unit_price]" min="0" step="1"
                   oninput="calcSubtotal(${i})"
                   id="price-${i}"
                   class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </td>
        <td class="px-3 py-2 text-right font-medium text-gray-900" id="subtotal-${i}">Rp 0</td>
        <td class="px-3 py-2 text-center">
            <button type="button" onclick="removeRow(${i})" class="text-red-400 hover:text-red-600 text-xs">✕</button>
        </td>`;
    body.appendChild(tr);
}

function removeRow(i) {
    const row = document.getElementById('row-' + i);
    if (row) { row.remove(); calcGrandTotal(); }
}

function fillPrice(i) {
    const select = document.querySelector('[name="items[' + i + '][item_id]"]');
    const option = select.options[select.selectedIndex];
    const price  = option ? option.dataset.price : 0;
    const priceEl = document.getElementById('price-' + i);
    if (priceEl) priceEl.value = price || 0;
    calcSubtotal(i);
}

function calcSubtotal(i) {
    const qty   = parseFloat(document.querySelector('[name="items[' + i + '][qty_ordered]"]')?.value) || 0;
    const price = parseFloat(document.getElementById('price-' + i)?.value) || 0;
    const sub   = qty * price;
    const el    = document.getElementById('subtotal-' + i);
    if (el) el.textContent = 'Rp ' + sub.toLocaleString('id-ID');
    calcGrandTotal();
}

function calcGrandTotal() {
    let total = 0;
    document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
        const val = el.textContent.replace('Rp ', '').replace(/\./g, '').replace(',', '.');
        total += parseFloat(val) || 0;
    });
    document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

document.querySelector('[name="items[0][item_id]"]')?.addEventListener('change', () => fillPrice(0));
</script>
@endsection