<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtraCost extends Model
{
    protected $fillable = [
        'name',
        'unit_price',
        'quote_id'
    ];
    public function Quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }
}
