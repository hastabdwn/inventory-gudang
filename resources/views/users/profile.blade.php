@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Info Profil --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center
                        text-blue-700 text-xl font-semibold">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <h3 class="font-semibold text-gray-900">{{ auth()->user()->name }}</h3>
                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                <div class="flex gap-2 mt-1">
                    @foreach($user->roles as $role)
                        <span class="px-2 py-0.5 bg-purple-50 text-purple-700 rounded-full text-xs font-medium">
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <h4 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">
            Perbarui Informasi Profil
        </h4>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', auth()->user()->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email"
                           value="{{ old('email', auth()->user()->email) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-5">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Ganti Password --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h4 class="text-sm font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">
            Ubah Password
        </h4>

        <form action="{{ route('profile.password') }}" method="POST">
            @csrf @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password Saat Ini <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="current_password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-400 @enderror">
                    @error('current_password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror"
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="mt-5">
                <button type="submit"
                        class="px-5 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

    {{-- Hak Akses --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h4 class="text-sm font-semibold text-gray-700 mb-4">Hak Akses Saya</h4>
        <div class="grid grid-cols-2 gap-2">
            @foreach(auth()->user()->getAllPermissions()->sortBy('name') as $permission)
            <div class="flex items-center gap-2 text-xs text-gray-600">
                <span class="w-4 h-4 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                </span>
                {{ $permission->name }}
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection