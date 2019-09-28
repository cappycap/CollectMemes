<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['userId']) and isset($_GET['senderId']) and isset($_GET['decision'])) {

  $userId = $con->real_escape_string($_GET['userId']);
  $senderId = $con->real_escape_string($_GET['senderId']);

  // Scenario: userId wants to unfriend senderId.
  if ($_GET['decision'] == 0) {

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
          $newFriends = str_replace(strval($senderId), ",", $friends);

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

      $stmt->bind_param("i",$senderId);

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

          $updateStmt->bind_param("si",$newestFriends,$senderId);

          if ($updateStmt->execute()) {

            $response['3-s-2'] = 1;

          } else {

            $response['3-s-2'] = $updateStmt->error;

          }

          $updateStmt->close();

        }

      }

    }

  // Scenario: userId wants to accept senderId's friend request.
  } else if ($_GET['decision'] == 1) {

    // Remove the request.
    $deleteRequestQuery = "DELETE FROM friendrequests WHERE userId=? AND senderId=?";

    if ($deleteStmt = $con->prepare($deleteRequestQuery)) {

      $deleteStmt->bind_param("ii",$userId,$senderId);

      if ($deleteStmt->execute()) {

        $response['1-s'] = 1;

      } else {

        $response['1-s'] = $deleteStmt->error;

      }

      $deleteStmt->close();

    }

    // Add the friend for both users.
    $url1 = "http://localhost/access/addFriend.php?userId=" . $userId . "&newFriendUserId=" . $senderId;
    $url2 = "http://localhost/access/addFriend.php?userId=" . $senderId . "&newFriendUserId=" . $userId;

    $http1 = file_get_contents($url1);
    $http2 = file_get_contents($url2);

    if ($http1 !== false and $http2 !== false) {

      $data1 = json_decode($http1);
      $data2 = json_decode($http2);

      $response['http1'] = $data1;
      $response['http2'] = $data2;

    }

  // Scenario: userId wants to deny senderId's request.
} else if ($_GET['decision'] == 2) {

    // Remove the request.
    $deleteRequestQuery2 = "DELETE FROM friendrequests WHERE userId=? AND senderId=?";

    if ($deleteStmt2 = $con->prepare($deleteRequestQuery2)) {


      $deleteStmt2->bind_param("ii",$userId,$senderId);


      if ($deleteStmt2->execute()) {

        $response['1-s'] = 1;

      } else {

        $response['1-s'] = $deleteStmt2->error;

      }

      $deleteStmt2->close();

    }

  }

} else {

  $response['success'] = 0;

}
echo json_encode($response);

$con->close();
?>
