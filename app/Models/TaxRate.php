<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    public function getApplicableTaxRateAttribute()
    {
        return TaxRate::where('valid_from', '<=', $this->created_at)
            ->orderBy('valid_from', 'desc')
            ->first();
    }
}
