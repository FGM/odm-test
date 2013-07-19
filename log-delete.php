<?php

use Doctrine\Common\Util\Debug;
use Documents\LogComment;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

$log = $dm->getRepository('Documents\LogComment')->findOneBy(array('uid' => 2));

var_dump($log);

if ($log) {
  $dm->remove($log);
  $dm->flush();
  echo 'Log deleted';
}
else {
  echo 'Log not found';
}


