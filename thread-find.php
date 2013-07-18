<?php
/**
 * @file
 * thread-find.php
 *
 * User: malek
 * Date: 18/07/13
 * Time: 11:45
 * IDE:  JetBrains PhpStorm
 */

use Doctrine\Common\Util\Debug;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

// Récupère un thread entier.
$thread = $dm->createQueryBuilder('Documents\Thread')
  ->field('_id')->equals($argv[1])
  ->select('_id', 'changed', 'comments')
  ->getQuery()
  ->getSingleResult();

if (!empty($thread)) {
  $boot->debug("Thread: " . $thread->id . " :: Changed:  :: Comment count: ". count($thread->comments) ." \n");
}
