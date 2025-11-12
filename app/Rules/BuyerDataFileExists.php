<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BuyerDataFileExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $buyerFile = storage_path("app/fba-shipping/buyer.$value.json");

        if (!file_exists($buyerFile)) {
            $fail("Buyer data file buyer.{$value}.json not found.");
        }

    }
}
