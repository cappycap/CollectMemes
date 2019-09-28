<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['userId']) and isset($_GET['memeId'])) {

  $userId = $con->real_escape_string($_GET['userId']);
  $memeId = $con->real_escape_string($_GET['memeId']);

  $queryMemeInfo = "SELECT rank,totalOwned,likes FROM memes WHERE id=?";

  if ($stmtMeme = $con->prepare($queryMemeInfo)) {

    $stmtMeme->bind_param("i",$memeId);

    $stmtMeme->execute();

    $stmtMeme->bind_result($rank,$totalOwned,$likes);

    if ($stmtMeme->fetch()) {

      $newTotalOwned = $totalOwned + 1;
      $newLikes = $likes + 1;

      $response['rank'] = $rank;
      $stmtMeme->close();

      $queryUpdateMeme = "UPDATE memes SET totalOwned=?, likes=? WHERE id=?";
      if ($stmtUpdateMeme = $con->prepare($queryUpdateMeme)) {

        $stmtUpdateMeme->bind_param("iii",$newTotalOwned,$newLikes,$memeId);

        $stmtUpdateMeme->execute();

        $response['successMeme'] = 1;
        $stmtUpdateMeme->close();

      } else {

        $response['successMeme'] = "0-2";
        echo $con->error;

      }

    } else {

      $response['successMeme'] = "0-1";

    }

  } else {

    $response['successMeme'] = "0-0";

  }

  $queryUserInfo = "SELECT collection,collectionSize,collectionSum FROM users WHERE id=?";

  if ($stmtUser = $con->prepare($queryUserInfo)) {

    $stmtUser->bind_param("i",$userId);

    $stmtUser->execute();

    $stmtUser->bind_result($collection,$collectionSize,$collectionSum);

    if ($stmtUser->fetch()) {

      // Update user's collection.
      $newCollectionSize = intval($collectionSize) + 1;
      $newCollectionSum = intval($collectionSum) + intval($response['rank']);

      if ($collectionSize == 0) {

        $newCollection = $memeId;
        $newUserAvgRank = $rank;

      } else {

        $newCollection = $collection . "," . $memeId;
        $newUserAvgRank = $newCollectionSum / $newCollectionSize;

      }



      $stmtUser->close();

      $queryUpdateUser = "UPDATE users SET collection=?, avgRank=?, collectionSize=?, collectionSum=? WHERE id=?";

      if ($stmtUpdateUser = $con->prepare($queryUpdateUser)) {

        $stmtUpdateUser->bind_param("siiii",$newCollection,$newUserAvgRank,$newCollectionSize,$newCollectionSum,$userId);

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
