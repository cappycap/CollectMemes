<?php

require 'db.php';

function isMemePartofChallenges($m, $con) {

	$check = array();
	$check['id'] = -1;

	$q = "SELECT id,title,memes FROM collections";

	if ($s = $con->prepare($q)) {

		$s->execute();

		$s->bind_param($id,$title,$memesStr);

		while ($s->fetch()) {

			$memes = explode(",",$memesStr);

			if (in_array($m,$memes)) {

				$check['id'] = $id;
				$check['title'] = $title;

			}

		}

		$s->close();

	}

	return $check;

}

function updateUserProgress($c, $m, $u, $con) {

	$ret = 0;

	$q = "SELECT id,memes,totalOwned FROM collectionsProgress WHERE collectionId=? AND userId=?";

	if ($s = $con->prepare($q)) {

		$s->bind_param("ii",$c,$u);

		$s->execute();

		$s->bind_param($id,$memesStr,$to);

		if ($s->fetch()) {

			$memes = explode(",",$memesStr);

			if (!in_array($m,$memes)) {

				$updateQ = "UPDATE collections SET memes=?,totalOwned=? WHERE id=?";

				$newMemes = "";
				if ($to == 0) {

					$newMemes = $m;

				} else {

					$newMemes = $memesStr . "," . $m;

				}

				$newTO = $to + 1;

				if ($uS = $con->prepare($updateQ)) {

					$uS->bind_param("sii",$newMemes,$newTO,$id);

					if ($uS->execute()) {

						$ret = 1;

					}

				}

			}

		}

		$s->close();

	}

}
function updateChallenges($memeId, $userId, $con) {

	$ret = "Check your Vault.";

	$m = $con->real_escape_string($memeId);
	$u = $con->real_escape_string($userId);

	$check = isMemePartofChallenges($m, $con);

	if ($check['id'] != -1) {

		if (updateUserProgress($check['id'], $m, $u, $con)) {

			$ret = "Challenge updated: " . $check['title'];

		}

	}

}

// Function for updating user's achievementProgress if necessary.
function achievementCheck($u, $m, $s) {

  $updated = 0;

  return $updated;

}
// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['memeId'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $memeId = $con->real_escape_string($_POST['memeId']);
  $time = time();

  $achieve = array();

  $queryMemeInfo = "SELECT rank,totalOwned,dateAdded FROM memes WHERE id=?";

  if ($stmtMeme = $con->prepare($queryMemeInfo)) {

    $stmtMeme->bind_param("i",$memeId);

    $stmtMeme->execute();

    $stmtMeme->bind_result($rank,$totalOwned,$dateAdded);

    if ($stmtMeme->fetch()) {

      $newTotalOwned = $totalOwned + 1;
      $userIdStr = (string) $userId;

      $response['rank'] = $rank;
      $stmtMeme->close();

      $queryUpdateMeme = "UPDATE memes SET totalOwned=? WHERE id=?";

      if ($stmtUpdateMeme = $con->prepare($queryUpdateMeme)) {

        $stmtUpdateMeme->bind_param("ii",$newTotalOwned,$memeId);

        $stmtUpdateMeme->execute();

        $response['successMeme'] = 1;
        $stmtUpdateMeme->close();

      } else {

        $response['successMeme'] = "0-2";
        echo $con->error;

      }

      $ins = "INSERT INTO owns (userId, memeId, dateAdded, rank) VALUES (?, ?, ?, ?)";

      if ($insStmt = $con->prepare($ins)) {

        $insStmt->bind_param("iiii",$userId,$memeId,$time,$rank);

        if ($insStmt->execute()) {

          $reponse['insOwnSuccess'] = 1;

        } else {

          $reponse['insOwnSuccess'] = 0;

        }

        $insStmt->close();

      }

    } else {

      $response['successMeme'] = "0-1";

    }

  } else {

    $response['successMeme'] = "0-0";

  }

  $queryUserInfo = "SELECT collectionSize,collectionSum FROM users WHERE id=?";

  if ($stmtUser = $con->prepare($queryUserInfo)) {

    $stmtUser->bind_param("i",$userId);

    $stmtUser->execute();

    $stmtUser->bind_result($collectionSize,$collectionSum);

    if ($stmtUser->fetch()) {

      $newCollectionSize = intval($collectionSize) + 1;
      $newCollectionSum = intval($collectionSum) + intval($response['rank']);

      if ($collectionSize == 0) {

        $newUserAvgRank = $response['rank'];

      } else {

        $newUserAvgRank = $newCollectionSum / $newCollectionSize;

      }

      $stmtUser->close();

      $queryUpdateUser = "UPDATE users SET avgRank=?, collectionSize=?, collectionSum=? WHERE id=?";

      $achieve['collectionSize'] = $newCollectionSize;

      if ($stmtUpdateUser = $con->prepare($queryUpdateUser)) {

        $stmtUpdateUser->bind_param("iiii",$newUserAvgRank,$newCollectionSize,$newCollectionSum,$userId);

        $stmtUpdateUser->execute();

        $response['successUser'] = 1;

        $stmtUpdateUser->close();

      } else {

        $response['successUser'] = "0-3";

      }

    } else {

      $response['successUser'] = "0-2";

    }

  } else {

    $response['successUser'] = "0-1";

  }

  // RUn achievement checks. If user achieved something, return that info to app.
  $achievementId = achievementCheck($userId, $memeId, $achieve['collectionSize']);

  if ($achievementId != -1) {

    $response['achievement'] = $achievementId;

    $achieveQuery = "SELECT title,image,reqs,xp FROM achievements WHERE id=?";

    if ($ach = $con->prepare($achieveQuery)) {

      $ach->bind_param("i",$achievementId);

      $ach->execute();

      $ach->bind_result($achTitle,$achImage,$achReqs,$achXP);

      if ($ach->fetch()) {

        $response['achievementTitle'] = $achTitle;
        $response['achievementImage'] = $achImage;
        $response['achievementReqs'] = $achReqs;
        $response['achievementXP'] = "+" . number_format($achXP) . " XP";

      }

      $ach->close();

    }

  } else {

    $response['achievement'] = -1;

  }

  // See if user completed any challenges.
  $response['bannerDescription'] = updateChallenges($memeId, $userId, $con);

} else {

  $response['success'] = "0-0";

}

echo json_encode($response);

$con->close();
?>
