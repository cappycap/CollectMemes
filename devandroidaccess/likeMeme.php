<?php
// Goals of likeMeme:
// 1. Update memes table (1 row)
//    - Increase 'likes' by 1.
// 2. Update users table (1 row)
//    - Add 'memeId' to 'likes'.
//    - Increase 'likesSize' by 1.

require 'db.php';

function updateAchievementsProgress($userId, $achievementId, $stage, $con) {

  $ret = false;

  $q = "SELECT progress FROM achievementsProgress WHERE userId=?";

  if ($s = $con->prepare($q)) {

    $s->bind_param("i",$userId);

    $s->execute();

    $s->bind_result($progress);

    if ($s->fetch()) {

			$s->close();

	    $p = explode(",",$progress);

	    $p[$achievementId] = $stage;

	    $new = implode(",",$p);

	    $uQ = "UPDATE achievementsProgress SET progress=? WHERE userId=?";

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

function checkAchievements($userId, $likes, $con) {

  $achievement = array();

  $likes = intval($likes);

  $achievementId = -1;
	$stage = 0;

  if ($likes == 10) {

    $achievementId = 3;
    $stage = 1;

  } else if ($likes == 100) {

    $achievementId = 3;
    $stage = 2;

  } else if ($likes == 500) {

    $achievementId = 3;
    $stage = 3;

  }

  if ($achievementId == -1) {

    $achievement['status'] = 0;

  } else {

    $achievement['status'] = 1;

    $q = "SELECT xp FROM achievements WHERE achievementId=? AND stage=?";

    if ($s = $con->prepare($q)) {

      $s->bind_param("ii",$achievementId,$stage);

      $s->execute();

      $s->bind_result($xp);

      if ($s->fetch()) {

        $s->close();

        $updateXP = giveXP($userId, intval($xp), $con);

      }

    }

    $achievement['updated'] = updateAchievementsProgress($userId, $achievementId, $stage, $con);

  }

  return $achievement;
}

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['memeId'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $memeId = $con->real_escape_string($_POST['memeId']);
  $time = time();

  // Update meme.
  $queryUpdateMeme = "UPDATE memes SET likes = likes + 1 WHERE id=?";

  if ($stmtMeme = $con->prepare($queryUpdateMeme)) {

    $stmtMeme->bind_param("i",$memeId);

    if ($stmtMeme->execute()) {

      $response['updateMemeSuccess'] = 1;

    } else {

      $response['updateMemeSuccess'] = 0;
      $response['updateMemeError'] = $con->error;

    }

  }

  // Update likes table. First, let's grab some necessary info from DB.
  $memeInfoQuery = "SELECT rank,likes FROM memes WHERE id=?";

  if ($memeInfoStmt = $con->prepare($memeInfoQuery)) {

    $memeInfoStmt->bind_param("i",$memeId);

    $memeInfoStmt->execute();

    $memeInfoStmt->bind_result($memeRank,$memeLikes);

    if ($memeInfoStmt->fetch()) {

      $memeInfoStmt->close();

      $ins = "INSERT INTO likes (userId, memeId, dateAdded, rank, likes) VALUES (?, ?, ?, ?, ?)";

      if ($insStmt = $con->prepare($ins)) {

        $insStmt->bind_param("iiiii",$userId,$memeId,$time,$memeRank,$memeLikes);

        if ($insStmt->execute()) {

          $reponse['insLikeSuccess'] = 1;

        } else {

          $reponse['insLikeSuccess'] = 0;

        }

        $insStmt->close();

      }

    }

  }

  // Begin to update user. First, let's grab some necessary info from DB.
  $getQ = "SELECT likesSize FROM users WHERE id=?";

  if ($get = $con->prepare($getQ)) {

    $get->bind_param("i",$userId);

    $get->execute();

    $get->bind_result($oldSize);

    if ($get->fetch()) {

      $size = $oldSize + 1;

      $get->close();

      $response['achievement'] = checkAchievements($userId, $size, $con);

      $updateQ = "UPDATE users SET likesSize=? WHERE id=?";

      if ($uS = $con->prepare($updateQ)) {

        $uS->bind_param("ii",$size,$userId);

        $uS->execute();

        $uS->close();

      }

    }

  }

}

echo json_encode($response);

$con->close();
?>
