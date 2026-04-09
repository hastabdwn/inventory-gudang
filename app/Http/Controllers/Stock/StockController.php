<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemStock;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = ItemStock::with(['item.category', 'item.unit', 'warehouse'])
            ->join('items', 'item_stocks.item_id', '=', 'items.id')
            ->select('item_stocks.*');

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('search')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->whereHas('item', fn($q) => $q->where('category_id', $request->category_id));
        }

        if ($request->filled('low_stock')) {
            $query->whereHas('item', fn($q) => $q->whereRaw('item_stocks.quantity <= items.min_stock'));
        }

        $stocks     = $query->orderBy('items.name')->paginate(20)->withQueryString();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $categories = \App\Models\Category::orderBy('name')->get();

        // Summary cards
        $totalItems    = Item::where('is_active', true)->count();
        $lowStockCount = ItemStock::join('items', 'item_stocks.item_id', '=', 'items.id')
            ->whereRaw('item_stocks.quantity <= items.min_stock')
            ->where('item_stocks.quantity', '>', 0)
            ->distinct('item_stocks.item_id')
            ->count();
        $emptyCount = Item::where('is_active', true)
            ->whereDoesntHave('stocks', fn($q) => $q->where('quantity', '>', 0))
            ->count();

        return view('stock.index', compact(
            'stocks', 'warehouses', 'categories',
            'totalItems', 'lowStockCount', 'emptyCount'
        ));
    }

    public function byWarehouse(Warehouse $warehouse, Request $request)
    {
        $stocks = ItemStock::with(['item.category', 'item.unit'])
            ->where('warehouse_id', $warehouse->id)
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->whereHas('item', fn($q2) =>
                    $q2->where('name', 'like', "%{$request->search}%")
                       ->orWhere('code', 'like', "%{$request->search}%")
                );
            })
            ->join('items', 'item_stocks.item_id', '=', 'items.id')
            ->select('item_stocks.*')
            ->orderBy('items.name')
            ->paginate(20)
            ->withQueryString();

        return view('stock.by-warehouse', compact('warehouse', 'stocks'));
    }
}