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
   * @ODM\String
   */
  protected $uid;

  /**
   * TODO a VOIR
   * Not persisted.
   *
   * @var UserCache
   */
  protected $user;

  /**
   * @ODM\String
   */
  protected $remote_id;

  /**
   * @ODM\String
   */
  protected $appid;

  /**
   * @ODM\String
   */
  protected $url;

  /**
   * @ODM\String
   */
  protected $rubrique;

  /**
   * @param $values
   * @param $userCache
   */
  public function __construct($values, $userCache) {
    foreach ($values as $key => $value) {
      $this->{$key} = $value;
    }
    $this->uid = $userCache->getId();
    $this->user = $userCache;
  }

  /**
   * @return UserCache
   */
  public function getUser() {
    return $this->user;
  }

  /**
   * @param $item
   * @param $value
   * @throws \ErrorException
   */
  public function __set($item, $value) {

    $properties = get_object_vars($this);
    if (!in_array($item, array_keys($properties))) {
      throw new \ErrorException('Propriété inconnue');
    }
    $this->{$item} = $value;
  }

  /**
   * @param $item
   * @return mixed
   */
  public function __get($item) {
    return $this->{$item};
  }
}

