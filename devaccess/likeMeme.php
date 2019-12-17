<?php
// Goals of likeMeme:
// 1. Update memes table (1 row)
//    - Increase 'likes' by 1.
// 2. Update users table (1 row)
//    - Add 'memeId' to 'likes'.
//    - Increase 'likesSize' by 1.

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['memeId'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $memeId = $con->real_escape_string($_POST['memeId']);
  $time = time();

  // Update meme.
  $queryUpdateMeme = "UPDATE memes SET likes = likes + 1 WHERE id=?";

  if ($stmtMeme = $con->prepare($queryUpdateMeme)) {

    $stmtMeme->bind_param("i",$memeId);

    if ($stmtMeme->execute()) {

      $response['updateMemeSuccess'] = 1;

    } else {

      $response['updateMemeSuccess'] = 0;
      $response['updateMemeError'] = $con->error;

    }

  }

  // Update likes table. First, let's grab some necessary info from DB.
  $memeInfoQuery = "SELECT rank,likes FROM memes WHERE id=?";

  if ($memeInfoStmt = $con->prepare($memeInfoQuery)) {

    $memeInfoStmt->bind_param("i",$memeId);

    $memeInfoStmt->execute();

    $memeInfoStmt->bind_result($memeRank,$memeLikes);

    if ($memeInfoStmt->fetch()) {

      $memeInfoStmt->close();

      $ins = "INSERT INTO likes (userId, memeId, dateAdded, rank, likes) VALUES (?, ?, ?, ?, ?)";

      if ($insStmt = $con->prepare($ins)) {

        $insStmt->bind_param("iiiii",$userId,$memeId,$time,$memeRank,$memeLikes);

        if ($insStmt->execute()) {

          $reponse['insLikeSuccess'] = 1;

        } else {

          $reponse['insLikeSuccess'] = 0;

        }

        $insStmt->close();

      }

    }

  }

  // Begin to update user. First, let's grab some necessary info from DB.
  $updateQ = "UPDATE users SET likesSize=likesSize+1 WHERE id=?";

  if ($uS = $con->prepare($updateQ)) {

    $uS->bind_param("i",$userId);

    $uS->execute();

    $uS->close();

  }

}

echo json_encode($response);

$con->close();
?>
