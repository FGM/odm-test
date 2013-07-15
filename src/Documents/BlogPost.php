<?php
namespace Documents;

use Doctrine\Common\Util\Debug;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class BlogPost {
  /**
   * @ODM\Id
   */
  private $id;

  /**
   * @ODM\String
   */
  private $title;

  /**
   * @ODM\String
   */
  private $body;

  /**
   * @ODM\Date
   */
  private $createdAt;

  /**
   * @ODM\ReferenceOne(targetDocument="User", inversedBy="posts", simple=true)
   */
  private $user;
  // ...

  public function __construct($body = NULL) {
    $this->body = $body;
  }

  public function setUser(User $user) {
    Debug::dump($user);
    $this->user = $user;
  }
}
