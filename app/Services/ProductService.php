<?php

namespace App\Services;

use App\Data\Product;

class ProductService
{
    protected array $products = [];

    public function __construct()
    {
        $this->initProducts();
    }

    protected function initProducts(): void
    {
        $products = json_decode(file_get_contents(__DIR__ . '/../../database/products.json'), true) ?? [];
        if (is_array($products)) {
            foreach ($products as $product) {
                try {
                    $product = new \App\Data\Product(...$product);
                    $this->products[$product->code] = $product;
                } catch (\Exception) {
                    continue; // invalid rule
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function getProductByCode(string $code): Product
    {
        try {
            return $this->products[$code];
        } catch (\Exception) {
            throw new \Exception('Product not found!');
        }
    }
}
