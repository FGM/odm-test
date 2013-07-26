<?php

use Doctrine\Common\Util\Debug;
use Figaro\Premium\Comments\Documents\User;

$boot = require 'Boot.php';
$dm = $boot->getDocumentManager();

// Debug::dump($dm);
$user = new User($argv[1], $argv[2]);
print_r($user);

$dm->persist($user);
print_r($user);

$dm->flush();
Debug::dump($user);
