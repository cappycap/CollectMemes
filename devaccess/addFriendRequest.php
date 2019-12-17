<?php
// addFriendRequest
// Returns success:
//            0 - username not found / user has blocked them.
//            1 - request sent.
//            2 - request already exists.

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['userId']) and isset($_GET['targetUsername'])) {

  // Clean variables.
  $senderId = $con->real_escape_string($_GET['userId']);
  $targetUser = $con->real_escape_string($_GET['targetUsername']);

  // See if username exists.
  $search = "SELECT id,blockList FROM users WHERE username=?";

  if ($searchStmt = $con->prepare($search)) {

    $searchStmt->bind_param("s",$targetUser);

    $searchStmt->execute();

    $result = $searchStmt->get_result();

    if ($result->num_rows > 0) {

      while ($data = $result->fetch_assoc()) {

        $userId = $data['id'];
        $blockList = $data['blockList'];

        $searchStmt->close();

        // Check if the sender is blocked by the user.
        if (strpos(strval($blockList), strval($senderId)) === false) {

          // We good. Continue.
          // Now, let's check if a friend request exists.
          $reqSearch = "SELECT id FROM friendrequests WHERE userId=? and senderId=?";

          if ($reqSearchStmt = $con->prepare($reqSearch)) {

            $reqSearchStmt->bind_param("ii", $userId,$senderId);

            $reqSearchStmt->execute();

            $reqResult = $reqSearchStmt->get_result();

            if ($reqResult->num_rows > 0) {

              $response['success'] = 2;

            } else {

              // A request doesn't exist. We can insert one.
              $insert = "INSERT INTO friendrequests (userId, senderId) VALUES (?, ?)";

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

          // User is in the blockList.
          $response['success'] = 0;

        }



      }

    }

  }

}
echo json_encode($response);

$con->close();
?>
