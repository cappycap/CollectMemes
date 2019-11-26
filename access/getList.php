<?php

require 'db.php';
// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['pass'])) {

  if ($_POST['pass'] == "933kfjhga7862344bv") {

    $userId = $con->real_escape_string($_POST['userId']);

    $collection = "";

    $getCollectionQuery = "SELECT collection FROM users WHERE id = ?";

    if ($gcs = $con->prepare($getCollectionQuery)) {

      $gcs->bind_param("i",$userId);

      $gcs->execute();

      $gcs->bind_result($ret);

      if ($gcs->fetch()) {

        $collection = $ret;

      }

      $gcs->free_result();

      $gcs->close();

    }

    $list = array();

    $count = 0;

    while ($count < 10) {

      $rank = (int) generateRank($con);

      $memeQuery = "SELECT id,title,image,source,creator FROM memes WHERE rank = ?";

      if ($stmt = $con->prepare($memeQuery)) {

        $stmt->bind_param("i",$rank);

        $stmt->execute();

        $stmt->bind_result($id,$title,$image,$source,$creator);

        if ($stmt->fetch()) {

          $idString = (string) $id;

          $check = false;

          if (empty($collection)) {

            $check = true;

          } else {

            if (strpos($collection,$idString) !== true) {

              $check = true;

            }

          }

          if ($check) {

            $meme = array();

            $meme['id'] = $id;
            $meme['title'] = $title;
            $meme['image'] = $image;
            $meme['rank'] = $rank;
            $meme['source'] = $source;
            $meme['creator'] = $creator;

            $list[$count] = $meme;

            $count = $count + 1;

          }

        }

        $stmt->free_result();

        $stmt->close();

      }

    }

    $response = $list;

  }

}

echo json_encode($response);

$con->close();
?>
