<?php

require 'db.php';

function ago($nextSpin) {

  $t = time() - ($nextSpin - 1800);

  $m = "";

  if ($t < 10) {

    $m = "collected just now!";

  } else if ($t < 60) {

    $m = "collected " . $t . "s ago";

  } else if ($t < 3600) {

    $mins = intval($t / 60);

    $m = "collected " . $mins . "mins ago";

  } else if ($t < 86400) {

    $hours = $t / 3600;

    $m = "collected " . $hours . " hours ago";

  } else {

    $days = $t / 86400;

    $m = "collected " . $days . " days ago";

  }

  return $m;

}

// Define response array for delivering status.
// modification from concept: view button instead of the image of their latest collected meme!
// profileTopHTML, addFriendImage, friends (array: id, avatarHTML, username, lastCollectText)

$response = array();
if (isset($_POST['userId']) and isset($_POST['scheme'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $scheme = $con->real_escape_string($_POST['scheme']);

  $bg = ($scheme == "light") ? "#ffffff" : "#111111";
  $textColor = ($scheme == "light") ? "#111111" : "#ffffff";

  $uQ = "SELECT username,avatar,xp,totalSpins,friends FROM users WHERE id=?";

  $friendIds = array();

  if ($uS = $con->prepare($uQ)) {

    $uS->bind_param("i",$userId);

    $uS->execute();

    $uS->bind_result($username,$avatar,$xp,$totalSpins,$friendsStr);

    if ($uS->fetch()) {

      $friendIds = explode(",",$friendsStr);

      $level = xpToLevel($xp);

      $response['profileTopHTML'] = "<html>
      <head>
      <style>
      body {
      	margin:0;
      	background:" . $bg . ";
      	font-family:Arial;
      }
      .container {
         background: linear-gradient(
                rgba(0, 0, 0, 0.2),
                rgba(0, 0, 0, 0.2)
              ),url('" . $avatar . "');
      	 background-size:cover;
         position: relative;
         width: 100%;
         padding-top: 100%;
      }
      .text {
         position:  absolute;
         top: 86%;
         bottom: 0;
         left: 0;
         font-size: 30px;
         color: white;
      	 padding:5px 10px;
      }
      .level {
      	position:absolute;
      	top:71%;
      	height:10%;
      	right:3%;
      }
      .progBar {
      	background:#dedede;
      	height:24px;
      	margin-bottom:5px;
      }
      .prog {
      	background:#308cca;
      	height:24px;
      }
      .info {
      	padding:1%;
      	color:" . $textColor . ";
      }
      .totalSpins {
      	width:48%;
      	display:inline-block;
      	float:left;
      }
      .xp {
      	width:49%;
      	display:inline-block;
      	float:right;
      	text-align:right;
      }
      </style>
      </head>
      <body>
      	<div class='container'>
      	 <div class='text'>" . $username . "</div>
      	 <div class='level'><img src='https://collectmemes.com/img/levels/" . $level . ".png'></div>
      	</div>
      	<div class='progBar'>
      		<div class='prog' style='width:70%'></div>
      	</div>
      	<div class='info'>
      		<div class='totalSpins'>" . $totalSpins . " spins</div>
      		<div class='xp'>" . number_format($xp) . " / " . number_format(nextLevelXpNeeded($level)) . " XP</div>
      	</div>
      </body>
      </html>";

    }

    $uS->close();

  }

  $frCount = "";

  $frQ = "SELECT COUNT(*) FROM friendRequests WHERE userId=?";

  $frGrab = $con->query($frQ);
  $frRow = $frGrab->fetch_row():

  if (intval($frRow[0]) > 5) {

    $frCount = "file://add-friend/5.png";

  } else {

    $frCount = "file://add-friend/" . (str)$frRow[0] . ".png";

  }

  $response['addFriendImage'] = $frCount;

  $friends = array();

  foreach ($friendIds as $id) {

    $friend = array();

    $friend['id'] = $id;

    $fQ = "SELECT username, avatar, xp, collectionSize, nextSpin FROM users WHERE id=?";

    if ($fS = $con->prepare($fQ)) {

      $fS->bind_param("i",$id);

      $fS->execute();

      $fS->bind_result($username, $avatar, $xp, $collectionSize, $nextSpin);

      if ($fS->fetch()) {

        $friend['username'] = $username;

        $ago = ago($nextSpin);

        $friend['lastCollectText'] = $ago;

        $friend['avatarHTML'] = "<html>
        <head>
        <style>
        body {
        	width:100px;
        	margin:auto;
        	background:" . $bg . ";
        	font-family:Arial;
        }
        .container {
           background: url('" . $avatar . "');
        	 background-size:cover;
           position: relative;
           width: 100%;
           padding-top: 100%;
        	 border-radius:100%;
        	 border:3px solid #dedede;
        }
        .level {
        	position:absolute;
        	top:71%;
        	height:10%;
        	right:3%;
        }
        </style>
        </head>
        <body>
        	<div class='container'>
        	 <div class='level'><img style='width:50%' src='https://collectmemes.com/img/levels/" . xpToLevel($xp) . ".png'></div>
        	</div>
        </body>
        </html>";

      }

      $fS->close();

    }

  }

}

echo json_encode($response);

$con->close();
?>
