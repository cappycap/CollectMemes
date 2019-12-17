<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

// Update 'rating' in DB for specified meme id.
function updateRating($id, $owns, $likes, $score, $oldRating, $con) {

	if ($oldRating == ($owns + $likes + $score)) {

		return 1;

	} else {

		$rating = ($owns + $likes + $score);

		$q = "UPDATE memes SET rating=? WHERE id=?";

		if ($stmt = $con->prepare($q)) {

			$stmt->bind_param("ii",$rating,$id);

			if ($stmt->execute()) {

				$stmt->close();
				return 1;

			} else {

				$stmt->close();
				return 0;

			}

		}

	}

}

// Update meme rank in memes, owns, and likes
function updateRank($id, $rank, $con) {

	$q = "UPDATE memes SET rank=? WHERE id=?";

	if ($stmt = $con->prepare($q)) {

		$stmt->bind_param("ii",$rank,$id);

		if ($stmt->execute()) {

			$stmt->close();

			$q2 = "UPDATE owns SET rank=? WHERE memeId=?";

			if ($s2 = $con->prepare($q2)) {

				$s2->bind_param("ii",$rank,$id);

				if ($s2->execute()) {

					$s2->close();

					$q3 = "UPDATE likes SET rank=? WHERE memeId=?";

					if ($s3 = $con->prepare($q3)) {

						$s3->bind_param("ii",$rank,$id);

						if ($s3->execute()) {

							$s3->close();
							return 1;

						}

					}

				} else {

					$s2->close();
					return 0;

				}

			}

		} else {

			$stmt->close();
			return 0;

		}

	}

}

// loop through and rate entire database
function rateDb($con) {

	$q = "SELECT id, totalOwned, likes, score, rating FROM memes ORDER BY id ASC";

	if ($stmt = $con->prepare($q)) {

		$stmt->execute();

		$stmt->bind_result($id, $owns, $likes, $score, $oldRating);

    $memes = array();

		while ($stmt->fetch()) {

      $meme = array();

      $meme['id'] = $id;
			$meme['owns'] = $owns;
      $meme['likes'] = $likes;
      $meme['score'] = $score;
      $meme['oldRating'] = $oldRating;

      $memes[] = $meme;

		}

    $stmt->free_result();

		$stmt->close();

    $check = array();

    foreach($memes as $meme) {

      $check[] = updateRating($meme['id'], $meme['owns'], $meme['likes'], $meme['score'], $meme['oldRating'], $con);

    }

    return !in_array(0, $check);

	}

}

function updateUser($id, $con) {

	$newSum = 0;
	$cQ = "SELECT collectionSize FROM users WHERE id=?";

	if ($c = $con->prepare($cQ)) {

		$c->bind_param("i",$id);

		$c->execute();

		$c->bind_result($size);

		if ($c->fetch()) {

			$c->close();

			$qQ = "SELECT rank FROM owns WHERE userId=?";

			if ($q = $con->prepare($qQ)) {

				$q->bind_param("i",$id);

				$q->execute();

				$q->bind_result($rank);

				while ($q->fetch()) {

					$newSum = $newSum + (int)$rank;

				}

				$q->close();

				$uQ = "UPDATE users SET collectionSum=?,avgRank=? WHERE id=?";

				if ($u = $con->prepare($uQ)) {

					$avgRank = $newSum / $size;

					$u->bind_param("iii",$newSum,$avgRank,$id);

					if ($u->execute()) {

						$u->close();
						return 1;

					} else {

						$u->close();
						return 0;

					}

				}

			}

		} else {

			return 0;

		}

	}

}

function updateUsers($con) {

	$qQ = "SELECT id FROM users";

	if ($q = $con->prepare($qQ)) {

		$q->execute();

		$q->bind_result($id);

		$users = array();

		while ($q->fetch()) {

			$user = array();

			$user['id'] = $id;

			$users[] = $user;

		}

		$q->close();

		$check = array();

		foreach ($users as $user) {

			$check[] = updateUser($user['id'], $con);

		}

		return !in_array(0, $check);

	} else {

		return 0;

	}

}
// apply 1 - num_rows for rank to each meme
function rankDb($con) {

	$rateSuccess = rateDb($con);

  if ($rateSuccess) {

  	$q = "SELECT id FROM memes ORDER BY rating DESC";

  	if ($stmt = $con->prepare($q)) {

  		$stmt->execute();

  		$stmt->bind_result($id);

  		$i = 1;

      $memes = array();

  		while ($stmt->fetch()) {

        $meme = array();

        $meme['id'] = $id;
        $meme['rank'] = $i;

        $memes[] = $meme;

        $i = $i + 1;

  		}

      $stmt->free_result();

  		$stmt->close();

      $check = array();

      foreach ($memes as $meme) {

        $check[] = updateRank($meme['id'], $meme['rank'], $con);

      }

      return !in_array(0, $check);

  	}

  } else {

    return "whoops";

  }

}

if (isset($_GET['pw'])) {

	if ($_GET['pw'] == "89271gh4jbef13y4895rbhr334jh56blk453j6b45jk6hhl67o4576n") {

		$response['memeSuccess'] = rankDb($con);
		$response['userSuccess'] = updateUsers($con);

	} else {

		$response['success'] = "nice try haxor";

	}

} else {

	$response['success'] = "hello there :)";

}


echo json_encode($response);

$con->close();
?>
