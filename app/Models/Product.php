<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = ['stock'];

    public function types()
    {
        return $this->belongsTo(Type::class, 'foreign_key');
    }

    public function transactions()
    {
        return $this->belongsTo(Transaction::class);
    }
}
