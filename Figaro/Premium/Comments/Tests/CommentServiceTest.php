<?php

namespace Figaro\Premium\Comments\Tests;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Figaro\Premium\Comments\CommentService;

/**
 * CommentService test case.
 */
class CommentServiceTest extends \PHPUnit_Framework_TestCase {

  const APP = 'comments-phpunit-tests';
  const DB = 'comments-phpunit-tests';
  const CHANNEL = 'comments-phpunit-tests';

  /**
   * @var Figaro\Premium\Comments\Tests\Boot
   */
  private $boot;

  /**
   *
   * @var CommentService
   */
  private $CommentService;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() {
    parent::setUp();

    $settings = array(
      'app_name' => static::APP,
      'db_credentials' => array(
        'host' => 'localhost',
        'port' => 27017,
        'base' => static::DB,
      ),
      'logger_channel' => static::CHANNEL,
    );

    $boot = new \Boot($settings);
    $this->CommentService = new CommentService($boot->getDocumentManager(), $boot->getLogger());
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
    $this->CommentService->getMongo()->dropDB(static::DB);
    $this->CommentService = null;
    parent::tearDown();
  }

  /**
   * Constructs the test case.
   */
  public function __construct() {
    // TODO Auto-generated constructor
  }

  /**
   * Tests CommentService->flushCache()
   */
  public function testFlushCache() {
    // TODO Auto-generated CommentServiceTest->testFlushCache()
    $this->markTestIncomplete("flushCache test not implemented");

    $this->CommentService->flushCache(/* parameters */);
  }

  /**
   * Tests CommentService::getAnnotatedDirectories()
   */
  public function testGetAnnotatedDirectories() {
    // TODO Auto-generated CommentServiceTest::testGetAnnotatedDirectories()
    $this->markTestIncomplete("getAnnotatedDirectories test not implemented");

    CommentService::getAnnotatedDirectories(/* parameters */);
  }

  /**
   * Tests CommentService::getDocumentClass()
   */
  public function testGetDocumentClass() {
    // TODO Auto-generated CommentServiceTest::testGetDocumentClass()
    $this->markTestIncomplete("getDocumentClass test not implemented");

    CommentService::getDocumentClass(/* parameters */);
  }

  /**
   * Tests CommentService->getDocumentManager()
   */
  public function testGetDocumentManager() {
    // TODO Auto-generated CommentServiceTest->testGetDocumentManager()
    $this->markTestIncomplete("getDocumentManager test not implemented");

    $this->CommentService->getDocumentManager(/* parameters */);
  }

  /**
   * Tests CommentService->getEventManager()
   */
  public function testGetEventManager() {
    // TODO Auto-generated CommentServiceTest->testGetEventManager()
    $this->markTestIncomplete("getEventManager test not implemented");

    $this->CommentService->getEventManager(/* parameters */);
  }

  /**
   * Tests CommentService->getMongo()
   */
  public function testGetMongo() {
    // TODO Auto-generated CommentServiceTest->testGetMongo()
    $this->markTestIncomplete("getMongo test not implemented");

    $this->CommentService->getMongo(/* parameters */);
  }

  /**
   * Tests CommentService->getLogger()
   */
  public function testGetLogger() {
    // TODO Auto-generated CommentServiceTest->testGetLogger()
    $this->markTestIncomplete("getLogger test not implemented");

    $this->CommentService->getLogger(/* parameters */);
  }

  /**
   * Tests CommentService->__construct()
   */
  public function test__construct() {
    // TODO Auto-generated CommentServiceTest->test__construct()
    $this->markTestIncomplete("__construct test not implemented");

    $this->CommentService->__construct(/* parameters */);
  }

  /**
   * Tests CommentService->finder()
   */
  public function testFinder() {
    // TODO Auto-generated CommentServiceTest->testFinder()
    $this->markTestIncomplete("finder test not implemented");

    $this->CommentService->finder(/* parameters */);
  }
}

