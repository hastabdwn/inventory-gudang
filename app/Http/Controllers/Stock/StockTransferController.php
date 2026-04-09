<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemStock;
use App\Models\Warehouse;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Services\StockService;
use App\Services\DocumentNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockTransferController extends Controller
{
    public function __construct(protected StockService $stockService) {}

    public function index(Request $request)
    {
        $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'transferredBy'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('stock.transfer.index', compact('transfers'));
    }

    public function create()
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $items      = Item::where('is_active', true)->with(['unit', 'stocks'])->orderBy('name')->get();

        return view('stock.transfer.create', compact('warehouses', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id'   => 'required|exists:warehouses,id|different:from_warehouse_id',
            'transfer_date'     => 'required|date',
            'notes'             => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.item_id'   => 'required|exists:items,id',
            'items.*.quantity'  => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $transfer = StockTransfer::create([
                    'transfer_number'   => DocumentNumberService::transfer(),
                    'from_warehouse_id' => $validated['from_warehouse_id'],
                    'to_warehouse_id'   => $validated['to_warehouse_id'],
                    'transferred_by' => Auth::id(),
                    'transfer_date'     => $validated['transfer_date'],
                    'status'            => 'completed',
                    'notes'             => $validated['notes'],
                ]);

                foreach ($validated['items'] as $row) {
                    StockTransferItem::create([
                        'stock_transfer_id' => $transfer->id,
                        'item_id'           => $row['item_id'],
                        'quantity'          => $row['quantity'],
                    ]);

                    $this->stockService->transfer(
                        $row['item_id'],
                        $validated['from_warehouse_id'],
                        $validated['to_warehouse_id'],
                        $row['quantity'],
                        $transfer->id,
                        $validated['notes']
                    );
                }
            });

            return redirect()->route('stock.transfer.index')
                ->with('success', 'Transfer stok berhasil dilakukan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(StockTransfer $stockTransfer)
    {
        $stockTransfer->load(['fromWarehouse', 'toWarehouse', 'transferredBy', 'items.item.unit']);
        return view('stock.transfer.show', compact('stockTransfer'));
    }
}