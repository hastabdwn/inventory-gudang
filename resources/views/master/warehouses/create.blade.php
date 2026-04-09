@extends('layouts.app')
@section('title', 'Tambah Gudang')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form action="{{ route('master.warehouses.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Gudang <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-400 @enderror"
                           placeholder="GDG-04">
                    @error('code') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Gudang <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror"
                           placeholder="Gudang Utama">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Lantai 1, Gedung A">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', '1') ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 rounded">
                    <label for="is_active" class="text-sm text-gray-700">Aktif</label>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Simpan
                </button>
                <a href="{{ route('master.warehouses.index') }}"
                   class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection