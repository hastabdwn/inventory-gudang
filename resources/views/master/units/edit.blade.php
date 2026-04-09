@extends('layouts.app')
@section('title', 'Edit Satuan')

@section('content')
<div class="max-w-md">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form action="{{ route('master.units.update', $unit) }}" method="POST">
            @csrf @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Satuan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $unit->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Singkatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="abbreviation" value="{{ old('abbreviation', $unit->abbreviation) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('abbreviation') border-red-400 @enderror">
                    @error('abbreviation')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Perbarui
                </button>
                <a href="{{ route('master.units.index') }}"
                   class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection