<?php
/**
 * @file
 * Demonstrates how to use class metadata from userland code.
 */

use Doctrine\Common\Util\Debug;
use Figaro\Premium\Comments\CommentService;
use Figaro\Premium\Comments\Documents\User;

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
$threadClass = $mdf->getMetadatafor(CommentService::getDocumentClass('Thread'));
print_r($threadClass->indexes);

foreach ($threadClass->fieldMappings as $fieldName => $fieldMapping) {
    echo $fieldName .": " . dump_mapping($fieldMapping) . "\n";
}
