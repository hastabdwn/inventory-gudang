<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Logo & Title --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">{{ config('app.name') }}</h1>
            <p class="text-sm text-gray-500 mt-1">Sistem Manajemen Inventory Gudang</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Masuk ke akun Anda</h2>

            {{-- Session error --}}
            @if(session('error'))
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-400 @enderror"
                               placeholder="email@contoh.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="••••••••">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember"
                                   class="w-4 h-4 text-blue-600 rounded border-gray-300">
                            <span class="text-sm text-gray-600">Ingat saya</span>
                        </label>
                    </div>
                </div>

                <button type="submit"
                        class="mt-6 w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Masuk
                </button>
            </form>
        </div>

        {{-- Demo accounts --}}
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4 text-xs text-blue-700">
            <p class="font-semibold mb-2">Akun Demo:</p>
            <div class="space-y-1">
                <p>Superadmin: <span class="font-mono">superadmin@inventory.test</span> / <span class="font-mono">password</span></p>
                <p>Admin Gudang: <span class="font-mono">admin@inventory.test</span> / <span class="font-mono">password</span></p>
                <p>Viewer: <span class="font-mono">viewer@inventory.test</span> / <span class="font-mono">password</span></p>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Portfolio Project.
        </p>
    </div>

</body>
</html>