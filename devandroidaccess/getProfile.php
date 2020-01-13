<?php

require 'db.php';

function ago($lastCollect) {

  $t = time() - $lastCollect;

  $m = "";

  if ($t < 10) {

    $m = "collected just now!";

  } else if ($t < 60) {

    $m = "collected " . intval($t) . "s ago";

  } else if ($t < 3600) {

    $mins = number_format(intval($t / 60));

    $m = "collected " . $mins . "mins ago";

  } else if ($t < 86400) {

    $hours = number_format(intval($t / 3600));

    $m = "collected " . $hours . " hours ago";

  } else {

    $days = number_format(intval($t / 86400));

    $m = "collected " . $days . " days ago";

  }

  return $m;

}

// Define response array for delivering status.
// modification from concept: view button instead of the image of their latest collected meme!
// profileTopHTML, addFriendImage, friends (array: id, avatarHTML, username, lastCollectText)

$response = array();

if (isset($_POST['userId']) and isset($_POST['scheme']) and isset($_POST['cur']) and isset($_POST['sort']) and isset($_POST['sortDir'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $scheme = $con->real_escape_string($_POST['scheme']);
  $c = (int)$con->real_escape_string($_POST['cur']);
  $s = $con->real_escape_string($_POST['sort']);
  $d = (int)$con->real_escape_string($_POST['sortDir']);

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

      $nextXp = nextLevelXpNeeded($level);

      $percent = intval(100 * ($xp / $nextXp));

      $response['profileTopHTML'] = "<html>
      <head>
      <style>
      body {
      	margin:0;
      	background:" . $bg . ";
      	font-family:Arial;
        width: 100%;
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
      		<div class='prog' style='width:" . $percent . "%'></div>
      	</div>
      	<div class='info'>
      		<div class='totalSpins'>" . $totalSpins . " spins</div>
      		<div class='xp'>" . number_format($xp) . " / " . number_format($nextXp) . " XP</div>
      	</div>
      </body>
      </html>";

    }

    $uS->close();

  }

  $frCount = "";

  $frQ = "SELECT id FROM friendRequests WHERE userId=" . $userId;

  if ($frGrab = $con->query($frQ)) {

    $frRow = $frGrab->num_rows;

    if (intval($frRow) > 5) {

      $frCount = "file://add-friend/5.png";

    } else {

      $str = (string)$frRow;

      $frCount = "file://add-friend/" . $str . ".png";

    }

    $frGrab->close();

  }

  $response['addFriendImage'] = $frCount;

  $friends = array();

  foreach ($friendIds as $id) {

    $friend = array();

    $friend['id'] = $id;

    $fQ = "SELECT username, avatar, xp, collectionSize, nextSpin, lastCollect, isPro FROM users WHERE id=?";

    if ($fS = $con->prepare($fQ)) {

      $fS->bind_param("i",$id);

      $fS->execute();

      $fS->bind_result($username, $avatar, $xp, $collectionSize, $nextSpin, $lastCollect, $isPro);

      if ($fS->fetch()) {

        $fS->close();

        $friend['username'] = $username;

        $ago = ago($lastCollect);

        $friend['lastCollectText'] = $ago;

        $friend['avatarHTML'] = "<html>
        <head>
        <style>
        body {
        	width:100%;
        	margin:auto;
        	background:" . $bg . ";
        	font-family:Arial;
        }
        .container {
           background: url('" . $avatar . "');
        	 background-size:cover;
           position: relative;
           width: 90%;
           padding-top: 90%;
        	 border-radius:90%;
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

        $friend['xp'] = intval($xp);
        $friend['lastCollect'] = intval($lastCollect);

        $friends[] = $friend;

      }

    }

  }

  function sortByXP($a, $b) {
    return $a['xp'] <=> $b['xp'];
  }

  function sortByLastCollect($a, $b) {
    return $a['lastCollect'] <=> $b['lastCollect'];
  }

  $choice = ($s == 'sortByXP') ? 'sortByXP' : 'sortByLastCollect';

  usort($friends, $choice);

  $retFriends = array();

  $size = count($friends);

  $nav = array();

  $curPage = 1 + intval($c / 10);
  $totalPages = 1 + intval($size / 10);

  $nav['pageLeft'] = ($curPage != 1) ? "file://shared/page-left-active.png" : "file://shared/page-left-null.png";
  $nav['pageRight'] = ($curPage != $totalPages and $totalPages != 1) ? "file://shared/page-right-active.png" : "file://shared/page-right-null.png";

  $nav['allowPageLeft'] = ($curPage != 1) ? 1 : 0;
  $nav['allowPageRight'] = ($curPage != $totalPages and $totalPages != 1) ? 1 : 0;

  $nav['displayPager'] = ($curPage == 1 and $totalPages == 1) ? 0 : 1;
  $nav['pageDisplay'] = $curPage . " / " . $totalPages;

  $top = 0;

  if ($d == 1) {

    $nav['sortButton'] = ($scheme == "light") ? "file://shared/sort-up-light.png" : "file://shared/sort-up-dark.png";

    $cur = $c;
    $top = $c + 10;

    while ($cur < $top and $cur < $size) {

      $retFriends[] = $friends[$cur];

      $cur++;

    }

  } else {

    $nav['sortButton'] = ($scheme == "light") ? "file://shared/sort-down-light.png" : "file://shared/sort-down-dark.png";

    $cur = ($size - 1) - $c;
    $top = $cur - 10;

    while ($cur > $top and $cur >= 0) {

      $retFriends[] = $friends[$cur];

      $cur--;

    }

  }

  $nav['curAdd'] = $top;

  $response['nav'] = $nav;
  $response['friends'] = $retFriends;
  $response['hasFriends'] = (empty($retFriends)) ? 0 : 1;
  $response['cur'] = $c;

}

echo json_encode($response, JSON_UNESCAPED_SLASHES);

$con->close();
?>
