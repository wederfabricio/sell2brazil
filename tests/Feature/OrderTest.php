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
        $this->postJson('/api/orders')
            ->assertStatus(422);
    }

    /**
     * Test invalid request.
     *
     * @return void
     */
    public function testInvalidRequest()
    {
        $this->postJson('/api/orders', [
            [
                'ArticleCode' => 'T12',
                'ArticleName' => 'Tractor 2011 - XYZ',
                'UnitPrice' => 200,
            ]
        ])
            ->assertStatus(422);
    }

    /**
     * Test validRequest v1
     *
     * @return void
     */
    public function testValidRequestV1()
    {
        $response = $this->postJson('/api/orders', $this->validData())
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

    /**
     * Test validRequest v2
     *
     * @return void
     */
    public function testValidRequestV2()
    {
        $response = $this->postJson('/api/orders/v2', $this->validData())
            ->assertStatus(200);

        $date = Carbon::now();
        $data = $response->json();

        $orderId = $data['id'];
        $orderCode = $date->format('y-m') . '-' . $orderId;
        $orderDate = $date->toDateString();

        $response->assertJsonPath('code', $orderCode);
        $response->assertJsonPath('date', $orderDate);
        $response->assertJsonPath('total', 1000);
        $response->assertJsonPath('discount', 150);
    }

    /**
     * Test validRequest v3
     *
     * @return void
     */
    public function testValidRequestV3()
    {
        $response = $this->postJson('/api/orders/v3', $this->validData())
            ->assertStatus(200);

        $date = Carbon::now();
        $data = $response->json();

        $orderId = $data['id'];
        $orderCode = $date->format('y-m') . '-' . $orderId;
        $orderDate = $date->toDateString();

        $response->assertJsonPath('code', $orderCode);
        $response->assertJsonPath('date', $orderDate);
        $response->assertJsonPath('totalAmount', 1000);
        $response->assertJsonPath('totalAmountWithDiscount', 850);
    }

    protected function validData(): array {
        return [
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
        ];
    }
}
