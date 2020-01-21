<?php
// Goals of addFriend:
// 1. Update users table (1r)
//    - append GETed newFriendUserId to userId's 'friends'.

// NEED TO ADD RESPONSE CHECKING IF EXECUTES WORKED CORRECTLY

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) && isset($_POST['remId'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $remId = $con->real_escape_string($_POST['remId']);

  // Begin deletion of senderId from userId's friends list.
  // Get friends list for userId.
  $query = "SELECT friends,friendsSize FROM users WHERE id=?";

  if ($stmt = $con->prepare($query)) {

    $stmt->bind_param("i",$userId);

    $stmt->execute();

    $stmt->bind_result($friends,$friendsSize);

    if ($stmt->fetch()) {

      $newestFriends = "";

      if (intval($friendsSize) != 1) {

        // Attempt to remove as 1,(2),3 -> 1,,,3 or (2),1,3 -> ,,1,3
        $newFriends = str_replace(strval($remId), ",", $friends);

        // Another replace to handle the broken commas
        // 1,,,3 -> 1,3
        // ,,1,3 -> 1,3
        $newestFriends = str_replace(",,", "", $newFriends);

      }

      $stmt->close();

      // Update userId.
      $updateQuery = "UPDATE users SET friends=?,friendsSize=friendsSize-1 WHERE id=?";

      if ($updateStmt = $con->prepare($updateQuery)) {

        $updateStmt->bind_param("si",$newestFriends,$userId);

        if ($updateStmt->execute()) {

          $response['3-s-1'] = 1;

        } else {

          $response['3-s-1'] = $updateStmt->error;

        }

        $updateStmt->close();

      }

    }

  }

  // Begin deletion of userId from senderId's friends list.
  $query2 = "SELECT friends, friendsSize FROM users WHERE id=?";

  if ($stmt = $con->prepare($query2)) {

    $stmt->bind_param("i",$remId);

    $stmt->execute();

    $stmt->bind_result($friends,$friendsSize);

    if ($stmt->fetch()) {

      $newestFriends = "";

      if (intval($friendsSize) != 1) {

        // Attempt to remove as 1,(2),3 -> 1,,,3 or (2),1,3 -> ,,1,3
        $newFriends = str_replace(strval($userId), ",", $friends);

        // Another replace to handle the broken commas
        // 1,,,3 -> 1,3
        // ,,1,3 -> 1,3
        $newestFriends = str_replace(",,", "", $newFriends);

      }

      $stmt->close();

      // Update userId.
      $updateQuery = "UPDATE users SET friends=?,friendsSize=friendsSize-1 WHERE id=?";

      if ($updateStmt = $con->prepare($updateQuery)) {

        $updateStmt->bind_param("si",$newestFriends,$remId);

        if ($updateStmt->execute()) {

          $response['3-s-2'] = 1;

        } else {

          $response['3-s-2'] = $updateStmt->error;

        }

        $updateStmt->close();

      }

    }

  }

}

echo json_encode($response);

$con->close();
?>
