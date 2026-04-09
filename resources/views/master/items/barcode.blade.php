@extends('layouts.app')
@section('title', 'Barcode — ' . $item->name)

@section('content')
<div class="max-w-sm">
    <div class="bg-white rounded-xl border border-gray-200 p-6 text-center">
        <p class="text-sm font-medium text-gray-700 mb-1">{{ $item->name }}</p>
        <p class="text-xs text-gray-400 mb-4 font-mono">{{ $item->code }}</p>
        <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" class="mx-auto max-w-full">
        <p class="mt-3 text-sm font-mono font-medium">{{ $item->barcode }}</p>
        <button onclick="window.print()"
                class="mt-4 px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">
            Print Barcode
        </button>
    </div>
</div>
@endsection