<?php

require 'db.php';
// Define response array for delivering status.
$response = array();
$check = false;

function getMeme($userId) {

  $meme = array();
  $count = 0;

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

          $meme['isMeme'] = 1;
          $meme['memeId'] = $id;
          $meme['title'] = $title;
          $meme['author'] = $creator;
          $meme['image'] = $image;
          $meme['rank'] = $rank;

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

  $info = getRankInfo($meme['rank'], $con);

  $meme['rarity'] = $info['rarity'];
  $meme['rarityColor'] = $info['rarityColor'];
  $meme['rarityImage'] = $info['rarityImage'];
  $meme['rarityLining'] = $info['rarityLining'];

  return $meme;

}

if (isset($_POST['userId']) and isset($_POST['pass'])) {

  if ($_POST['pass'] == "933kfjhga7862344bv") {

    $cur = array();
    $achievement = array();

    $userId = $con->real_escape_string($_POST['userId']);

    $q = "SELECT nextSpin, spinsLeft, totalSpins FROM users WHERE id=?";

    if ($s = $con->prepare($q)) {

      $s->bind_param("i",$userId);

      $s->execute();

      $s->bind_result($nextSpin, $spinsLeft, $totalSpins);

      if ($s->fetch()) {

        $s->close();

        $time = time();

        if ($nextSpin < $time) {

          $newSpinsLeft = 0;
          $uQ = "";

          if ($spinsLeft == 0) {

            $newSpinsLeft = 10;

            $cur['isMeme'] = "";
            $cur['memeId'] = "";
            $cur['title'] = "";
            $cur['image'] = ""; // need to make this image
            $cur['spinStatus'] = 1;
            $cur['collectStatus'] = 1;

            $uQ = "UPDATE users SET spinsLeft=? WHERE id=?";

          } else if ($spinsLeft == 1) {

            $newSpinsLeft = 0;

            $cur = getMeme($userId);
            $cur['spinStatus'] = 0;
            $cur['collectStatus'] = 1;

            $uQ = "UPDATE users SET spinsLeft=?,totalSpins=totalSpins+1 WHERE id=?";

          } else {

            $newSpinsLeft = $spinsLeft - 1;

            $cur = getMeme($userId);
            $cur['spinStatus'] = 1;
            $cur['collectStatus'] = 1;

            $uQ = "UPDATE users SET spinsLeft=?,totalSpins=totalSpins+1 WHERE id=?";

          }



          if ($u = $con->prepare($uQ)) {

            $u->bind_param("ii",$newSpinsLeft,$userId);

            $u->execute():

            $u->close();

          }

        } else {

          //

        }

      }

    }

  }

}

echo json_encode($response);

$con->close();
?>
