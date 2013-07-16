<?php
namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class UserCache {
  /**
   * @ODM\Int
   *
   * The Drupal user id.
   */
  protected $uid;

  /**
   * @ODM\String
   *
   * The Drupal user name, unsanitized.
   */
  protected $name;

  public function __construct($uid, $name) {
    $this->uid = $uid;
    $this->name = $name;
  }
}
