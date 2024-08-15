<?php

namespace Processors;

use App\Basket;
use App\Contracts\BasketOfferProcessor;

class HalfSecondPrice implements BasketOfferProcessor
{
    public function __construct(array $data = [])
    {
    }

    public function calculate(Basket $basket, \App\Data\BasketProduct $product): int
    {
        // every second product is with half price

        // calculate how many products are with full price
        $fullPriceItems = intdiv($product->quantity, 2) + ($product->quantity % 2);
        $halfPriceItems = $product->quantity - $fullPriceItems;

        // calculate total
        return ($fullPriceItems * $product->price) + ($halfPriceItems * ($product->price / 2));
    }
}
