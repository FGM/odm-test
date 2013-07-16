<?php
namespace Documents;

use Doctrine\Common\Util\Debug;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class Customer {
  /** @ODM\Id */
  private $customer_id;

  /**
   * @ODM\ReferenceOne(targetDocument="Cart", mappedBy="customer")
   */
  public $cart;

  public function setCart(Cart $cart) {
    $this->cart = $cart;
  }
}
