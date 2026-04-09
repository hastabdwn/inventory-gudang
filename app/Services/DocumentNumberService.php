<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\Distribution;
use App\Models\GoodsReceipt;
use App\Models\StockTransfer;
use App\Models\ItemReturn;

class DocumentNumberService
{
    public static function generate(string $prefix, string $model): string
    {
        $year  = now()->format('Y');
        $month = now()->format('m');
        $base  = "{$prefix}/{$year}/{$month}/";

        $last = $model::withTrashed()
            ->where('po_number', 'like', "{$base}%")
            ->orWhere('dist_number', 'like', "{$base}%")
            ->orWhere('receipt_number', 'like', "{$base}%")
            ->orWhere('transfer_number', 'like', "{$base}%")
            ->orWhere('return_number', 'like', "{$base}%")
            ->latest('id')
            ->first();

        $seq = $last ? (int) substr($last->po_number ?? $last->dist_number ?? $last->receipt_number ?? $last->transfer_number ?? $last->return_number, -4) + 1 : 1;

        return $base . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    // Versi lebih simple per model
    public static function po(): string
    {
        return self::makeNumber('PO', PurchaseOrder::class, 'po_number');
    }

    public static function receipt(): string
    {
        return self::makeNumber('GR', GoodsReceipt::class, 'receipt_number');
    }

    public static function distribution(): string
    {
        return self::makeNumber('DO', Distribution::class, 'dist_number');
    }

    public static function transfer(): string
    {
        return self::makeNumber('TR', StockTransfer::class, 'transfer_number');
    }

    public static function return(): string
    {
        return self::makeNumber('RT', ItemReturn::class, 'return_number');
    }

    private static function makeNumber(string $prefix, string $model, string $field): string
    {
        $year  = now()->format('Y');
        $month = now()->format('m');
        $base  = "{$prefix}/{$year}/{$month}/";

        $last = $model::withTrashed()
            ->where($field, 'like', "{$base}%")
            ->latest('id')
            ->value($field);

        $seq = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $base . str_pad($seq, 4, '0', STR_PAD_LEFT);
        // Hasil: PO/2025/04/0001
    }
}