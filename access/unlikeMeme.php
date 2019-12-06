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

  // Update meme.
  $queryUpdateMeme = "UPDATE memes SET likes = likes - 1 WHERE id=?";

  if ($stmtMeme = $con->prepare($queryUpdateMeme)) {

    $stmtMeme->bind_param("i",$memeId);

    if ($stmtMeme->execute()) {

      $response['updateMemeSuccess'] = 1;

    } else {

      $response['updateMemeSuccess'] = 0;
      $response['updateMemeError'] = $con->error;

    }

  }

  // Remove from likes table.
  $deleteQ = "DELETE FROM likes WHERE userId=" . $userId . " AND memeId=" . $memeId;

  if ($con->query($deleteQ) === TRUE) {

    $response['delSucc'] = 1;
    echo $deleteQ;
  } else {

    $response['delSucc'] = 0;

  }

  // decrement likes
  $queryUpdateUser = "UPDATE users SET likesSize = likesSize - 1 WHERE id = ?";

  if ($stmtUpdateUser = $con->prepare($queryUpdateUser)) {

    $stmtUpdateUser->bind_param("i",$userId);

    if ($stmtUpdateUser->execute()) {

      $response['updateUserSuccess'] = 1;

    } else {

      $response['updateUserSuccess'] = 0;
      $response['updateUserError'] = $con->error;

    }

    $stmtUpdateUser->close();

  }

}

echo json_encode($response);

$con->close();
?>
