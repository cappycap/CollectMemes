<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

// Scenario: userId wants to deny senderId's request.

if (isset($_POST['userId']) and isset($_POST['senderId']) and isset($_POST['decision'])) {

  $userId = $con->real_escape_string($_POST['userId']);
  $senderId = $con->real_escape_string($_POST['senderId']);

  // Scenario: userId wants to deny senderId's request.
  if ($_POST['decision'] == 0) {

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

  // Scenario: userId wants to accept senderId's friend request.
  } else if ($_POST['decision'] == 1) {

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

    $http1 = file_POST_contents($url1);
    $http2 = file_POST_contents($url2);

    if ($http1 !== false and $http2 !== false) {

      $data1 = json_decode($http1);
      $data2 = json_decode($http2);

      $response['http1'] = $data1;
      $response['http2'] = $data2;

    }


}

} else {

  $response['success'] = 0;

}
echo json_encode($response);

$con->close();
?>
