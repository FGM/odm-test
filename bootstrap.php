<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Util\Debug;
use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Log\D6Logger;
use Monolog\Logger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

require 'vendor/autoload.php';

class Boot {
  /**
   * @var string
   */
  protected $app_name;

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

  public function __construct($app_name = NULL) {
    $this->app_name = $this->initApp($app_name);
    $this->logger = $this->initLogger();
    $this->config = $this->initConfig();
    $this->mongo = $this->initMongo();

    // Not mentioned in the docs, but used in tests and avoids autoloader errors
    // on annotation classes.
    AnnotationDriver::registerAnnotationClasses();
  }

  public function __call($method, array $arguments) {
    $psr3_methods = array(
      'log',
      'debug', 'info', 'notice', 'warning',
      'error', 'critical', 'alert', 'emergency',
    );
    if (in_array($method, $psr3_methods)) {
      $ret = call_user_func_array(array($this->logger, $method), $arguments);
    }
    else {
      throw new ErrorException('Undefined logging method invoked.');
    }
    return $ret;
  }

  /**
   * Lazy instantiation of document manager.
   *
   * @return \Doctrine\ODM\MongoDB\DocumentManager
   */
  public function getDocumentManager() {
    if (!isset($this->documentManager)) {
      $this->documentManager = DocumentManager::create(new Connection($this->mongo), $this->config);
    }

    return $this->documentManager;
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

  public function initApp($app_name = NULL) {
    if (!isset($app_name)) {
      $app_name = basename(__DIR__);
    }
    return $app_name;
  }

  /**
   * @return Configuration
   */
  public function initConfig() {
    $cache_dir = __DIR__ . '/cache';
    $db_name = 'odm';
    $annotated_directories = array(
      __DIR__ . '/Documents',
    );

    if (!is_dir($cache_dir)) {
      $status = mkdir($cache_dir, 0777, TRUE);
      if (!$status) {
        throw new \ErrorException('Could not create cache directory.');
      }
    }

    $config = new Configuration();
    $config->setProxyDir($cache_dir);
    $config->setProxyNamespace('Proxies');
    $config->setDefaultDB($db_name);
    $config->setHydratorDir($cache_dir);
    $config->setHydratorNamespace('Hydrators');

    $reader = new AnnotationReader();
    $config->setMetadataCacheImpl(new FilesystemCache($cache_dir));
    foreach ($annotated_directories as $dir) {
      $config->setMetadataDriverImpl(new AnnotationDriver($reader, $dir));
    }

    return $config;
  }

  public function initLogger() {
    if (function_exists('watchdog')) {
      $logger = new D6Logger(pathinfo(__FILE__, PATHINFO_FILENAME));
    }
    else {
      $logger = new Logger($this->app_name);
    }

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
