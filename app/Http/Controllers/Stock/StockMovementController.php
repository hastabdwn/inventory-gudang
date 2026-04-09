<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['item.unit', 'warehouse', 'user'])
            ->latest('moved_at');

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('moved_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('moved_at', '<=', $request->date_to);
        }

        $movements  = $query->paginate(25)->withQueryString();
        $items      = Item::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();

        $types = [
            'in'           => 'Barang Masuk',
            'out'          => 'Barang Keluar',
            'transfer_in'  => 'Transfer Masuk',
            'transfer_out' => 'Transfer Keluar',
            'retur'        => 'Retur',
            'adjustment'   => 'Penyesuaian',
        ];

        return view('stock.movements.index', compact('movements', 'items', 'warehouses', 'types'));
    }
}