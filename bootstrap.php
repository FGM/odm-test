<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\Debug;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Monolog\Logger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

require 'vendor/autoload.php';

class Boot {
  /**
   * @var \Doctrine\ODM\MongoDB\Configuration
   */
  protected $config;

  /**
   * @var \Doctrine\ODM\MongoDB\DocumentManager
   */
  protected $documentManager;

  /**
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * @var \Mongo
   */
  protected $mongo;

  public function __construct() {
    $this->logger = $this->initLogger();
    $this->config = $this->initConfig();
    $this->mongo = $this->initMongo();

    // Not mentioned in the docs, but used in tests and avoids autoloader errors
    // on annotation classes.
    AnnotationDriver::registerAnnotationClasses();
  }

  /**
   * @return \Doctrine\ODM\MongoDB\DocumentManager
   */
  public function getDocumentManager() {
    $dm = DocumentManager::create(new Connection($this->mongo), $this->config);
    return $dm;
  }

  /**
   * @return \Psr\Log\LoggerInterface
   */
  public function getLogger() {
    return $this->logger;
  }

  /**
   * @return \Mongo
   */
  public function getMongo() {
    return $this->mongo;
  }

  /**
   * @return Configuration
   */
  public function initConfig() {
    $config = new Configuration();
    $config->setProxyDir(__DIR__ . '/cache');
    $config->setProxyNamespace('Proxies');
    $config->setDefaultDB('odm');
    $config->setHydratorDir(__DIR__ . '/cache');
    $config->setHydratorNamespace('Hydrators');

    $reader = new AnnotationReader();
    // $reader->setDefaultAnnotationNamespace('Doctrine\ODM\MongoDB\Mapping\\');
    $config->setMetadataDriverImpl(new AnnotationDriver($reader, __DIR__ . '/Documents'));

    return $config;
  }

  public function initLogger() {
    $logger = new Logger('odm');
    return $logger;
  }

  public function initMongo() {
    // URL: mongodb://[username:password@]host1[:port1][,host2[:port2:],...]/db
    $mongo = new Mongo();
    return $mongo;
  }

  public function listDBs() {
    $dbs = $this->mongo->listDBs();
    $this->logger->debug("Databases: " . implode(', ', array_map(function ($db) {
        $ret = $db['name'] . '('
        . ($db['empty'] ? 'empty' : $db['sizeOnDisk'] / 1024 / 1024 . "M")
        . ')';
        return $ret;
      }, $dbs['databases'])) . "\n");
    return $dbs;
  }

  public function logUowSize() {
    $size = $this->documentManager->getUnitOfWork()->size();
    $this->logger->debug($size);
  }
}

$boot = new Boot();
return $boot;
