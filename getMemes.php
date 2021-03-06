<?php

require 'db.php';
$response = array();

if (isset($_GET['key'])) {

  if ($_GET['key'] == "xiuyy96721383ghqkjn5n5243h54356") {

    echo "<form>URL: <input type='text' name='url'><input type='submit'></form>";

    if (isset($_POST['submit'])) {

      $str = file_get_contents($con->real_escape_string($_POST['url']));

      $json = json_decode($str, true);

      foreach ($json['data']['children'] as $field => $value) {

          $image = $con->real_escape_string($value['data']['url']);

          // Check: Image Exists, and is gif,png,jpg,gifv
          $check = "SELECT id FROM memes WHERE image = ? LIMIT 1";

          $isOk = false;

          if (strpos($image, '.jpg') == true || strpos($image, '.png') == true || strpos($image, '.gif') == true || strpos($image, '.gifv') == true) {

            $isOk = true;

          }

          if ($checkStmt = $con->prepare($check) and $isOk) {

            $checkStmt->bind_param("s",$image);

            $checkStmt->execute();

            $checkStmt->bind_result($exists);

            $checkStmt->fetch();

            if (!$exists) {

              $checkStmt->close();

              $title = $con->real_escape_string($value['data']['title']);

              $score = $con->real_escape_string($value['data']['score']);
              $rank = getMemeCount($con) + 1;
              $edition = 1;
              $creator = $con->real_escape_string($value['data']['author']);
              $source = "reddit";
              $dateAdded = date("Y-m-d",time());

              $ins = "INSERT INTO memes (title, image, score, rank, edition, source, creator, dateAdded)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

              if ($insStmt = $con->prepare($ins)) {

                $insStmt->bind_param("ssiiisis", $title, $image, $score, $rank, $edition, $source, $creator, $dateAdded);

                if ($insStmt->execute()) {

                  $response['success'] = 1;

                } else {

                  $response['success'] = 0;

                }

                $insStmt->close();

              }


            }

          }

      }

    }

  }

}


echo json_encode($response);

$con->close();

?>
