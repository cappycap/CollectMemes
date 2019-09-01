<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

// Start by checking they got the password right.
if (isset($_POST['pwd'])) {

  if ($_POST['pwd'] == "askkilfefuy8or62463ufsdmnkbflu4iy532457896terugh") {

    // Password is correct. Next, ensure all necessary fields have been posted.
		// Fields required: title, image, source, creator, edition.
		if (isset($_POST['title']) and isset($_POST['image']) and isset($_POST['source']) and isset($_POST['creator'])) {

			// Calculate rank.
			$grabRank = $con->query("SELECT COUNT(*) FROM memes");
			$grabRankRow = $grabRank->fetch_row();

			// Build meme profile.
			$title = $con->real_escape_string(isset($_POST['title']));
			$image = $con->real_escape_string(isset($_POST['image']));
			$totalOwned = 0;
			$rank = $grabRankRow[0] + 1;
			$inRotation = 1;
			$edition = 1;
			$source = $con->real_escape_string(isset($_POST['source']));
			$creator = $con->real_escape_string(isset($_POST['creator']));
			$dateAdded = date("Y-m-d");

			$q = "INSERT INTO `memes` (`title`, `image`, `totalOwned`, `rank`, `inRotation`, `edition`, `source`, `creator`, `dateAdded`)
			 VALUES ('".$title."','".$image."','".$totalOwned."','".$rank."','".$inRotation."','".$edition."','".$source."','".$creator."','".$dateAdded."',)";

		  if (!$con->query($q)) {

				// Insert failed. Report error.
				$response['success'] = 0;
				$response['message'] = "Failed (" . $con->errno . "): " . $con->error;

			} else {

				// Insert passed!
				$response['success'] = 1;
				$response['message'] = $con->insert_id;

			}

		} else {

			// Not all fields were supplied.
			$response['success'] = 0;
			$response['message'] = "E-0003";

		}

  } else {

    // Password is wrong.
    $response["success"] = 0;
    $response["message"] = "E-0002";

  }

} else {

  // Did not supply password.
  $response["success"] = 0;
  $response["message"] = "E-0001";
}

echo json_encode($response);

$con->close();
?>
