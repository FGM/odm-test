<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\Debug;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

require 'vendor/autoload.php';

function boot ($verbose = FALSE) {
  // URL: mongodb://[username:password@]host1[:port1][,host2[:port2:],...]/db
  $mongo = new Mongo();
  $dbs = $mongo->listDBs();
  $dbs = $dbs['databases'];

  if ($verbose) {
    echo "Databases: " . implode(', ', array_map(function ($db) {
      $ret = $db['name'] . '(';
      if ($db['empty']) {
        $ret .= 'empty';
      }
      else {
        $ret .= $db['sizeOnDisk'] / 1024 / 1024 . "M";
      }
      $ret .= ')';
      return $ret;
    }, $dbs)) . "\n";
  }

  $config = new Configuration();
  $config->setProxyDir(__DIR__ . '/cache');
  $config->setProxyNamespace('Proxies');
  $config->setDefaultDB('odm');

  $config->setHydratorDir(__DIR__ . '/cache');
  $config->setHydratorNamespace('Hydrators');

  $reader = new AnnotationReader();
  // $reader->setDefaultAnnotationNamespace('Doctrine\ODM\MongoDB\Mapping\\');
  $config->setMetadataDriverImpl(new AnnotationDriver($reader, __DIR__ . '/Documents'));

  // Not mentioned in the docs, but used in tests and avoids autoloader errors
  // on annotation classes.
  AnnotationDriver::registerAnnotationClasses();

  $dm = DocumentManager::create(new Connection($mongo), $config);
  // Debug::dump($dm);
  // Debug::dump($dm->getUnitOfWork());
  if ($verbose) {
    echo "UoW size: " . $dm->getUnitOfWork()->size() . "\n";
  }
  return $dm;
}

return boot();