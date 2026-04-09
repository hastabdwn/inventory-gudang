@extends('layouts.app')
@section('title', 'Data Satuan')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div></div>
    @can('manage master-data')
    <a href="{{ route('master.units.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
        + Tambah Satuan
    </a>
    @endcan
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Nama Satuan</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Singkatan</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Jumlah Barang</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($units as $unit)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $unit->name }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded font-mono text-xs">
                        {{ $unit->abbreviation }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                        {{ $unit->items_count }} barang
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('master.units.show', $unit) }}"
                           class="px-3 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">Detail</a>
                        @can('manage master-data')
                        <a href="{{ route('master.units.edit', $unit) }}"
                           class="px-3 py-1 text-xs border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50">Edit</a>
                        <form action="{{ route('master.units.destroy', $unit) }}" method="POST"
                              onsubmit="return confirm('Hapus satuan ini?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 text-xs border border-red-300 text-red-600 rounded-lg hover:bg-red-50">Hapus</button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data satuan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $units->links() }}
    </div>
</div>
@endsection