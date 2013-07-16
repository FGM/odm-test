<?php
namespace Documents;

use Doctrine\Common\Util\Debug;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class Cart {
  /** @ODM\Id */
  private $cart_id;

  /**
   * @ODM\ReferenceOne(targetDocument="Customer", inversedBy="cart", simple=true)
   */
  public $customer;

  public function setCustomer(Customer $customer) {
    $this->customer = $customer;
  }
}
