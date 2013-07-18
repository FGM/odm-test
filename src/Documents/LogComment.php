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

  public function __construct($values) {

    $x = new \DateTime();

    $properties = get_object_vars($this);

    $array_intersect = is_array($values) ? array_intersect(array_keys($properties), array_keys($values)) : array();
    // La classe a aussi un $_id (autoincrement)
    if (count($array_intersect) != (count($properties) - 1)) {
      throw new \ErrorException("Nombre d'elements incorect.");
    }

    foreach ($values as $key => $value) {
      $this->{$key} = $value;
    }
  }

  public function __set($item, $value) {

    $properties = get_object_vars($this);
    if (!in_array($item, array_keys($properties))) {
      throw new \ErrorException('Propriété inconnue');
    }
    $this->{$item} = $value;
  }

  public function __get($item) {

    $properties = get_object_vars($this);
    if (!in_array($item, array_keys($properties))) {
      throw new \ErrorException('Propriété inconnue');
    }
    return $this->{$item};
  }
}
