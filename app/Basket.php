<?php

namespace App;

use App\Data\BasketOffer;
use App\Data\BasketProduct;
use App\Data\BasketTotal;
use App\Data\Product;
use App\Resolvers\BasketOfferResolver;
use App\Services\ProductService;

class Basket
{
    protected static ?self $instance = null;

    /**
     * @var \App\Data\BasketProduct[]
     */
    protected array $items;

    protected array $deliveryRates;

    protected array $offerRules;

    protected ProductService $productService;

    protected function __construct()
    {
        // logic for filling basket from db/session
        // can be done easily
        $this->items = $this->getUserBasket();

        $this->productService = new ProductService();

        // delivery rules
        $this->calcDeliveryRates();

        // offer rules
        $this->calcOfferRules();
    }

    protected function getUserBasket(): array
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        return array_map(
            fn (array $product) => BasketProduct::fromStorable(
                $product,
                $this->productService->getProductByCode($product['code'])
            ),
            $_SESSION['cart']
        );
    }

    protected function saveBasket(): void
    {
        $_SESSION['cart'] = array_map(fn (BasketProduct $product) => $product->makeStorable(), $this->items);
    }

    public static function make(): self
    {
        if (static::$instance === null) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * @return \App\Data\BasketProduct[]
     */
    public function items(): array
    {
        return $this->items;
    }

    protected function calcDeliveryRates(): void
    {
        // can be extracted from database
        $this->deliveryRates = json_decode(file_get_contents(__DIR__ . '/../database/delivery-rates.json'), true) ?? [];
    }

    protected function calcOfferRules(): void
    {
        // can be extracted from database
        $offers = json_decode(file_get_contents(__DIR__ . '/../database/offers-rules.json'), true) ?? [];
        if (is_array($offers)) {
            foreach ($offers as $offer) {
                try {
                    $this->offerRules[] = new BasketOffer(...$offer);
                } catch (\Exception) {
                    continue; // invalid rule
                }
            }
        }
    }

    public function deliveryRates(): array
    {
        return $this->deliveryRates;
    }

    public function addProduct(string $productCode, int $quantity = 1): void
    {
        $product = $this->productService->getProductByCode($productCode);

        // if item already in basket - increase
        if (isset($this->items[$productCode])) {
            $this->items[$productCode]->quantity += $quantity;

            return;
        }

        $this->items[$productCode] = $this->makeBasketProduct($product);
        $this->items[$productCode]->quantity = $quantity;

        $this->saveBasket();
    }

    protected function makeBasketProduct(Product $product): BasketProduct
    {
        return new BasketProduct(
            $product->name,
            $product->code,
            $product->price
        );
    }

    protected function decreaseProduct(string $productCode): void
    {
        if (isset($this->items[$productCode])) {
            if ($this->items[$productCode]->quantity > 1) {
                $this->items[$productCode]->quantity--;
            } else {
                $this->removeProduct($productCode);
            }

            $this->saveBasket();
        }
    }

    protected function removeProduct(string $productCode): void
    {
        if (isset($this->items[$productCode])) {
            unset($this->items[$productCode]);
        }

        $this->saveBasket();
    }

    public function total(): BasketTotal
    {
        return \App\Factories\BasketTotalFactory::handle($this);
    }

    public function getProductPrice(BasketProduct $product): int
    {
        // trying to apply offers, to one product can be applied only one offer
        foreach ($this->offerRules as $offerRule) {
            if ($offerRule->productCode === $product->code) {
                $processor = BasketOfferResolver::resolve($offerRule->processor, $offerRule->data);
                if ($processor) {
                    return $processor->calculate($this, $product);
                }
            }
        }

        // default
        return $product->price * $product->quantity;
    }

    public function clear(): void
    {
        $this->items = [];
        $this->saveBasket();
    }
}
