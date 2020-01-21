<?php

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

function checkAchievements($userId, $size, $con) {

  $achievement = array();

  $size = intval($size);

  $achievementId = -1;
	$stage = 0;

  if ($size == 1) {

    $achievementId = 4;
    $stage = 1;

  } else if ($size == 10) {

    $achievementId = 4;
    $stage = 2;

  } else if ($size == 50) {

    $achievementId = 4;
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

function addFriend($userId, $friendId, $con) {

  $ret = 0;

  $checkQ = "SELECT friends,friendsSize FROM users WHERE id=?";

  if ($check = $con->prepare($checkQ)) {

    $check->bind_param("i",$userId);

    $check->execute();

    $check->bind_result($friendsStr,$friendsSize);

    if ($check->fetch()) {

      $newFriendsStr = "";
      $newFriendsSize = 0;

      if ($friendsSize == 0) {

        $newFriendsStr = $friendId;
        $newFriendsSize = 1;

      } else {

        $newFriendsStr = $friendsStr . "," . $friendId;
        $newFriendsSize = $friendsSize + 1;

      }

      $check->close();

      $achievement = checkAchievements($userId, $newFriendsSize, $con);

      $uQ = "UPDATE users SET friends=?,friendsSize=? WHERE id=?";

      if ($u = $con->prepare($uQ)) {

        $u->bind_param("sii",$newFriendsStr,$newFriendsSize,$userId);

        if ($u->execute()) {

          $ret = 1;

        }

        $u->close();

      }

    }

  }

  return $ret;

}
// Define response array for delivering status.
$response = array();

// Scenario: userId wants to deny senderId's request.

if (isset($_POST['userId']) and isset($_POST['senderId']) and isset($_POST['decision'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $senderId = $con->real_escape_string($_POST['senderId']);

  // Scenario: userId wants to deny senderId's request.
  if ($_POST['decision'] == 0) {

    // Remove the request.
    $deleteRequestQuery2 = "DELETE FROM friendRequests WHERE userId=? AND senderId=?";

    if ($deleteStmt2 = $con->prepare($deleteRequestQuery2)) {

      $deleteStmt2->bind_param("ii",$userId,$senderId);

      if ($deleteStmt2->execute()) {

        $response['1-s'] = 1;

        $response['title'] = "Processed!";
        $response['description'] = "Request has been denied.";

      } else {

        $response['1-s'] = $deleteStmt2->error;

      }

      $deleteStmt2->close();

    }

  // Scenario: userId wants to accept senderId's friend request.
  } else if ($_POST['decision'] == 1) {

    // Remove the request.
    $deleteRequestQuery = "DELETE FROM friendRequests WHERE userId=? AND senderId=?";

    if ($deleteStmt = $con->prepare($deleteRequestQuery)) {

      $deleteStmt->bind_param("ii",$userId,$senderId);

      if ($deleteStmt->execute()) {

        $response['1-s'] = 1;

        $response['title'] = "Processed!";
        $response['description'] = "Request has been accepted.";

      } else {

        $response['1-s'] = $deleteStmt->error;

      }

      $deleteStmt->close();

    }

    // Add the friend for both users.
    $response['addSuccess1'] = addFriend($userId, $senderId, $con);
    $response['addSuccess2'] = addFriend($senderId, $userId, $con);

}

} else {

  $response['success'] = 0;

}

echo json_encode($response);

$con->close();
?>
