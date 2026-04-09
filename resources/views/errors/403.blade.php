<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-2xl mb-6">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-2">403</h1>
        <p class="text-lg font-semibold text-gray-700 mb-2">Akses Ditolak</p>
        <p class="text-sm text-gray-500 mb-8">
            Anda tidak memiliki izin untuk mengakses halaman ini.
            Hubungi administrator jika Anda membutuhkan akses.
        </p>
        <div class="flex gap-3 justify-center">
            <a href="{{ url()->previous() }}"
               class="px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50">
                Kembali
            </a>
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                Ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>