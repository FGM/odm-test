<?php
/**
 * @file
 * Demonstrates how to use class metadata from userland code.
 */

namespace Documents;

use Doctrine\Common\Util\Debug;
use Documents\User;

function dump_mapping($mapping) {
  $ret = array();
  foreach (array('name', 'type') as $key) {
    $ret[] = "$key=" . $mapping[$key];
  }
  if (!empty($mapping['embedded'])) {
    $ret[] = 'embedded';
  }

  return implode(', ', $ret);
}

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

$mdf = $dm->getMetadataFactory();
$threadClass = $mdf->getMetadatafor('Documents\Thread');
// Debug::dump($threadClass->fieldMappings);

foreach ($threadClass->fieldMappings as $fieldName => $fieldMapping) {
    echo $fieldName .": " . dump_mapping($fieldMapping) . "\n";
}
