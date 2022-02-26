<?php

namespace App\Transformers\OrderTransformers;

use App\Models\Order;

interface OrderTransformer
{
    public function transform(Order $order): array;
}
