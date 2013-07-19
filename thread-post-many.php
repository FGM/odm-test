<?php
/**
 * @file
 * thread-post-many.php
 *
 * User: malek
 * Date: 18/07/13
 * Time: 15:29
 * IDE:  JetBrains PhpStorm
 */

use Documents\Thread;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

use Doctrine\Common\Util\Debug;
use Documents\Comment;
use Documents\NodeCache;
use Documents\UserCache;


$boot = require 'bootstrap.php';
// Debug::dump($boot);

$dm = $boot->getDocumentManager();
// Debug::dump($dm);

$user = new UserCache(1, 'John Doe');
// Debug::dump($user);

$node = new NodeCache(42, "Let's dance", $user);
// Debug::dump($node);

$thread = new Thread($node);
//Debug::dump($thread);

// Quelques parents.
for ($i = 0; $i < 3; $i++) {
  $user_id = rand(30, 40);
  $user = new UserCache($user_id, 'John Doe ' . $user_id);
  $comment = array(
    'cid'         => $i,
    'comment_uid' => $user_id,
    'ip'          => '168.128.0.' . rand(21, 30),
    'pid'         => 0,
    'status'      => rand(0, 1),
    'workflow'    => rand(0, 7),
    'created'     => time(),
    'changed'     => time(),
    'contenu'     => 'Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Donec rutrum congue leo eget malesuada. Donec sollicitudin molestie malesuada. Cras ultricies ligula sed magna dictum porta. Donec rutrum congue leo eget malesuada. Vivamus suscipit tortor eget felis porttitor volutpat. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Proin eget tortor risus. Curabitur aliquet quam id dui posuere blandit. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    'note'        => array('note' => rand(0, 5), 'total' => 5),
  );
  $c = new Comment($comment, $user);
  $thread->addComment($c);
  $thread->addUser($user);
  $dm->persist($c);
}

// Some children..
for ($i = 3; $i <= 10; $i++) {
  $user_id = rand(30, 40);
  $user = new UserCache($user_id, 'John Doe ' . $user_id);
  $comment = array(
    'cid'         => rand(3, 10),
    'comment_uid' => $user_id,
    'ip'          => '168.128.0.' . rand(21, 30),
    'pid'         => rand(0, 2),
    'status'      => rand(0, 1),
    'workflow'    => rand(0, 7),
    'created'     => time(),
    'changed'     => time(),
    'contenu'     => 'Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Donec rutrum congue leo eget malesuada. Donec sollicitudin molestie malesuada. Cras ultricies ligula sed magna dictum porta. Donec rutrum congue leo eget malesuada. Vivamus suscipit tortor eget felis porttitor volutpat. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Proin eget tortor risus. Curabitur aliquet quam id dui posuere blandit. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    'note'        => array('note' => rand(0, 5), 'total' => 5),
  );
  $c = new Comment($comment, $user);
  $thread->addComment($c);
  $thread->addUser($user);
  $dm->persist($c);
}

foreach (array('user', 'node', 'thread') as $var) {
  echo "Persisting $var\n";
  $dm->persist($$var);
}

$dm->flush();
