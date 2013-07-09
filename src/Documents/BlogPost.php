<?php
namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

class_exists('Doctrine\ODM\MongoDB\Mapping\Annotations\Document');
class_exists('Doctrine\ODM\MongoDB\Mapping\Annotations\Id');
class_exists('Doctrine\ODM\MongoDB\Mapping\Annotations\String');
class_exists('Doctrine\ODM\MongoDB\Mapping\Annotations\Date');
class_exists('Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany');

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

  // ...
}