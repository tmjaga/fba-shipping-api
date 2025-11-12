<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OrderDataFileExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $orderFile = storage_path("app/fba-shipping/order.$value.json");

        if (!file_exists($orderFile)) {
            $fail("Order data file order.{$value}.json not found.");
        }
    }
}
