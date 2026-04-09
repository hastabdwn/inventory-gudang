<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Item;
use App\Models\ItemStock;
use App\Models\ItemReturn;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Summary Cards ──────────────────────────────────────
        $totalItems     = Item::where('is_active', true)->count();
        $totalWarehouses = Warehouse::where('is_active', true)->count();

        $lowStockItems  = ItemStock::join('items', 'item_stocks.item_id', '=', 'items.id')
            ->whereRaw('item_stocks.quantity > 0')
            ->whereRaw('item_stocks.quantity <= items.min_stock')
            ->distinct('item_stocks.item_id')
            ->count('item_stocks.item_id');

        $emptyStockItems = Item::where('is_active', true)
            ->whereDoesntHave('stocks', fn($q) => $q->where('quantity', '>', 0))
            ->count();

        $pendingPo      = PurchaseOrder::whereIn('status', ['draft', 'waiting_approval', 'approved', 'partial'])->count();
        $pendingReturns = ItemReturn::whereIn('status', ['draft', 'sent'])->count();

        // ── Chart: Mutasi Stok 30 Hari ─────────────────────────
        $movementChart = StockMovement::select(
                DB::raw('DATE(moved_at) as date'),
                DB::raw("SUM(CASE WHEN type IN ('in','transfer_in') THEN quantity ELSE 0 END) as total_in"),
                DB::raw("SUM(CASE WHEN type IN ('out','transfer_out','retur') THEN quantity ELSE 0 END) as total_out")
            )
            ->where('moved_at', '>=', now()->subDays(29)->startOfDay())
            ->groupBy(DB::raw('DATE(moved_at)'))
            ->orderBy('date')
            ->get();

        // Fill hari yang kosong
        $movementDates  = [];
        $movementIn     = [];
        $movementOut    = [];
        $indexed        = $movementChart->keyBy('date');

        for ($i = 29; $i >= 0; $i--) {
            $date            = now()->subDays($i)->format('Y-m-d');
            $movementDates[] = now()->subDays($i)->format('d/m');
            $movementIn[]    = $indexed[$date]->total_in  ?? 0;
            $movementOut[]   = $indexed[$date]->total_out ?? 0;
        }

        // ── Chart: Stok per Kategori ───────────────────────────
        $stockByCategory = ItemStock::join('items', 'item_stocks.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(item_stocks.quantity) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        $categoryLabels = $stockByCategory->pluck('name')->toArray();
        $categoryValues = $stockByCategory->pluck('total')->toArray();

        // ── Chart: Nilai PO per Bulan (6 bulan terakhir) ───────
        $poChart = PurchaseOrder::select(
                DB::raw('DATE_FORMAT(order_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total_po'),
                DB::raw('SUM(total_amount) as total_value')
            )
            ->where('order_date', '>=', now()->subMonths(5)->startOfMonth())
            ->whereNotIn('status', ['cancelled'])
            ->groupBy(DB::raw('DATE_FORMAT(order_date, "%Y-%m")'))
            ->orderBy('month')
            ->get();

        $poMonths = [];
        $poValues = [];
        $poCounts = [];
        $indexedPo = $poChart->keyBy('month');

        for ($i = 5; $i >= 0; $i--) {
            $month      = now()->subMonths($i)->format('Y-m');
            $label      = now()->subMonths($i)->translatedFormat('M Y');
            $poMonths[] = $label;
            $poValues[] = $indexedPo[$month]->total_value ?? 0;
            $poCounts[] = $indexedPo[$month]->total_po    ?? 0;
        }

        // ── Alert: Stok Rendah ─────────────────────────────────
        $lowStockAlerts = ItemStock::with(['item.category', 'item.unit', 'warehouse'])
            ->join('items', 'item_stocks.item_id', '=', 'items.id')
            ->whereRaw('item_stocks.quantity > 0')
            ->whereRaw('item_stocks.quantity <= items.min_stock')
            ->select('item_stocks.*')
            ->orderByRaw('item_stocks.quantity / items.min_stock ASC')
            ->limit(10)
            ->get();

        $emptyStockAlerts = Item::with(['category', 'unit'])
            ->where('is_active', true)
            ->whereDoesntHave('stocks', fn($q) => $q->where('quantity', '>', 0))
            ->limit(10)
            ->get();

        // ── Recent Activity ────────────────────────────────────
        $recentMovements = StockMovement::with(['item', 'warehouse', 'user'])
            ->latest('moved_at')
            ->limit(8)
            ->get();

        $recentPo = PurchaseOrder::with(['supplier', 'creator'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalItems', 'totalWarehouses',
            'lowStockItems', 'emptyStockItems',
            'pendingPo', 'pendingReturns',
            'movementDates', 'movementIn', 'movementOut',
            'categoryLabels', 'categoryValues',
            'poMonths', 'poValues', 'poCounts',
            'lowStockAlerts', 'emptyStockAlerts',
            'recentMovements', 'recentPo'
        ));
    }
}