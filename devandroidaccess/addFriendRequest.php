<?php
// addFriendRequest
// Returns success:
//            0 - username not found / user has blocked them.
//            1 - request sent.
//            2 - request already exists.

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_POST['userId']) and isset($_POST['targetUsername'])) {

  // Clean variables.
  $senderId = $con->real_escape_string($_POST['userId']);
  $targetUser = $con->real_escape_string($_POST['targetUsername']);

  // See if username exists.
  $search = "SELECT id,friends FROM users WHERE username=?";

  if ($searchStmt = $con->prepare($search)) {

    $searchStmt->bind_param("s",$targetUser);

    $searchStmt->execute();

    $searchStmt->store_result();

    $searchStmt->bind_result($userId,$friends);

    if ($searchStmt->num_rows > 0) {

      if ($searchStmt->fetch()) {

        $friendsArr = explode(",",$friends);

        // Check if the sender is added by the user.
        if (!in_array($senderId,$friendsArr)) {

          $searchStmt->close();

          // We good. Continue.
          // Now, let's check if a friend request exists.
          $reqSearch = "SELECT id FROM friendRequests WHERE userId=? and senderId=?";

          if ($reqSearchStmt = $con->prepare($reqSearch)) {

            $reqSearchStmt->bind_param("ii", $userId,$senderId);

            $reqSearchStmt->execute();

            $reqSearchStmt->store_result();

            if ($reqSearchStmt->num_rows > 0) {

              $response['success'] = 1;

            } else {

              // A request doesn't exist. We can insert one.
              $insert = "INSERT INTO friendRequests (userId, senderId) VALUES (?, ?)";

              if ($insertStmt = $con->prepare($insert)) {

                $insertStmt->bind_param("ii", $userId, $senderId);

                if ($insertStmt->execute()) {

                  $response['success'] = 1;

                } else {

                  $response['success'] = 0;

                }

                $insertStmt->close();

              }

            }

          }

        } else {

          $searchStmt->close();
          $response['success'] = 2;

        }



      }

    } else {

      $response['success'] = -1;

    }

  }

}
echo json_encode($response);

$con->close();
?>
