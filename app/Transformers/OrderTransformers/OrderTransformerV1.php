<?php

namespace App\Transformers\OrderTransformers;

use App\Models\Order;

class OrderTransformerV1 implements OrderTransformer
{
    public function transform(Order $order): array
    {
        return [
            'OrderId' => $order->id,
            'OrderCode' => $order->code,
            'OrderDate' => $order->created_at->toDateString(),
            'TotalAmountWithoutDiscount' => $order->total,
            'TotalAmountWithDiscount' => $order->totalWithDiscounts,
        ];
    }
}
