<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'barcode', 'name', 'category_id', 'unit_id',
        'purchase_price', 'min_stock', 'description', 'image', 'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ItemStock::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getTotalStockAttribute(): int
    {
        return $this->stocks()->sum('quantity');
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->total_stock <= $this->min_stock;
    }
}