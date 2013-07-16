<?php
namespace Documents;

use Doctrine\ODM\MongoDB\Tests\Events\User;

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
   * @ODM\Int
   */
  protected $uid;

  /**
   * Not persisted.
   *
   * @var UserCache
   */
  protected $user;


  public function __construct($nid, $title, UserCache $userCache) {
    $this->nid = $nid;
    $this->title = $title;
    $this->uid = $userCache->getId();

    $this->user = $userCache;
  }

  public function getUser() {
    return $this->user;
  }
}
