<?php

namespace App\Services;

use App\Models\ItemReturn;
use App\Models\ItemReturnItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnService
{
    public function __construct(protected StockService $stockService) {}

    public function create(array $data): ItemReturn
    {
        return DB::transaction(function () use ($data) {
            $return = ItemReturn::create([
                'return_number'     => DocumentNumberService::return(),
                'purchase_order_id' => $data['purchase_order_id'] ?? null,
                'supplier_id'       => $data['supplier_id'],
                'warehouse_id'      => $data['warehouse_id'],
                'created_by'        => Auth::id(),
                'return_date'       => $data['return_date'],
                'status'            => 'draft',
                'reason'            => $data['reason'],
                'notes'             => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $row) {
                ItemReturnItem::create([
                    'return_id'  => $return->id,
                    'item_id'    => $row['item_id'],
                    'quantity'   => $row['quantity'],
                    'unit_price' => $row['unit_price'] ?? 0,
                    'notes'      => $row['notes'] ?? null,
                ]);
            }

            return $return;
        });
    }

    public function send(ItemReturn $return): void
    {
        DB::transaction(function () use ($return) {
            if ($return->status !== 'draft') {
                throw new \Exception('Hanya retur dengan status draft yang bisa dikirim.');
            }

            $return->load('items.item');

            // Cek stok semua barang dulu
            foreach ($return->items as $item) {
                if (!$this->stockService->hasStock($item->item_id, $return->warehouse_id, $item->quantity)) {
                    $stock = $this->stockService->getStock($item->item_id, $return->warehouse_id);
                    throw new \Exception(
                        "Stok {$item->item->name} tidak mencukupi untuk diretur. " .
                        "Tersedia: {$stock}, dibutuhkan: {$item->quantity}."
                    );
                }
            }

            // Kurangi stok semua barang
            foreach ($return->items as $item) {
                $this->stockService->stockOut(
                    $item->item_id,
                    $return->warehouse_id,
                    $item->quantity,
                    ItemReturn::class,
                    $return->id,
                    "Retur ke supplier {$return->supplier->name} — {$return->return_number}"
                );
            }

            $return->update(['status' => 'sent']);
        });
    }

    public function confirm(ItemReturn $return): void
    {
        if ($return->status !== 'sent') {
            throw new \Exception('Hanya retur dengan status terkirim yang bisa dikonfirmasi.');
        }

        $return->update(['status' => 'confirmed']);
    }

    public function cancel(ItemReturn $return): void
    {
        DB::transaction(function () use ($return) {
            if ($return->status === 'cancelled') {
                throw new \Exception('Retur ini sudah dibatalkan.');
            }

            if ($return->status === 'confirmed') {
                throw new \Exception('Retur yang sudah dikonfirmasi tidak dapat dibatalkan.');
            }

            // Jika sudah sent, kembalikan stok
            if ($return->status === 'sent') {
                $return->load('items.item');

                foreach ($return->items as $item) {
                    $this->stockService->stockIn(
                        $item->item_id,
                        $return->warehouse_id,
                        $item->quantity,
                        ItemReturn::class,
                        $return->id,
                        "Pembatalan retur {$return->return_number}"
                    );
                }
            }

            $return->update(['status' => 'cancelled']);
        });
    }
}