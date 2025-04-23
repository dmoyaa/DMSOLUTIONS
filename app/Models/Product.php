<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'products'; // Asegúrate de que el nombre de la tabla es correcto

    protected $fillable = [
        'prod_name',
        'prod_reference',
        'prod_des',
        'provider_id',
        'prod_status',
        'prod_price_purchase',
        'prod_price_sales',
        'prod_image',
        'money_exchange'
    ];

    use HasFactory;
    public function quotes()
    {
        return $this->belongsToMany(Quote::class, 'quote_materials')->withPivot('quantity');
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
}
