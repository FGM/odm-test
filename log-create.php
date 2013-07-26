<?php

use Doctrine\Common\Util\Debug;
use Figaro\Premium\Comments\Documents\LogComment;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

// Debug::dump($dm);


$log_info = array(
  'cid' => 1,
  'timestamp' => 1234567890,
  'oldStatus'=> 1,
  'newStatus' => 2,
  'message' => 'Message test',
  'uid' => 50,
  'ip' => '192.168.0.5',
);

$log = new LogComment($log_info);
print_r($log);


$dm->persist($log);
print_r($log);

$dm->flush();
Debug::dump($log);

