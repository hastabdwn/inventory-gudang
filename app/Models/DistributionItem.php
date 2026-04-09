<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistributionItem extends Model
{
    protected $fillable = [
        'distribution_id', 'item_id', 'quantity',
    ];

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(Distribution::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}