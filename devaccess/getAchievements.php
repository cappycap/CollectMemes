<?php

require 'db.php';

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

        $ach = array();

        $ach['image'] = $image;
        $ach['title'] = $title;
        $ach['reqs'] = $reqs;

        if ($isFinal) {

          $ach['html'] = "";

          $ach['xp'] = "Complete!";
          $ach['xpColor'] = "#2ecc71";

        } else {

          $ach['xp'] = "(+" . number_format($xp) . " XP)";
          $ach['xpColor'] = "#3498db";

          if ($isStaged) {

            $ach['html'] = "<html><head>body { color:#111111;text-align:center; }</head><body>" . $stageMsg . "</body></html>";

          } else {

            $ach['html'] = "";

          }

        }

        $s->close();

        $achievements[] = $ach;

      }

    }

    $c++;

  }

}

echo json_encode($achievements);

$con->close();
?>
