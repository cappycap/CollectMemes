<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

// Update 'rating' in DB for specified meme id.
function updateRating($id, $likes, $score, $oldRating, $con) {

	if ($oldRating == ($likes + $score)) {

		return 1;

	} else {

		$rating = ($likes + $score);

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

function updateRank($id, $rank, $con) {

	$q = "UPDATE memes SET rank=? WHERE id=?";

	if ($stmt = $con->prepare($q)) {

		$stmt->bind_param("ii",$rank,$id);

		if ($stmt->execute()) {

			$stmt->close();
			return 1;

		} else {

			$stmt->close();
			return 0;

		}

	}

}
// loop through and rate entire database
function rateDb($con) {

	$q = "SELECT id, likes, score, rating FROM memes ORDER BY id ASC";

	if ($stmt = $con->prepare($q)) {

		$stmt->execute();

		$stmt->bind_result($id, $likes, $score, $oldRating);

    $memes = array();

		while ($stmt->fetch()) {

      $meme = array();

      $meme['id'] = $id;
      $meme['likes'] = $likes;
      $meme['score'] = $score;
      $meme['oldRating'] = $oldRating;

      $memes[] = $meme;

		}

    $stmt->free_result();

		$stmt->close();

    $check = array();

    foreach($memes as $meme) {

      $check[] = updateRating($meme['id'], $meme['likes'], $meme['score'], $meme['oldRating'], $con);

    }

    return !in_array(0, $check);

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

    return "$rateSuccess";

  }

}

$response['success'] = rankDb($con);

echo json_encode($response);

$con->close();
?>
