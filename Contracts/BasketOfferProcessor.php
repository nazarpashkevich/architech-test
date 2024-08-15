<?php

namespace Contracts;

use Basket;

interface BasketOfferProcessor
{
    public function __construct(array $data = []);

    // can be extended by passed
    public function calculate(Basket $basket, \BasketProduct $product): int;
}
