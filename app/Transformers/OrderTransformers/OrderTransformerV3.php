<?php

namespace App\Transformers\OrderTransformers;

use App\Models\Order;

class OrderTransformerV3 implements OrderTransformer
{
    public function transform(Order $order): array
    {
        return [
            'id' => $order->id,
            'code' => $order->code,
            'date' => $order->created_at->toDateString(),
            'totalAmount' => $order->total,
            'totalAmountWithDiscount' => $order->totalWithDiscounts,
        ];
    }
}
