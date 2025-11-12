<?php

namespace App\Services;

use App\Data\AbstractOrder;
use App\Data\BuyerInterface;
use App\Data\Exceptions\FbaException;
use App\ShippingServiceInterface;
use Illuminate\Support\Str;
use Random\RandomException;

/**
 * FBA (Fulfillment by Amazon) Service
 *
 * Simulates interaction with Amazon FBA shipping API for order fulfillment.
 */
class FbaService implements ShippingServiceInterface
{
    /**
     * Process order shipment through Amazon FBA
     *
     * @param  AbstractOrder  $order  The order to be shipped
     * @param  BuyerInterface  $buyer  The buyer information
     * @return string Tracking number for the shipped order
     *
     * @throws FbaException When FBA service rejects the order or encounters an error
     */
    public function ship(AbstractOrder $order, BuyerInterface $buyer): string
    {
        $orderData = $order->data ?? [];

        // make a fake (mock) request
        $response = $this->makeMockRequest($orderData, $buyer);

        if ($response['status'] === 'success') {
            return $response['tracking_number'];
        }

        throw new FbaException($response['message'] ?? 'Unknown FBA Servise error', 400);
    }

    /**
     * Simulates API request to Amazon FBA service
     *
     * @param  array  $orderData  Order information including items, quantities, etc.
     * @param  BuyerInterface  $buyer  Buyer details for shipping
     * @return array API response containing status and additional data
     *
     * Response formats:
     * - Success: ['status' => 'success', 'tracking_number' => 'FBA-...', 'estimated_delivery' => 'YYYY-MM-DD']
     * - Error: ['status' => 'error', 'message' => 'Error description']
     */
    protected function makeMockRequest(array $orderData, BuyerInterface $buyer): array
    {
        // request emulation with delay
        usleep(random_int(300000, 800000));

        // success response emulation
        if (random_int(1, 10) <= 8) {
            return [
                'status' => 'success',
                'tracking_number' => 'FBA-'.strtoupper(Str::random(10)),
                'estimated_delivery' => now()->addDays(rand(2, 5))->toDateString(),
            ];
        }

        // error response emulation
        return [
            'status' => 'error',
            'message' => 'FBA rejected order: out of stock',
        ];
    }
}
