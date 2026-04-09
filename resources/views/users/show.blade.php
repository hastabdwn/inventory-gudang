@extends('layouts.app')
@section('title', 'Detail User — ' . $user->name)

@section('content')
<div class="max-w-2xl space-y-6">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex justify-between items-start mb-5">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center
                            text-blue-700 text-xl font-semibold">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-lg">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
            <div class="flex flex-col items-end gap-2">
                @if($user->is_active)
                    <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                @else
                    <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-medium">Nonaktif</span>
                @endif
                @foreach($user->roles as $role)
                    <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-medium">
                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                    </span>
                @endforeach
            </div>
        </div>

        <dl class="grid grid-cols-2 gap-4 text-sm border-t border-gray-100 pt-4">
            <div>
                <dt class="text-gray-500">Bergabung</dt>
                <dd class="font-medium mt-0.5">{{ $user->created_at->format('d M Y') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Terakhir Diperbarui</dt>
                <dd class="font-medium mt-0.5">{{ $user->updated_at->format('d M Y H:i') }}</dd>
            </div>
        </dl>

        <div class="flex gap-2 mt-5 pt-5 border-t border-gray-100">
            <a href="{{ route('users.edit', $user) }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                Edit User
            </a>
            @if($user->id !== auth()->id())
            <form action="{{ route('users.toggle', $user) }}" method="POST">
                @csrf
                <button class="px-4 py-2 border text-sm rounded-lg
                    {{ $user->is_active
                        ? 'border-yellow-300 text-yellow-600 hover:bg-yellow-50'
                        : 'border-green-300 text-green-600 hover:bg-green-50' }}">
                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            <form action="{{ route('users.destroy', $user) }}" method="POST"
                  onsubmit="return confirm('Hapus user ini?')">
                @csrf @method('DELETE')
                <button class="px-4 py-2 border border-red-300 text-red-600 text-sm rounded-lg hover:bg-red-50">
                    Hapus User
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Permissions --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h4 class="text-sm font-semibold text-gray-700 mb-4">Hak Akses</h4>
        <div class="grid grid-cols-2 gap-2">
            @foreach($user->getAllPermissions()->sortBy('name') as $permission)
            <div class="flex items-center gap-2 text-xs text-gray-600">
                <span class="w-4 h-4 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                </span>
                {{ $permission->name }}
            </div>
            @endforeach
        </div>
    </div>

    <a href="{{ route('users.index') }}" class="inline-block text-sm text-blue-600 hover:underline">
        &larr; Kembali
    </a>
</div>
@endsection