@extends('layouts.app')
@section('title', 'Buat Distribusi')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form action="{{ route('distribution.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-6">
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

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Distribusi <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="dist_date"
                           value="{{ old('dist_date', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tujuan / Divisi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="destination"
                           value="{{ old('destination') }}" required
                           placeholder="Divisi IT, Departemen HRD, dll."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('destination') border-red-400 @enderror">
                    @error('destination')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
                    <input type="text" name="recipient"
                           value="{{ old('recipient') }}"
                           placeholder="Nama PIC yang menerima"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Opsional">{{ old('notes') }}</textarea>
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
                            <th class="px-3 py-2 text-left text-gray-600 font-medium">Stok Tersedia</th>
                            <th class="px-3 py-2 text-left text-gray-600 font-medium w-36">Qty</th>
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
                <a href="{{ route('distribution.index') }}"
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
          'stocks' => $i->stocks->pluck('quantity', 'warehouse_id'),
      ])), ENT_QUOTES, 'UTF-8') }}">

<script>
let rowCount  = 1;
const itemsData = JSON.parse(document.querySelector('meta[name="items-data"]').getAttribute('content'));

function buildOptions() {
    return itemsData.map(item =>
        '<option value="' + item.id + '" data-stocks=\'' + JSON.stringify(item.stocks) + '\'>' +
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
                    onchange="updateAvailableStock(${i})"
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

function updateStockInfo() {
    const rows = document.querySelectorAll('[id^="row-"]');
    rows.forEach((row, i) => updateAvailableStock(i));
}
</script>
@endsection