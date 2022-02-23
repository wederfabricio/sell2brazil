<?php

namespace App\Http\Controllers;

use App\Jobs\CreateOrder;
use Illuminate\Http\Request;

class OrdersController
{
    public function create(Request $request)
    {
        $order = (new CreateOrder($request->all()))->handle();
        return response()->json([
            'OrderId' => $order->id,
            'OrderCode' => $order->code,
            'OrderDate' => $order->created_at->toDateString(),
            'TotalAmountWithoutDiscount' => $order->total,
            'TotalAmountWithDiscount' => $order->totalWithDiscounts,
        ]);
    }
}
