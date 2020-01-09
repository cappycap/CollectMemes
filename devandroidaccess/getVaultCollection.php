<?php

require 'db.php';

// Define response array for delivering status.
$response = array();
$list = array();
$components = array();

if (isset($_POST['userId']) and isset($_POST['cur']) and isset($_POST['sort']) and isset($_POST['sortDir']) and isset($_POST['scheme'])) {

  $u = $con->real_escape_string($_POST['userId']);
  $c = (int)$con->real_escape_string($_POST['cur']) - 1;
  $s = $con->real_escape_string($_POST['sort']);
  $d = (int)$con->real_escape_string($_POST['sortDir']);

  $scheme = $_POST['scheme'];

  $nav = array();

  $nav['collectLeft'] = ($scheme == "light") ? "file://nav/collect-left-light.png" : "file://nav/collect-left-dark.png";
  $nav['likesRight'] = ($scheme == "light") ? "file://nav/likes-right-light.png" : "file://nav/likes-right-dark.png";

  $dir = "";

  if ($s == 'rank') {

    if ($d == 1) {

      $dir = "ASC";

    } else {

      $dir = "DESC";

    }

  } else {

    if ($d == 1) {

      $dir = "DESC";

    } else {

      $dir = "ASC";

    }

  }


  $q = "SELECT memeId FROM owns WHERE userId=? ORDER BY " . $s . " " . $dir . " LIMIT 12" . " OFFSET " . $c;

  if ($stmt = $con->prepare($q)) {

    $stmt->bind_param("i", $u);
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
              height:90%;
              width:100%;
              overflow:hidden;
              display:block;
              box-sizing: border-box;
            }
            body {
                background-color: #111111;
                margin:0;
                padding:0;
                height:97%;
                width:100%;
                text-align:center;
                border:3px solid " . $info['color'] . ";
                overflow:hidden;
                display:block;
                box-sizing:border-box;
                background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(" . $image . ");
                background-size:     cover;
                background-repeat:   no-repeat;
                background-position: center center;
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
            <body class='child'>
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

  $sQ = "SELECT collectionSize,avgRank FROM users WHERE id=?";

  if ($stmt = $con->prepare($sQ)) {

    $stmt->bind_param("i",$u);
    $stmt->execute();
    $stmt->bind_result($size,$avgRank);

    if ($stmt->fetch()) {

      $response['size'] = number_format($size);
      $response['avgRank'] = $avgRank;
      $response['curAdd'] = $c + 13;

      $bg = ($scheme == "light") ? "#ffffff" : "#111111";
      
      $response['stats'] = "<html><head><style>body { margin:0; }</style></head><body></body></html>";

    }

    $stmt->close();

  }

  $response['components'] = $components;

}
echo json_encode($response, JSON_UNESCAPED_SLASHES);

$con->close();
?>
