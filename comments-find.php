<?php
/**
 * @file
 * comments-find.php
 *
 * User: malek
 * Date: 18/07/13
 * Time: 14:46
 * IDE:  JetBrains PhpStorm
 */

use Doctrine\Common\Util\Debug;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

$comments = array();
$uid = $argv[1];
$threads = $dm->createQueryBuilder('Documents\Thread')
  ->select('_id', 'userCache', 'comments')
  ->getQuery()
  ->execute();

$real_deal = '';

foreach ($threads as $thread) {
  // var_dump($thread);
  foreach ($thread->userCache as $user) {
    if ($user->uid == $uid) {
      foreach ($thread->comments as $comment) {
        if ($comment->comment_uid == $uid) {
          $boot->debug($thread->id . "::" . $comment->cid ."::" . $user->name . " :: Cid: " . $comment->content ." \n");
        }
      }
    }
  }
}
