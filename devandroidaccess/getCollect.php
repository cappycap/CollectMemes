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

          $memeWidth = intval(0.78 * $_POST['screenWidth']);

          list($width, $height) = getimagesize($image);

          if ($width < $height) {

            $meme['height'] = intval($memeWidth);
            $meme['heightdiv2'] = intval($memeWidth/2);

          } else {

            $ratio = $memeWidth / $width;

            $newHeight = $ratio * $height;

            $meme['height'] = intval($newHeight);
            $meme['heightdiv2'] = intval($memeWidth/2);

          }

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

function spinMessage($spinsLeft, $isCountdown, $scheme, $nextSpin, $isLast) {

  $m = "";
  $color = "#111111";
  $background = "#ffffff";

  if ($scheme == "dark") {

    $color = "#ffffff";
    $background = "#111111";

  }

  $out = ($isLast) ? "Out of spins!" : "Spin to refresh!";

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
        document.getElementById('demo').innerHTML = '" . $out . "';
      }
    }, 1000);
    </script>
    </html>";

  } else {

    $spinsLeftText = strval($spinsLeft);
    $spinsLeftText .= ($spinsLeft == 1) ? " spin" : " spins";

    $m = "<html><head><style>body{ background-color:" . $background . "; text-align:center; margin:0; padding:10px 0; color:#22a258; font-size:15px; }</style></head><body>" . $spinsLeftText . " left</body></html>";

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

  $top = count($options) - 1;

  $num = mt_rand(0,$top);

  $emma['image'] = $options[$num]['image'];
  $emma['quote'] = $options[$num]['quote'];

  return $emma;

}

function updateAchievementsProgress($userId, $achievementId, $stage, $con) {

  $ret = false;

  $q = "SELECT progress FROM achievementsProgress WHERE userId=?";

  if ($s = $con->prepare($q)) {

    $s->bind_param("i",$userId);

    $s->execute();

    $s->bind_result($progress);

    if ($s->fetch()) {

      $p = explode(",",$progress);

      $p[$achievementId] = $stage;

      $new = implode(",",$p);

      $uQ = "UPDATE achievementsProgress SET progress=? WHERE userId=?";

      $s->close();

      if ($u = $con->prepare($uQ)) {

        $u->bind_param("si",$new,$userId);

        if ($u->execute()) {

          $ret = true;

        }

        $u->close();

      }

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

    $q = "SELECT image, title, reqs, xp FROM achievements WHERE achievementId=? AND stage=?";

    if ($s = $con->prepare($q)) {

      $s->bind_param("ii",$achievementId,$stage);

      $s->execute();

      $s->bind_result($image,$title,$reqs,$xp);

      if ($s->fetch()) {

        $s->close();

        $achievement['image'] = $image;
        $achievement['title'] = $title;
        $achievement['reqs'] = $reqs;
        $achievement['xp'] = "+" . number_format($xp) . " XP";

        $updateXP = giveXP($userId, intval($xp), $con);

      }

    }

    $emma = getAchievementEmma();

    $achievement['emmaImage'] = $emma['image'];
    $achievement['emmaQuote'] = $emma['quote'];

    $achievement['exitTemplate'] = "body";

    $achievement['update'] = updateAchievementsProgress($userId, $achievementId, $stage, $con);

  }

  return $achievement;

}

if (isset($_POST['userId']) and isset($_POST['pass']) and isset($_POST['scheme']) and isset($_POST['spin']) and isset($_POST['screenWidth'])) {

  if ($_POST['pass'] == "933kfjhga7862344bv") {

    $pro = 0;

    $cur = array();
    $achievement = array();

    $userId = $con->real_escape_string($_POST['userId']);
    $scheme = $_POST['scheme'];

    if ($_POST['spin'] == "true") {

      $response['update'] = 1;

      $q = "SELECT nextSpin, spinsLeft, totalSpins, isPro FROM users WHERE id=?";

      if ($s = $con->prepare($q)) {

        $s->bind_param("i",$userId);

        $s->execute();

        $s->bind_result($nextSpin, $spinsLeft, $totalSpins, $isPro);

        if ($s->fetch()) {

          $pro = $isPro;
          $s->close();

          $time = time();

          if ($nextSpin < $time) {

            $newSpinsLeft = 0;
            $uQ = "";
            $nU = 0;

            if ($spinsLeft == 11 or $spinsLeft == 0) {

              $newSpinsLeft = 10;

              $cur['isMeme'] = "0";
              $cur['memeId'] = "0";
              $cur['title'] = "Spin to get started!";
              $cur['image'] = ($scheme == 'light') ? "file://collect/start-light.png" : "file://collect/start-dark.png";
              $cur['rarityLining'] = "file://shared/lining.png";
              $cur['spinStatus'] = 1;
              $cur['collectStatus'] = 1;

              $cur['spinMessage'] = spinMessage($newSpinsLeft, 0, $scheme, 0, 0);

              $memeWidth = intval(0.84 * $_POST['screenWidth']);

              $cur['height'] = intval($memeWidth);
              $cur['heightdiv2'] = intval($memeWidth/2);

              $achievement['status'] = 0;

              $uQ = "UPDATE users SET spinsLeft=?,totalSpins=totalSpins WHERE id=?";

            } else if ($spinsLeft == 1) {

              $updateXP = giveXP($userId, 20, $con);

              $newSpinsLeft = 0;

              $cur = getMeme($userId, $con);
              if ($cur['liked'] == 0) {
                $response['heart'] = ($scheme == "light") ? "file://collect/heart-empty-light.png" : "file://collect/heart-empty-dark.png";
              } else {
                $response['heart'] = "file://shared/heart-full.png";
              }
              $cur['spinStatus'] = 0;
              $cur['collectStatus'] = 1;
              $cur['spinMessage'] = spinMessage($newSpinsLeft, 1, $scheme, $nextSpin, 1);

              $achievement = checkAchievements($userId, $totalSpins+1, $con);

              $uQ = "UPDATE users SET spinsLeft=?,totalSpins=totalSpins+1,nextSpin=? WHERE id=?";
              $nU = 1;

            } else {

              $updateXP = giveXP($userId, 20, $con);

              $newSpinsLeft = $spinsLeft - 1;

              $cur = getMeme($userId, $con);
              if ($cur['liked'] == 0) {
                $response['heart'] = ($scheme == "light") ? "file://collect/heart-empty-light.png" : "file://collect/heart-empty-dark.png";
              } else {
                $response['heart'] = "file://shared/heart-full.png";
              }
              $cur['spinStatus'] = 1;
              $cur['collectStatus'] = 1;
              $cur['spinMessage'] = spinMessage($newSpinsLeft, 0, $scheme, 0, 0);

              $achievement = checkAchievements($userId, $totalSpins+1, $con);

              $uQ = "UPDATE users SET spinsLeft=?,totalSpins=totalSpins+1 WHERE id=?";

            }

            if ($u = $con->prepare($uQ)) {

              if ($nU) {

                $n = ($pro) ? 900 : 1800;

                $newNextSpin = time() + $n;

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
            $cur['image'] = ($scheme == 'light') ? "file://collect/timeout-light.png" : "file://collect/timeout-dark.png";
            $cur['rarityLining'] = "file://shared/lining.png";
            $cur['spinStatus'] = 0;
            $cur['collectStatus'] = 0;
            $cur['spinMessage'] = spinMessage(0, 1, $scheme, $nextSpin, 0);

            $memeWidth = intval(0.80 * $_POST['screenWidth']);

            $cur['height'] = intval($memeWidth);
            $cur['heightdiv2'] = intval($memeWidth/2);

            $achievement['status'] = 0;

          }

        }

      }

      $cur['challengesLeft'] = ($scheme == 'light') ? "file://nav/challenges-left-light.png" : "file://nav/challenges-left-dark.png";
      $cur['profileRight'] = ($scheme == 'light') ? "file://nav/profile-right-light.png" : "file://nav/profile-right-dark.png";

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
