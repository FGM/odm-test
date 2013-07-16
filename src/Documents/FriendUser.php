<?php
namespace Documents;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class FriendUser {
  /**
   * @ODM\Id
   */
  public $id;

  /**
   * @ODM\String
   */
  public $name;

  /**
   * @ODM\ReferenceMany(targetDocument="FriendUser", mappedBy="myFriends", cascade={"all"})
   */
  public $friendsWithMe;

  /**
   * @ODM\ReferenceMany(targetDocument="FriendUser", inversedBy="friendsWithMe", cascade={"all"})
   */
  public $myFriends;

  public function __construct($name) {
    $this->name = $name;
    $this->friendsWithMe = new ArrayCollection();
    $this->myFriends = new ArrayCollection();
  }

  public function addFriend(FriendUser $user) {
    $user->friendsWithMe[] = $this;
    $this->myFriends[] = $user;
  }
}