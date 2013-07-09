<?php

use Doctrine\Common\Util\Debug;
use Documents\User;

$dm = require 'bootstrap.php';

// Debug::dump($dm);
$user = new User($argv[1], $argv[2]);
print_r($user);

$dm->persist($user);
print_r($user);

$dm->flush();
Debug::dump($user);
