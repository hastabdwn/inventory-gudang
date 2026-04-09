<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemStock;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockService
{
    /**
     * Tambah stok (barang masuk)
     */
    public function stockIn(
        int $itemId,
        int $warehouseId,
        int $quantity,
        string $referenceType = null,
        int $referenceId = null,
        string $notes = null
    ): ItemStock {
        return DB::transaction(function () use ($itemId, $warehouseId, $quantity, $referenceType, $referenceId, $notes) {
            $stock = ItemStock::firstOrCreate(
                ['item_id' => $itemId, 'warehouse_id' => $warehouseId],
                ['quantity' => 0]
            );

            $stockBefore = $stock->quantity;
            $stock->increment('quantity', $quantity);

            StockMovement::create([
                'item_id'        => $itemId,
                'warehouse_id'   => $warehouseId,
                'user_id'        => Auth::id(),
                'type'           => 'in',
                'quantity'       => $quantity,
                'stock_before'   => $stockBefore,
                'stock_after'    => $stockBefore + $quantity,
                'reference_type' => $referenceType,
                'reference_id'   => $referenceId,
                'notes'          => $notes,
                'moved_at'       => now(),
            ]);

            return $stock->fresh();
        });
    }

    /**
     * Kurangi stok (barang keluar)
     */
    public function stockOut(
        int $itemId,
        int $warehouseId,
        int $quantity,
        string $referenceType = null,
        int $referenceId = null,
        string $notes = null
    ): ItemStock {
        return DB::transaction(function () use ($itemId, $warehouseId, $quantity, $referenceType, $referenceId, $notes) {
            $stock = ItemStock::where('item_id', $itemId)
                ->where('warehouse_id', $warehouseId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($stock->quantity < $quantity) {
                $item      = Item::find($itemId);
                $warehouse = Warehouse::find($warehouseId);
                throw new \Exception(
                    "Stok {$item->name} di {$warehouse->name} tidak mencukupi. " .
                    "Tersedia: {$stock->quantity}, dibutuhkan: {$quantity}."
                );
            }

            $stockBefore = $stock->quantity;
            $stock->decrement('quantity', $quantity);

            StockMovement::create([
                'item_id'        => $itemId,
                'warehouse_id'   => $warehouseId,
                'user_id'        => Auth::id(),
                'type'           => 'out',
                'quantity'       => $quantity,
                'stock_before'   => $stockBefore,
                'stock_after'    => $stockBefore - $quantity,
                'reference_type' => $referenceType,
                'reference_id'   => $referenceId,
                'notes'          => $notes,
                'moved_at'       => now(),
            ]);

            return $stock->fresh();
        });
    }

    /**
     * Transfer stok antar gudang
     */
    public function transfer(
        int $itemId,
        int $fromWarehouseId,
        int $toWarehouseId,
        int $quantity,
        int $referenceId = null,
        string $notes = null
    ): void {
        DB::transaction(function () use ($itemId, $fromWarehouseId, $toWarehouseId, $quantity, $referenceId, $notes) {
            // Cek stok asal
            $fromStock = ItemStock::where('item_id', $itemId)
                ->where('warehouse_id', $fromWarehouseId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($fromStock->quantity < $quantity) {
                $item      = Item::find($itemId);
                $warehouse = Warehouse::find($fromWarehouseId);
                throw new \Exception(
                    "Stok {$item->name} di {$warehouse->name} tidak mencukupi. " .
                    "Tersedia: {$fromStock->quantity}, dibutuhkan: {$quantity}."
                );
            }

            // Kurangi stok asal
            $fromBefore = $fromStock->quantity;
            $fromStock->decrement('quantity', $quantity);

            StockMovement::create([
                'item_id'        => $itemId,
                'warehouse_id'   => $fromWarehouseId,
                'user_id'        => Auth::id(),
                'type'           => 'transfer_out',
                'quantity'       => $quantity,
                'stock_before'   => $fromBefore,
                'stock_after'    => $fromBefore - $quantity,
                'reference_type' => 'App\Models\StockTransfer',
                'reference_id'   => $referenceId,
                'notes'          => $notes,
                'moved_at'       => now(),
            ]);

            // Tambah stok tujuan
            $toStock = ItemStock::firstOrCreate(
                ['item_id' => $itemId, 'warehouse_id' => $toWarehouseId],
                ['quantity' => 0]
            );

            $toBefore = $toStock->quantity;
            $toStock->increment('quantity', $quantity);

            StockMovement::create([
                'item_id'        => $itemId,
                'warehouse_id'   => $toWarehouseId,
                'user_id'        => Auth::id(),
                'type'           => 'transfer_in',
                'quantity'       => $quantity,
                'stock_before'   => $toBefore,
                'stock_after'    => $toBefore + $quantity,
                'reference_type' => 'App\Models\StockTransfer',
                'reference_id'   => $referenceId,
                'notes'          => $notes,
                'moved_at'       => now(),
            ]);
        });
    }

    /**
     * Adjustment stok manual (koreksi)
     */
    public function adjust(
        int $itemId,
        int $warehouseId,
        int $newQuantity,
        string $notes = null
    ): ItemStock {
        return DB::transaction(function () use ($itemId, $warehouseId, $newQuantity, $notes) {
            $stock = ItemStock::firstOrCreate(
                ['item_id' => $itemId, 'warehouse_id' => $warehouseId],
                ['quantity' => 0]
            );

            $stockBefore    = $stock->quantity;
            $stock->quantity = $newQuantity;
            $stock->save();

            StockMovement::create([
                'item_id'        => $itemId,
                'warehouse_id'   => $warehouseId,
                'user_id'        => Auth::id(),
                'type'           => 'adjustment',
                'quantity'       => abs($newQuantity - $stockBefore),
                'stock_before'   => $stockBefore,
                'stock_after'    => $newQuantity,
                'reference_type' => null,
                'reference_id'   => null,
                'notes'          => $notes ?? 'Penyesuaian stok manual',
                'moved_at'       => now(),
            ]);

            return $stock->fresh();
        });
    }

    /**
     * Cek stok tersedia
     */
    public function getStock(int $itemId, int $warehouseId): int
    {
        return ItemStock::where('item_id', $itemId)
            ->where('warehouse_id', $warehouseId)
            ->value('quantity') ?? 0;
    }

    /**
     * Cek apakah stok cukup
     */
    public function hasStock(int $itemId, int $warehouseId, int $quantity): bool
    {
        return $this->getStock($itemId, $warehouseId) >= $quantity;
    }
}