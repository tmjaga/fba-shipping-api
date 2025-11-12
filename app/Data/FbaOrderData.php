<?php

namespace App\Data;

use App\Data\Exceptions\FbaException;

class FbaOrderData extends AbstractOrder
{
    protected function loadOrderData(int $id): array
    {
        $orderFile = storage_path("app/fba-shipping/order.{$id}.json");

        if (! file_exists($orderFile)) {
            throw new FbaException("Order file not found: $orderFile");
        }

        $data = json_decode(file_get_contents($orderFile), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new FbaException('Invalid JSON format: '.json_last_error_msg());
        }

        return $data;
    }
}
