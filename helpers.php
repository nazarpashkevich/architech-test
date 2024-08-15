<?php

function formatTotal(BasketTotal $total, int $i = 1): void
{
    echo "-----\n";
    successText("Basket #$i");
    infoText("Products total: " . formatMoney($total->productsPrice));
    infoText("Delivery total: " . formatMoney($total->deliveryPrice));
    infoText("Basket total: " . formatMoney($total->total));
    echo "-----\n";
}

function successText(string $text): void
{
    echo "\033[1;32m$text\033[0m \n";
}

function infoText(string $text): void
{
    echo "\033[1;36m$text\033[0m \n";
}

function formatMoney(int $value, string $currency = '$'): string
{
    return $currency . number_format($value / 100, 2);
}
