<?php
namespace Documents;

use Doctrine\Common\Util\Debug;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

// Debug::dump($dm);

$customer = new Customer();
Debug::dump($customer);
$cart = new Cart();
Debug::dump($cart);

// $cart->setCustomer($customer);
$customer->setCart($cart);

$dm->persist($customer);
$dm->persist($cart);
$dm->flush();
