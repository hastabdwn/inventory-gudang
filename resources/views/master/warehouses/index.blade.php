@extends('layouts.app')
@section('title', 'Data Gudang')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div></div>
    @can('manage master-data')
    <a href="{{ route('master.warehouses.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
        + Tambah Gudang
    </a>
    @endcan
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Kode</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Nama Gudang</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Lokasi</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Status</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($warehouses as $warehouse)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-gray-700">{{ $warehouse->code }}</td>
                <td class="px-4 py-3 font-medium text-gray-900">{{ $warehouse->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $warehouse->location ?? '-' }}</td>
                <td class="px-4 py-3 text-center">
                    @if($warehouse->is_active)
                        <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-medium">Nonaktif</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('master.warehouses.show', $warehouse) }}"
                           class="px-3 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">Detail</a>
                        @can('manage master-data')
                        <a href="{{ route('master.warehouses.edit', $warehouse) }}"
                           class="px-3 py-1 text-xs border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50">Edit</a>
                        <form action="{{ route('master.warehouses.destroy', $warehouse) }}" method="POST"
                              onsubmit="return confirm('Hapus gudang ini?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 text-xs border border-red-300 text-red-600 rounded-lg hover:bg-red-50">Hapus</button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada data gudang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $warehouses->links() }}
    </div>
</div>
@endsection