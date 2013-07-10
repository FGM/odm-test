<?php

use Doctrine\Common\Util\Debug;
use Documents\User;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

$user_repo = $dm->getRepository('Documents\User');

$users = $user_repo->findBy(array('name' => $argv[1]));
foreach ($users as $user) {
  // Debug::dump($user);
  echo "User name: " . $user->getName() . ", email: " . $user->getEmail() . "\n";
}
