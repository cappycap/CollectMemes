<?php

require 'db.php';

function userSearch($userId, $column, $con) {

  $ret = 0;

  $q = "SELECT " . $column . " FROM users WHERE id=?";

  if ($s = $con->prepare($q)) {

    $s->bind_param("i",$userId);

    $s->execute();

    $s->bind_result($r);

    if ($s->fetch()) {

      $ret = $r;

    }

    $s->close();

  }

  return $ret;

}

function collectionsSearch($userId, $con) {

  $q = "SELECT COUNT(*) FROM collectionsProgress WHERE userId=" . $userId . " AND completed=1";

  $result = $con->query($q);

  $row = $result->fetch_row();

  return $row[0];

}

function getMin($achievementId, $userId, $con) {

  $min = 0;

  // achievements that don't require a DB check
  $bin = array(5, 6, 7, 8, 14);

  if (!in_array($achievementId,$bin)) {

    if ($achievementId == 0) {

      $min = userSearch($userId, "collectionSize", $con);

    } else if ($achievementId == 1) {

      $min = userSearch($userId, "totalSpins", $con);

    } else if ($achievementId == 2) {

      $min = collectionsSearch($userId, $con);

    } else if ($achievementId == 3) {

      $min = userSearch($userId, "likesSize", $con);

    } else if ($achievementId == 4) {

      $min = userSearch($userId, "friendsSize", $con);

    } else if ($achievementId == 9) {

      $min = userSearch($userId, "commonCount", $con);

    } else if ($achievementId == 10) {

      $min = userSearch($userId, "uncommonCount", $con);

    } else if ($achievementId == 11) {

      $min = userSearch($userId, "rareCount", $con);

    } else if ($achievementId == 12) {

      $min = userSearch($userId, "epicCount", $con);

    } else if ($achievementId == 13) {

      $min = userSearch($userId, "legendaryCount", $con);

    }

  }

  return $min;

}

function getProgress($achievementId, $stage, $userId, $con) {

  $progress = array();

  // array of max values by $achievementId and stage
  $maxVals = array(
    array(1,10,100,0),
    array(100,500,1000,0),
    array(1,5,10,-1),
    array(10,100,500,0),
    array(1,10,50,0),
    array(1,0),
    array(1,0),
    array(1,0),
    array(1,0),
    array(1,10,0),
    array(1,10,0),
    array(1,10,0),
    array(1,10,0),
    array(1,10,0),
    array(1,0)
  );

  $max = $maxVals[$achievementId][$stage];

  // finding min values by achievementId

  $min = 0;

  if ($max != 0) {

    $min = getMin($achievementId, $userId, $con);

  }

  // generate progress bar

  $p = ($max != 0) ? intval(($min / $max) * 100) : 100;

  $s = "<html><head><style>body {margin:0; height:24px; background:#dedede;}
  .bar { width:" . $p . "%; height:24px; } </style></head><body>
  <div class='bar'></div>
  </body></html>";

  $progress['bar'] = $s;
  $progress['min'] = $min;
  $progress['max'] = $max;

  return $progress;

}

// Define response array for delivering status.
// Each lime should have
$achievements = array();

if (isset($POST['userId']) {

  $prog = array();

  $u = $con->real_escape_string($_POST['userId']);

  $pQ = "SELECT progress FROM achievementsProgress WHERE userId=?";

  if ($pS = $con->prepare($pQ)) {

    $pS->bind_param("i",$u);

    $pS->execute();

    $pS->bind_result($progress);

    if ($pS->fetch()) {

      $prog = explode(",",$progress);

    }

    $pS->close();

  }

  $c = 0;

  foreach ($prog as $stage) {

    $id = $c;

    $q = "SELECT isFinal,isStaged,stageMsg,title,reqs,image,xpNext WHERE achievementId=? AND stage=?";

    if ($s = $con->prepare($q)) {

      $s->bind_param("ii",$id,$stage);

      $s->execute();

      $s->bind_result($isFinal,$isStaged,$stageMsg,$title,$reqs,$image,$xp);

      if ($s->fetch()) {

        $s->close();

        $ach = array();

        $ach['image'] = $image;
        $ach['title'] = $title;
        $ach['reqs'] = $reqs;

        $progInfo = getProgress($achievementId, $stage, $userId, $con);

        $ach['bar'] = $progInfo['bar'];

        if ($isFinal) {

          $ach['xp'] = "Complete!";
          $ach['xpColor'] = "#2ecc71";
          $ach['progress'] = "";


        } else {

          $ach['xp'] = $stageMsg . " (+" . number_format($xp) . " XP)";
          $ach['xpColor'] = "#3498db";
          $ach['progress'] = $progInfo['min'] . " / " . $progInfo['max'];

        }

        $achievements[] = $ach;

      }

    }

    $c++;

  }

}

echo json_encode($achievements);

$con->close();
?>
