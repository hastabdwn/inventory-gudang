@extends('layouts.app')
@section('title', 'Detail Distribusi — ' . $distribution->dist_number)

@section('content')
@php
    $statusColor = match($distribution->status) {
        'draft'     => 'bg-gray-100 text-gray-600',
        'issued'    => 'bg-green-50 text-green-700',
        'cancelled' => 'bg-red-50 text-red-700',
        default     => 'bg-gray-100 text-gray-600',
    };
    $statusLabel = match($distribution->status) {
        'draft'     => 'Draft',
        'issued'    => 'Diterbitkan',
        'cancelled' => 'Dibatalkan',
        default     => $distribution->status,
    };
@endphp

<div class="max-w-3xl space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex justify-between items-start mb-5">
            <div>
                <p class="text-xs text-gray-400 font-mono">{{ $distribution->dist_number }}</p>
                <h3 class="font-semibold text-gray-900 text-lg mt-0.5">Surat Jalan / Distribusi</h3>
            </div>
            <span class="px-3 py-1 text-sm rounded-full font-medium {{ $statusColor }}">
                {{ $statusLabel }}
            </span>
        </div>

        <div class="grid grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Gudang Asal</p>
                <p class="font-medium mt-0.5">{{ $distribution->warehouse->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tujuan</p>
                <p class="font-medium mt-0.5">{{ $distribution->destination }}</p>
            </div>
            <div>
                <p class="text-gray-500">Penerima</p>
                <p class="font-medium mt-0.5">{{ $distribution->recipient ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal</p>
                <p class="font-medium mt-0.5">{{ $distribution->dist_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Dibuat Oleh</p>
                <p class="font-medium mt-0.5">{{ $distribution->issuer->name }}</p>
            </div>
            @if($distribution->notes)
            <div>
                <p class="text-gray-500">Catatan</p>
                <p class="mt-0.5">{{ $distribution->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Action buttons --}}
        <div class="flex gap-2 mt-5 pt-5 border-t border-gray-100">
            @if($distribution->status === 'draft')
                @can('create distribution')
                <form action="{{ route('distribution.issue', $distribution) }}" method="POST"
                      onsubmit="return confirm('Terbitkan distribusi ini? Stok akan langsung dikurangi.')">
                    @csrf
                    <button class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                        Terbitkan & Kurangi Stok
                    </button>
                </form>
                <form action="{{ route('distribution.cancel', $distribution) }}" method="POST"
                      onsubmit="return confirm('Batalkan distribusi ini?')">
                    @csrf
                    <button class="px-4 py-2 border border-red-300 text-red-600 text-sm rounded-lg hover:bg-red-50">
                        Batalkan
                    </button>
                </form>
                @endcan
            @endif

            @if($distribution->status === 'issued')
                <a href="{{ route('distribution.print', $distribution) }}"
                   target="_blank"
                   class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">
                    Cetak Surat Jalan
                </a>
                @can('create distribution')
                <form action="{{ route('distribution.cancel', $distribution) }}" method="POST"
                      onsubmit="return confirm('Batalkan distribusi ini? Stok akan dikembalikan.')">
                    @csrf
                    <button class="px-4 py-2 border border-red-300 text-red-600 text-sm rounded-lg hover:bg-red-50">
                        Batalkan & Kembalikan Stok
                    </button>
                </form>
                @endcan
            @endif
        </div>
    </div>

    {{-- Daftar barang --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h4 class="text-sm font-semibold text-gray-700">Daftar Barang</h4>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Barang</th>
                    <th class="px-4 py-2 text-right text-gray-600 font-medium">Qty</th>
                    <th class="px-4 py-2 text-left text-gray-600 font-medium">Satuan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($distribution->items as $item)
                <tr>
                    <td class="px-4 py-2">
                        <p class="font-medium text-gray-900">{{ $item->item->name }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $item->item->code }}</p>
                    </td>
                    <td class="px-4 py-2 text-right font-semibold text-gray-900">
                        {{ number_format($item->quantity) }}
                    </td>
                    <td class="px-4 py-2 text-gray-500">
                        {{ $item->item->unit->name }} ({{ $item->item->unit->abbreviation }})
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('distribution.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
        &larr; Kembali
    </a>
</div>
@endsection