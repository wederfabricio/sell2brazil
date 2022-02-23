<?php

namespace Tests\Unit;

use App\Jobs\CreateOrder;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create order with discount.
     *
     * @return void
     */
    public function testCreateOrderWithDiscount()
    {
        /** @var Order */
        $order = (new CreateOrder([
            [
                'ArticleCode' => 'T12',
                'ArticleName' => 'Tractor 2011 - XYZ',
                'UnitPrice' => 200,
                'Quantity' => 3,
            ],
            [
                'ArticleCode' => 'T12',
                'ArticleName' => 'Tractor 2011 - XYZ',
                'UnitPrice' => 200,
                'Quantity' => 2,
            ]
        ]))->handle();

        $this->assertEquals(1, OrderItem::count());
        $this->assertEquals($order->total, 1000);
        $this->assertEquals($order->totalWithDiscounts, 850);
    }

    /**
     * Create order with discount.
     *
     * @return void
     */
    public function testCreateOrderWithoutDiscount()
    {
        /** @var Order */
        $order = (new CreateOrder([
            [
                'ArticleCode' => 'T12',
                'ArticleName' => 'Tractor 2011 - XYZ',
                'UnitPrice' => 200,
                'Quantity' => 2,
            ]
        ]))->handle();

        $this->assertEquals(1, OrderItem::count());
        $this->assertEquals($order->total, 400);
        $this->assertEquals($order->totalWithDiscounts, 400);
    }
}
