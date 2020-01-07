<?php

require 'db.php';
// Define response array for delivering status.
$response = array();
$check = false;

function getMeme($userId, $con) {

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

function spinMessage($spinsLeft, $isCountdown, $scheme, $nextSpin) {

  $m = "";
  $color = "#111111";
  $background = "#ffffff";

  if ($scheme == "dark") {

    $color = "#ffffff";
    $background = "#111111";

  }

  if ($isCountdown) {

    $m = "<html><head><style>body{ background-color:" . $background . "; text-align:center; margin:0; padding:10px 0; color:" . $color . "; font-size:15px; }</style></head><body id='demo'></body>

    <script>
    var countDownDate = " . $nextSpin . ";
    var x = setInterval(function() {
      var now = new Date().getTime() / 1000;
      var distance = countDownDate - now;
      var minutes = Math.floor((distance % (60 * 60)) / (60));
      var seconds = Math.floor((distance % (60)));
      document.getElementById('demo').innerHTML = minutes + 'm ' + seconds + 's ';
      if (distance < 0) {
        clearInterval(x);
        document.getElementById('demo').innerHTML = 'EXPIRED';
      }
    }, 1000);
    </script>
    </html>";

  } else {

    $m = "<html><head><style>body{ background-color:" . $background . "; text-align:center; margin:0; padding:10px 0; color:#22a258; font-size:15px; }</style></head><body>" . $spinsLeft . " spins left</body></html>";

  }

  return $m;

}

function getAchievementEmma() {

  $emma = array();

  $options = array(
    array("image"=>"file://emma/laughing.png","quote"=>"Congrats, you got an achievement!"),
    array("image"=>"file://emma/surprised.png","quote"=>"Whoa! You unlocked an achievement!"),
    array("image"=>"file://emma/laughing.png","quote"=>"Yay, you unlocked a new achievement!")
  );

  $num = mt_rand(0,count($options));

  $emma['image'] = $options[$num]['image'];
  $emma['quote'] = $options[$num]['quote'];

  return $emma;

}

function updateAchievementsProgress($userId, $achievementId, $stage) {

  $ret = false;

  $q = "SELECT progress FROM achievementsProgress WHERE userId=?";

  if ($s = $con->prepare($q)) {

    $s->bind_param("i",$userId);

    $s->execute();

    $s->bind_result($progress);

    if ($s->fetch());

    $s->close();

    $p = explode(",",$progress);

    $p[$achievementId] = $stage;

    $new = implode(",",$p);

    $uQ = "UPDATE achievementsProgress SET progress=?, completed=completed+1 WHERE userId=?";

    if ($u = $con->prepare($uQ)) {

      $u->bind_param("si",$new,$userId);

      if ($s->execute()) {

        $ret = true;

      }

      $s->close();

    }

  }

  return $ret;

}

function checkAchievements($userId, $totalSpins, $con) {

  $achievement = array();

  // spin achievement check:
  $achievementId = -1;
  $stage = -1;

  if ($totalSpins == 100) {

    $achievementId = 1;
    $stage = 1;

  } else if ($totalSpins == 500) {

    $achievementId = 1;
    $stage = 2;

  } else if ($totalSpins == 1000) {

    $achievementId = 1;
    $stage = 3;

  }

  if ($achievementId == -1) {

    $achievement['status'] = 0;

  } else {

    $achievement['status'] = 1;

    $q = "SELECT image, title, reqs, xp FROM achievements WHERE achievementId=?, stage=?";

    if ($s = $con->prepare($q)) {

      $s->bind_param("ii",$achievementId,$stage);

      $s->execute();

      $s->bind_result($image,$title,$reqs,$xp);

      if ($s->fetch()) {

        $achievement['image'] = $image;
        $achievement['title'] = $title;
        $achievement['reqs'] = $reqs;
        $achievement['xp'] = "+" . number_format($xp) . " XP";

      }

      $s->close();

    }

    $emma = getAchievementEmma();

    $achievement['emmaImage'] = $emma['image'];
    $achievement['emmaQuote'] = $emma['quote'];

    $achievement['nextTemplate'] = "body";

    updateAchievementsProgress($userId, $achievementId, $stage);

  }

  return $achievement;

}

if (isset($_POST['userId']) and isset($_POST['pass']) and isset($_POST['scheme']) and isset($_POST['spin'])) {

  if ($_POST['pass'] == "933kfjhga7862344bv") {

    $cur = array();
    $achievement = array();

    $userId = $con->real_escape_string($_POST['userId']);
    $scheme = $_POST['scheme'];

    $passCur = json_decode($_POST['cur']);

    if ($_POST['spin'] == "true") {

      $response['update'] = 1;

      $q = "SELECT nextSpin, spinsLeft, totalSpins FROM users WHERE id=?";

      if ($s = $con->prepare($q)) {

        $s->bind_param("i",$userId);

        $s->execute();

        $s->bind_result($nextSpin, $spinsLeft, $totalSpins);

        if ($s->fetch()) {

          $s->close();

          $time = time();

          if ($nextSpin < $time and $spinsLeft != 0) {

            $newSpinsLeft = 0;
            $uQ = "";
            $nU = 0;

            if ($spinsLeft == 11) {

              $newSpinsLeft = 10;

              $cur['isMeme'] = "0";
              $cur['memeId'] = "0";
              $cur['title'] = "Spin to get started!";
              $cur['image'] = ($scheme == 'light') ? "file://collect/start-light.png" : "file://collect/start-dark.png";
              $cur['rarityLining'] = "file://shared/lining.png";
              $cur['spinStatus'] = 1;
              $cur['collectStatus'] = 1;

              $achievement['status'] = 0;

              $uQ = "UPDATE users SET spinsLeft=?,totalSpins=totalSpins+1 WHERE id=?";

            } else if ($spinsLeft == 1) {

              $newSpinsLeft = 0;

              $cur = getMeme($userId, $con);
              if ($cur['liked'] == 0) {
                $response['heart'] = ($scheme == "light") ? "file://collect/heart-empty-light.png" : "file://collect/heart-empty-dark.png";
              } else {
                $response['heart'] = "file://shared/heart-full.png";
              }
              $cur['spinStatus'] = 0;
              $cur['collectStatus'] = 1;
              $cur['spinMessage'] = spinMessage($newSpinsLeft, 1, $scheme, $nextSpin);

              $achievement = checkAchievements($userId, $totalSpins+1, $con);

              $uQ = "UPDATE users SET spinsLeft=?,totalSpins=totalSpins+1,nextSpin=? WHERE id=?";
              $nU = 1;

            } else {

              $newSpinsLeft = $spinsLeft - 1;

              $cur = getMeme($userId, $con);
              if ($cur['liked'] == 0) {
                $response['heart'] = ($scheme == "light") ? "file://collect/heart-empty-light.png" : "file://collect/heart-empty-dark.png";
              } else {
                $response['heart'] = "file://shared/heart-full.png";
              }
              $cur['spinStatus'] = 1;
              $cur['collectStatus'] = 1;
              $cur['spinMessage'] = spinMessage($newSpinsLeft, 0, $scheme, 0);

              $achievement = checkAchievements($userId, $totalSpins+1, $con);

              $uQ = "UPDATE users SET spinsLeft=?,totalSpins=totalSpins+1 WHERE id=?";

            }

            if ($u = $con->prepare($uQ)) {

              if ($nU) {

                $newNextSpin = time() + 1800;

                $u->bind_param("iii",$newSpinsLeft,$newNextSpin,$userId);

              } else {

                $u->bind_param("ii",$newSpinsLeft,$userId);

              }

              $u->execute();

              $u->close();

            }

          } else {

            $cur['isMeme'] = "0";
            $cur['memeId'] = "0";
            $cur['title'] = "Out of spins!";
            $cur['image'] = ($scheme == 'light') ? "file://collect/out-light.png" : "file://collect/out-dark.png";
            $cur['rarityLining'] = "file://shared/lining.png";
            $cur['spinStatus'] = 0;
            $cur['collectStatus'] = 0;
            $cur['spinMessage'] = spinMessage(0, 1, $scheme, $nextSpin);

            $achievement['status'] = 0;

          }

        }

      }

      $response['cur'] = $cur;
      $response['achievement'] = $achievement;

    } else {

      $response['update'] = 0;

    }

  }

}

echo json_encode($response, JSON_UNESCAPED_SLASHES);

$con->close();
?>