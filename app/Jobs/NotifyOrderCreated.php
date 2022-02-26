<?php

namespace App\Jobs;

use Exception;
use App\Models\Order;
use App\Transformers\OrderTransformers\OrderTransformerFactory;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyOrderCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var array
     */
    protected $servers = [
        'https://localhost:9001/order' => 'v1',
        'https://localhost:9002/v1/order' => 'v2',
        'https://localhost:9003/web_api/order' => 'v3',
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return Order
     */
    public function handle(): void
    {
        foreach($this->servers as $ip => $apiVersion) {
            try {
                $data = OrderTransformerFactory::create($apiVersion)->transform($this->order);
                Log::info('Sending order #' . $this->order->id . ' to server ' . $ip, $data);
                $client = new Client();
                $client->post($ip, [
                    'json' => $data,
                ]);
                Log::info('Send order #' . $this->order->id . ' to server ' . $ip . ' successful!');
            } catch (Exception $e) {
                Log::warning('Failed to send order #' . $this->order->id . ' to server ' . $ip . ': ' . $e->getMessage());
            }
        }
    }
}
