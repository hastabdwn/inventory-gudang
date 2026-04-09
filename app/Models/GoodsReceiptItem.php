<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsReceiptItem extends Model
{
    protected $fillable = [
        'goods_receipt_id', 'po_item_id', 'item_id', 'qty_received', 'notes',
    ];

    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

    public function poItem(): BelongsTo
    {
        return $this->belongsTo(PoItem::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}