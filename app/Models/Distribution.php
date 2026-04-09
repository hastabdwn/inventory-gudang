<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distribution extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'dist_number', 'warehouse_id', 'issued_by',
        'destination', 'recipient', 'dist_date', 'status', 'notes',
    ];

    protected $casts = [
        'dist_date' => 'date',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DistributionItem::class);
    }
}