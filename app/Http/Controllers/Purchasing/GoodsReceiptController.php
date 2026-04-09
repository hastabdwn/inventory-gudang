<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceipt;
use App\Models\PurchaseOrder;
use App\Services\PurchaseOrderService;
use Illuminate\Http\Request;

class GoodsReceiptController extends Controller
{
    public function __construct(protected PurchaseOrderService $poService) {}

    public function index(Request $request)
    {
        $receipts = GoodsReceipt::with(['purchaseOrder.supplier', 'warehouse', 'receiver'])
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('receipt_date', '>=', $request->date_from))
            ->when($request->filled('date_to'),   fn($q) => $q->whereDate('receipt_date', '<=', $request->date_to))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('purchasing.receipts.index', compact('receipts'));
    }

    public function create(PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->status, ['approved', 'partial'])) {
            return redirect()->route('purchasing.orders.show', $purchaseOrder)
                ->with('error', 'PO ini tidak dalam status yang bisa menerima barang.');
        }

        $purchaseOrder->load(['supplier', 'warehouse', 'items.item.unit']);

        return view('purchasing.receipts.create', compact('purchaseOrder'));
    }

    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'receipt_date'              => 'required|date',
            'notes'                     => 'nullable|string',
            'items'                     => 'required|array|min:1',
            'items.*.po_item_id'        => 'required|exists:po_items,id',
            'items.*.qty_received'      => 'required|integer|min:0',
            'items.*.notes'             => 'nullable|string',
        ]);

        try {
            $receipt = $this->poService->receiveGoods($purchaseOrder, $validated);

            return redirect()->route('purchasing.receipts.show', $receipt)
                ->with('success', "Penerimaan barang {$receipt->receipt_number} berhasil dicatat.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load([
            'purchaseOrder.supplier',
            'warehouse',
            'receiver',
            'items.item.unit',
            'items.poItem',
        ]);

        return view('purchasing.receipts.show', compact('goodsReceipt'));
    }
}