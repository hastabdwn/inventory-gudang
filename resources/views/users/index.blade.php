@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama / email..."
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-56 focus:outline-none focus:ring-2 focus:ring-blue-500">

        <select name="role"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Role</option>
            @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                </option>
            @endforeach
        </select>

        <button type="submit"
                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200">
            Cari
        </button>
        @if(request()->hasAny(['search','role']))
        <a href="{{ route('users.index') }}"
           class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
        @endif

        <div class="ml-auto">
            <a href="{{ route('users.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                + Tambah User
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">User</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Email</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Role</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Status</th>
                <th class="px-4 py-3 text-left text-gray-600 font-medium">Bergabung</th>
                <th class="px-4 py-3 text-center text-gray-600 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 {{ !$user->is_active ? 'opacity-60' : '' }}">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center
                                    text-blue-700 text-xs font-semibold flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                            @if($user->id === auth()->id())
                                <span class="text-xs text-blue-500">(Anda)</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-600 text-xs">{{ $user->email }}</td>
                <td class="px-4 py-3">
                    @foreach($user->roles as $role)
                        <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-medium">
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </span>
                    @endforeach
                </td>
                <td class="px-4 py-3 text-center">
                    @if($user->is_active)
                        <span class="px-2 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-medium">Nonaktif</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">
                    {{ $user->created_at->format('d M Y') }}
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-1.5">
                        <a href="{{ route('users.show', $user) }}"
                           class="px-2.5 py-1 text-xs border border-gray-300 rounded-lg hover:bg-gray-50">
                            Detail
                        </a>
                        <a href="{{ route('users.edit', $user) }}"
                           class="px-2.5 py-1 text-xs border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50">
                            Edit
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.toggle', $user) }}" method="POST">
                            @csrf
                            <button class="px-2.5 py-1 text-xs border rounded-lg
                                {{ $user->is_active
                                    ? 'border-yellow-300 text-yellow-600 hover:bg-yellow-50'
                                    : 'border-green-300 text-green-600 hover:bg-green-50' }}">
                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button class="px-2.5 py-1 text-xs border border-red-300 text-red-600 rounded-lg hover:bg-red-50">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada user.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">{{ $users->total() }} user</p>
        {{ $users->links() }}
    </div>
</div>
@endsection