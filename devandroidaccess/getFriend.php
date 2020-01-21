<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['friendId']) and isset($_POST['scheme'])) {

  $f = $con->real_escape_string($_POST['friendId']);

  $scheme = $_POST['scheme'];

  $bg = ($scheme == "light") ? "#ffffff" : "#111111";

  $textColor = ($scheme == "light") ? "#111111" : "#ffffff";

  $uQ = "SELECT username,avatar,xp,totalSpins FROM users WHERE id=?";

  $friendIds = array();

  if ($uS = $con->prepare($uQ)) {

    $uS->bind_param("i",$f);

    $uS->execute();

    $uS->bind_result($username,$avatar,$xp,$totalSpins);

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

  $components = array();

  $q = "SELECT memeId FROM owns WHERE userId=? ORDER BY rank ASC LIMIT 9";

  if ($stmt = $con->prepare($q)) {

    $stmt->bind_param("i", $f);
    
    $stmt->execute();

    $stmt->store_result();

    $stmt->bind_result($memeId);

    if ($stmt->num_rows > 0) {

      $counter1 = 0;
      $results = array();

      while ($stmt->fetch()) {

        $item = array();

        $item['memeId'] = $memeId;

        $results[$counter1] = $item;

        $counter1++;

      }

      $stmt->close();

      $counter2 = 0;

      foreach($results as $data) {

        $iQ = "SELECT image,rank FROM memes WHERE id=?";

        if ($iQS = $con->prepare($iQ)) {

          $iQS->bind_param("i",$data['memeId']);

          $iQS->execute();

          $iQS->bind_result($image,$rank);

          if ($iQS->fetch()) {

            $iQS->close();

            $info = getRankInfo($rank, $con);

            $list[$counter2] = array("text"=>"<html>
            <head>
            <style>
            html {
              margin:0;
              padding:0;
              width:100%;
              overflow:hidden;
              display:block;
              box-sizing: border-box;
            }
            body {
                background-color: #ffffff;
                margin:0;
                padding:0;
                width:100%;
                text-align:center;
                overflow:hidden;
                display:block;
                box-sizing:border-box;
                background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url(" . $image . ");
                background-size:     cover;
                background-repeat:   no-repeat;
                background-position: center center;
            }
            .shadow {
               box-shadow:         inset 0 0 40px " . $info['rarityColor'] . ";
            }
            .main {
                height: 100%;
                width: 100%;
                display: table;
            }
            .wrapper {
                display: table-cell;
                height: 100%;
                vertical-align: middle;
            }

            .text {
              text-align:center;
              font-size:30px;
              color:#fff;
            }

            </style>
            </head>
            <body class='shadow'>
              <div class='main'>
                <div class='wrapper'>
                  <div class='text'>#" . $rank . "</div>
                </div>
              </div>
            </body>
            </html>",
            "memeId"=>$data['memeId']);

          }

        }

        $counter2++;

      }

      while ($counter2 < 9) {

        $list[$counter2] = array("text"=>"<html>
        <head>
        <style>
        body {
            width:100%;
            padding-top:100%;
            margin:0;
        }
        </style>
        </head>
        <body>

        </body>
        </html>");

        $counter2++;

      }

      // Components to be returned to type vertical
      $components[0] = array("components"=>array($list[0],$list[1],$list[2]));
      $components[1] = array("components"=>array($list[3],$list[4],$list[5]));
      $components[2] = array("components"=>array($list[6],$list[7],$list[8]));

    } else {

      $components = "undefined";

    }

  }

  $response['components'] = $components;


}
echo json_encode($response);

$con->close();
?>
