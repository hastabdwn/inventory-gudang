@extends('layouts.app')
@section('title', 'Tambah User')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror"
                           placeholder="Nama lengkap user">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror"
                           placeholder="email@contoh.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-400 @enderror">
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password <span class="text-red-500">*</span>
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
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ulangi password">
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200 text-xs text-gray-600">
                <p class="font-medium text-gray-700 mb-2">Hak Akses per Role:</p>
                <ul class="space-y-1">
                    <li><span class="font-medium text-purple-700">Superadmin</span> — Akses penuh ke semua fitur termasuk manajemen user</li>
                    <li><span class="font-medium text-purple-700">Admin Gudang</span> — Akses operasional penuh kecuali approval PO & user management</li>
                    <li><span class="font-medium text-purple-700">Viewer</span> — Hanya bisa melihat data dan mengekspor laporan</li>
                </ul>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                    Tambah User
                </button>
                <a href="{{ route('users.index') }}"
                   class="px-5 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection