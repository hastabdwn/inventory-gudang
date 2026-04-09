@extends('layouts.app')
@section('title', 'Data Supplier')

@section('content')
<div class="flex justify-between items-center mb-6">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama / kode supplier..."
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit"
                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
            Cari
        </button>
        @if(request('search'))
        <a href="{{ route('master.suppliers.index') }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif
    </form>

    @can('manage master-data')
    <a href="{{ route('master.suppliers.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
        + Tambah Supplier
    </a>
    @endcan
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Kode</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Nama Supplier</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Kontak</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Telepon</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Status</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($suppliers as $supplier)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-gray-600 text-xs">{{ $supplier->code }}</td>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-900">{{ $supplier->name }}</p>
                    @if($supplier->email)
                        <p class="text-xs text-gray-400">{{ $supplier->email }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $supplier->contact_person ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $supplier->phone ?? '-' }}</td>
                <td class="px-4 py-3 text-center">
                    @if($supplier->is_active)
                        <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-medium">Nonaktif</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('master.suppliers.show', $supplier) }}"
                           class="px-3 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">Detail</a>
                        @can('manage master-data')
                        <a href="{{ route('master.suppliers.edit', $supplier) }}"
                           class="px-3 py-1 text-xs border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50">Edit</a>
                        <form action="{{ route('master.suppliers.destroy', $supplier) }}" method="POST"
                              onsubmit="return confirm('Hapus supplier ini?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 text-xs border border-red-300 text-red-600 rounded-lg hover:bg-red-50">Hapus</button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada data supplier.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection
