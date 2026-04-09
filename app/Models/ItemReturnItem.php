<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemReturnItem extends Model
{
    protected $table = 'return_items';

    protected $fillable = [
        'return_id', 'item_id', 'quantity', 'unit_price', 'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function itemReturn(): BelongsTo
    {
        return $this->belongsTo(ItemReturn::class, 'return_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}