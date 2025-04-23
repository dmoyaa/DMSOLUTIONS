<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public function quotes()
    {
        return $this->hasMany(Quote::class, 'quote_client_id');
    }
}
