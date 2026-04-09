@extends('layouts.app')
@section('title', 'Data Kategori')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div></div>
    @can('manage master-data')
    <a href="{{ route('master.categories.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
        + Tambah Kategori
    </a>
    @endcan
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Nama Kategori</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Slug</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Deskripsi</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Jumlah Barang</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($categories as $category)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $category->name }}</td>
                <td class="px-4 py-3 font-mono text-gray-500 text-xs">{{ $category->slug }}</td>
                <td class="px-4 py-3 text-gray-600">{{ Str::limit($category->description, 60) ?? '-' }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                        {{ $category->items_count }} barang
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('master.categories.show', $category) }}"
                           class="px-3 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">Detail</a>
                        @can('manage master-data')
                        <a href="{{ route('master.categories.edit', $category) }}"
                           class="px-3 py-1 text-xs border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50">Edit</a>
                        <form action="{{ route('master.categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1 text-xs border border-red-300 text-red-600 rounded-lg hover:bg-red-50">Hapus</button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada data kategori.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $categories->links() }}
    </div>
</div>
@endsection