<?php
namespace Documents;

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

  // ...
}