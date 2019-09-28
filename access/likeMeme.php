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

if (isset($_GET['userId']) and isset($_GET['memeId'])) {

  $userId = $con->real_escape_string($_GET['userId']);
  $memeId = $con->real_escape_string($_GET['memeId']);

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

  // Begin to update user. First, let's grab some necessary info from DB.
  $queryUserInfo = "SELECT likesSize FROM users WHERE id=?";

  if ($stmtUser = $con->prepare($queryUserInfo)) {

    $stmtUser->bind_param("i",$userId);

    if ($stmtUser->execute()) {

      $response['queryUserSuccess'] = 1;

    } else {

      $response['queryUserSuccess'] = 0;
      $response['queryUserError'] = $con->error;

    }

    $stmtUser->bind_result($currentLikesSize);

    if ($stmtUser->fetch()) {

      $stmtUser->close();

      // Determine query to use depending on how many memes the user has already liked.
      $queryUpdateUser = "";
      $memeIdFormatted = "";

      if ($currentLikesSize == 0) {

        // Does not need appendation.
        $queryUpdateUser = "UPDATE users SET likesSize = 1,likes = ? WHERE id = ?";
        $memeIdFormatted = strval($memeId);

      } else {

        $queryUpdateUser = "UPDATE users SET likesSize = likesSize + 1,likes = concat(likes,?) WHERE id = ?";
        $memeIdFormatted = "," . strval($memeId);

      }

      if ($stmtUpdateUser = $con->prepare($queryUpdateUser)) {

        $stmtUpdateUser->bind_param("si",$memeIdFormatted,$userId);

        if ($stmtUpdateUser->execute()) {

          $response['updateUserSuccess'] = 1;

        } else {

          $response['updateUserSuccess'] = 0;
          $response['updateUserError'] = $con->error;

        }


      }

    }

  }

}

echo json_encode($response);

$con->close();
?>
