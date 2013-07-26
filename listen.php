<?php

use Doctrine\Common\EventManager;
use Doctrine\ODM\MongoDB\Events;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Figaro\Premium\Comments\Documents\User;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();
$em = $dm->getEventManager();

class EventTest {
  const prePersist = 'prePersist';
  const postPersist = 'postPersist';

  private $_evm;

  public $prePersistInvoked = false;
  public $postPersistInvoked = false;

  public function prePersist(LifecycleEventArgs $e) {
    echo "Invoking " . __METHOD__ . "\n";
    $this->prePersistInvoked = true;
  }

  public function postPersist(LifecycleEventArgs $e) {
    echo "Invoking " . __METHOD__ . "\n";
    $this->postPersistInvoked = true;
  }
}

// Create a new instance
$test = new EventTest();

$em->addEventListener(array(
  Events::prePersist,
  Events::postPersist,
), $test);

// Check event triggering.
$user = new User(52, 'cinquante-deux');
echo "Before persisting\n";
$dm->persist($user);
echo "After persisting\n";

$dm->flush($user);
