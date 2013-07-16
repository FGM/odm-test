<?php
namespace Documents;

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
   * @ODM\DateTime
   */
  protected $changed;

  /**
   * @ODM\EmbedMany(targetDocument="Comment")
   */
  protected $comments;

  /**
   * ODM\EmbedOne(targetDocument="NodeCache")
   */
  protected $node;

  /**
   * @ODM\EmbedMany(targetDocument="UserCache")
   */
  protected $userCache;
}