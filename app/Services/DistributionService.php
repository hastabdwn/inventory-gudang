<?php

namespace App\Services;

use App\Models\Distribution;
use App\Models\DistributionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DistributionService
{
    public function __construct(protected StockService $stockService) {}

    public function create(array $data): Distribution
    {
        return DB::transaction(function () use ($data) {
            $distribution = Distribution::create([
                'dist_number'  => DocumentNumberService::distribution(),
                'warehouse_id' => $data['warehouse_id'],
                'issued_by'    => Auth::id(),
                'destination'  => $data['destination'],
                'recipient'    => $data['recipient'] ?? null,
                'dist_date'    => $data['dist_date'],
                'status'       => 'draft',
                'notes'        => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $row) {
                DistributionItem::create([
                    'distribution_id' => $distribution->id,
                    'item_id'         => $row['item_id'],
                    'quantity'        => $row['quantity'],
                ]);
            }

            return $distribution;
        });
    }

    public function issue(Distribution $distribution): void
    {
        DB::transaction(function () use ($distribution) {
            if ($distribution->status !== 'draft') {
                throw new \Exception('Hanya distribusi dengan status draft yang bisa diterbitkan.');
            }

            $distribution->load('items.item');

            // Cek stok semua barang dulu sebelum dikurangi
            foreach ($distribution->items as $item) {
                if (!$this->stockService->hasStock($item->item_id, $distribution->warehouse_id, $item->quantity)) {
                    $stock = $this->stockService->getStock($item->item_id, $distribution->warehouse_id);
                    throw new \Exception(
                        "Stok {$item->item->name} tidak mencukupi. " .
                        "Tersedia: {$stock}, dibutuhkan: {$item->quantity}."
                    );
                }
            }

            // Kurangi stok semua barang
            foreach ($distribution->items as $item) {
                $this->stockService->stockOut(
                    $item->item_id,
                    $distribution->warehouse_id,
                    $item->quantity,
                    Distribution::class,
                    $distribution->id,
                    "Distribusi ke {$distribution->destination} — {$distribution->dist_number}"
                );
            }

            $distribution->update(['status' => 'issued']);
        });
    }

    public function cancel(Distribution $distribution): void
    {
        DB::transaction(function () use ($distribution) {
            if ($distribution->status === 'cancelled') {
                throw new \Exception('Distribusi ini sudah dibatalkan.');
            }

            // Jika sudah issued, kembalikan stok
            if ($distribution->status === 'issued') {
                $distribution->load('items.item');

                foreach ($distribution->items as $item) {
                    $this->stockService->stockIn(
                        $item->item_id,
                        $distribution->warehouse_id,
                        $item->quantity,
                        Distribution::class,
                        $distribution->id,
                        "Pembatalan distribusi {$distribution->dist_number}"
                    );
                }
            }

            $distribution->update(['status' => 'cancelled']);
        });
    }
}