<?php

namespace App\Data;

use App\Data\Exceptions\FbaException;

class FbaBuyerData implements BuyerInterface
{
    private array $buyerData = [];

    public function __construct(int $buyerId)
    {
        $buyerFile = storage_path("app/fba-shipping/buyer.{$buyerId}.json");

        if (! file_exists($buyerFile)) {
            throw new FbaException("Buyer file not found: $buyerFile");
        }

        $this->buyerData = json_decode(file_get_contents($buyerFile), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new FbaException('Invalid JSON format: '.json_last_error_msg());
        }
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->buyerData[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->buyerData[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->buyerData[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->buyerData[$offset]);
    }
}
