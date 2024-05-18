<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProductBooking extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'product__bookings';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'id', 'product_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'eid', 'modified');
    }

    public function employee()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
