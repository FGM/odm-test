<?php
namespace Documents;

use Doctrine\Common\Util\Debug;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

// Debug::dump($dm);

$user = new User($argv[1], $argv[2]);

$post1 = new BlogPost('premier post');
$post1->setUser($user);

$post2 = new BlogPost('deuxième post');
$post2->setUser($user);

$post3 = new BlogPost('troisième post');
$post3->setUser($user);

$dm->persist($user);
$dm->persist($post1);
$dm->persist($post2);
$dm->persist($post3);
$dm->flush();