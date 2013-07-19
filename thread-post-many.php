<?php
/**
 * @file
 * thread-post-many.php
 *
 * User: malek
 * Date: 18/07/13
 * Time: 15:29
 * IDE:  JetBrains PhpStorm
 */

use Documents\Thread;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

use Doctrine\Common\Util\Debug;
use Documents\Comment;
use Documents\NodeCache;
use Documents\UserCache;


$amount_to_create = isset($argv[1]) ? $argv[1] : 1;
$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

for ($t = 0; $t < $amount_to_create; $t++) {
  echo "Creating thread " . ($t + 1) . "\n";
  createSample($dm);
}

function createSample($dm) {
  $comments_posted = array();
  $rubrique = array('Actu', 'Eco', 'Politique', 'Sport', 'Hight-Tech');
  $node_author_uid = rand(6000, 9000);
  $node_author = array(
    'uid'             => $node_author_uid,
    'username'        => "node_author_name_$node_author_uid",
    'avatar'          => "node_author_url_avatar_$node_author_uid",
    'url_page_perso'  => "node_author_url_page_perso_$node_author_uid",
    'abonne'          => rand(0, 1),
    'journaliste'     => rand(0, 1),
  );
  $user = new UserCache($node_author);

  $node_sample_nid = rand(3000, 4500);
  $node_sample = array(
    'nid'       => $node_sample_nid,
    'title'     => "node_title_$node_sample_nid",
    'uid'       => $node_author_uid,
    'remote_id' => sha1("_remote_id_$node_sample_nid"),
    'appid'     => sha1(rand(2000, 2010)),
    'url'       => "url_node_$node_sample_nid",
    'rubrique'  => $rubrique[rand(0, 4)],
  );

  $node = new NodeCache($node_sample, $user);
// Debug::dump($node);

  $thread_sample = array(
    'nid'       => $node_sample_nid,
    'node_uids' => array($node_author_uid),
    'gids'      => array(),
    'created'   => time(),
    'changed'   => time(),
    'thread'    => ''
  );
  $thread = new Thread($thread_sample, $node);
//Debug::dump($thread);

// Commentaire parent.
  $thread_owner_uid = rand(6000, 9000);
  $thread_owner = array(
    'uid'             => $thread_owner_uid,
    'username'        => "thread_owner_name_$thread_owner_uid",
    'avatar'          => "thread_owner_url_avatar_$thread_owner_uid",
    'url_page_perso'  => "thread_owner_url_page_perso_$thread_owner_uid",
    'abonne'          => rand(0, 1),
    'journaliste'     => rand(0, 1),
  );
  $user = new UserCache($thread_owner);

  $thread_owner_cid = rand(12000, 15000);
  $comment_sample = array(
    'cid'         => $thread_owner_cid,
    'comment_uid' => $thread_owner_uid,
    'ip'          => '168.128.0.' . rand(21, 30),
    'pid'         => 0,
    'status'      => rand(0, 1),
    'workflow'    => rand(0, 7),
    'created'     => time(),
    'changed'     => time(),
    'content'     => 'Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Donec rutrum congue leo eget malesuada. Donec sollicitudin molestie malesuada. Cras ultricies ligula sed magna dictum porta. Donec rutrum congue leo eget malesuada. Vivamus suscipit tortor eget felis porttitor volutpat. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Proin eget tortor risus. Curabitur aliquet quam id dui posuere blandit. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    'note'        => array('note' => rand(0, 5), 'total' => 5),
  );
  $comments_posted[] = $comment_sample;
  $c = new Comment($comment_sample, $user);
  $thread->addComment($c);
  $thread->addUser($user);
  $dm->persist($c);

  // Quelques enfants.
  for ($i = $thread_owner_cid + 1; $i < $thread_owner_cid + rand(0, 11); $i++) {
    $comment_owner_uid =  rand(6000, 9000);
    $user_sample = array(
      'uid'             => $comment_owner_uid,
      'username'        => "comment_owner_name_$comment_owner_uid",
      'avatar'          => "comment_owner_url_avatar_$comment_owner_uid",
      'url_page_perso'  => "comment_owner_url_page_perso_$comment_owner_uid",
      'abonne'          => rand(0, 1),
      'journaliste'     => rand(0, 1),
    );
    $user = new UserCache($user_sample);
    $comment_sample = array(
      'cid'         => $i,
      'comment_uid' => $comment_owner_uid,
      'ip'          => '168.128.0.' . rand(21, 30),
      'pid'         => $comments_posted[rand(0, count($comments_posted) -1)]['cid'],
      'status'      => rand(0, 1),
      'workflow'    => rand(0, 7),
      'created'     => time(),
      'changed'     => time(),
      'content'     => 'Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Donec rutrum congue leo eget malesuada. Donec sollicitudin molestie malesuada. Cras ultricies ligula sed magna dictum porta. Donec rutrum congue leo eget malesuada. Vivamus suscipit tortor eget felis porttitor volutpat. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Proin eget tortor risus. Curabitur aliquet quam id dui posuere blandit. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
      'note'        => array('note' => rand(0, 5), 'total' => 5),
    );
    $comments_posted[] = $comment_sample;
    $c = new Comment($comment_sample, $user);
    $thread->addComment($c);
    $thread->addUser($user);
    $dm->persist($c);
  }

  foreach (array('user', 'node', 'thread') as $var) {
    $dm->persist($$var);
  }

  $dm->flush();
}
