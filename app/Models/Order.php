<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read Collection<OrderItem> $items
 * @property-read string $code
 * @property-read float $totalWithDiscounts
 * @property-read float $total
 * @property Carbon $created_at
 */
class Order extends Model
{
    use HasFactory;

    public $fillable = [];

    public $timestamps = true;

    public function getCodeAttribute(): string
    {
        return $this->created_at->format('y-m') . '-' . $this->id;
    }

    /**
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function getTotalWithDiscountsAttribute(): float
    {
        $discounts = $this->items->sum(function (OrderItem $item) {
            return $item->discounts;
        });

        return $this->total - $discounts;
    }

    public function getTotalAttribute(): float
    {
        return $this->items->sum('amount');
    }
}
