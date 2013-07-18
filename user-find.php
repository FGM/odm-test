<?php

use Doctrine\Common\Util\Debug;
use Documents\User;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

$user_repo = $dm->getRepository('Documents\User');

echo "Class: " . $user_repo->getClassName() . "\n";

$users = $user_repo->findBy(array('username' => $argv[1]));
foreach ($users as $user) {
  $boot->debug("User name: " . $user->username . ", uid: " . $user->uid . "\n");
}
