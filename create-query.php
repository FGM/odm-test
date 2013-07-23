<?php
/**
 * @file
 * Demonstrates how to control the "indexesRequired" metadatum when querying.
 */

namespace Documents;

use Doctrine\MongoDB\Query\Query;

use Doctrine\ODM\MongoDB\MongoDBException;

function attemptQuery(Query $query, $shouldThrow) {
  try {
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

function checkQuery(Query $query) {
  echo "Query indexed ? " . ($query->isIndexed() ? 'Y' : 'N') . "\n";
}

// Boot.
$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

// Create a query on an unindexed collection requiring indexes to query.
$qb = $dm->createQueryBuilder('Figaro\Premium\Comments\Documents\Thread')
  ->field('name')
  ->equals(new \MongoRegex('/ar/'));
$query = $qb->getQuery();

// Verify that the query is not indexed.
echo "Abide by the index requirement\n";
checkQuery($query);

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

// Verify that the query is still not indexed.
checkQuery($query);

// Run it again, it should no longer throw.
attemptQuery($query, $shouldThrow = FALSE);
