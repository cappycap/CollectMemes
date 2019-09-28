<?php
// Goals of addFriend:
// 1. Update users table (1r)
//    - append GETed newFriendUserId to userId's 'friends'.

// NEED TO ADD RESPONSE CHECKING IF EXECUTES WORKED CORRECTLY

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['userId']) && isset($_GET['newFriendUserId'])) {

  $userId = $con->real_escape_string($_GET['userId']);
  $newFriendUserId = $con->real_escape_string($_GET['newFriendUserId']);

  // query existing friend count from database.
  $queryUserInfo = "SELECT friendsSize FROM users WHERE id=?";

  if ($stmtUserInfo = $con->prepare($queryUserInfo)) {

    $stmtUserInfo->bind_param("i",$userId);

    $stmtUserInfo->execute();

    $stmtUserInfo->bind_result($currentFriendsSize);

    if ($stmtUserInfo->fetch()) {

      // Now, let's compute the query for updating the user.

      if ($currentFriendsSize == 0) {

        $updateUserInfo = "UPDATE users SET friends = ?,friendsSize = 1 WHERE id=?";
        $newFriendUserIdStr = strval($newFriendUserId);

      } else {

        $updateUserInfo = "UPDATE users SET friends = concat(friends,?),friendsSize = friendsSize + 1 WHERE id=?";
        $newFriendUserIdStr = "," . strval($newFriendUserId);

      }

      $stmtUserInfo->close();

      if ($stmtUpdateUser = $con->prepare($updateUserInfo)) {

        $stmtUpdateUser->bind_param("si",$newFriendUserIdStr,$userId);

        $stmtUpdateUser->execute();

        $response['success'] = 1;

      } else {

        $response['success'] = 0;

      }

      $stmtUpdateUser->close();

    }

  }

}

echo json_encode($response);

$con->close();
?>
