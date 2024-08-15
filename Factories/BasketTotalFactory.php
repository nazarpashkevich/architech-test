<?php

namespace Factories;

class BasketTotalFactory
{

    protected function __construct(protected \Basket $basket)
    {
    }

    public static function handle(\Basket $basket): \BasketTotal
    {
        return (new self($basket))->calculate();
    }

    protected function calculate(): \BasketTotal
    {
        $productsPrice = $this->calculateProductsTotal();
        // delivery price depends on the products
        $delivery = $this->calculateDelivery($productsPrice);

        return new \BasketTotal(
            $productsPrice,
            $delivery,
            $productsPrice + $delivery
        );
    }

    protected function calculateProductsTotal(): int
    {
        $total = 0;

        foreach ($this->basket->items() as $product) {
            $total += $this->basket->getProductPrice($product);
        }

        return $total;
    }

    protected function calculateDelivery(int $productPrice): int
    {
        $deliveryRates = $this->basket->deliveryRates();
        foreach ($deliveryRates as $orderAmount => $deliveryCost) {
            if ($productPrice < $orderAmount) {
                return $deliveryCost;
            }
        }

        return 0;
    }
}
