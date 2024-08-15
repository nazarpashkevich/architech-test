<?php

class BasketTotal
{
    public function __construct(
        public int $productsPrice, // can be Money type
        public int $deliveryPrice, // can be Money type
        public int $total // can be Money type
    )
    {
    }
}
