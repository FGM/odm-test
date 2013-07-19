<?php

use Doctrine\Common\Util\Debug;
use Documents\LogComment;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

$log = $dm->getRepository('Documents\LogComment')->findOneBy(array('uid' => (int) $argv[1]));

var_dump($argv[1]);
var_dump($log);

if (!$log) {
  echo 'Log inexistant pour les critieres selectiones.';
  return FALSE;
}

// Recuperer l'id pour pouvoir tester l'existance après la suppresion.
$lid = $log->_id;

var_dump($lid);
$dm->remove($log);
$dm->flush();

$boot2 = new Boot();

$dm2 = $boot->getDocumentManager();
$log2 = $dm->getRepository('Documents\LogComment')->findOneBy(array('_id' => $lid));


if (is_null($log2)) {
  echo 'Log supprimé';
}
else {
  echo 'Erreur pendant la suppresion du Log';
}

