<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $guarded = [];

    use HasFactory;
    protected $fillable = [
        'provider_name',
        'provider_number',
        'provider_email',
        'provider_nit',
        'status',
    ];
    public function products()
    {
        return $this->hasMany(Product::class, 'provider_id');
    }
}
