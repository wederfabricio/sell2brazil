<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test empty request.
     *
     * @return void
     */
    public function testEmptyRequest()
    {
        $this->postJson('/orders')
            ->assertStatus(422);
    }

    /**
     * Test invalid request.
     *
     * @return void
     */
    public function testInvalidRequest()
    {
        $this->postJson('/orders', [
            [
                'ArticleCode' => 'T12',
                'ArticleName' => 'Tractor 2011 - XYZ',
                'UnitPrice' => 200,
            ]
        ])
            ->assertStatus(422);
    }

    /**
     * Test validRequest
     *
     * @return void
     */
    public function testValidRequest()
    {
        $response = $this->postJson('/orders', [
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
            ],
        ])
            ->assertStatus(200);

        $date = Carbon::now();
        $data = $response->json();

        $orderId = $data['OrderId'];
        $orderCode = $date->format('y-m') . '-' . $orderId;
        $orderDate = $date->toDateString();

        $response->assertJsonPath('OrderCode', $orderCode);
        $response->assertJsonPath('OrderDate', $orderDate);
        $response->assertJsonPath('TotalAmountWithoutDiscount', 1000);
        $response->assertJsonPath('TotalAmountWithDiscount', 850);
    }
}
