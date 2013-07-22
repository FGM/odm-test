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


echo "================================================\n";
echo "How many thread do you want to create ? : \n";
$handle = fopen ("php://stdin","r");
$amount_to_create = is_int(trim(fgets($handle))) | 1;

echo "================================================\n";
echo "How many comments by thread do you want to create ? : \n";
$handle = fopen ("php://stdin","r");
$answers_number = trim(fgets($handle)) | 20;

echo "================================================\n";
echo "Do you need first level's answers only ? (y/n) : \n";
$handle = fopen ("php://stdin","r");
$first_level_only = trim(fgets($handle)) == 'y' ? TRUE : FALSE;

$boot = require 'bootstrap.php';
$dm = $boot->getDocumentManager();

for ($t = 0; $t < $amount_to_create; $t++) {
  echo "Creating thread " . ($t + 1) . "\n";
  create_thread($dm, $answers_number, $first_level_only);
}

/**
 * Création d'un thread.
 *
 * @param $dm
 */
function create_thread($dm, $answers_number, $first_level_only) {

  // Création node et de son auteur.
  $node_author_uid = rand(6000, 9000);
  $user = create_user($dm, $node_author_uid, 'node_author');
  $node_sample_nid = rand(3000, 4500);
  $node = create_node($dm, $node_sample_nid, $user);
  // Debug::dump($node);

  // Création du thread.
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
  $thread_owner_cid = rand(12000, 15000);
  create_comment($dm, $thread, rand(6000, 9000), "thread_owner", $thread_owner_cid, TRUE);

  // Quelques enfants.
  for ($i = $thread_owner_cid + 1; $i < $thread_owner_cid + $answers_number; $i++) {
    create_comment($dm, $thread, rand(6000, 9000), "comment_owner", $i, $first_level_only);
  }

  $dm->persist($thread);
  $dm->flush();
}

/**
 * Création d'un node.
 *
 * @param $nid
 * @param $user
 * @return NodeCache
 */
function create_node($dm, $nid, $user) {
  $rubrique = array('Actu', 'Eco', 'Politique', 'Sport', 'Hight-Tech');
  $node_sample = array(
    'nid'       => $nid,
    'title'     => "node_title_$nid",
    'uid'       => $user->uid,
    'remote_id' => sha1("_remote_id_$nid"),
    'appid'     => sha1(rand(2000, 2010)),
    'url'       => "url_node_$nid",
    'rubrique'  => $rubrique[rand(0, 4)],
  );
  $node = new NodeCache($node_sample, $user);
  $dm->persist($node);
  return $node;
}

/**
 * Création d'un utilisateur.
 *
 * @param $dm
 * @param $uid
 * @param string $prefix
 * @param bool $insert
 * @return UserCache
 */
function create_user($dm, $uid, $prefix = '') {
  $user_sample = array(
    'uid'             => $uid,
    'username'        => $prefix . "_name_" . $uid,
    'avatar'          => $prefix . "_url_avatar_" . $uid,
    'url_page_perso'  => $prefix . "_url_page_perso_" . $uid,
    'abonne'          => rand(0, 1),
    'journaliste'     => rand(0, 1),
  );

  $user = new UserCache($user_sample);
  $dm->persist($user);
  return $user;
}

/**
 * Création d'un commentaire.
 *
 * @param $dm
 * @param $thread
 * @param $uid
 * @param $prefix
 * @param $cid
 * @param bool $pid
 */
function create_comment($dm, $thread, $uid, $prefix, $cid, $parent = FALSE) {
  static $comments_posted = array();

  $user = create_user($dm, $uid, $prefix);
  $comment_sample = array(
    'cid'         => $cid,
    'comment_uid' => $uid,
    'ip'          => '168.128.0.' . rand(21, 30),
    'pid'         => !$parent ? $comments_posted[rand(0, count($comments_posted) -1)]['cid'] : (empty($comments_posted) ? 0 : $comments_posted[0]['cid']),
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

/**
 * @todo construction du thread pour un commentaire.
 *
 * @param $parent_id
 * @param $comment_posted
 */
function get_thread_path($parent_id, $comment_posted) {
  // ...
}
