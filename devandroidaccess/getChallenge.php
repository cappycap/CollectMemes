<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['collectionId']) and isset($_POST['scheme'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $collectionId = $con->real_escape_string($_POST['collectionId']);
  $scheme = $con->real_escape_string($_POST['scheme']);

  $bg = ($scheme == "light") ? "#ffffff" : "#111111";

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

  $challenge = array();

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

        $h3Color = ($scheme == "light") ? "#111111" : "#ffffff";
        $h3Text = number_format($xpReward) . " XP reward";

      }

      $response['titleHTML'] = "<html>
      <head>
        <style>
          body { background:" . $bg . "; }
          h3 { color:" . $h3Color . "; }
        </style>
      </head>
      <body>
        <h1>" . $title . "</h1>
        <h2>" . $progress['totalOwned'] . " / " . $totalMemes . " collected</h2>
        <h3>" . $h3Text . "</h3>
      </body>
      </html>";

    }

    $s->close();

  }

  $memeId = explode(",",$challenge['memes']);
  $status = explode(",",$progress['memes']);

  $count = 0; $len = count($status);

  $htmlMemes = array();

  while ($count < $len) {

    $mcurrent = $memeId[$count];
    $cstatus = intval($status[$count]);

    $memeTitle = "";
    $memeImage = "";
    $memeSubtext = "";

    $memeQ = "SELECT title,image FROM memes WHERE id=?";

    if ($mS = $con->prepare($memeQ)) {

      $ms->bind_param("i",$mcurrent);

      $mS->execute();

      $mS->bind_result($title,$image);

      if ($mS->fetch()) {

        $memeTitle = $title;

        if ($cstatus == 1) {

          $memeImage = $image;
          $memeSubtext = "<span style='color:#22a258;'>Owned!</span>";

        } else {

          $memeImage = ($scheme == "light") ? "https://collectmemes.com/img/unowned-light.png" : "https://collectmemes.com/img/unowned-dark.png";
          $memeSubtext = "<span style='color:#dedede;'>Unowned!</span>";

        }

      }

      $mS->close();

    }

    $html = "<div class='meme'>
      <h2>" . $memeTitle . "</h2>
      <img src='" . $memeImage . "'>
      <h3>" . $memeSubtext . "</h3>
    </div>";

    $htmlMemes[] = $html;

    $count++;

  }

  $progressHTML = "<html>
  	<head>
  		<style>
  		body {
  			text-align:center;
        background:" . $bg . ";
  		}
  		img {
  			width:200px;
  			height:200px;
  			margin:20px auto;
  			border-radius:200px;
  		}
  		.meme {
  			width:33%;
  			margin:auto;
  			display:inline-block;
  			text-align:center;
  			color:#111111;
  		}
  		</style>
  	</head>
  	<body>";

  foreach ($htmlMemes as $html) {

    $progressHTML .= $html;

  }

  $progressHTML .= "</body></html>";

  $response['progressHTML'] = $progressHTML;

}

echo json_encode($response);

$con->close();
?>
