<?php

namespace App\Services;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\PoItem;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderService
{
    public function __construct(protected StockService $stockService) {}

    public function create(array $data): PurchaseOrder
    {
        return DB::transaction(function () use ($data) {
            $po = PurchaseOrder::create([
                'po_number'    => DocumentNumberService::po(),
                'supplier_id'  => $data['supplier_id'],
                'warehouse_id' => $data['warehouse_id'],
                'created_by'   => Auth::id(),
                'status'       => 'draft',
                'order_date'   => $data['order_date'],
                'expected_date'=> $data['expected_date'] ?? null,
                'notes'        => $data['notes'] ?? null,
                'total_amount' => 0,
            ]);

            $total = 0;
            foreach ($data['items'] as $row) {
                $subtotal = $row['qty_ordered'] * $row['unit_price'];
                $total   += $subtotal;

                PoItem::create([
                    'purchase_order_id' => $po->id,
                    'item_id'           => $row['item_id'],
                    'qty_ordered'       => $row['qty_ordered'],
                    'qty_received'      => 0,
                    'unit_price'        => $row['unit_price'],
                    'subtotal'          => $subtotal,
                ]);
            }

            $po->update(['total_amount' => $total]);

            return $po;
        });
    }

    public function submitForApproval(PurchaseOrder $po): void
    {
        if ($po->status !== 'draft') {
            throw new \Exception('Hanya PO dengan status draft yang bisa diajukan untuk approval.');
        }

        $po->update(['status' => 'waiting_approval']);
    }

    public function approve(PurchaseOrder $po): void
    {
        if ($po->status !== 'waiting_approval') {
            throw new \Exception('Hanya PO dengan status menunggu approval yang bisa disetujui.');
        }

        $po->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
    }

    public function cancel(PurchaseOrder $po): void
    {
        if (in_array($po->status, ['completed', 'cancelled'])) {
            throw new \Exception('PO ini tidak dapat dibatalkan.');
        }

        $po->update(['status' => 'cancelled']);
    }

    public function receiveGoods(PurchaseOrder $po, array $data): GoodsReceipt
    {
        return DB::transaction(function () use ($po, $data) {
            if ($po->status !== 'approved' && $po->status !== 'partial') {
                throw new \Exception('Barang hanya bisa diterima untuk PO yang sudah disetujui.');
            }

            $receipt = GoodsReceipt::create([
                'receipt_number'    => DocumentNumberService::receipt(),
                'purchase_order_id' => $po->id,
                'warehouse_id'      => $po->warehouse_id,
                'received_by'       => Auth::id(),
                'receipt_date'      => $data['receipt_date'],
                'notes'             => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $row) {
                if (($row['qty_received'] ?? 0) <= 0) continue;

                $poItem = PoItem::findOrFail($row['po_item_id']);

                // Validasi tidak melebihi sisa qty
                $remaining = $poItem->qty_ordered - $poItem->qty_received;
                if ($row['qty_received'] > $remaining) {
                    throw new \Exception(
                        "Qty diterima untuk {$poItem->item->name} melebihi sisa order ({$remaining})."
                    );
                }

                GoodsReceiptItem::create([
                    'goods_receipt_id' => $receipt->id,
                    'po_item_id'       => $poItem->id,
                    'item_id'          => $poItem->item_id,
                    'qty_received'     => $row['qty_received'],
                    'notes'            => $row['notes'] ?? null,
                ]);

                // Update qty_received di po_item
                $poItem->increment('qty_received', $row['qty_received']);

                // Update stok gudang
                $this->stockService->stockIn(
                    $poItem->item_id,
                    $po->warehouse_id,
                    $row['qty_received'],
                    GoodsReceipt::class,
                    $receipt->id,
                    "Penerimaan barang dari PO {$po->po_number}"
                );
            }

            // Update status PO
            $po->refresh();
            $allComplete = $po->items->every(
                fn($item) => $item->qty_received >= $item->qty_ordered
            );
            $po->update(['status' => $allComplete ? 'completed' : 'partial']);

            return $receipt;
        });
    }
}