@extends('layouts.app')
@section('title', 'Edit Barang')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form action="{{ route('master.items.update', $item) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                {{-- Kode Barang --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Kode Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" value="{{ old('code', $item->code) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-400 @enderror">
                    @error('code')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Barang --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $item->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-400 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Satuan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Satuan <span class="text-red-500">*</span>
                    </label>
                    <select name="unit_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('unit_id') border-red-400 @enderror">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id', $item->unit_id) == $unit->id ? 'selected' : '' }}>
                                {{ $unit->name }} ({{ $unit->abbreviation }})
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Harga Beli --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli (Rp)</label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price', $item->purchase_price) }}"
                           min="0" step="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Stok Minimum --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', $item->min_stock) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Deskripsi --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $item->description) }}</textarea>
                </div>

                {{-- Foto --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Barang</label>
                    @if($item->image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                 class="h-24 w-24 object-cover rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-400 mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/jpg,image/jpeg,image/png,image/webp"
                           class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           onchange="previewImage(this)">
                    <div id="image-preview" class="mt-2 hidden">
                        <img id="preview-img" src="" alt="Preview"
                             class="h-24 w-24 object-cover rounded-lg border border-gray-200">
                    </div>
                    @error('image')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $item->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 rounded">
                    <label for="is_active" class="text-sm text-gray-700">Aktif</label>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Perbarui
                </button>
                <a href="{{ route('master.items.index') }}"
                   class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const img     = document.getElementById('preview-img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection