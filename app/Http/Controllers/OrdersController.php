<?php

namespace App\Http\Controllers;

use App\Jobs\CreateOrder;
use App\Transformers\OrderTransformers\OrderTransformerFactory;
use Illuminate\Http\Request;

class OrdersController
{
    public function create(Request $request, string $apiVersion = 'v1')
    {
        $order = (new CreateOrder($request->all()))->handle();
        return response()->json(OrderTransformerFactory::create($apiVersion)->transform($order));
    }
}
