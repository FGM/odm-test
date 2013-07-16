<?php
namespace Documents;

use Doctrine\ODM\MongoDB\MongoDBException;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

$qb = $dm->createQueryBuilder('Documents\User')
  ->field('name')
  ->equals(new \MongoRegex('/ar/'));
$query = $qb->getQuery();
echo "Query indexed ? " . ($query->isIndexed() ? 'Y' : 'N') . "\n";

try {
  $users = $query->execute();
}
catch (MongoDBException $e) {
  echo "Caught exception as expected\n";
}

echo "Ignore the index requirement\n";
$qb->requireIndexes(FALSE);
$query = $qb->getQuery();
echo "Query indexed now ? " . ($query->isIndexed() ? 'Y' : 'N') . "\n";

try {
  $users = $query->execute();
  foreach ($users as $user) {
    echo "Name: " . $user->getName() . ", mail: " . $user->getEmail() . "\n";
  }
}
catch (\MongoDBException $e) {
  echo "Caught exception as expected\n";
}
