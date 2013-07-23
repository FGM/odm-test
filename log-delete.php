<?php

use Doctrine\Common\Util\Debug;
use Figaro\Premium\Comments\Documents\LogComment;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

$log = $dm->getRepository(Boot::getDocumentClass('LogComment'))->findOneBy(array('uid' => (int) $argv[1]));


if (!$log) {
  die('Log inexistant pour les critères sélectionnés.');
}

// Recuperer l'id pour pouvoir tester l'existance après la suppresion.
$lid = $log->_id;

Debug::dump('Lid ' . $lid);
$dm->remove($log);
$dm->flush();

$boot2 = new Boot();

$dm2 = $boot->getDocumentManager();
$log2 = $dm2->find(boot::getDocumentClass('LogComment'), $lid);

if (is_null($log2)) {
  Debug::dump('Log supprimé');
}
else {
  Debug::dump('Erreur pendant la suppresion du Log');
}

