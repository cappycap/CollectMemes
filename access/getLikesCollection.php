<?php

require 'db.php';

// Define response array for delivering status.
$response = array();
$list = array();
$components = array();

if (isset($_POST['userId']) and isset($_POST['cur'])) {

  $u = $con->real_escape_string($_POST['userId']);
  $c = (int)$con->real_escape_string($_POST['cur']) - 1;

  $q = "SELECT id,image FROM memes WHERE CONTAINS(likers, ?) ORDER BY dateAdded OFFSET " . $c . " DESC LIMIT 9";

  if ($stmt = $con->prepare($q)) {

    $stmt->bind_param("s", $u);
    $stmt->execute();

    $result = $stmt->get_result();

    $stmt->close();

    if ($result->num_rows > 0) {

      $counter = 0;

      while ($data = $result->fetch_assoc()) {

        $list[$counter] = array("type":"html","text":"<html>
        <head>
        <style>
        body {
            background-color: #111111;
            width:100%;
            margin:0;
            height:130px;
        }

        .image {
          background-image:    url(" . $data['image'] . ");
          background-size:     cover;                      /* <------ */
          background-repeat:   no-repeat;
          background-position: center center;              /* optional, center the image */
          width:100%;
          height:130px;
          margin:0;
          font-size:30px;
        }


        </style>
        </head>
        <body>
        <div class='image'>

        </div>
        </body>
        </html>","style":array("height":"130"),
        "action":array("type":"\$href","options":array("url":"https://collectmemes.com/views/viewLikedMeme.json","memeId":$data['id'])));

        $counter++;

      }

      while ($counter < 9) {

        $list[$counter] = array("type":"html","text":"<html>
        <head>
        <style>
        body {
            background-image:    url(https://collectmemes.com/img/empty.png);
            background-size:     cover;                      /* <------ */
            background-repeat:   no-repeat;
            background-position: center center;              /* optional, center the image */
            height:100%;
            margin:0;
        }
        </style>
        </head>
        <body>

        </body>
        </html>","style":array("height":"130"));

      }

      // Components to be returned to type vertical
      $components[0] = array("type":"horizontal","style":array("padding":"0","spacing":"0"),"components":array($list[0],$list[1],$list[2]));
      $components[1] = array("type":"horizontal","style":array("padding":"0","spacing":"0"),"components":array($list[3],$list[4],$list[5]));
      $components[2] = array("type":"horizontal","style":array("padding":"0","spacing":"0"),"components":array($list[6],$list[7],$list[8]));

    } else {

      $components = "undefined";

    }

  }

  $sQ = "SELECT likesSize FROM users WHERE id=?";

  if ($stmt = $con->prepare($sQ)) {

    $stmt->bind_param("i",$u);
    $stmt->execute();
    $stmt->bind_result($size);

    if ($stmt->fetch) {

      $response['size'] = $size;

    }

    $stmt->close();

  }

  $response['components'] = $components;

}
echo json_encode($response);

$con->close();
?>
