<?php

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

use Doctrine\Common\Util\Debug;
use Documents\Comment;
use Documents\NodeCache;
use Documents\UserCache;


$boot = require 'bootstrap.php';
Debug::dump($boot);

$dm = $boot->getDocumentManager();
Debug::dump($dm);

$user = new UserCache(1, 'John Doe');
$node = new NodeCache(42, "Let's dance");
$c1 = new Comment(421, 'Commentaire 1 sur le 42');
$c2 = new Comment(4211, 'Commentaire 11 réponse au 1 sur le 42');
$c3 = new Comment(422, 'Commentaire 2 sur le 42');

foreach (array('user', 'node', 'c1', 'c2', 'c3') as $var) {
  echo "Persisting $var\n";
  $dm->persist($$var);
}

$dm->flush();