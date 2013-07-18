<?php
namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Comment {

  /**
   * @ODM\Id
   */
  protected $_id;

  /**
   * @ODM\Int
   */
  protected $cid;

  /**
  * @ODM\Int
  */
  protected $comment_uid;

  /**
   * @ODM\String
   */
  protected $ip;

  /**
   * @ODM\Int
   */
  protected $pid;

  /**
   * @ODM\Boolean
   */
  protected $status;

  /**
   * @ODM\Int
   */
  protected $workflow;

  /**
   * @ODM\Date
   */
  protected $created;

  /**
   * @ODM\Date
   */
  protected $changed;

  /**
   * @ODM\String
   */
  protected $contenu;

  /**
   * @ODM\Hash
   */
  protected $note;

  public function __construct($comment, $userCache) {
    foreach ($comment as $key => $value) {
      $this->{$key} = $value;
    }
  }

  public function __set($item, $value) {

    $properties = get_object_vars($this);
    if (in_array($item, array_keys($properties))) {
      // throw new \ErrorException('Propriété inconnue');
      $this->{$item} = $value;
    }
  }

  public function __get($item) {

    $properties = get_object_vars($this);
    if (!in_array($item, array_keys($properties))) {
      throw new \ErrorException('Propriété inconnue');
    }
    return $this->{$item};
  }

}
