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
  protected $cid;


  /**
   * @ODM\String
   */
  protected $body;

  public function __construct($cid, $body) {
    $this->cid = $cid;
    $this->body = $body;
  }
}
