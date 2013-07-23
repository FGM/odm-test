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

const THREAD_CLASS = 'Figaro\Premium\Comments\Documents\Thread';

echo "Welcome to the query builder interface:\n";
while (!$choose = prompt_choice($boot, $dm)) {
// .. Nothing here.
}

function prompt_choice($boot, $dm) {
  echo "================================================\n";
  echo "Which operation do you want me to do :\n";

  echo <<<EOF
0) Exit
1) 10 last threads
2) Show known users.
3) Comments by uid.
4) Comments count by nid.

EOF;

  echo "Choose one of theme by its number : ";

  $handle = fopen ("php://stdin","r");
  $line = fgets($handle);
  switch(trim($line)) {
    case 0:
      echo "See ya!\n";
      return TRUE;
      break;

    case 1:
      get_20_last_threads($boot, $dm);
      echo "================================================\n";
      return end_demo();
      break;

    case 2:
      $more = 0;
      while ($more = get_20_users($boot, $dm, $more)) {
        echo "================================================\n";
        echo "Do you want to see more of theme ? y/n : ";
        $handle = fopen ("php://stdin","r");
        if (trim(fgets($handle)) == 'n') {
          return FALSE;
        }
      }
      echo "================================================\n";
      echo "Sadly, There's no more of theme...\n";
      return end_demo();
      break;

    case 3:
      $more = 0;
      echo "Which user's comments are ou interrested in ? ";
      $handle = fopen ("php://stdin","r");
      while ($more = get_20_comments($boot, $dm, trim(fgets($handle), $more))) {
        echo "================================================\n";
        echo "Do you want to see more of theme ? y/n : ";
        $handle = fopen ("php://stdin","r");
        if (trim(fgets($handle)) == 'n') {
          return FALSE;
        }
      }
      echo "================================================\n";
      echo "Sadly, There's no more of theme...\n";
      return end_demo();
      break;

    case 4:
      echo "Which node comments count are ou interrested in ? ";
      get_comments_count_by_nid($boot, $dm, trim(fgets($handle)));
      return end_demo();
    default;
      return FALSE;
  }
}

function end_demo() {
  echo "Do you want to make any other opération ? y/n : ";
  $handle = fopen ("php://stdin","r");
  return trim(fgets($handle)) == 'y' ? FALSE : TRUE;
}

function get_20_last_threads($boot, $dm) {
  $threads = $dm->createQueryBuilder(THREAD_CLASS)
    ->select('_id', 'nid', 'rubrique', 'created', 'comments', 'nodeCache')
    ->sort('created', 'desc')
    ->limit(20)
    ->getQuery()
    ->execute();

  foreach ($threads as $thread) {
    echo "Id: " . $thread->_id . " || ";
    echo "nid: " . $thread->nid . " || ";
    echo "rubrique: " . $thread->nodeCache->rubrique . " \t|| ";
    echo "Created: " . $thread->created->format('Y-m-d H:i:s') . " || ";
    echo "Comments count: ". count($thread->comments) ." \n";
  }
}

function get_20_users($boot, $dm, $base = 0) {
  $tot = $i = 0;
  $limit = 20;
  $cap = $base + $limit;

  $threads = $dm->createQueryBuilder(THREAD_CLASS)
    ->select('userCache')
    ->getQuery()
    ->execute();

  foreach ($threads as $thread) {
    foreach ($thread->userCache as $user) {
      if (!isset($users[$user->uid])) {
        $users[$user->uid] = $user;
        if ($tot >= $base && $tot < $cap) {
          echo "Uid: " . $user->uid . " || ";
          echo "username: " . $user->username . " \t|| ";
          echo "abonne: " . ($user->abonne ? '1' : '0') . " \t|| ";
          echo "journaliste: " . ($user->journaliste ? '1' : '0') . "\n";
          $i++;
        }
        $tot++;
      }
    }
  }
  echo "User ". ($base + 1) ." to ". ($i < $cap ? $i : $cap) ." / $tot\n";
  return $cap > $tot ? FALSE : $cap;
}

function get_20_comments($boot, $dm, $uid, $base = 0) {
  $tot = $i = 0;
  $limit = 20;
  $cap = $base + $limit;

  $threads = $dm->createQueryBuilder(THREAD_CLASS)
    ->field("comments.comment_uid")
    ->in(array((int) $uid))
    ->select('comments', 'nodeCache')
    ->getQuery()
    ->execute();

  foreach ($threads as $thread) {
    foreach ($thread->comments as $comment) {
      if ($comment->comment_uid == $uid && $tot >= $base && $tot < $cap) {
        echo "Nid: " . $thread->nodeCache->nid . " || ";
        echo "Node Title: " . $thread->nodeCache->title . " || ";
        echo "Cid: " . $comment->cid . " || ";
        echo "uid: " . $comment->comment_uid . " \t|| ";
        echo "Ip: " . $comment->ip . " \t|| ";
        echo "Pid: " . $comment->pid . " \t|| ";
        echo "Status: " . ($comment->status ? 1 : 0) . " \t|| ";
        echo "Workflow: " . $comment->workflow . " \t|| ";
        echo "Created: " . $comment->created->format('Y-m-d H:i:s') . "\n";
        $i++;
        $tot++;
      }
    }
  }
  echo "User $base to ". ($i < $cap ? $i : $cap) ." / $tot\n";
  return $cap > $tot ? FALSE : $cap;
}

function get_comments_count_by_nid($boot, $dm, $nid) {
  $count_query = $dm->createQueryBuilder(THREAD_CLASS)
    ->group(array('nid' => 1, 'nodeCache' => 0), array('count' => 0))
    ->reduce(<<<JS
function (curr, result) {
  result.count += curr.comments.length;
}
JS
    )
    ->field('nid')->equals((int) $nid)
    ->getQuery()
    ->execute();

  if (!empty($count_query['count'])) {
    $values = $count_query['retval'];
    $value = reset($values);
    echo "================================================\n";
    echo <<<EOF
Nid:            {$value['nid']}
Title:          {$value['nodeCache']['title']}
Uid:            {$value['nodeCache']['uid']}
Url:            {$value['nodeCache']['url']}
Rubrique:       {$value['nodeCache']['rubrique']}
Comments count: {$value['count']}
EOF;
    echo "\n================================================\n";
  }
  else {
    echo "No comments found for this node.\n";
  }
}

/*
{
  "_id": ObjectId("51e802efe837a0c33bd83620"),
  "changed": ISODate("2013-07-18T14:59:59Z"),
  "comments":
  [
    { "_id": ObjectId("51e802efe837a0c33bd83621"), "cid": 12106, "comment_uid": 8211, "ip": "168.128.0.24", "pid": 0, "status": false, "workflow": 6, "created": ISODate("2013-07-18T14:59:59Z"), "changed": ISODate("2013-07-18T14:59:59Z"), "note": { "note": 5, "total": 5 } },
    { "_id": ObjectId("51e802efe837a0c33bd83622"), "cid": 12107, "comment_uid": 8089, "ip": "168.128.0.23", "pid": 12106, "status": true, "workflow": 5, "created": ISODate("2013-07-18T14:59:59Z"), "changed": ISODate("2013-07-18T14:59:59Z"), "note": { "note": 1, "total": 5 } },
    { "_id": ObjectId("51e802efe837a0c33bd83623"), "cid": 12108, "comment_uid": 8310, "ip": "168.128.0.25", "pid": 12107, "status": true, "workflow": 3, "created": ISODate("2013-07-18T14:59:59Z"), "changed": ISODate("2013-07-18T14:59:59Z"), "note": { "note": 4, "total": 5 } }
  ],
  "created": ISODate("2013-07-18T14:59:59Z"),
  "gids": {},
  "nid": 3925,
  "nodeCache": {
  "nid": 3925,
    "title": "node_title_3925",
    "uid": "8160",
    "remote_id": "85765261099228e264ed02a8c215bf3d3d162075",
    "appid": "a4ac914c09d7c097fe1f4f96b897e625b6922069",
    "url": "url_node_3925",
    "rubrique": "Eco"
  },
  "node_uids": {"0": 8160},
  "thread": "",
  "userCache": [
    { "uid": 8160, "username": "node_author_name_8160", "avatar": "node_author_url_avatar_8160", "url_page_perso": "node_author_url_page_perso_8160", "abonne": false, "journaliste": true },
    { "uid": 8211, "username": "thread_owner_name_8211", "avatar": "thread_owner_url_avatar_8211", "url_page_perso": "thread_owner_url_page_perso_8211", "abonne": true, "journaliste": false },
    { "uid": 8089, "username": "thread_owner_name_8089", "avatar": "thread_owner_url_avatar_8089", "url_page_perso": "thread_owner_url_page_perso_8089", "abonne": false, "journaliste": true },
    { "uid": 8310, "username": "thread_owner_name_8310", "avatar": "thread_owner_url_avatar_8310", "url_page_perso": "thread_owner_url_page_perso_8310", "abonne": false, "journaliste": true }
  ]
}
*/
