<?php

require 'db.php';

// Define response array for delivering status.
// Each lime should have
$jason = array();

if (isset($_POST['userId']) and isset($_POST['scheme']) and isset($_POST['sort']) and isset($_POST['sortDir'])) {

  $totalCompleted = 0;

  $u = $con->real_escape_string($_POST['userId']);
  $scheme = $_POST['scheme'];

  $s = $con->real_escape_string($_POST['sort']);
  $d = $con->real_escape_string($_POST['sortDir']);

  $nav = array();

  $dir = "";

  if ($d == 0) {

    $dir = "ASC";
    $nav['sortButton'] = ($scheme == "light") ? "file://shared/sort-down-light.png" : "file://shared/sort-down-dark.png";

  } else {

    $dir = "DESC";
    $nav['sortButton'] = ($scheme == "light") ? "file://shared/sort-up-light.png" : "file://shared/sort-up-dark.png";

  }

  $challenges = array();

  $nav['collectRight'] = ($scheme == "light") ? "file://nav/collect-right-light.png" : "file://nav/collect-right-dark.png";
  $nav['achievementsLeft'] = ($scheme == "light") ? "file://nav/achievements-left-light.png" : "file://nav/achievements-left-dark.png";

  $cpQ = "SELECT collectionId,totalOwned,completed FROM collectionsProgress WHERE userId=? ORDER BY " . $s . " " . $dir;

  if ($cpS = $con->prepare($cpQ)) {

    $cpS->bind_param("i",$u);

    $cpS->execute();

    $cpS->bind_result($collectionId,$totalOwned,$completed);

    while ($cpS->fetch()) {

      $chal = array();

      $chal['id'] = $collectionId;
      $chal['totalOwned'] = $totalOwned;
      $chal['completed'] = $completed;

      if ($completed == 1) {

        $totalCompleted++;

      }

      $challenges[] = $chal;

    }

    $cpS->close();

    $new = array();

    foreach ($challenges as $challenge) {

      $chal = array();

      $chal['id'] = $challenge['id'];

      $q = "SELECT title,totalMemes,xpReward FROM collections WHERE id=?";

      if ($s = $con->prepare($q)) {

        $s->bind_param("i",$challenge['id']);

        $s->execute();

        $s->bind_result($title,$totalMemes,$xpReward);

        if ($s->fetch()) {

          $percent = intval(intval($challenge['totalOwned']) / intval($totalMemes) * 100);

          $chal['circleHTML'] = "<html>
          <head>
          <style>body { margin:0; } </style>
          <link href='https://collectmemes.com/dist/css-circular-prog-bar.css' rel='stylesheet'>
          </head>
          <body>
          <div class='progress-circle p" . $percent . "'>
            <span>" . $challenge['totalOwned'] . "/" . $totalMemes . "</span>
            <div class='left-half-clipper'>
              <div class='first50-bar'></div>
              <div class='value-bar'></div>
            </div>
          </div>
          </body>
          </html>";

          $chal['title'] = $title;

          if ($challenge['completed']) {

            $chal['xpColor'] = "#2ecc71";
            $chal['xpTop'] = "Completed!";
            $chal['xp'] = "";

          } else {

            $chal['xpColor'] = "#3498db";
            $chal['xpTop'] = "on completion:";
            $chal['xp'] = "(+" . number_format($xpReward) . " XP)";

          }

        }

        $s->close();

      }

      $new[] = $chal;

    }

    $jason['challenges'] = $new;
    $jason['nav'] = $nav;

    $bg = ($scheme == "light") ? "#ffffff" : "#111111";

    $size = count($new);

    $jason['stats'] = "<html><head><style>body { background-color:" . $bg . ";margin:0;color:#c3c3c3;font-size:20px;text-align:center; }</style></head><body><span style='font-weight:bold;'>" . $totalCompleted . " / " . $size ."</span> completed</body></html>";

  }

}

echo json_encode($jason);

$con->close();
?>
