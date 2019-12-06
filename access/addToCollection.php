<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['memeId'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $memeId = $con->real_escape_string($_POST['memeId']);

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

        $insStmt->bind_param("iiii",$userId,$memeId,$dateAdded,$rank);

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

      // Update user's collection.
      $newCollectionSize = intval($collectionSize) + 1;
      $newCollectionSum = intval($collectionSum) + intval($response['rank']);

      if ($collectionSize == 0) {

        $newUserAvgRank = $response['rank'];

      } else {

        $newUserAvgRank = $newCollectionSum / $newCollectionSize;

      }

      $stmtUser->close();

      $queryUpdateUser = "UPDATE users SET avgRank=?, collectionSize=?, collectionSum=? WHERE id=?";

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

} else {

  $response['success'] = "0-0";

}

echo json_encode($response);

$con->close();
?>
