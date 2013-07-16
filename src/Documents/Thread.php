<?php
namespace Documents;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class Thread {
  /**
   * @ODM\Id
   */
  protected $id;

  /**
   * @ODM\Date
   */
  protected $changed;

  /**
   * @ODM\EmbedMany(targetDocument="Comment")
   */
  protected $comments;

  /**
   * @ODM\EmbedOne(targetDocument="NodeCache")
   */
  protected $nodeCache;

  /**
   * @ODM\EmbedMany(targetDocument="UserCache")
   */
  protected $userCache;

  public function __construct(NodeCache $nodeCache) {
    $this->comments = new ArrayCollection();
    $this->nodeCache = $nodeCache;
    $this->userCache[] = $nodeCache->getUser();
  }

  public function addComment(Comment $comment) {
    $this->comments[] = $comment;
  }

  /** @ODM\PrePersist */
  public function prePersistChanged() {
    $this->changed = new \DateTime();
  }
}
