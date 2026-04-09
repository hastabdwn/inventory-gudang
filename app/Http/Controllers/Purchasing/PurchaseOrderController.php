<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\PurchaseOrderService;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function __construct(protected PurchaseOrderService $poService) {}

    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'warehouse', 'creator'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders    = $query->paginate(15)->withQueryString();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        $statuses = [
            'draft'            => 'Draft',
            'waiting_approval' => 'Menunggu Approval',
            'approved'         => 'Disetujui',
            'partial'          => 'Diterima Sebagian',
            'completed'        => 'Selesai',
            'cancelled'        => 'Dibatalkan',
        ];

        return view('purchasing.orders.index', compact('orders', 'suppliers', 'statuses'));
    }

    public function create()
    {
        $suppliers  = Supplier::where('is_active', true)->orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();
        $items      = Item::where('is_active', true)->with('unit')->orderBy('name')->get();

        return view('purchasing.orders.create', compact('suppliers', 'warehouses', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'        => 'required|exists:suppliers,id',
            'warehouse_id'       => 'required|exists:warehouses,id',
            'order_date'         => 'required|date',
            'expected_date'      => 'nullable|date|after_or_equal:order_date',
            'notes'              => 'nullable|string',
            'items'              => 'required|array|min:1',
            'items.*.item_id'    => 'required|exists:items,id',
            'items.*.qty_ordered' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            $po = $this->poService->create($validated);

            return redirect()->route('purchasing.orders.show', $po)
                ->with('success', "PO {$po->po_number} berhasil dibuat.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load([
            'supplier', 'warehouse', 'creator', 'approver',
            'items.item.unit', 'receipts.receiver', 'receipts.items.item',
        ]);

        return view('purchasing.orders.show', compact('purchaseOrder'));
    }

    public function submit(PurchaseOrder $purchaseOrder)
    {
        try {
            $this->poService->submitForApproval($purchaseOrder);
            return back()->with('success', 'PO berhasil diajukan untuk approval.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function approve(PurchaseOrder $purchaseOrder)
    {
        try {
            $this->poService->approve($purchaseOrder);
            return back()->with('success', 'PO berhasil disetujui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel(PurchaseOrder $purchaseOrder)
    {
        try {
            $this->poService->cancel($purchaseOrder);
            return back()->with('success', 'PO berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}