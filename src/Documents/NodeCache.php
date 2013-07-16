<?php
namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class NodeCache {
  /**
   * @ODM\Int
   *
   * The Drupal node id.
   */
  protected $nid;

  /**
   * @ODM\String
   *
   * The Drupal node title, unsanitized.
   */
  protected $title;

  /**
   * ReferenceOne(targetDocument="UserCache", simple=true)
   */
  protected $user;

  public function __construct($nid, $title, UserCache $user) {
    $this->nid = $nid;
    $this->title = $title;
    $this->user = $user;
  }

  public function getUser() {
    return $this->user;
  }
}
