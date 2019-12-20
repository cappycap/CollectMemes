<?php

require 'db.php';
// Define response array for delivering status.
$response = array();
$check = false;

if (isset($_POST['userId']) and isset($_POST['pass']) and isset($_POST['spinsLeft'])) {

  if ($_POST['pass'] == "933kfjhga7862344bv") {

    $meme = array();

    $count = 0;

    if ((int)$_POST['spinsLeft'] == 10) {

      $meme['id'] = -1;
      $meme['title'] = "Spin to get started!";
      $meme['image'] = "file://start.png";
      $meme['top'] = "file://c-common-top.png";
      $meme['bot'] = "file://c-common-bot.png";

    } else {

      $userId = $con->real_escape_string($_POST['userId']);
      $spinsLeft = $con->real_escape_string($_POST['spinsLeft']);

      // update totalSpins and spinsLeft.
      $uTSQ = "UPDATE users SET totalSpins = totalSpins+1, lastSpinsLeft=? WHERE id=?";

      if ($uTSQS = $con->prepare($uTSQ)) {

        $uTSQS->bind_param("ii",$spinsLeft,$userId);

        $uTSQS->execute();

        $uTSQS->close();

      }

      while ($count < 1) {

        $rank = (int) generateRank($con);

        // Check if rank has already been claimed by user.
        $checkQ = "SELECT memeId FROM owns WHERE rank=? AND userId=?";

        if ($checkS = $con->prepare($checkQ)) {

          $checkS->bind_param("ii",$rank,$userId);

          $checkS->execute();

          $checkS->store_result();

          if ($checkS->num_rows == 0) {

            $check = true;

          }

          $checkS->free_result();

          $checkS->close();

        }

        if ($check) {

          // We good, query meme.
          $memeQuery = "SELECT id,title,image,creator FROM memes WHERE rank = ?";

          if ($stmt = $con->prepare($memeQuery)) {

            $stmt->bind_param("i",$rank);

            $stmt->execute();

            $stmt->bind_result($id,$title,$image,$creator);

            if ($stmt->fetch()) {

              $idString = (string) $id;

              $meme['id'] = $id;
              $meme['title'] = $title;
              $meme['image'] = $image;
              $meme['rank'] = $rank;
              $meme['creator'] = $creator;

              $count = $count + 1;

              $stmt->free_result();

              $stmt->close();

              $checkLike = "SELECT memeId FROM likes WHERE rank=? AND userId=?";

              if ($like = $con->prepare($checkLike)) {

                $like->bind_param("ii",$rank,$userId);

                $like->execute();

                $like->store_result();

                if ($like->num_rows == 0) {

                  $meme['liked'] = 0;

                } else {

                  $meme['liked'] = 1;

                }

                $like->free_result();

                $like->close();

              }

            } else {

              $stmt->free_result();

              $stmt->close();

            }

          }

        }

      }

    }

    if ($meme['id'] != -1) {

      $info = getRankInfo($meme['rank'], $con);

      $meme['color'] = $info['color'];
      $meme['rarity'] = $info['rarity'];
      $meme['top'] = $info['top'];
      $meme['bot'] = $info['bot'];
      $meme['fsize'] = $info['font-size-view'];

    }

    $response = $meme;

  }

}

echo json_encode($response);

$con->close();
?>
