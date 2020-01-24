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

function checkAchievements($userId, $con) {

  $achievement = array();

  $q = "SELECT progress FROM achievementsProgress WHERE userId=?";

  if ($s = $con->prepare($q)) {

    $s->bind_param("i",$userId);

    $s->execute();

    $s->bind_result($progressStr);

    if ($s->fetch()) {

      $prog = explode(",",$progressStr);

      $s->close();

      $check = ($prog[5] == 0) ? 1 : 0;

      if ($check) {

        $achievement['status'] = 1;

        $update = updateAchievementsProgress($userId, 5, 1, $con);

        $xp = giveXP($userId, 1000, $con);

      } else {

        $achievement['status'] = 0;

      }

    }

  }


  return $achievement;

}

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['memeId']) and isset($_POST['rank'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $memeId = $con->real_escape_string($_POST['memeId']);
  $r = (int)$con->real_escape_string($_POST['rank']);

  $response['achievement'] = checkAchievements($userId, $con);

  // Remove from owns table.
  $deleteQ = "DELETE FROM owns WHERE userId=" . $userId . " AND memeId=" . $memeId;

  if ($con->query($deleteQ) === TRUE) {

    $response['delSucc'] = 1;

  } else {

    $response['delSucc'] = 0;

  }

  $selectQuery = "SELECT collectionSize,collectionSum FROM users WHERE id=?";

  if ($selectStmt = $con->prepare($selectQuery)) {

    $selectStmt->bind_param("i",$userId);

    $selectStmt->execute();

    $selectStmt->bind_result($collectionSize,$collectionSum);

    if ($selectStmt->fetch()) {

      if ($collectionSize != 1) {

        $newCollectionSum = $collectionSum - $r;
        $newAvgRank = ($newCollectionSum) / ($collectionSize - 1);

      } else {

        $newCollectionSum = 0;
        $newAvgRank = 0;

      }

      $selectStmt->close();

      $updateQuery = "UPDATE users SET avgRank=?collectionSum=?,collectionSize=collectionSize-1 WHERE id=?";

      if ($updateStmt = $con->prepare($updateQuery)) {

        $updateStmt->bind_param("iii",$newAvgRank,$newCollectionSum,$userId);

        if ($updateStmt->execute()) {

          $response['success'] = 1;

        } else {

          $response['success'] = $updateStmt->error;

        }

        $updateStmt->close();

      }

    }

  }

} else {

  $response['success'] = 0;

}

echo json_encode($response);

$con->close();
?>
