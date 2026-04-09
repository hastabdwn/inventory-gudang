@extends('layouts.app')
@section('title', 'Detail Kategori')

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-900 mb-4">{{ $category->name }}</h3>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Slug</dt>
                <dd class="font-mono mt-0.5">{{ $category->slug }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Jumlah Barang</dt>
                <dd class="font-medium mt-0.5">{{ $category->items->count() }} barang</dd>
            </div>
            <div class="col-span-2">
                <dt class="text-gray-500">Deskripsi</dt>
                <dd class="mt-0.5">{{ $category->description ?? '-' }}</dd>
            </div>
        </dl>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h4 class="text-sm font-semibold text-gray-700">Barang dalam Kategori Ini</h4>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Kode</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Nama Barang</th>
                    <th class="px-4 py-2 text-center text-gray-600 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($category->items as $item)
                <tr>
                    <td class="px-4 py-2 font-mono text-gray-600">{{ $item->code }}</td>
                    <td class="px-4 py-2 text-gray-900">{{ $item->name }}</td>
                    <td class="px-4 py-2 text-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ $item->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada barang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('master.categories.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
        &larr; Kembali
    </a>
</div>
@endsection