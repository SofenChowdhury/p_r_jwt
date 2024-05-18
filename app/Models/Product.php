<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\ProductBooking;

class Product extends Model
{
    use HasFactory;
    protected $table = 'pos__products';

    public function productBooking(): HasOne
    {
        return $this->hasOne(ProductBooking::class, 'product_id', 'id');
    }
}
