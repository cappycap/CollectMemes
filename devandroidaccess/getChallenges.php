<?php

require 'db.php';

// Define response array for delivering status.
// Each lime should have
$challenges = array();

if (isset($POST['userId']) {

  $totalCompleted = 0;

  $u = $con->real_escape_string($_POST['userId']);

  $cpQ = "SELECT collectionId,totalOwned,completed FROM collectionsProgress WHERE userId=? ORDER BY totalOwned DESC";

  if ($cpS = $con->prepare($cpQ)) {

    $cpS->bind_param("i",$u);

    $cpS->execute():

    $cpS->bind_result($collectionId,$totalOwned,$completed);

    while ($cpS->fetch()) {

      $chal = array();

      $chal['id'] = $collectionId;
      $chal['completed'] = $completed;

      if ($completed == 1) {

        $totalCompleted++;

      }

      $challenges[] = $chal;

    }

    $cpS->close();

    foreach ($challenges as $challenge) {

      $q = "SELECT title,totalMemes,xpReward FROM collections WHERE id=?";

      if ($s = $con->prepare($q)) {

        $s->bind_param("i",$challenge['id']);

        $s->execute();

        $s->bind_result($title,$totalMemes,$xpReward);

        if ($s->fetch()) {

          $percent = intval(intval($totalOwned) / intval($totalMemes) * 100);

          $challenge['circleHTML'] = "<html>
          <head>
          <style>body { margin:0; }</style>
          <link href='https://collectmemes.com/dist/css-circular-prog-bar.css' rel='stylesheet'>
          </head>
          <body>
          <div class='progress-circle p" . $percent . "'>
            <span>" . $totalOwned . "/" . $totalMemes . "</span>
            <div class='left-half-clipper'>
              <div class='first50-bar'></div>
              <div class='value-bar'></div>
            </div>
          </div>
          </body>
          </html>";

          $challenge['title'] = $title;

          if ($challenge['completed']) {

            $challenge['xpColor'] = "#2ecc71";
            $challenge['xp'] = "Completed!";

          } else {

            $challenge['xpColor'] = "#3498db";
            $challenge['xp'] = "on completion: (+" . number_format($xpReward) . " XP)";

          }

          unset($challenge['completed']);


        }

        $s->close();

      }

    }

  }

}

echo json_encode($challenges);

$con->close();
?>
