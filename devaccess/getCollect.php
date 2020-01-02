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

function spinMessage($spinsLeft, $isCountdown) {

  $m = "";

  if ($isCountdown) {

    // js countdown

  } else {

    // spins left

  }

  return $m;

}

function checkAchievements($userId, $totalSpins) {

  $achievement = array();

  // spin achievement check:
  $achievementId = -1;
  $stage = -1;

  if ($totalSpins == 100) {

    $achievemntId = 1;
    $stage = 1;

  } else if ($totalSpins == 500) {


  } else if ($totalSpins == 1000) {


  }

  if ($achievementId == -1) {

    $achievement['status'] = 0;

  } else {

    $achievement['status'] = 1;


    updateAchievementsProgress($userId, $achievementId, $stage);

  }

  return $achievement;
  
}

if (isset($_POST['userId']) and isset($_POST['pass']) and isset($_POST['scheme'])) {

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
          $nU = 0;

          if ($spinsLeft == 0) {

            $newSpinsLeft = 10;

            $cur['isMeme'] = "0";
            $cur['memeId'] = "0";
            $cur['title'] = "Spin to get started!";
            $cur['image'] = ($_POST['scheme'] == 'light') ? "file://collect/start-light.png" : "file://collect/start-dark.png";
            $cur['spinStatus'] = 1;
            $cur['collectStatus'] = 1;

            $achievement['status'] = 0;

            $uQ = "UPDATE users SET spinsLeft=? WHERE id=?";

          } else if ($spinsLeft == 1) {

            $newSpinsLeft = 0;

            $cur = getMeme($userId);
            $cur['spinStatus'] = 0;
            $cur['collectStatus'] = 1;
            $cur['spinMessage'] = spinMessage($newSpinsLeft, 0);

            $achievement = checkAchievements($totalSpins+1);

            $uQ = "UPDATE users SET spinsLeft=?,totalSpins=totalSpins+1,nextSpin=? WHERE id=?";
            $nU = 1;

          } else {

            $newSpinsLeft = $spinsLeft - 1;

            $cur = getMeme($userId);
            $cur['spinStatus'] = 1;
            $cur['collectStatus'] = 1;
            $cur['spinMessage'] = spinMessage($newSpinsLeft, 0);

            $achievement = checkAchievements($totalSpins+1);

            $uQ = "UPDATE users SET spinsLeft=?,totalSpins=totalSpins+1 WHERE id=?";

          }

          if ($u = $con->prepare($uQ)) {

            if ($nU) {

              $newNextSpin = time() + 1800;

              $u->bind_param("iii",$newSpinsLeft,$newNextSpin,$userId);

            } else {

              $u->bind_param("ii",$newSpinsLeft,$userId);

            }

            $u->execute():

            $u->close();

          }

        } else {

          $cur['isMeme'] = "0";
          $cur['memeId'] = "0";
          $cur['title'] = "Out of spins!";
          $cur['image'] = ($_POST['scheme'] == 'light') ? "file://collect/out-light.png" : "file://collect/out-dark.png";
          $cur['spinStatus'] = 0;
          $cur['collectStatus'] = 0;
          $cur['spinMessage'] = spinMessage(0, 1);

          $achievement['status'] = 0;

        }

      }

    }

    $response['cur'] = $cur;
    $response['achievement'] = $achievement;

  }

}

echo json_encode($response);

$con->close();
?>
