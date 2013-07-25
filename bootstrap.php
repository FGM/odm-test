<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\EventManager;
use Doctrine\Common\Util\Debug;

use Doctrine\MongoDB\Configuration as DbConfig;
use Doctrine\MongoDB\Connection;

use Doctrine\ODM\MongoDB\Configuration as DmConfig;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

use Figaro\Premium\Comments\CommentService;
use Figaro\Premium\Log\DoctrineLogger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as StandardLogger;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

require 'vendor/autoload.php';

class Boot {
  /**
   * @var \Doctrine\ODM\MongoDB\DocumentManager
   */
  protected $documentManager;

  /**
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * @var array
   */
  public $settings;

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
      throw new ErrorException("Undefined logging method invoked: $method.\n");
    }
    return $ret;
  }

  /**
   * @return \Doctrine\MongoDB\MongoClient
   */
  public function getMongo() {
    return $this->documentManager->getConnection()->getMongo();
  }

  public function listDBs() {
    $dbs = $this->getMongo()->listDBs();
    $this->logger->info("Databases: " . implode(', ', array_map(function ($db) {
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

  /**
   * Constructor. Merge default and actual parameters before initializing.
   *
   * @param array $settings
   */
  public function __construct(array $given_settings = array()) {
    $default_settings = array(
      'app_name' => basename(__DIR__),
      'cache_dir' => 'cache',
      'cache_namespace' => 'fpcomments',
      // The ODM defaults to 'safe' => TRUE.
      'commit_options' => array(
        'safe' => TRUE,
        'fsync' => TRUE,
      ),
      'logger_channel' => basename(__DIR__),
    );

    $this->settings = array_merge($default_settings, $given_settings);

    $this->logger = new StandardLogger($this->settings['logger_channel']);
    $this->logger->pushHandler(new StreamHandler('php://stderr', StandardLogger::INFO));
    $this->documentManager = $this->createDocumentManager();
  }

  // TODO handle authenticated connections.
  // TODO handle default values
  protected static function buildConnectionString($credentials) {
    $url = 'mongodb://' . $credentials['host'] . ':' . $credentials['port']
      . '/' . $credentials['base'];
    return $url;
  }

  /**
   * Helper to build the document manager.
   *
   * @see createDocumentManager()
   *
   * @throws \ErrorException
   *
   * @return string
   */
  protected function initCache() {
    $cache_dir = $this->settings['cache_dir'];
    if (!is_dir($cache_dir)) {
      $status = mkdir($cache_dir, 0777, TRUE);
      if (!$status) {
        throw new \ErrorException(t('Could not create FP Comments cache directory.'));
      }
    }
    return $cache_dir;
  }

  /**
   * Builds the a new DocumentManager from configuration.
   *
   * @throws \ErrorException
   *
   * @return Doctrine\ODM\MongoDB\DocumentManager
   */
  protected function createDocumentManager() {
    $credentials = $this->settings['db_credentials'];
    $evm = new EventManager();

    // Set up connection.
    $client = new \MongoClient(static::buildConnectionString($credentials));

    $db_config = new DbConfig();
    $logger_callable = new DoctrineLogger($this->logger);
    $db_config->setLoggerCallable($logger_callable);
    $connection = new Connection($client, array(), $db_config, $evm);

    // Set up general DocumentManager configuration
    $dm_config = new DmConfig();
    $dm_config->setLoggerCallable($logger_callable);

    // DM: Directories.
    $cache_dir = $this->initCache();
    $dm_config->setProxyDir($cache_dir);
    $dm_config->setProxyNamespace('Proxies');
    $dm_config->setHydratorDir($cache_dir);
    $dm_config->setHydratorNamespace('Hydrators');

    $dm_config->setDefaultDB($credentials['base']);
    $dm_config->setDefaultCommitOptions($this->settings['commit_options']);

    // DM: Metadata
    $reader = new AnnotationReader();
    $cache = new FilesystemCache($cache_dir);
    $cache_namespace = $this->settings['cache_namespace'];
    $cache->setNamespace($cache_namespace);
    $dm_config->setMetadataCacheImpl($cache);
    // Not mentioned in the docs, but used in tests and avoids autoloader errors
    // on annotation classes.
    AnnotationDriver::registerAnnotationClasses();
    foreach (CommentService::getAnnotatedDirectories() as $dir) {
      $annotation_driver = new AnnotationDriver($reader, $dir);
      $dm_config->setMetadataDriverImpl($annotation_driver);
    }

    $dm = DocumentManager::create($connection, $dm_config, $evm);
    return $dm;
  }

  public function getDocumentManager() {
    return $this->documentManager;
  }

  public function getLogge() {
    return $this->logger;
  }
}

$settings = array(
  'app_name' => 'odm-test',
  'db_credentials' => array(
    'host' => 'localhost',
    'port' => 27017,
    'base' => 'comments',
  ),
  'logger_channel' => 'odm-test',
);

$boot = new Boot($settings);
return $boot;
