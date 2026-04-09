<?php

namespace App\Http\Controllers\ItemReturn;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemReturn;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\ReturnService;
use Illuminate\Http\Request;

class ItemReturnController extends Controller
{
    public function __construct(protected ReturnService $returnService) {}

    public function index(Request $request)
    {
        $query = ItemReturn::with(['supplier', 'warehouse', 'creator'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('return_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('return_date', '<=', $request->date_to);
        }

        $returns    = $query->paginate(15)->withQueryString();
        $suppliers  = Supplier::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();

        return view('returns.index', compact('returns', 'suppliers', 'warehouses'));
    }

    public function create(Request $request)
    {
        $suppliers  = Supplier::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $items      = Item::where('is_active', true)->with(['unit', 'stocks'])->orderBy('name')->get();

        // Jika dari halaman PO, pre-fill supplier & items
        $selectedPo = null;
        if ($request->filled('po_id')) {
            $selectedPo = PurchaseOrder::with(['supplier', 'items.item.unit'])
                ->find($request->po_id);
        }

        return view('returns.create', compact('suppliers', 'warehouses', 'items', 'selectedPo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'          => 'required|exists:suppliers,id',
            'warehouse_id'         => 'required|exists:warehouses,id',
            'purchase_order_id'    => 'nullable|exists:purchase_orders,id',
            'return_date'          => 'required|date',
            'reason'               => 'required|string|max:1000',
            'notes'                => 'nullable|string',
            'items'                => 'required|array|min:1',
            'items.*.item_id'      => 'required|exists:items,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.unit_price'   => 'nullable|numeric|min:0',
            'items.*.notes'        => 'nullable|string',
        ]);

        try {
            $return = $this->returnService->create($validated);

            return redirect()->route('returns.show', $return)
                ->with('success', "Retur {$return->return_number} berhasil dibuat.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(ItemReturn $itemReturn)
    {
        $itemReturn->load([
            'supplier',
            'warehouse',
            'creator',
            'purchaseOrder',
            'items.item.unit',
        ]);

        return view('returns.show', compact('itemReturn'));
    }

    public function send(ItemReturn $itemReturn)
    {
        try {
            $this->returnService->send($itemReturn);

            return back()->with('success', 'Retur berhasil dikirim. Stok telah dikurangi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function confirm(ItemReturn $itemReturn)
    {
        try {
            $this->returnService->confirm($itemReturn);

            return back()->with('success', 'Retur berhasil dikonfirmasi oleh supplier.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel(ItemReturn $itemReturn)
    {
        try {
            $this->returnService->cancel($itemReturn);

            return back()->with('success', 'Retur berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}