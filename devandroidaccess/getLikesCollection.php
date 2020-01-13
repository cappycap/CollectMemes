<?php

require 'db.php';

// Define response array for delivering status.
$response = array();
$list = array();
$components = array();

if (isset($_POST['userId']) and isset($_POST['cur']) and isset($_POST['sort']) and isset($_POST['sortDir']) and isset($_POST['scheme'])) {

  $u = $con->real_escape_string($_POST['userId']);
  $c = (int)$con->real_escape_string($_POST['cur']);
  $s = $con->real_escape_string($_POST['sort']);
  $d = (int)$con->real_escape_string($_POST['sortDir']);

  $scheme = $_POST['scheme'];

  $nav = array();

  $nav['vaultLeft'] = ($scheme == "light") ? "file://nav/collection-left-light.png" : "file://nav/collection-left-dark.png";

  $dir = "";

  if ($s == 'likes') {

    if ($d == 1) {

      $dir = "ASC";
      $nav['sortButton'] = ($scheme == "light") ? "file://shared/sort-up-light.png" : "file://shared/sort-up-dark.png";

    } else {

      $dir = "DESC";
      $nav['sortButton'] = ($scheme == "light") ? "file://shared/sort-down-light.png" : "file://shared/sort-down-dark.png";

    }

  } else {

    if ($d == 1) {

      $dir = "DESC";
      $nav['sortButton'] = ($scheme == "light") ? "file://shared/sort-up-light.png" : "file://shared/sort-up-dark.png";

    } else {

      $dir = "ASC";
      $nav['sortButton'] = ($scheme == "light") ? "file://shared/sort-down-light.png" : "file://shared/sort-down-dark.png";

    }

  }

  $q = "SELECT memeId FROM likes WHERE userId=? ORDER BY " . $s . " " . $dir . " LIMIT 9" . " OFFSET " . $c;

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


            </style>
            </head>
            <body class='shadow'>

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

  $sQ = "SELECT likesSize FROM users WHERE id=?";

  if ($stmt = $con->prepare($sQ)) {

    $stmt->bind_param("i",$u);
    $stmt->execute();
    $stmt->bind_result($size);

    if ($stmt->fetch()) {

      $size = number_format($size);

      $response['cur'] = $c;

      $nav['curAdd'] = $c + 9;
      $nav['curMin'] = $c - 9;

      $curPage = 1 + intval($c / 9);
      $totalPages = 1 + intval($size / 9);

      $nav['pageLeft'] = ($curPage != 1) ? "file://likes/page-left-active.png" : "file://shared/page-left-null.png";
      $nav['pageRight'] = ($curPage != $totalPages and $totalPages != 1) ? "file://likes/page-right-active.png" : "file://shared/page-right-null.png";

      $nav['allowPageLeft'] = ($curPage != 1) ? "1" : "0";
      $nav['allowPageRight'] = ($curPage != $totalPages and $totalPages != 1) ? "1" : "0";

      $nav['pageDisplay'] = $curPage . " / " . $totalPages;

      $bg = ($scheme == "light") ? "#ffffff" : "#111111";

      $memeText = ($size == 1) ? "meme" : "memes";
      
      $response['stats'] = "<html><head><style>body { background-color:" . $bg . ";margin:0;color:#dedede;font-size:20px;text-align:center; }</style></head><body><span style='font-weight:bold;'>" . $size . "</span> " . $memeText . " liked!</body></html>";

    }

    $stmt->close();

  }

  $response['nav'] = $nav;
  $response['components'] = $components;

}
echo json_encode($response, JSON_UNESCAPED_SLASHES);

$con->close();
?>
