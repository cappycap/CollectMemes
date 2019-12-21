<?php

require 'db.php';

// Define response array for delivering status.
// Each lime should have
$challenges = array();

if (isset($POST['userId']) {

  $u = $con->real_escape_string($_POST['userId']);

  $cpQ = "SELECT collectionId,totalOwned,completed FROM collectionsProgress WHERE userId=? ORDER BY totalOwned DESC";

  if ($cpS = $con->prepare($cpQ)) {

    $cpS->bind_param("i",$u);

    $cpS->execute():

    $cpS->bind_result($collectionId,$totalOwned,$completed);

    while ($cpS->fetch()) {

      $chal = array();

      $chal['id'] = $colledtionId;
      $chal['totalOwned'] = $totalOwned;
      $chal['completed'] = $completed;

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

          $challenge['progress'] = $challenge['totalOwned'] . "/" . $totalMemes;
          $challenge['title'] = $title;

          if ($challenge['completed']) {

            $challenge['progressColor'] = "#2ecc71";
            $challenge['xpColor'] = "#2ecc71";
            $challenge['xp'] = "Complete!";

          } else {

            $challenge['progressColor'] = "#f1c40f";
            $challenge['xpColor'] = "#3498db";
            $challenge['xp'] = "(+" . number_format($xpReward) . " XP)";

          }

          unset($challenge['totalOwned']);
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
