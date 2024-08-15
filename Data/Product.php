<?php

class Product
{

    public function __construct(
        public string $name,
        public string $code,
        public int $price // can be Money type
    )
    {
    }
}
