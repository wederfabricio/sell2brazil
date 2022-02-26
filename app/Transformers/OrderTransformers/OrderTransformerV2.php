<?php

namespace App\Transformers\OrderTransformers;

use App\Models\Order;

class OrderTransformerV2 implements OrderTransformer
{
    public function transform(Order $order): array
    {
        return [
            'id' => $order->id,
            'code' => $order->code,
            'date' => $order->created_at->toDateString(),
            'total' => $order->total,
            'discount' => $order->discounts,
        ];
    }
}
