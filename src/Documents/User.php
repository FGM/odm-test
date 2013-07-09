<?php
namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

class_exists('Doctrine\ODM\MongoDB\Mapping\Annotations\Document');
class_exists('Doctrine\ODM\MongoDB\Mapping\Annotations\Id');
class_exists('Doctrine\ODM\MongoDB\Mapping\Annotations\String');
class_exists('Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany');

/**
 * @ODM\Document
 */
class User {
  /**
   * @ODM\Id()
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
   * @ODM\ReferenceMany(targetDocument="BlogPost", cascade="all")
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
}
