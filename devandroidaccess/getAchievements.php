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

  $q = "SELECT id FROM collectionsProgress WHERE userId=" . $userId . " AND completed=1";

  $row = 0;

  if ($result = $con->query($q)) {

    $row = $result->num_rows;

    $result->close();

  }

  return $row;

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

  $s = "<html><head><style>body { margin:0; height:24px; background:#dedede; } .bar { width:" . $p . "%; height:24px;background:#3498db; } </style></head><body><div class='bar'></div></body></html>";

  $progress['bar'] = $s;
  $progress['min'] = $min;
  $progress['max'] = $max;

  return $progress;

}

// Define response array for delivering status.
$achievements = array();
$jason = array();

if (isset($_POST['userId']) and isset($_POST['scheme'])) {

  $scheme = $_POST['scheme'];
  $u = $con->real_escape_string($_POST['userId']);

  $nav = array();

  $nav['collectRight'] = ($scheme == "light") ? "file://nav/collect-right-light.png" : "file://nav/collect-right-dark.png";

  $prog = array();

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

  $totalCompleted = 0;

  foreach ($prog as $stage) {

    $id = $c;

    $q = "SELECT isFinal,isStaged,stageMsg,title,reqs,image,xpNext FROM achievements WHERE achievementId=? AND stage=?";

    if ($s = $con->prepare($q)) {

      $s->bind_param("ii",$id,$stage);

      $s->execute();

      $s->bind_result($isFinal,$isStaged,$stageMsg,$title,$reqs,$image,$xp);

      if ($s->fetch()) {

        $s->close();

        $ach = array();

        $totalCompleted += $stage;

        $ach['stage'] = ($isFinal) ? 100 : $stage;

        $ach['image'] = $image;
        $ach['title'] = $title;
        $ach['reqs'] = $reqs;

        $progInfo = getProgress($id, $stage, $u, $con);

        $ach['bar'] = $progInfo['bar'];

        if ($isFinal) {

          $ach['xp'] = "Complete!";
          $ach['xpColor'] = "#2ecc71";
          $ach['progress'] = "";

        } else {

          $ach['xp'] = "next: +" . number_format($xp) . " XP";
          $ach['xpColor'] = "#3498db";
          $ach['progress'] = $progInfo['min'] . " / " . $progInfo['max'];

        }

        $achievements[] = $ach;

      }

    }

    $c++;

  }

  $totalAchievements = 45;

  function sortByStage($a, $b) {
    return $a['stage'] <=> $b['stage'];
  }

  usort($achievements, 'sortByStage');

  $achievementsFin = array_reverse($achievements);

  $jason['achievements'] = $achievementsFin;
  $jason['nav'] = $nav;

  $bg = ($scheme == "light") ? "#ffffff" : "#111111";

  $jason['stats'] = "<html><head><style>body { background-color:" . $bg . ";margin:0;color:#c3c3c3;font-size:20px;text-align:center; }</style></head><body><span style='font-weight:bold;'>" . $totalCompleted . " / " . $totalAchievements ."</span> completed</body></html>";

}

echo json_encode($jason);

$con->close();
?>
