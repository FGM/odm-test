<?php

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
Debug::dump($user);

$node = new NodeCache(42, "Let's dance", $user);
Debug::dump($node);

$c1 = new Comment(421, 'Commentaire 1 sur le 42');
$c2 = new Comment(4211, 'Commentaire 11 réponse au 1 sur le 42');
$c3 = new Comment(422, 'Commentaire 2 sur le 42');

$thread = new Thread($node);
//Debug::dump($thread);

$thread->addComment($c1);
$thread->addComment($c2);
$thread->addComment($c3);

foreach (array('user', 'node', 'c1', 'c2', 'c3', 'thread') as $var) {
  echo "Persisting $var\n";
  $dm->persist($$var);
}

$dm->flush();