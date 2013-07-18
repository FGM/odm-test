<?php
namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class LogComment {

  const DEFAULT_LOG_STATUS = 0;

  /**
   * @ODM\Id
   */
  private $_id;

  /**
   * @ODM\Int
   */
  private $cid;

  /**
   * @ODM\String
   */
  private $timestamp;

  /**
   * @ODM\String
   */
  private $oldStatus;

  /**
   * @ODM\String
   */
  private $newStatus;

  /**
   * @ODM\String
   */
  private $message;

  /**
   * @ODM\Int
   */
  private $uid;

  /**
   * @ODM\String
   */
  private $ip;

  /**
   * @param $values
   */
  public function __construct($values) {

    $properties = get_object_vars($this);

    $array_intersect = is_array($values) ? array_intersect_key($values, $properties) : array();
    //$array_intersect = is_array($values) ? array_intersect(array_keys($properties), array_keys($values)) : array();
    // La classe a aussi un $_id (autoincrement)
    if (count($array_intersect) < (count($properties) - 1)) {
      throw new \ErrorException("Nombre d'elements incorect.");
    }

    foreach ($values as $key => $value) {
      $this->{$key} = $value;
    }
  }

  /**
   * @param $item
   * @param $value
   */
  public function __set($item, $value) {

    $properties = get_object_vars($this);
    if (in_array($item, array_keys($properties))) {
      $this->{$item} = $value;
    }
  }

  /**
   * @param $item
   * @return mixed
   */
  public function __get($item) {
    return $this->{$item};
  }
}
