<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = ['id_products', 'qty'];

    public function products()
    {
        return $this->belongsTo(Product::class, 'id_products');
    }
}
