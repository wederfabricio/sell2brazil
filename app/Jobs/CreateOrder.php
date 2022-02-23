<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreateOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $validate = Validator::make(['data' => $data], [
            'data' => 'required|array|min:1',
            'data.*.ArticleCode' => 'required|string',
            'data.*.ArticleName' => 'required|string',
            'data.*.UnitPrice' => 'required|numeric',
            'data.*.Quantity' => 'required|numeric',
        ]);

        $validate->validate();

        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return Order
     */
    public function handle(): Order
    {
        return DB::transaction(function () {
            $totalDiscounts = 0;
            $totalAmount  = 0;

            $order = new Order();
            $order->save();

            collect($this->data)
                ->groupBy('ArticleCode')
                ->map(
                    function (Collection $items) use ($order, &$totalAmount, &$totalDiscounts) {
                        $quantity = $items->sum('Quantity');
                        $item = $items->get(0);
                        $price = $item['UnitPrice'];

                        $discounts = 0;
                        $amount = $price * $quantity;

                        if ($quantity >= 5 && $quantity <= 9 && $amount >= 500) {
                            $discounts = $amount * 0.15;
                        }

                        $totalAmount += $amount;
                        $totalDiscounts += $discounts;

                        $order->items()->save(new OrderItem([
                            'article_code' => $item['ArticleCode'],
                            'article_name' => $item['ArticleName'],
                            'quantity' => $quantity,
                            'unit_price' => $price,
                            'discounts' => $discounts,
                            'amount' => $amount,
                        ]));
                    }
                );

            return $order;
        });
    }
}
