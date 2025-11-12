<?php

namespace App\Http\Controllers;

use App\Data\Exceptions\FbaException;
use App\Data\FbaBuyerData;
use App\Data\FbaOrderData;
use App\Rules\BuyerDataFileExists;
use App\Rules\OrderDataFileExists;
use App\Services\FbaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FbaServiceController extends Controller
{
    public function __construct(private FbaService $fbaService) {}

    public function fulfillOrder(string $buyer_id, string $order_id): JsonResponse
    {
        // validate input data
        $validator = Validator::make([
            'buyer_id' => $buyer_id,
            'order_id' => $order_id,
        ], [
            'buyer_id' => ['required', 'integer', new BuyerDataFileExists],
            'order_id' => ['required', 'integer', new OrderDataFileExists],
        ]);

        if ($validator->fails()) {
            abort(422, json_encode($validator->errors()->all()));
        }

        try {
            $order = new FbaOrderData((int) $order_id);
            $buyer = new FbaBuyerData((int) $buyer_id);

            $trackingNumber = $this->fbaService->ship($order, $buyer);

            return response()->json([
                'success' => true,
                'message' => 'Order fulfilled successfully.',
                'data' => [
                    'tracking_number' => $trackingNumber,
                ],
            ], 200);
        } catch (FbaException $e) {
            Log::error('FBA Service Error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
