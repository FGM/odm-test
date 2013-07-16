<?php
namespace Documents;

use Doctrine\Common\Util\Debug;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

$qb = $dm->createQueryBuilder('Documents\User')
  ->field('name')
  ->equals(new \MongoRegex('/ar/'));
// Debug::dump($qb);
$query = $qb->getQuery();
// Debug::dump($query);
$users = $query->execute();
// Debug::dump($users);
foreach ($users as $user) {
  echo "Name: " . $user->getName() . ", mail: " . $user->getEmail() . "\n";
  // Debug::dump($user)
}
