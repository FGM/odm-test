<?php
namespace Documents;

use Doctrine\Common\Util\Debug;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Documents\BlogPost;

/**
 * @ODM\Document
 */
class User {
  /**
   * @ODM\Id
   *
   * Strategies: AUTO|ALNUM|CUSTOM|INCREMENT|UUID|NONE.
   */
  private $id;

  /**
   * @ODM\String
   */
  private $name;

  /**
   * @ODM\String
   */
  private $email;

  /**
   * @ODM\ReferenceMany(targetDocument="BlogPost", inversedBy="user", cascade="all", simple=true)
   */
  private $posts = array();


  public function getEmail() {
    return $this->email;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function setEmail($email) {
    $this->email = $email;
  }

  public function __construct($name, $email) {
    $this->setName($name);
    $this->setEmail($email);
  }

  public function getPosts() {
    return $this->posts;
  }

  public function setPosts($posts) {
    $this->posts = $posts;
  }
}
