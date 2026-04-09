<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Warehouse;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function create(Request $request)
    {
        $items      = Item::where('is_active', true)->with('unit')->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();

        // Jika dari halaman stok, pre-fill item & warehouse
        $selectedItem      = $request->item_id ? Item::find($request->item_id) : null;
        $selectedWarehouse = $request->warehouse_id ? Warehouse::find($request->warehouse_id) : null;
        $currentStock      = 0;

        if ($selectedItem && $selectedWarehouse) {
            $currentStock = $this->stockService->getStock($selectedItem->id, $selectedWarehouse->id);
        }

        return view('stock.adjustment.create', compact(
            'items', 'warehouses', 'selectedItem', 'selectedWarehouse', 'currentStock'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id'      => 'required|exists:items,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'new_quantity' => 'required|integer|min:0',
            'notes'        => 'required|string|max:500',
        ]);

        try {
            $this->stockService->adjust(
                $validated['item_id'],
                $validated['warehouse_id'],
                $validated['new_quantity'],
                $validated['notes']
            );

            return redirect()->route('stock.index')
                ->with('success', 'Penyesuaian stok berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}