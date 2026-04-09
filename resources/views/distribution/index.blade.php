@extends('layouts.app')
@section('title', 'Distribusi Barang')

@section('content')
{{-- Filter --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari no. distribusi / tujuan..."
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-56 focus:outline-none focus:ring-2 focus:ring-blue-500">

        <select name="warehouse_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Gudang</option>
            @foreach($warehouses as $wh)
                <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                    {{ $wh->name }}
                </option>
            @endforeach
        </select>

        <select name="status"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="draft"     {{ request('status') == 'draft'     ? 'selected' : '' }}>Draft</option>
            <option value="issued"    {{ request('status') == 'issued'    ? 'selected' : '' }}>Diterbitkan</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>

        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

        <button type="submit"
                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
            Filter
        </button>
        @if(request()->hasAny(['search','warehouse_id','status','date_from','date_to']))
        <a href="{{ route('distribution.index') }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif

        @can('create distribution')
        <div class="ml-auto">
            <a href="{{ route('distribution.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                + Buat Distribusi
            </a>
        </div>
        @endcan
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No. Distribusi</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Gudang Asal</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Tujuan</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Penerima</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Tanggal</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Dibuat Oleh</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Status</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($distributions as $dist)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono text-xs text-gray-700">{{ $dist->dist_number }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $dist->warehouse->name }}</td>
                <td class="px-4 py-3 font-medium text-gray-900">{{ $dist->destination }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $dist->recipient ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $dist->dist_date->format('d M Y') }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $dist->issuer->name }}</td>
                <td class="px-4 py-3 text-center">
                    @php
                        $color = match($dist->status) {
                            'draft'     => 'bg-gray-100 text-gray-600',
                            'issued'    => 'bg-green-50 text-green-700',
                            'cancelled' => 'bg-red-50 text-red-700',
                            default     => 'bg-gray-100 text-gray-600',
                        };
                        $label = match($dist->status) {
                            'draft'     => 'Draft',
                            'issued'    => 'Diterbitkan',
                            'cancelled' => 'Dibatalkan',
                            default     => $dist->status,
                        };
                    @endphp
                    <span class="px-2 py-1 text-xs rounded-full font-medium {{ $color }}">
                        {{ $label }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('distribution.show', $dist) }}"
                       class="px-3 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">
                        Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-4 py-8 text-center text-gray-400">Belum ada data distribusi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">{{ $distributions->total() }} record</p>
        {{ $distributions->links() }}
    </div>
</div>
@endsection