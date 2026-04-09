<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemReturn extends Model
{
    use SoftDeletes;

    protected $table = 'returns';

    protected $fillable = [
        'return_number', 'purchase_order_id', 'supplier_id', 'warehouse_id',
        'created_by', 'return_date', 'status', 'reason', 'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItemReturnItem::class, 'return_id');
    }
}