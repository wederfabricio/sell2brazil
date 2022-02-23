<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property float $unit_price
 * @property float $quantity
 * @property float $amount
 * @property float $discounts
 * @property-read float $total
 */
class OrderItem extends Model
{
    use HasFactory;

    public $fillable = [
        'article_code',
        'article_name',
        'quantity',
        'unit_price',
        'discounts',
        'amount',
    ];

    public function getTotalWithDiscountsAttribute(): float {
        return $this->amount - $this->discounts;
    }
}
