<?php

// load everything manually
use App\Basket;

require 'autoload.php';

$basket = Basket::make();

$i = 1;

$basket->addProduct('B01');
$basket->addProduct('G01');
formatTotal($basket->total(), $i);
$basket->clear();
$i++;

$basket->addProduct('R01', 2);
formatTotal($basket->total(), $i);
$basket->clear();
$i++;

$basket->addProduct('R01');
$basket->addProduct('G01');

formatTotal($basket->total(), $i);
$basket->clear();
$i++;

$basket->addProduct('R01', 3);
$basket->addProduct('B01', 2);

formatTotal($basket->total(), $i);
$basket->clear();
