<?php
namespace Documents;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="threads", requireIndexes=true)
 */
class Thread {

  /**
   * @ODM\Id
   */
  protected $_id;

  /**
   * @ODM\Int
   */
  protected $nid;

  /**
   * @ODM\Hash
   */
  protected $node_uids;

  /**
   * @ODM\Hash
   */
  protected $gids;

  /**
   * @ODM\Date
   */
  protected $created;

  /**
   * @ODM\Date
   */
  protected $changed;

  /**
   * @ODM\String
   */
  protected $thread;

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

  /*
   *
   */
  public function __construct(NodeCache $nodeCache) {
    $this->comments = new ArrayCollection();
    $this->userCache = new ArrayCollection();
    $this->nodeCache = $nodeCache;
    $this->addUser($nodeCache->getUser());
  }

  /**
   * @param $item
   * @return mixed
   */
  public function __get($item) {

    $properties = get_object_vars($this);
    if (!in_array($item, array_keys($properties))) {
      throw new \ErrorException('Propriété inconnue');
    }
    return $this->{$item};
  }

  /**
   * @param $item
   * @param $value
   */
  public function __set($item, $value) {

    $properties = get_object_vars($this);
    if (in_array($item, array_keys($properties))) {
      $this->{$item} = $value;
    }
  }

  public function addComment(Comment $comment) {
    $this->comments[] = $comment;
  }

  public function addUser(UserCache $user) {
    $this->userCache[] = $user;
  }

  /** @ODM\PrePersist */
  public function prePersistChanged() {
    $this->changed = new \DateTime();
  }

}
