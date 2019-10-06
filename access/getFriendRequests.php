<?php

require 'db.php';

// Define response array for delivering status.
$response = array();

if (isset($_GET['userId'])) {

  // CLean vars.
  $userId = $con->real_escape_string($_GET['userId']);

  // Create array for storing active requests.
  $users = array();

  $search = "SELECT senderId FROM friendrequests WHERE userId=?";

  if ($stmt = $con->prepare($search)) {

    $stmt->bind_param("i",$userId);

    $stmt->execute();

    $result = $stmt->get_result();

    $stmt->close();

    if ($result->num_rows > 0) {

      while ($data = $result->fetch_assoc()) {

        $profile = array();

        // Grab other info to display to user.
        $userSearch = "SELECT username,avatar FROM users WHERE id=?";

        if ($userSearchStmt = $con->prepare($userSearch)) {

          $userSearchStmt->bind_param("i",$data['senderId']);

          $userSearchStmt->execute();

          $userSearchStmt->bind_result($username,$avatar);

          if ($userSearchStmt->fetch()) {

            $profile['username'] = $username;

            $profile['avatar'] = $avatar;

            $users[$data['senderId']] = $profile;

          }

          $userSearchStmt->close();

        }

      }

      $response['success'] = 1;
      $response['users'] = $users;

    } else {

      $response['success'] = 0;
      
    }

  }

} else {

  $response['success'] = 0;

}

echo json_encode($response);

$con->close();
?>
