<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PoItem extends Model
{
    protected $fillable = [
        'purchase_order_id', 'item_id', 'qty_ordered',
        'qty_received', 'unit_price', 'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function receiptItems(): HasMany
    {
        return $this->hasMany(GoodsReceiptItem::class);
    }

    public function getRemainingQtyAttribute(): int
    {
        return $this->qty_ordered - $this->qty_received;
    }
}