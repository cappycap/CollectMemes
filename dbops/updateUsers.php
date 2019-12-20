<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function createAchievementsProgress($uid, $con) {

  $ret = 0;

  $progress = "0,0,0,0,0,0,0,0,0,0,0,0,0,0,0";

  $q = "INSERT INTO achievementsProgress (userId, progress) VALUES (?, ?)";

  if ($s = $con->prepare($q)) {

    $s->bind_param("is",$uid,$progress);

    if ($s->execute()) {

      $ret = 1;

    }

    $s->close();

  }

  return $ret;

}

function createCollectionsProgress($uid, $con) {

  $check = array();

  // Call to collections
  $collectionsQ = "SELECT id,memes FROM collections WHERE 1";

  $collections = array();

  if ($a = $con->prepare($collectionsQ)) {

    $a->execute();

    $a->bind_result($cId,$cMemes);

    while ($a->fetch()) {

      $length = count(explode(",",$cMemes));

      $collections[] = array("id"=>$cId,"memes"=>$length);

    }

    $a->close();

    // Create progress trackers for users
    foreach ($collections as $col) {

      $memes = "0";
      $l = $col['memes'] - 1;

      $count = 0;

      while ($count < $l) {

        $memes .= ",0";

      }

      $ins = "INSERT INTO collectionsProgress (userId,collectionId,memes,completed) VALUES (?, ?, ?, ?)";

      if ($q = $con->prepare($ins)) {

        $completed = 0;

        $q->bind_param("iisi",$uid,$col['id'],$memes,$completed);

        if ($q->execute()) {

          $check[] = 1;

        } else {

          $check[] = 0;

        }

        $q->close();

      }

    }

  }

  return !in_array(0, $check);

}

require 'db.php';

function updateUsers($con) {

  $check = array();

  $q = "SELECT id FROM users";

  if ($s = $con->prepare($q)) {

    $s->execute();

    $s->bind_result($id);

    $ids = array();

    while ($s->fetch()) {

      $ids[] = $id;

    }

    $s->close();

    foreach ($ids as $uid) {

      ;

      ;

      if (createCollectionsProgress($uid, $con) and createAchievementsProgress($uid, $con)) {

        $check[] = 1;

      } else {

        $check[] = 0;
        
      }

    }

  }

  return !in_array(0, $check);

}

echo json_encode(updateUsers());

$con->close();

?>
