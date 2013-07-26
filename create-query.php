<?php
/**
 * @file
 * Demonstrates how to control the "indexesRequired" metadatum when querying.
 */

namespace Documents;

use Doctrine\ODM\MongoDB\Query\Query;
use Doctrine\ODM\MongoDB\MongoDBException;
use Figaro\Premium\Comments\CommentService;

function attemptQuery(Query $query, $shouldThrow) {
  try {
    echo "Query indexed ? " . ($query->isIndexed() ? "Y" : "N") . "\n";
    $users = $query->execute();
    echo $shouldThrow
      ? "No exception thrown, but it should have.\n"
      : "No exception thrown, as expected.\n";
    foreach ($users as $user) {
      echo "Name: " . $user->getName() . ", mail: " . $user->getEmail() . "\n";
    }
  }
  catch (MongoDBException $e) {
    echo $shouldThrow
      ? "Caught MongoDBException exception as expected.\n"
      : "Caught unexpected MongoDBException.\n";
  }
  catch (\Exception $e) {
    echo "Caught unexpected other exception " . get_class($e) . "\n";
  }
}

// Bootstrap.
$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

// Create a query on an unindexed collection requiring indexes to query.
$qb = $dm->createQueryBuilder(CommentService::getDocumentClass('Thread'))
  ->field('nid')
  ->equals(42);

// Verify that the query is not indexed.
echo "Require index\n";
$qb->requireIndexes(TRUE);
$query = $qb->getQuery();

try {
  attemptQuery($query, $shouldThrow = TRUE);
}
catch (\Exception $e) {
  echo "Threw another exception: " . $e->getTraceAsString();
}

// Make sure it throws when executed because the collection requires indexes.

// Now Bypass the index requirement.
echo "\nIgnore the index requirement\n";
$qb->requireIndexes(FALSE);
$query = $qb->getQuery();

// Run it again, it should no longer throw.
attemptQuery($query, $shouldThrow = FALSE);
