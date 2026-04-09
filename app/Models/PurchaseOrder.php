<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'po_number', 'supplier_id', 'warehouse_id', 'created_by',
        'approved_by', 'status', 'order_date', 'expected_date',
        'notes', 'total_amount', 'approved_at',
    ];

    protected $casts = [
        'order_date'    => 'date',
        'expected_date' => 'date',
        'approved_at'   => 'datetime',
        'total_amount'  => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PoItem::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(ItemReturn::class);
    }
}