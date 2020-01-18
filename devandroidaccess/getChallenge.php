<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['collectionId']) and isset($_POST['scheme']) and isset($_POST['deviceHeight'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $collectionId = $con->real_escape_string($_POST['collectionId']);
  $scheme = $con->real_escape_string($_POST['scheme']);

  $titlePer = intval(0.17 * intval($_POST['deviceHeight']));

  $bg = ($scheme == "light") ? "#ffffff" : "#111111";

  $nav = array();

  $nav['back'] = ($scheme == "light") ? "file://nav/tap-left-light.png" : "file://nav/tap-left-dark.png";

  $progress = array();

  $q = "SELECT memes,totalOwned,completed FROM collectionsProgress WHERE userId=? AND collectionId=?";

  if ($s = $con->prepare($q)) {

    $s->bind_param("ii",$userId,$collectionId);

    $s->execute();

    $s->bind_result($memes,$totalOwned,$completed);

    if ($s->fetch()) {

      $progress['memes'] = $memes;
      $progress['totalOwned'] = $totalOwned;
      $progress['completed'] = $completed;

    }

    $s->close();

  }

  // for carrying memes
  $challenge = array();

  // for returning
  $chal = array();

  $q2 = "SELECT title,memes,totalMemes,xpReward FROM collections WHERE id=?";

  if ($s = $con->prepare($q2)) {

    $s->bind_param("i",$collectionId);

    $s->execute();

    $s->bind_result($title,$memes,$totalMemes,$xpReward);

    if ($s->fetch()) {

      $challenge['memes'] = $memes;

      $h3Color = ""; $h3Text = "";

      if (intval($progress['completed']) == 1) {

        $h3Color = "#22a258";
        $h3Text = "Completed!";

      } else {

        $h3Color = "#3498db";
        $h3Text = number_format($xpReward) . " XP reward";

      }

      $textColor = ($scheme == "light") ? "#111111" : "#ffffff";

      $chal['title'] = $title;

      $chal['progress'] = $progress['totalOwned'] . " / " . $totalMemes;
      
      $chal['rewardText'] = $h3Text;
      $chal['rewardColor'] = $h3Color;

      $response['challenge'] = $chal;

    }

    $s->close();

  }

  $memeId = explode(",",$challenge['memes']);
  $status = explode(",",$progress['memes']);

  $count = 0; $len = count($status);

  $memes = array();

  while ($count < $len) {

    $meme = array();

    $mcurrent = $memeId[$count];
    $cstatus = intval($status[$count]);

    $memeQ = "SELECT title,image FROM memes WHERE id=?";

    if ($mS = $con->prepare($memeQ)) {

      $mS->bind_param("i",$mcurrent);

      $mS->execute();

      $mS->bind_result($title,$image);

      if ($mS->fetch()) {

        $meme['title'] = $title;

        if ($cstatus == 1) {

          $meme['image'] = $image;
          $meme['text'] = "Owned!";
          $meme['textColor'] = "#22a258";

        } else {

          $meme['image'] = ($scheme == "light") ? "file://shared/unowned-light.png" : "file://shared/unowned-dark.png";
          $meme['text'] = "Unowned!";
          $meme['textColor'] = "#c3c3c3";

        }

      }

      $mS->close();

    }

    $memes[] = $meme;

    $count++;

  }

  function sortByOwned($a, $b) {
    return $a['text'] <=> $b['text'];
  }

  usort($memes, 'sortByOwned');

  $response['memes'] = $memes;
  $response['nav'] = $nav;

}

echo json_encode($response);

$con->close();
?>
