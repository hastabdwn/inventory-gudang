@extends('layouts.app')
@section('title', 'Laporan Distribusi')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
               placeholder="Cari no. / tujuan..."
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-48 focus:outline-none focus:ring-2 focus:ring-blue-500">

        <select name="warehouse_id"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Gudang</option>
            @foreach($warehouses as $wh)
                <option value="{{ $wh->id }}" {{ ($filters['warehouse_id'] ?? '') == $wh->id ? 'selected' : '' }}>
                    {{ $wh->name }}
                </option>
            @endforeach
        </select>

        <select name="status"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="draft"     {{ ($filters['status'] ?? '') == 'draft'     ? 'selected' : '' }}>Draft</option>
            <option value="issued"    {{ ($filters['status'] ?? '') == 'issued'    ? 'selected' : '' }}>Diterbitkan</option>
            <option value="cancelled" {{ ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
        </select>

        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

        <button type="submit"
                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">Filter</button>
        @if(array_filter($filters))
        <a href="{{ route('reports.distributions') }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif

        @can('export report')
        <div class="ml-auto flex gap-2">
            <a href="{{ route('reports.distributions.export', array_merge(['format' => 'excel'], $filters)) }}"
               class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">Ekspor Excel</a>
            <a href="{{ route('reports.distributions.export', array_merge(['format' => 'pdf'], $filters)) }}"
               class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">Ekspor PDF</a>
        </div>
        @endcan
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-3 mb-4 flex gap-6 text-sm">
    <div>
        <span class="text-gray-500">Total Distribusi:</span>
        <span class="font-semibold ml-1">{{ number_format($data->count()) }}</span>
    </div>
    <div>
        <span class="text-gray-500">Diterbitkan:</span>
        <span class="font-semibold text-green-600 ml-1">{{ $data->where('status','issued')->count() }}</span>
    </div>
    <div>
        <span class="text-gray-500">Draft:</span>
        <span class="font-semibold text-gray-600 ml-1">{{ $data->where('status','draft')->count() }}</span>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">No. Distribusi</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Tanggal</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Gudang Asal</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Tujuan</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Penerima</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Jml Item</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Status</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Dibuat Oleh</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $i => $dist)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 text-gray-400 text-xs">{{ $i + 1 }}</td>
                <td class="px-4 py-2 font-mono text-xs text-gray-700">
                    <a href="{{ route('distribution.show', $dist) }}"
                       class="hover:text-blue-600 hover:underline">{{ $dist->dist_number }}</a>
                </td>
                <td class="px-4 py-2 text-gray-500 text-xs">{{ $dist->dist_date->format('d/m/Y') }}</td>
                <td class="px-4 py-2 text-gray-600 text-xs">{{ $dist->warehouse->name }}</td>
                <td class="px-4 py-2 font-medium text-gray-900">{{ $dist->destination }}</td>
                <td class="px-4 py-2 text-gray-500 text-xs">{{ $dist->recipient ?? '-' }}</td>
                <td class="px-4 py-2 text-center text-gray-600">{{ $dist->items->count() }}</td>
                <td class="px-4 py-2 text-center">
                    @php
                        $dc = match($dist->status) {
                            'draft'     => 'bg-gray-100 text-gray-600',
                            'issued'    => 'bg-green-50 text-green-700',
                            'cancelled' => 'bg-red-50 text-red-700',
                            default     => 'bg-gray-100 text-gray-600',
                        };
                        $dl = match($dist->status) {
                            'draft'     => 'Draft',
                            'issued'    => 'Diterbitkan',
                            'cancelled' => 'Dibatalkan',
                            default     => $dist->status,
                        };
                    @endphp
                    <span class="px-2 py-0.5 text-xs rounded-full {{ $dc }}">{{ $dl }}</span>
                </td>
                <td class="px-4 py-2 text-gray-500 text-xs">{{ $dist->issuer->name }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-4 py-8 text-center text-gray-400">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection