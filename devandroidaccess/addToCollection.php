<?php

require 'db.php';

function isMemePartofChallenges($m, $con) {

	$check = array();
	$check['collectionId'] = -1;

	$q = "SELECT id,memes,totalMemes,xpReward FROM collections";

	if ($s = $con->prepare($q)) {

		$s->execute();

		$s->bind_result($id,$memesStr,$total,$xp);

		while ($s->fetch()) {

			$memes = explode(",",$memesStr);

			if (in_array($m,$memes)) {

				$check['collectionId'] = $id;
				$check['total'] = $total;
				$check['xpReward'] = $xp;

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
function updateUserProgress($c, $index, $total, $u, $con) {

	$ret = 0;

	$q = "SELECT memes,totalOwned FROM collectionsProgress WHERE collectionId=? AND userId=?";

	if ($s = $con->prepare($q)) {

		$s->bind_param("ii",$c,$u);

		$s->execute();

		$s->bind_result($memesStr,$to);

		if ($s->fetch()) {

			$memes = explode(",",$memesStr);

			$i = intval($index);

			$memes[$i] = 1;

			$new = implode(",",$memes);

			$newTO = $to + 1;

			$completed = ($total == $to) ? 1 : 0;

			$updateQ = "UPDATE collectionsProgress SET memes=?,totalOwned=?,completed=? WHERE collectionId=? AND userId=?";

			$s->close();

			if ($uS = $con->prepare($updateQ)) {

				$uS->bind_param("siiii",$new,$newTO,$completed,$c,$u);

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

		$ret = updateUserProgress($check['collectionId'], $check['index'], $check['total'], $userId, $con);

		if ($ret == 2) {

			$updateXP = giveXP($userId, intval($check['xpReward']), $con);

		}

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

    $q = "SELECT image, title, reqs, xp FROM achievements WHERE achievementId=? AND stage=?";

    if ($s = $con->prepare($q)) {

      $s->bind_param("ii", $achievementId, $stage);

      $s->execute();

      $s->bind_result($image,$title,$reqs,$xp);

      if ($s->fetch()) {

        $achievement['image'] = $image;
        $achievement['title'] = $title;
        $achievement['reqs'] = $reqs;
        $achievement['xp'] = "+" . number_format($xp) . " XP";

				$s->close();

				$updateXP = giveXP($userId, intval($xp), $con);

      }

    }

    $emma = getAchievementEmma();

    $achievement['emmaImage'] = $emma['image'];
    $achievement['emmaQuote'] = $emma['quote'];

    $achievement['exitTemplate'] = "memeCollected";

    $achievement['progressUpdated'] = updateAchievementsProgress($userId, $achievementId, $stage, $con);

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

	        $time2 = $time + 1800;

	      } else {

	        $time2 = $time + 900;

	      }

	      $queryUpdateUser = "UPDATE users SET " . $rarity . "Count=" . $rarity . "Count+1, avgRank=?, collectionSize=?, collectionSum=?, nextSpin=?, lastCollect=?, spinsLeft=0 WHERE id=?";

	      $response['collectionSize'] = $newCollectionSize;

	      if ($stmtUpdateUser = $con->prepare($queryUpdateUser)) {

	        $stmtUpdateUser->bind_param("iiiiii",$newUserAvgRank,$newCollectionSize,$newCollectionSum,$time2,$time,$userId);

	        $stmtUpdateUser->execute();

	        $response['successUser'] = 1;

	        $stmtUpdateUser->close();

	      } else {

	        $response['successUser'] = $con->error;

	      }

	    } else {

	      $response['successUser'] = "0-2";

	    }

	  } else {

	    $response['successUser'] = "0-1";

	  }

		// See if user completed any challenges.
	  $challengesUpdated = updateChallenges($memeId, $userId, $con);

		$response['challengesUpdated'] = $challengesUpdated;

	  // RUn achievement checks. If user achieved something, return that info to app.
	  $achievement = checkAchievements($userId, $memeId, $response['collectionSize'], $rarity, $response['rarityCount'], $challengesUpdated, $con);

	  if ($achievement['status'] == 1) {

			$achievement['nextTemplate'] = "achievement";

	  } else {

			$achievement['nextTemplate'] = "memeCollected";

	  }

		$response['achievement'] = $achievement;

	} else {

		$achievement['nextTemplate'] = "memeCollected";

	}


} else {

  $response['success'] = "0-0";

}

echo json_encode($response);

$con->close();
?>
