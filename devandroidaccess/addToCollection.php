<?php

require 'db.php';

function isMemePartofChallenges($m, $con) {

	$check = array();
	$check['collectionId'] = -1;

	$q = "SELECT id,memes,totalMemes FROM collections";

	if ($s = $con->prepare($q)) {

		$s->execute();

		$s->bind_param($id,$memesStr,$total);

		while ($s->fetch()) {

			$memes = explode(",",$memesStr);

			if (in_array($m,$memes)) {

				$check['collectionId'] = $id;
				$check['total'] = $total;

				$done = false;
				$c = 0;

				while (!$done) {

					if ($memes[$c] == $m) {

						$done = true;

						$check['index'] = $c;

					} else {

						$c++;

					}

				}

			}

		}

		$s->close();

	}

	return $check;

}

// returns 0, failed
// 				 1, user progress updated
// 				 2, user progress updated and challenge was completed
function updateUserProgress($c, $index, $total, $m, $u, $con) {

	$ret = 0;

	$q = "SELECT id,memes,totalOwned FROM collectionsProgress WHERE collectionId=? AND userId=?";

	if ($s = $con->prepare($q)) {

		$s->bind_param("ii",$c,$u);

		$s->execute();

		$s->bind_param($id,$memesStr,$to);

		if ($s->fetch()) {

			$s->close();

			$memes = explode(",",$memesStr);

			$memes[$index] = 1;

			$newMemes = implode(",",$memes);

			$newTO = $to + 1;

			$completed = ($total == $to) ? 1 : 0;

			$updateQ = "UPDATE collectionsProgress SET memes=?,totalOwned=?,completed=? WHERE collectionId=? AND userId=?";

			if ($uS = $con->prepare($updateQ)) {

				$uS->bind_param("siiii",$newMemes,$newTO,$completed,$c,$u);

				if ($uS->execute()) {

					$ret = ($completed) ? 2 : 1;

				}

				$uS->close();

			}

		}

	}

	return $ret;

}

// returns 0, meme is not part of challenge
// 				 1, meme is part of challenge, user progress updated
// 				 2, meme is part of challenge, user progress updated and challenge was completed
function updateChallenges($memeId, $userId, $con) {

	$ret = 0;

	$check = isMemePartofChallenges($memeId, $con);

	if ($check['collectionId'] != -1) {

		$ret = updateUserProgress($check['collectionId'], $check['index'], $check['total'], $memeId, $userId, $con);

	}

	return $ret;

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

    $uQ = "UPDATE achievementsProgress SET progress=?, totalCompleted=totalCompleted+1 WHERE userId=?";

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

// achievements to check:
// challenge 1,5,10 | collect 1,10,100 | rarities 1,10
function checkAchievements($userId, $memeId, $collectionSize, $rarity, $rarityCount, $challengesUpdated, $con) {

	$achievement = array();

  $achievementId = -1;
	$stage = 0;

	// challenge achievement checks
	if ($challengesUpdated == 2) {

		$checkQ = "SELECT id FROM collectionsProgress WHERE userId=" . $userId . " AND completed=1";

		if ($result = $con->query($checkQ)) {

			$row_cnt = $result->num_rows;

			if ($row_cnt == 1) {

				$achievementId = 2;
				$stage = 1;

			} else if ($row_cnt == 5) {

				$achievementId = 2;
				$stage = 2;

			} else if ($row_cnt == 10) {

				$achievementId = 2;
				$stage = 3;

			}

			$result->close();

		}

	}

	// collect achievement checks
	if ($achievementId == -1) {

		if ($collectionSize == 1) {

			$achievementId = 0;
			$stage = 1;

		} else if ($collectionSize == 10) {

			$achievementId = 0;
			$stage = 2;

		} else if ($collectionSize == 100) {

			$achievementId = 0;
			$stage = 3;

		}

	}

	// rarityCount achievement checks
	if ($achievementId == -1) {

		if ($rarityCount == 1) {

			if ($rarity == "common") {

				$achievementId = 9;
				$stage = 1;

			} else if ($rarity == "uncommon") {

				$achievementId = 10;
				$stage = 1;

			} else if ($rarity == "rare") {

				$achievementId = 11;
				$stage = 1;

			} else if ($rarity == "epic") {

				$achievementId = 12;
				$stage = 1;

			} else if ($rarity == "legendary") {

				$achievementId = 13;
				$stage = 1;

			}

		} else if ($rarityCount == 10) {

			if ($rarity == "common") {

				$achievementId = 9;
				$stage = 2;

			} else if ($rarity == "uncommon") {

				$achievementId = 10;
				$stage = 2;

			} else if ($rarity == "rare") {

				$achievementId = 11;
				$stage = 2;

			} else if ($rarity == "epic") {

				$achievementId = 12;
				$stage = 2;

			} else if ($rarity == "legendary") {

				$achievementId = 13;
				$stage = 2;

			}

		}

	}

	if ($achievementId == -1) {

    $achievement['status'] = 0;

  } else {

    $achievement['status'] = 1;

    $q = "SELECT image, title, reqs, xp FROM achievements WHERE achievementId=?, stage=?";

    if ($s = $con->prepare($q)) {

      $s->bind_param("ii", $achievementId, $stage);

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

    $achievement['nextTemplate'] = "memeCollected";

    $achievement['progressUpdated'] = updateAchievementsProgress($userId, $achievementId, $stage);

  }

  return $achievement;

}

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['memeId'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $memeId = $con->real_escape_string($_POST['memeId']);

  $time = time();

  $achievement = array();

	$check = false;

	$checkQ = "SELECT id FROM owns WHERE userId=" . $userId . " AND memeId=" . $memeId;

	if ($result = $con->query($checkQ)) {

		$row_cnt = $result->num_rows;

		if ($row_cnt == 0) {

			$check = true;

		}

		$result->close();

	}

	if ($check) {

		$queryMemeInfo = "SELECT rank,totalOwned,dateAdded FROM memes WHERE id=?";

	  if ($stmtMeme = $con->prepare($queryMemeInfo)) {

	    $stmtMeme->bind_param("i",$memeId);

	    $stmtMeme->execute();

	    $stmtMeme->bind_result($rank,$totalOwned,$dateAdded);

	    if ($stmtMeme->fetch()) {

	      $newTotalOwned = $totalOwned + 1;

	      $response['rank'] = $rank;

	      $stmtMeme->close();

	      $queryUpdateMeme = "UPDATE memes SET totalOwned=? WHERE id=?";

	      if ($stmtUpdateMeme = $con->prepare($queryUpdateMeme)) {

	        $stmtUpdateMeme->bind_param("ii",$newTotalOwned,$memeId);

	        $stmtUpdateMeme->execute();

	        $response['successMeme'] = 1;

	        $stmtUpdateMeme->close();

	      } else {

	        $response['successMeme'] = $con->error;

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

		$info = getRankInfo($response['rank'], $con);

		$rarity = strtolower($info['rarity']);

	  $queryUserInfo = "SELECT " . $rarity . "Count,collectionSize,collectionSum,isPro,nextSpin FROM users WHERE id=?";

	  if ($stmtUser = $con->prepare($queryUserInfo)) {

	    $stmtUser->bind_param("i",$userId);

	    $stmtUser->execute();

	    $stmtUser->bind_result($rarityCount,$collectionSize,$collectionSum,$isPro,$nextSpin);

	    if ($stmtUser->fetch()) {

				$response['rarityCount'] = $rarityCount + 1;

	      $newCollectionSize = intval($collectionSize) + 1;
	      $newCollectionSum = intval($collectionSum) + intval($response['rank']);

	      if ($collectionSize == 0) {

	        $newUserAvgRank = $response['rank'];

	      } else {

	        $newUserAvgRank = $newCollectionSum / $newCollectionSize;

	      }

	      $stmtUser->close();

				$time2 = 0;

	      if (!$isPro) {

	        $time2 = time() + 3600;

	      } else {

	        $time2 = time() + 1800;

	      }

	      $queryUpdateUser = "UPDATE users SET " . $rarity . "Count=" . $rarity . "Count+1, avgRank=?, collectionSize=?, collectionSum=?, nextSpin=?, spinsLeft=0 WHERE id=?";

	      $response['collectionSize'] = $newCollectionSize;

	      if ($stmtUpdateUser = $con->prepare($queryUpdateUser)) {

	        $stmtUpdateUser->bind_param("iiiii",$newUserAvgRank,$newCollectionSize,$newCollectionSum,$time2,$userId);

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

		// See if user completed any challenges.
	  $challengesUpdated = updateChallenges($memeId, $userId, $con);

	  // RUn achievement checks. If user achieved something, return that info to app.
	  $achievement = checkAchievements($userId, $memeId, $response['collectionSize'], $rarity, $response['rarityCount'], $challengesUpdated, $con);

	  if ($achievement['status'] == 1) {

			$response['nextTemplate'] = "achievement";

	  } else {

			$response['nextTemplate'] = "memeCollected";

	  }

		$response['achievement'] = $achievement;

	} else {

		$response['nextTemplate'] = "memeCollected";

	}


} else {

  $response['success'] = "0-0";

}

echo json_encode($response);

$con->close();
?>