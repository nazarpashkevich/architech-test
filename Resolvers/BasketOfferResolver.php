<?php

namespace Resolvers;

use Contracts\BasketOfferProcessor;

class BasketOfferResolver
{
    protected function __construct()
    {}

    public static function resolve(string $processor, array $data): ?BasketOfferProcessor
    {
        $class = "\Processors\\$processor";
        if (class_exists($class)) {
            return new $class($data);
        }

        return null;
    }
}
