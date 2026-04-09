@extends('layouts.app')
@section('title', 'QR Code — ' . $item->name)

@section('content')
<div class="max-w-sm">
    <div class="bg-white rounded-xl border border-gray-200 p-6 text-center">
        <p class="text-sm font-medium text-gray-700 mb-1">{{ $item->name }}</p>
        <p class="text-xs text-gray-400 mb-4 font-mono">{{ $item->code }}</p>
        <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code" class="mx-auto w-48 h-48">
        <button onclick="window.print()"
                class="mt-4 px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">
            Print QR Code
        </button>
    </div>
</div>
@endsection