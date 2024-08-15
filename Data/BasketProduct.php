<?php

class BasketProduct
{

    public function __construct(
        public string $name,
        public string $code,
        public int $price, // can be Money type, price per 1 item
        public int $quantity = 1,
    )
    {}

    public function makeStorable(): array
    {
        return [
          'name' => $this->name,
          'code' => $this->code,
          'quantity' => $this->quantity,
        ];
    }

    public static function fromStorable(array $data, Product $product): self
    {
        return new self(
            $data['name'],
            $data['code'],
            $product->price,
            $data['quantity'],
        );
    }
}
