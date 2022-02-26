<?php

namespace App\Transformers\OrderTransformers;

use Exception;

abstract class OrderTransformerFactory
{
    public static function create(string $version): OrderTransformer
    {
        return match ($version) {
            'v1' => new OrderTransformerV1(),
            'v2' => new OrderTransformerV2(),
            'v3' => new OrderTransformerV3(),
            default => throw new Exception('OrderTransformer not available for ' . $version),
        };
    }
}
