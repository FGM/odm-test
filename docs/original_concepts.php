<?php
/**
 * @file
 * Preliminary design notes for yet another MongoDB ObjectDocumentMapper.
 *
 * That was /before/ reading/using Doctrine MongoDB and Mongoose ODMs.
 *
 * (c) 2013 Ouest Systèmes Informatiques (OSInet)
 */

interface EntityInterface {
  static function isMappable();
  /**
   * @return EntityInterface
   */
  static function create();

  /**
   * @return EntityInterface
   */
  static function load($id); // proxy for ESCIF::load($id)

  /**
   * @return bool
   */
  public function isMapped();

  /**
   * @return void
   */
  public function save(); // proxy for ESCIF::save($entity)

  /**
   * @return void
   */
  public function delete(); // // proxy for ESCIF::delete($entity)

  /**
   *
   * @param array $new_values
   *
   * @return EntityInterface
   */
  public function modify(array $new_values); // proxy for ESCIF::modify($new_values)
}

interface EntityStorageControllerInterface {
  public function load($id);
  public function save(EntityInterface $entity);
  public function delete(EntityInterface $entity);
  public function modify(array $new_values);

  public function query($q);
  public function __construct(EntityManager $mgr);
}

interface EntityStubberInterface {
  public function asStub(EntityInterface $entity);
}

class MongoDBStorageController implements EntityStorageControllerInterface {

}

class MySQLStorageController implements EntityStorageControllerInterface {

}

class EntityManager {
  protected $controller;

  public function __construct(array $args) {
    $this->controller = new MongoDBStorageController($this);
  }
}

class EntityException extends \Exception {}

class DocumentEntity implements EntityInterface {
  /**
   * @var EntityStorageControllerInterface
   */
  protected $controller;

  public function __construct(EntityStorageControllerInterface $controller) {
    $this->controller = $controller;
  }

  public function save() {
    if (!static::isMappable()) {
      throw new EntityException('Cannot save instance of non mappable class.');
    }
    $this->controller->save($this);
  }
}

class Comment extends DocumentEntity {
  public static function isMappable() {
    return TRUE;
  }

  public function save() {
    // Prepare extra data before delegating save. Then..
    parent::save();
  }
}

abstract class DrupalEntity implements EntityInterface {
  public static function isMappable() {
    return FALSE;
  }
}

class Node extends DrupalEntity {
  public function asStub(EntityStubberInterface $commentNodeStubber) {}
}

class User extends DrupalEntity {
  public function asStub(EntityStubberInterface $commentUserStubber) {}
}

class CommentNodeStubber implements EntityStubberInterface {
  public function asStub(EntityInterface $entity) {
    return array();
  }
}

class CommentUserStubber implements EntityStubberInterface {
  public function asStub(EntityInterface $entity) {
    return array();
  }
}

//==============================================================================

// 1. Saving a comment
$c = Comment::create(array('some' => 'args'));
$c->save();

// 2. Some function deletes a comment it received
function foo(Comment $c) {
  $c->delete();
}

// 3. Some function completely replaces a comment it received
$c = Comment::load($id);
$c->foo = 'bar';
$c->save();
// At this point the whole comment is replaced.

// 4. Some function selectively updates comment fields in place.
function bar(Comment $c) {
  $c->modify(array('foo' => 'bar'));
}
// The rest of the comment is not modified.
