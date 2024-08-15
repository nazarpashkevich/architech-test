<?php

class BasketOffer
{

    public function __construct(
        public string $productCode,
        public string $processor,
        public array $data = [],
    )
    {
    }
}
