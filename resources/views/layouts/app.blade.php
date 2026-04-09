<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- Overlay mobile --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden lg:hidden"
         onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside id="sidebar"
           class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-gray-900 text-white flex flex-col
                  transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">

        {{-- Logo --}}
        <div class="px-5 py-4 border-b border-gray-700 flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="min-w-0">
                <h1 class="text-sm font-semibold truncate">{{ config('app.name') }}</h1>
                <p class="text-xs text-gray-400 truncate">{{ Auth::user()->name }}</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto text-sm">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            {{-- Master Data --}}
            @can('view master-data')
            <div class="pt-4 pb-1 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Master Data
            </div>
            @foreach([
                ['route' => 'master.warehouses.index', 'label' => 'Gudang', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ['route' => 'master.categories.index', 'label' => 'Kategori', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ['route' => 'master.units.index', 'label' => 'Satuan', 'icon' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3'],
                ['route' => 'master.suppliers.index', 'label' => 'Supplier', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'master.items.index', 'label' => 'Barang', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ] as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs(str_replace('.index','',$item['route']).'*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach
            @endcan

            {{-- Stok --}}
            @can('view stock')
            <div class="pt-4 pb-1 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stok</div>
            @foreach([
                ['route' => 'stock.index', 'label' => 'Posisi Stok', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['route' => 'stock.movements.index', 'label' => 'Mutasi Stok', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                ['route' => 'stock.transfer.index', 'label' => 'Transfer Stok', 'icon' => 'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z'],
            ] as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs(rtrim($item['route'],'index').'*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach
            @can('adjust stock')
            <a href="{{ route('stock.adjustment.create') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('stock.adjustment*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                Penyesuaian Stok
            </a>
            @endcan
            @endcan

            {{-- Pembelian --}}
            @can('view purchase-order')
            <div class="pt-4 pb-1 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembelian</div>
            <a href="{{ route('purchasing.orders.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('purchasing.orders*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Purchase Order
            </a>
            <a href="{{ route('purchasing.receipts.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('purchasing.receipts*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                Penerimaan Barang
            </a>
            @endcan

            {{-- Distribusi --}}
            @can('view distribution')
            <div class="pt-4 pb-1 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Distribusi</div>
            <a href="{{ route('distribution.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('distribution.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
                Distribusi & Surat Jalan
            </a>
            @endcan

            {{-- Retur --}}
            @can('view return')
            <div class="pt-4 pb-1 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Retur</div>
            <a href="{{ route('returns.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('returns.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
                Retur Barang
            </a>
            @endcan

            {{-- Laporan --}}
            @can('view report')
            <div class="pt-4 pb-1 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan</div>
            <a href="{{ route('reports.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan
            </a>
            @endcan

            {{-- Akun --}}
            <div class="pt-4 pb-1 px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Akun</div>
            <a href="{{ route('profile.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('profile.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil Saya
            </a>
            @can('manage users')
            <a href="{{ route('users.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors
                      {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Manajemen User
            </a>
            @endcan

        </nav>

        {{-- Logout --}}
        <div class="px-3 py-3 border-t border-gray-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-gray-400
                               hover:text-white hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- Top Header --}}
        <header class="bg-white border-b border-gray-200 px-4 lg:px-6 py-3 flex items-center gap-4 flex-shrink-0">
            {{-- Mobile hamburger --}}
            <button onclick="toggleSidebar()"
                    class="lg:hidden p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h2 class="text-base font-semibold text-gray-800 flex-1">@yield('title', 'Dashboard')</h2>

            {{-- User badge --}}
            <div class="flex items-center gap-2 text-sm">
                <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center
                            text-blue-700 text-xs font-semibold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <span class="hidden md:block text-gray-600">{{ Auth::user()->name }}</span>
                <span class="hidden md:block px-2 py-0.5 bg-purple-50 text-purple-700 rounded-full text-xs">
                    {{ ucfirst(str_replace('_', ' ', Auth::user()->roles->first()?->name ?? 'user')) }}
                </span>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success') || session('error') || session('warning'))
        <div id="flash-messages" class="px-4 lg:px-6 pt-4">
            @if(session('success'))
            <div class="flash-msg flex items-start gap-3 px-4 py-3 bg-green-50 border border-green-200
                        text-green-800 rounded-xl text-sm mb-2">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="flex-1">{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endif
            @if(session('error'))
            <div class="flash-msg flex items-start gap-3 px-4 py-3 bg-red-50 border border-red-200
                        text-red-800 rounded-xl text-sm mb-2">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="flex-1">{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endif
            @if(session('warning'))
            <div class="flash-msg flex items-start gap-3 px-4 py-3 bg-yellow-50 border border-yellow-200
                        text-yellow-800 rounded-xl text-sm mb-2">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="flex-1">{{ session('warning') }}</span>
                <button onclick="this.parentElement.remove()" class="text-yellow-400 hover:text-yellow-600 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endif
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-4 lg:px-6 py-5">
            @yield('content')
        </main>

    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const isOpen  = !sidebar.classList.contains('-translate-x-full');

    if (isOpen) {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    } else {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    }
}

// Auto-dismiss flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.flash-msg').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity 0.4s';
            el.style.opacity    = '0';
            setTimeout(function () { el.remove(); }, 400);
        }, 5000);
    });
});
</script>

</body>
</html>