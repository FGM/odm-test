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

  public function __construct($nid, $title) {
    $this->nid = $nid;
    $this->title = $title;
  }
}
