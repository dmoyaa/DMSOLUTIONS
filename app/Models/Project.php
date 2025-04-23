<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'quote_id'];

    public function quote()
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }
}
