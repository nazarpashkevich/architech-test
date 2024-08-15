<?php

namespace App\Contracts;

use App\Basket;

interface BasketOfferProcessor
{
    public function __construct(array $data = []);

    // can be extended by passed
    public function calculate(Basket $basket, \App\Data\BasketProduct $product): int;
}
