<?php

require 'db.php';

// Define response array for delivering status.
$response = array();
$list = array();
$components = array();

if (isset($_POST['userId']) and isset($_POST['cur']) and isset($_POST['sort']) and isset($_POST['sortDir'])) {

  $u = $con->real_escape_string($_POST['userId']);
  $c = (int)$con->real_escape_string($_POST['cur']) - 1;
  $s = $con->real_escape_string($_POST['sort']);
  $d = (int)$con->real_escape_string($_POST['sortDir']);

  $dir = "";

  if ($s == 'likes') {

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

        $iQ = "SELECT image FROM memes WHERE id=?";

        if ($iQS = $con->prepare($iQ)) {

          $iQS->bind_param("i",$data['memeId']);

          $iQS->execute();

          $iQS->bind_result($image);

          if ($iQS->fetch()) {

            $iQS->close();

            $list[$counter2] = array("text"=>"<html>
            <head>
            <style>
            body {
                background-color: #111111;
                margin:0;
                height:130px;
            }

            .image {
              background-image:    url(" . $image . ");
              background-size:     cover;
              background-repeat:   no-repeat;
              background-position: center center;
              width:100%;
              height:130px;
              margin:0;
            }

            </style>
            </head>
            <body>
            <div class='image'></div>
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
            background-image:    url(https://collectmemes.com/img/empty2.png);
            background-size:     cover;                      /* <------ */
            background-repeat:   no-repeat;
            background-position: center center;              /* optional, center the image */
            height:124px;
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

      $response['size'] = number_format($size);
      $response['curAdd'] = $c + 9;

    }

    $stmt->close();

  }

  $response['components'] = $components;

}
echo json_encode($response);

$con->close();
?>
