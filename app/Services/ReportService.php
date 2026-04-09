<?php

namespace App\Services;

use App\Models\Distribution;
use App\Models\Item;
use App\Models\ItemReturn;
use App\Models\ItemStock;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class ReportService
{
    // ── Laporan Stok ───────────────────────────────────────────
    public function stockReport(array $filters = []): \Illuminate\Support\Collection
    {
        $query = ItemStock::with(['item.category', 'item.unit', 'warehouse'])
            ->join('items', 'item_stocks.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->join('warehouses', 'item_stocks.warehouse_id', '=', 'warehouses.id')
            ->select('item_stocks.*');

        if (!empty($filters['warehouse_id'])) {
            $query->where('item_stocks.warehouse_id', $filters['warehouse_id']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('items.category_id', $filters['category_id']);
        }

        if (!empty($filters['low_stock'])) {
            $query->whereRaw('item_stocks.quantity <= items.min_stock');
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('items.name', 'like', "%{$filters['search']}%")
                  ->orWhere('items.code', 'like', "%{$filters['search']}%");
            });
        }

        return $query->orderBy('categories.name')
            ->orderBy('items.name')
            ->get();
    }

    // ── Laporan Mutasi Stok ────────────────────────────────────
    public function movementReport(array $filters = []): \Illuminate\Support\Collection
    {
        $query = StockMovement::with(['item.unit', 'warehouse', 'user'])
            ->join('items', 'stock_movements.item_id', '=', 'items.id')
            ->select('stock_movements.*');

        if (!empty($filters['item_id'])) {
            $query->where('stock_movements.item_id', $filters['item_id']);
        }

        if (!empty($filters['warehouse_id'])) {
            $query->where('stock_movements.warehouse_id', $filters['warehouse_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('stock_movements.type', $filters['type']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('moved_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('moved_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('stock_movements.moved_at', 'desc')->get();
    }

    // ── Laporan Purchase Order ─────────────────────────────────
    public function purchaseOrderReport(array $filters = []): \Illuminate\Support\Collection
    {
        $query = PurchaseOrder::with(['supplier', 'warehouse', 'creator', 'items.item']);

        if (!empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if (!empty($filters['warehouse_id'])) {
            $query->where('warehouse_id', $filters['warehouse_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('order_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('order_date', '<=', $filters['date_to']);
        }

        return $query->orderBy('order_date', 'desc')->get();
    }

    // ── Laporan Distribusi ─────────────────────────────────────
    public function distributionReport(array $filters = []): \Illuminate\Support\Collection
    {
        $query = Distribution::with(['warehouse', 'issuer', 'items.item.unit']);

        if (!empty($filters['warehouse_id'])) {
            $query->where('warehouse_id', $filters['warehouse_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('dist_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('dist_date', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('dist_number', 'like', "%{$filters['search']}%")
                  ->orWhere('destination', 'like', "%{$filters['search']}%");
            });
        }

        return $query->orderBy('dist_date', 'desc')->get();
    }

    // ── Laporan Retur ──────────────────────────────────────────
    public function returnReport(array $filters = []): \Illuminate\Support\Collection
    {
        $query = ItemReturn::with(['supplier', 'warehouse', 'creator', 'items.item.unit']);

        if (!empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if (!empty($filters['warehouse_id'])) {
            $query->where('warehouse_id', $filters['warehouse_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('return_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('return_date', '<=', $filters['date_to']);
        }

        return $query->orderBy('return_date', 'desc')->get();
    }

    // ── Summary untuk dashboard laporan ───────────────────────
    public function stockSummary(): array
    {
        return [
            'total_items'     => Item::where('is_active', true)->count(),
            'total_stock'     => ItemStock::sum('quantity'),
            'low_stock'       => ItemStock::join('items', 'item_stocks.item_id', '=', 'items.id')
                                    ->whereRaw('item_stocks.quantity <= items.min_stock')
                                    ->where('item_stocks.quantity', '>', 0)
                                    ->count(),
            'empty_stock'     => Item::where('is_active', true)
                                    ->whereDoesntHave('stocks', fn($q) => $q->where('quantity', '>', 0))
                                    ->count(),
            'total_po_value'  => PurchaseOrder::whereNotIn('status', ['cancelled'])->sum('total_amount'),
        ];
    }
}