@extends('layouts.app')
@section('title', 'Detail Satuan')

@section('content')
<div class="max-w-xl space-y-6">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-900 mb-4">{{ $unit->name }}</h3>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Nama</dt>
                <dd class="font-medium mt-0.5">{{ $unit->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Singkatan</dt>
                <dd class="mt-0.5">
                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded font-mono text-sm">
                        {{ $unit->abbreviation }}
                    </span>
                </dd>
            </div>
        </dl>
    </div>

    <a href="{{ route('master.units.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
        &larr; Kembali
    </a>
</div>
@endsection